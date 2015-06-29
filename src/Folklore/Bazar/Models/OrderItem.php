<?php namespace Folklore\Bazar\Models;

class OrderItem extends Model {

	protected $table = 'orders_items';
	
	protected $fillable = array(
		'buyable_item_id',
		'cart_row_id',
		'name',
		'quantity',
		'price',
		'options'
	);
	
	protected $appends = array(
		'options_key'
	);

	/*
	 *
	 * Relationships
	 * 
	 */
	public function order()
	{
		return $this->belongsTo('Folklore\Bazar\Models\Order','order_id');
	}
    public function buyable()
    {
        return $this->belongsTo('Folklore\Bazar\Models\BuyableItem','buyable_item_id');
    }

	/*
	*
	* Accessors and mutators
	* 
	*/
	protected function setOptionsAttribute($value)
	{
		$this->attributes['options'] = !is_string($value) ? json_encode($value):$value;
	}
	protected function getOptionsAttribute($value)
	{
		if(empty($value)) {
			return array();
		}
		return is_string($value) ? @json_decode($value,true):$value;
	}
	
	public function getOptionsKeyAttribute()
	{
		if(!$this->options || !sizeof($this->options))
		{
			return 'item';
		}
		
		$ids = array();
		foreach($this->options as $option)
		{
			$ids[] = (int)$option['value'];
		}
		sort($ids);
		return implode('_', $ids);
	}

}
