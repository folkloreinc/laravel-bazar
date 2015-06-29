<?php namespace Folklore\Bazar\Models;

use Folklore\Bazar\Models\Collections\BuyablePriceCollection;

class BuyablePrice extends Model {

	protected $table = 'buyable_prices';

	protected $fillable = array(
		'type',
		'price'
	);

	protected $appends = array(
		'price_formatted'
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

	/*
	 *
	 * Accessors and mutators
	 *
	 */
	protected function getPriceFormattedAttribute()
	{
		$price = $this->price;
		if(is_numeric($price) && floor($price) != $price) {
			return number_format($price,2,'.',' ');
		} else {
			return number_format($price,0,'.',' ');
		}
	}

	/*
	*
	* Collections
	*
	*/
	public function newCollection(array $models = array())
    {
        return new BuyablePriceCollection($models);
    }
}
