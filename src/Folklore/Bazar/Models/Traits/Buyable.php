<?php namespace Folklore\Bazar\Models\Traits;

use Folklore\Bazar\Models\BuyableItem;

trait Buyable {

	/*
	 *
	 * Relationships
	 *
	 */
    public function buyable()
    {
        return $this->morphOne('Folklore\Bazar\Models\BuyableItem', 'buyable');
    }

    /*
     *
     * Sync methods
     *
     */
    public function syncBuyable($item)
    {
        if($item && is_array($item) && sizeof($item))
        {
            
            $buyableItem = !$this->buyable ? new BuyableItem():$this->buyable;
            $buyableItem->fill($item);
            $buyableItem->save();

            if(isset($item['prices'])) {
                $buyableItem->syncPrices($item['prices']);
            }

            if(isset($item['options'])) {
                $buyableItem->syncOptions($item['options']);
            }

            if(isset($item['inventory'])) {
                $buyableItem->syncInventory($item['inventory']);
            }

            $this->buyable()->save($buyableItem);
        }
        else if($this->buyable)
        {
            $this->buyable->delete();
        }
    }

}
