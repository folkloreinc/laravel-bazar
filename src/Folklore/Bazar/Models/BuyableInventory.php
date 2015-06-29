<?php namespace Folklore\Bazar\Models;

use Folklore\Bazar\Models\Collections\BuyableInventoryCollection;

class BuyableInventory extends Model {

	protected $table = 'buyable_inventory';

	protected $fillable = array(
		'buyable_item_id',
		'order_id',
		'order_item_id',
		'options',
		'quantity'
	);

	protected $appends = array(

	);

	/*
	 *
	 * Relationships
	 *
	 */
    public function buyable_item()
    {
        return $this->belongsTo('\Folklore\Bazar\Models\BuyableItem','buyable_item_id');
    }
	public function order_item()
	{
		return $this->belongsTo('\Folklore\Bazar\Models\OrderItem','order_item_id');
	}

	/*
	 *
	 * Accessors and mutators
	 *
	 */
	protected function setOptionsAttribute($value) {
		if(is_array($value)) {
			sort($value);
			$this->attributes['options'] = implode('_'.$value);
		} else {
			$this->attributes['options'] = $value;
		}
	}

	/*
	 *
	 * Collections
	 *
	 */
	public function newCollection(array $models = array())
	{
		return new BuyableInventoryCollection($models);
	}
}
