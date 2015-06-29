<?php namespace Folklore\Bazar\Models;

use Folklore\Bazar\Models\OrderAddress;
use Folklore\Bazar\Models\OrderUpdate;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Order extends Model {

	protected $table = 'orders';
	
	use SoftDeletingTrait;

    protected $dates = [
		'deleted_at'
	];
	
	protected $fillable = [
		'user_name',
		'user_email',
		'shipping_same_as_billing',
		'subtotal',
		'discount',
		'taxes',
		'taxes_conditions',
		'shipping',
		'shipping_mode',
		'total',
		'currency',
		'paid',
		'shipped',
		'refunded'
	];

	protected $with = [
		'items',
		'addresses',
		'charges',
		'shipments',
		'last_update'
	];

	/*
	 *
	 * Relationships
	 * 
	 */
	public function items()
	{
		return $this->hasMany('Folklore\Bazar\Models\OrderItem','order_id')
					->orderBy('id','asc');
	}
	public function addresses()
	{
		return $this->hasMany('Folklore\Bazar\Models\OrderAddress','order_id')
					->orderBy('id','asc');
	}
	public function charges()
	{
		return $this->hasMany('Folklore\Bazar\Models\OrderCharge','order_id')
					->orderBy('updated_at','desc');
	}
	public function shipments()
	{
		return $this->hasMany('Folklore\Bazar\Models\OrderShipment','order_id')
					->orderBy('updated_at','desc');
	}
	public function updates()
	{
		return $this->hasMany('Folklore\Bazar\Models\OrderUpdate','order_id')
					->orderBy('updated_at','desc');
	}
	public function last_update()
	{
		return $this->hasOne('Folklore\Bazar\Models\OrderUpdate','order_id')
					->orderBy('updated_at','desc');
	}

	/*
	 *
	 * Accessors and mutators
	 * 
	 */
	protected function setTaxesConditionsAttribute($value)
	{
		$this->attributes['taxes_conditions'] = !is_string($value) ? json_encode($value):$value;
	}
	protected function getTaxesConditionsAttribute($value)
	{
		if(empty($value)) {
			return array();
		}
		return is_string($value) ? @json_decode($value,true):$value;
	}
	
	/*
	*
	* Query scopes
	* 
	*/
	public function scopeSearch($query, $search)
	{
		return $query->where(function($query) use ($search) {
			$query->where('user_name','LIKE','%'.$search.'%')
					->orWhere('user_email','LIKE','%'.$search.'%')
					->orWhereHas('addresses',function($query) use ($search) {
						$query->where(function($query) use ($search) {
							$query->where('firstname','LIKE','%'.$search.'%');
							$query->orWhere('lastname','LIKE','%'.$search.'%');
							$query->orWhere('email','LIKE','%'.$search.'%');
							$query->orWhere('phone','LIKE','%'.$search.'%');
							$query->orWhere('address','LIKE','%'.$search.'%');
							$query->orWhere('city','LIKE','%'.$search.'%');
							$query->orWhere('postalcode','LIKE','%'.$search.'%');
							$query->orWhere('region','LIKE','%'.$search.'%');
						});
					});
		});
	}
	
	public function scopePaid($query, $status = 1)
	{
		return $query->where('paid', (int)$status);
	}
	
	public function scopeShipped($query, $status = 1)
	{
		return $query->where('shipped', (int)$status);
	}
	
	/*
	*
	* Special getters
	* 
	*/
	public function isPaid()
	{
		return (int)$this->paid === 1;
	}
	public function isPaidWithCharges()
	{
		$delta = $this->total - $this->charges->totalPaid();
		return $delta < 0.05;
	}
	public function isShipped()
	{
		return (int)$this->shipped === 1;
	}
	
	/*
	*
	* Create methods
	* 
	*/
	public function addUpdate($type, $data = array())
	{
		
		$update = new OrderUpdate();
		$update->type = $type;
		$update->data = $data;
		
		$this->updates()->save($update);
		
		return $update;
		
	}
	
	/*
	*
	* Sync method
	* 
	*/
	public function syncFromCart($cart)
	{
		$this->subtotal = $cart->subtotal();
		$this->discount = $cart->conditionsTotalSum('discount');
		$this->taxes = $cart->conditionsTotalSum('tax');
		$this->taxes_conditions = $cart->conditionsTotal('tax');
		$this->shipping = $cart->conditionsTotalSum('shipping');
		$this->total = $cart->total();
		
		$shippingModes = $cart->conditions('shipping');
		if($shippingModes && sizeof($shippingModes)) {
			$this->shipping_mode = $shippingModes[0]->get('name');
		} else {
			$this->shipping_mode = '';
		}
		
		$this->save();
		
		$this->syncItems($cart->items());
	}
	
	
	public function syncItems($items)
	{
		//Save items
		if(sizeof($items)) {

			$ids = array();
			foreach($items as $item)
			{
				$data = $item->toArray();
				
				$model = $this->items()->where('cart_row_id',$data['rowId'])->first();
				if(!$model)
				{
					$model = new OrderItem();
				}
				
				$model->buyable_item_id = $data['id'];
				$model->cart_row_id = $data['rowId'];
				$model->name = $data['name'];
				$model->quantity = $data['quantity'];
				$model->price = $data['price'];
				$model->options = $data['attributes'];
				
				$this->items()->save($model);
				
				$ids[] = $model->id;
			}

			$itemsToDelete = $this->items()->whereNotIn('id',$ids)->get();
			foreach($itemsToDelete as $item) {
				$item->delete();
			}
		} else {
			foreach($this->items as $item) {
				$item->delete();
			}
		}
	}
	
	public function syncAddresses($addresses)
	{
		//Save items
		if(is_array($addresses) && sizeof($addresses)) {

			$ids = array();
			foreach($addresses as $data)
			{
				$model = $this->addresses()->where('type',$data['type'])->first();
				if(!$model)
				{
					$model = new OrderAddress();
				}
				$model->fill($data);
				$this->addresses()->save($model);
				$ids[] = $model->id;
			}

			$itemsToDelete = $this->addresses()->whereNotIn('id',$ids)->get();
			foreach($itemsToDelete as $item) {
				$item->delete();
			}
		} else {
			foreach($this->addresses as $item) {
				$item->delete();
			}
		}
	}
	
	public function syncAddressByType($type, $address = null)
	{
		
		
		//Save items
		if(is_array($address) && sizeof($address)) {

			$model = $this->addresses()->where('type',$type)->first();

			if(!$model)
			{
				$model = new OrderAddress();
			}
			$model->fill($address);
			$this->addresses()->save($model);
			
			$itemsToDelete = $this->addresses()->where('type',$type)->where('id','!=',$model->id)->get();
			foreach($itemsToDelete as $item) {
				$item->delete();
			}
			
		} else {
			$addresses = $this->addresses()->where('type',$type)->get();
			foreach($addresses as $address) {
				$address->delete();
			}
		}
	}

}
