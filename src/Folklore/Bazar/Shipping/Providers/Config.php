<?php namespace Folklore\Bazar\Shipping\Providers;

use Folklore\Bazar\Shipping\ShippingProviderInterface;

class Config implements ShippingProviderInterface {
    
    protected $app;
    protected $config;
    
    public function __construct($app, $config)
    {
        $this->app = $app;
        $this->config = $config;
    }
    
    public function rates($to, $from = null, $options = array())
    {
        $services = array();
        foreach($this->config['services'] as $shipping)
        {
            if(isset($shipping['country']) && $shipping['country'] === $to['country'])
            {
                $services[] = $shipping;
            }
        }
        
        if(!sizeof($services))
        {
            $services[] = $this->config['services'][$this->config['default']];
        }
        
        return $services;
        
    }
}
