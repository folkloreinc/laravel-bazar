<?php namespace Folklore\Bazar;

use Illuminate\Support\ServiceProvider;

use Cartalyst\Cart\Storage\IlluminateSession;

use Omnipay\Omnipay;

use Folklore\Bazar\Shipping\Shipping;

use Folklore\Bazar\Models\Order;
use Folklore\Bazar\Models\OrderCharge;
use Folklore\Bazar\Models\OrderShipment;
use Folklore\Bazar\Models\Observers\OrderObserver;
use Folklore\Bazar\Models\Observers\OrderChargeObserver;
use Folklore\Bazar\Models\Observers\OrderShipmentObserver;

class BazarServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('folklore/bazar');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
		$this->registerPaymentGateway();

		$this->registerCartStorage();

		$this->registerCart();

		$this->registerShipping();

		$this->registerBazar();
		
		$this->registerModelObservers();

		
	}

	/**
     * Register the payment gateway
     *
     * @return void
     */
    protected function registerPaymentGateway()
    {
        $this->app['bazar.gateway'] = $this->app->share(function($app)
        {

        	$gateway = $app['config']->get('bazar::gateway.default');
        	$gatewayConfig = $app['config']->get('bazar::gateway.gateways.'.$gateway);

        	$className = studly_case($gateway);
        	$gateway = Omnipay::create($className);

        	if(isset($gatewayConfig['api_key']))
        	{
				$gateway->setApiKey($gatewayConfig['api_key']);
			}
			else if($gatewayConfig['username'])
			{
				$gateway->setUsername($gatewayConfig['username']);
				$gateway->setPassword($gatewayConfig['password']);
			}

			return $gateway;
        });
    }

	/**
     * Register the storage driver used by the cart.
     *
     * @return void
     */
    protected function registerCartStorage()
    {
        $this->app['bazar.cart.storage'] = $this->app->share(function($app)
        {
            $sessionKey = $app['config']->get('bazar::session_key');

            return new IlluminateSession($app['session.store'], $sessionKey, 'bazar');
        });
    }

    /**
     * Register the cart.
     *
     * @return void
     */
    protected function registerCart()
    {
        $this->app['bazar.cart'] = $this->app->share(function($app)
        {
        	$cart = new Cart('bazar', $app['bazar.cart.storage'], $app['events']);

        	$taxes = $app['config']->get('bazar::taxes');
        	if(is_array($taxes))
        	{
        		$cart->setTaxes($taxes);
        	}

            return $cart;
        });
    }
	
	/**
	* Register the shipping evaluator.
	*
	* @return void
	*/
	protected function registerShipping()
	{
		$this->app['bazar.shipping'] = $this->app->share(function($app)
		{
			$shipping = new Shipping($app);

			return $shipping;
		});
	}

	/**
	 * Register the bazar service
	 *
	 * @return void
	 */
	public function registerBazar()
	{
		$app = $this->app;

		$this->app['bazar'] = $this->app->share(function() use ($app)
		{
			$bazar = new Bazar($app);
			return $bazar;
		});
	}
	
	public function registerModelObservers()
	{
		$app = $this->app;
		
		$this->app->before(function($request) use ($app)
		{
			Order::observe(new OrderObserver($app));
			OrderCharge::observe(new OrderChargeObserver($app));
			OrderShipment::observe(new OrderShipmentObserver($app));
		});
		
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('bazar','bazar.cart.storage','bazar.cart','bazar.gateway','bazar.shipping');
	}

}
