<?php namespace Folklore\Bazar\Models;

use Folklore\Bazar\Models\Collections\OrderAddressCollection;

class OrderAddress extends Model {

	protected $table = 'orders_addresses';
	
	protected $fillable = array(
		'type',
		'firstname',
		'lastname',
		'company',
		'email',
		'phone',
		'address',
		'city',
		'postalcode',
		'region',
		'country'
	);
	
	protected $appends = array(
		'name'
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
	
	/*
	*
	* Accessors and mutators
	* 
	*/
	protected function getNameAttribute($value)
	{
		$name = array();
		if(!empty($this->firstname)) $name[] = $this->firstname;
		if(!empty($this->lastname)) $name[] = $this->lastname;
		return implode(' ',$name);
	}

	/*
	 *
	 * Collections
	 *
	 */
	public function newCollection(array $models = array())
	{
		return new OrderAddressCollection($models);
	}

}
