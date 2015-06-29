<?php namespace Folklore\Bazar\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class BuyableInventoryCollection extends Collection {

    public function findByOptions($options) {

        if(is_array($options)) {
            sort($options);
            $options = implode('_'.$options);
        }

        return $this->first(function($key,$item) use ($options) {
            return $item->options == $options;
        });

    }

    public function quantityTotal($key = null) {

        $quantity = 0;
        foreach($this->items as $item) {
            if(empty($item->order_item_id) && (!isset($key) || $item->options == $key)) {
                $quantity += $item->quantity;
            }
        }

        return $quantity;

    }

    public function quantitySold($key = null) {

        $quantity = 0;
        foreach($this->items as $item) {
            if((int)$item->order_item_id > 0 && (!isset($key) || $item->options == $key)) {
                $quantity += abs($item->quantity);
            }
        }

        return $quantity;


    }

    public function quantityLeft($key = null) {

        $quantity = 0;
        foreach($this->items as $item) {
            if(!isset($key) || $item->options == $key) {
                $quantity += $item->quantity;
            }
        }

        return $quantity;

    }

}
