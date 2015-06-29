<?php namespace Folklore\Bazar\Shipping;

use Illuminate\Support\Manager;

use Folklore\Bazar\Shipping\Providers\CanadaPost as CanadaPostDriver;
use Folklore\Bazar\Shipping\Providers\Config as ConfigDriver;

class Shipping {
    
    /**
	 * The application instance.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * The registered custom driver creators.
	 *
	 * @var array
	 */
	protected $customCreators = array();

	/**
	 * The array of created "providers".
	 *
	 * @var array
	 */
	protected $providers = array();

	/**
	 * Create a new manager instance.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}
    
    /**
	 * Create an instance of the Imagine Gd driver.
	 *
	 * @return \Imagine\Gd\Imagine
	 */
	protected function createCanadapostDriver($provider)
	{
		return new CanadaPostDriver($this->app, $provider);
	}
    
    /**
	 * Create an instance of the Imagine Gd driver.
	 *
	 * @return \Imagine\Gd\Imagine
	 */
	protected function createConfigDriver($provider)
	{
		return new ConfigDriver($this->app, $provider);
	}
    
    
    public function getDefaultProvider()
    {
        return $this->app['config']['bazar::shipping.provider'];
    }
    
    public function setDefaultProvider($provider)
    {
        $this->app['config']['bazar::shipping.provider'] = $provider;
    }

	/**
	 * Get a driver instance.
	 *
	 * @param  string  $driver
	 * @return mixed
	 */
	public function provider($provider = null)
	{
		$provider = $provider ?: $this->getDefaultProvider();

		// If the given driver has not been created before, we will create the instances
		// here and cache it so we can return it next time very quickly. If there is
		// already a driver created by this name, we'll just return that instance.
		if ( ! isset($this->providers[$provider]))
		{
			$this->providers[$provider] = $this->createProvider($provider);
		}

		return $this->providers[$provider];
	}

	/**
	 * Create a new driver instance.
	 *
	 * @param  string  $driver
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function createProvider($providerName)
	{
        $provider = $this->app['config']['bazar::shipping.providers.'.$providerName];
        
        if(!$provider || !isset($provider['driver']))
        {
            throw new \InvalidArgumentException("Driver [$driver] not supported.");
        }
        
        $driver = $provider['driver'];
		$method = 'create'.ucfirst($driver).'Driver';

		// We'll check to see if a creator method exists for the given driver. If not we
		// will check for a custom driver creator, which allows developers to create
		// drivers using their own customized driver creator Closure to create it.
		if (isset($this->customCreators[$driver]))
		{
			return $this->callCustomCreator($driver, $provider);
		}
		elseif (method_exists($this, $method))
		{
			return $this->$method($provider);
		}

		throw new \InvalidArgumentException("Driver [$driver] not supported.");
	}

	/**
	 * Call a custom driver creator.
	 *
	 * @param  string  $driver
	 * @return mixed
	 */
	protected function callCustomCreator($driver, $provider)
	{
		return $this->customCreators[$driver]($provider);
	}

	/**
	 * Register a custom driver creator Closure.
	 *
	 * @param  string    $driver
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function extend($driver, Closure $callback)
	{
		$this->customCreators[$driver] = $callback;

		return $this;
	}

	/**
	 * Get all of the created "drivers".
	 *
	 * @return array
	 */
	public function getProviders()
	{
		return $this->providers;
	}

	/**
	 * Dynamically call the default driver instance.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array(array($this->provider(), $method), $parameters);
	}
    
}
