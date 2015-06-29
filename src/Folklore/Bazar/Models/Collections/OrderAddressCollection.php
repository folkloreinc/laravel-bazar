<?php namespace Folklore\Bazar\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class OrderAddressCollection extends Collection {

    /*
     *
     * Get a specific address by it's type
     *
     */
    public function __get($type)
    {
        
        return $this->first(function($key,$model) use ($type) {
            return $model->type === $type;
        });
        
    }


}
