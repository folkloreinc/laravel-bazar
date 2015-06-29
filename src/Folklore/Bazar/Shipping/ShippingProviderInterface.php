<?php namespace Folklore\Bazar\Shipping;

interface ShippingProviderInterface
{
    public function rates($to, $from, $options);
}
