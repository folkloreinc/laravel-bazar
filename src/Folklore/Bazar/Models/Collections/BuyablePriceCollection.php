<?php namespace Folklore\Bazar\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class BuyablePriceCollection extends Collection
{

    public function price($type)
    {
        $price = $this->first(function($key,$item) use ($type) {
            return $item->type == $type;
        });
        
        return $price ? $price->price:0;
    }

}
