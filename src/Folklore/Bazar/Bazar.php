<?php namespace Folklore\Bazar;

use Omnipay\Common\CreditCard;
use Folklore\Bazar\Models\Order;

class Bazar {

	protected $app;
	
	protected $order;

	public function __construct($app)
	{
		$this->app = $app;
	}
	
	public function money($amount)
	{
		return number_format((float)$amount,2,'.',' ').'$';
	}

	public function card($input, Order $order = null)
	{
		if($order)
		{
			$billingAddress = $order->addresses->billing;
			$shippingAddress = $order->addresses->shipping;
			if($billingAddress) {
				$input['firstName'] = $billingAddress->firstname;
				$input['lastName'] = $billingAddress->lastname;
				$input['email'] = $billingAddress->email;
				$input['billingPhone'] = $billingAddress->phone;
				$input['billingAddress1'] = $billingAddress->address;
				$input['billingCity'] = $billingAddress->city;
				$input['billingPostcode'] = $billingAddress->postalcode;
				$input['billingState'] = $billingAddress->region;
				$input['billingCountry'] = $billingAddress->country;
			}
			if($shippingAddress) {
				$input['shippingAddress1'] = $shippingAddress->address;
				$input['shippingCity'] = $shippingAddress->city;
				$input['shippingPostcode'] = $shippingAddress->postalcode;
				$input['shippingState'] = $shippingAddress->region;
				$input['shippingCountry'] = $shippingAddress->country;
			}
		}
		
		$card = new CreditCard($input);
		return $card;
	}

	public function purchase($input)
	{
		return $this->gateway()->purchase($input)->send();
	}

	public function authorize($input)
	{
		return $this->gateway()->authorize($input)->send();
	}

	public function refund($input)
	{
		return $this->gateway()->refund($input)->send();
	}

	public function void($input)
	{
		return $this->gateway()->void($input)->send();
	}

	public function cart()
	{
		return $this->app['bazar.cart'];
	}

	public function gateway()
	{
		return $this->app['bazar.gateway'];
	}

}
