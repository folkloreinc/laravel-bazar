<?php namespace Folklore\Bazar\Models;

use Folklore\Bazar\Models\BuyableOption;
use Folklore\Bazar\Models\BuyableItemPrice;
use Folklore\Bazar\Models\BuyableInventory;
use Illuminate\Database\Eloquent\Collection;

class BuyableItem extends Model {

	protected $table = 'buyable_items';

	protected $fillable = array(
		'price',
		'quantity',
		'dimension_l',
		'dimension_w',
		'dimension_h',
		'weight',
		'out_of_stock',
		'on_sale'
	);

	protected $appends = array(
		'price_formatted'
	);

	protected $with = array(
		'prices',
		'options'
	);

	/*
	 *
	 * Relationships
	 *
	 */
    public function buyable()
    {
        return $this->morphTo();
    }
	public function prices()
	{
		return $this->hasMany('\Folklore\Bazar\Models\BuyablePrice','buyable_item_id');
	}
	public function inventory()
	{
		return $this->hasMany('\Folklore\Bazar\Models\BuyableInventory','buyable_item_id');
	}
	public function options()
	{
		$pivotTable = \Config::get('bazar::database_prefix').'buyable_items_options_pivot';
		return $this->belongsToMany('Folklore\Bazar\Models\BuyableOptionValue', $pivotTable, 'buyable_item_id', 'buyable_option_value_id')
					->withPivot('price','weight', 'dimension_l', 'dimension_w', 'dimension_h')
					->withTimestamps();
	}



	/*
	 *
	 * Get method
	 *
	 */
	public function quantity()
	{
		$quantity = 0;
		$quantity += $this->quantity;
		foreach($this->options as $option) {
			$quantity += $option->pivot->quantity;
		}
		return $quantity;
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
     * Sync methods
     *
     */
    public function syncPrices($prices = array())
    {

        //Save prices
        if(is_array($prices) && sizeof($prices)) {

            $ids = array();
            foreach($prices as $data)
            {
                $model = $this->prices()->where('type',$data['type'])->first();
                if(!$model)
                {
                    $model = new BuyablePrice();
                }
                $model->fill($data);
                $model->save();
                $this->prices()->save($model);
                $ids[] = $model->id;
            }

            $itemsToDelete = $this->prices()->whereNotIn('id',$ids)->get();
            foreach($itemsToDelete as $item) {
                $item->delete();
            }
        } else {
            foreach($this->prices as $item) {
                $item->delete();
            }
        }
    }

	public function syncOptions($items)
	{
		//Save items
		if(is_array($items) && sizeof($items)) {

				$ids = array();
				foreach($items as $id => $item) {
					if(isset($item['selected']) && (boolean)$item['selected']) {
						$ids[$id] = array(
							'price' => isset($item['price']) ? $item['price']:0,
							'dimension_l' => isset($item['dimension_l']) ? $item['dimension_l']:0,
							'dimension_w' => isset($item['dimension_w']) ? $item['dimension_w']:0,
							'dimension_h' => isset($item['dimension_h']) ? $item['dimension_h']:0,
							'weight' => isset($item['weight']) ? $item['weight']:0
						);
					}
				}

				$this->options()->sync($ids);

		} else {
			foreach($this->options as $item) {
				$item->delete();
			}
		}
	}

	public function syncInventory($inventory = array())
	{

		//Save inventory
		if(is_array($inventory) && sizeof($inventory)) {

			$ids = array();
			foreach($inventory as $data)
			{
				$options = '';
				if(isset($data['options'])) {
					if(is_array($data['options'])) {
						sort($data['options']);
						$options = implode('_'.$data['options']);
					} else {
						$options = $data['options'];
					}
				}

				$model = $this->inventory()
								->where('options',$options)
								->where('order_item_id',0)
								->first();
				if(!$model)
				{
					$model = new BuyableInventory();
				}
				$model->fill($data);
				$model->save();
				$this->inventory()->save($model);
				$ids[] = $model->id;
			}

			$itemsToDelete = $this->inventory()
									->whereNotIn('id',$ids)
									->where('order_item_id',0)
									->get();
			foreach($itemsToDelete as $item) {
				$item->delete();
			}
		} else {
			foreach($this->inventory as $item) {
				$item->delete();
			}
		}
	}
}
