<?php namespace Folklore\Bazar\Models;

class OrderShipment extends Model {

	protected $table = 'orders_shipments';
	
	protected $fillable = array(
		'order_id',
		'carrier',
		'carrier_service',
		'tracking_number',
		'shipped'
	);

	/*
	 *
	 * Factory
	 * 
	 */

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
	* Special getters
	* 
	*/
	public function isShipped()
	{
		return (int)$this->shipped === 1;
	}

}
