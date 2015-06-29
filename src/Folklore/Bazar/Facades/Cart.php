<?php namespace Folklore\Bazar\Facades;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade {

    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor() { return 'bazar.cart'; }

}
