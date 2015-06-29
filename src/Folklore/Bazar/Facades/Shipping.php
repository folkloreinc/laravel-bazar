<?php namespace Folklore\Bazar\Facades;

use Illuminate\Support\Facades\Facade;

class Shipping extends Facade {

    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor() { return 'bazar.shipping'; }

}
