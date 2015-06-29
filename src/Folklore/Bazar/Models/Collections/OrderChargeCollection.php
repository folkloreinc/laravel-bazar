<?php namespace Folklore\Bazar\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class OrderChargeCollection extends Collection {

    public function total($items = null)
    {
        $items = !$items ? $this->all():$items;
        $total = 0;
        foreach($items as $item)
        {
            $total += $item->amount;
        }
        return $total;
    }
    
    public function totalPaid()
    {
        $items = $this->filter(function($item) {
            return $item->isPaid();
        });
        return $this->total($items);
    }


}
