<?php namespace Folklore\Bazar\Models;

class OrderUpdate extends Model {
	
	const TYPE_CREATED = 'created';
	const TYPE_PAYMENT = 'payment';
	const TYPE_SHIPMENT = 'shipment';
	const TYPE_REFUND = 'refund';

	protected $table = 'orders_updates';
	
	protected $fillable = array(
		'type',
		'data'
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
	protected function setDataAttribute($value)
	{
		$this->attributes['data'] = !is_string($value) ? json_encode($value):$value;
	}
	protected function getDataAttribute($value)
	{
		if(empty($value)) {
			return array();
		}
		return is_string($value) ? @json_decode($value,true):$value;
	}

}
