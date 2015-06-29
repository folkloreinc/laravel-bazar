<?php namespace Folklore\Bazar\Models;

use Folklore\Bazar\Models\Collections\OrderChargeCollection;

class OrderCharge extends Model {

	const TYPE_CREDIT = 'credit';
	const TYPE_DEBIT = 'debit';
	const TYPE_CHECK = 'check';
	const TYPE_MONEY = 'money';
	const TYPE_OTHER = 'other';

	protected $table = 'orders_charges';
	
	protected $fillable = array(
		'order_id',
		'label',
		'type',
		'amount',
		'provider',
		'provider_charge_id',
		'provider_user_id',
		'provider_status',
		'paid',
		'refused'
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
	* Collections
	*
	*/
	public function newCollection(array $models = array())
	{
		return new OrderChargeCollection($models);
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

}
