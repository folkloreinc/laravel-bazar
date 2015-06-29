<?php namespace Folklore\Bazar\Shipping\Providers;

use Folklore\Bazar\Shipping\ShippingProviderInterface;

class CanadaPost implements ShippingProviderInterface {
    
    protected $app;
    protected $config;
    
    public function __construct($app, $config)
    {
        $this->app = $app;
        $this->config = $config;
    }
    
    public function rates($to, $from = null, $options = array())
    {
        if(is_array($from) && !sizeof($options)) {
            $options = $from;
            $from = $this->app['config']->get('bazar::shipping.from');
        }
        
        $response = $this->getRates($to,$from, $options);
        
        return $response;
        
    }
    
    protected function getRates($to, $from, $options)
    {
        
        //Get default dimensions
        $defaultDimensions = $this->app['config']->get('bazar::shipping.dimensions');
        
        // Build request
        $data = array_merge(array(
            'customerNumber' => $this->config['customer_number'],
            'from' => $from['postalcode'],
            'to' => $to
        ), $defaultDimensions, $options);
        $request = $this->app['view']->make('bazar::shipping.canadapost_getrates_request',$data).'';
        
        //Do request
        $response = $this->doRequest('/rs/ship/price',$request);
        
        //Return response
        return $this->prepareGetRatesResponse($response);
        
    }
    
    protected function prepareGetRatesResponse($xml)
    {
        $services = array();
        if($xml && $xml->{'price-quotes'}) {
    		$priceQuotes = $xml->{'price-quotes'}->children('http://www.canadapost.ca/ws/ship/rate-v3');
    		if($priceQuotes->{'price-quote'}) {
    			foreach($priceQuotes as $priceQuote) {  
                    $services[] = array(
                        'code' => (string)$priceQuote->{'service-code'},
                        'name' => (string)$priceQuote->{'service-name'},
                        'price' => (float)$priceQuote->{'price-details'}->{'due'},
                        'days' => (int)$priceQuote->{'service-standard'}->{'expected-transit-time'}
                    );
    			}
    		}
    	}
        return $services;
    }
    
    protected function doRequest($path,$request) {
        
        $username = $this->config['username'];
        $password = $this->config['password'];
        $url = $this->config['url'];
        $service_url = $url.$path;
        
        //Create CURL request
        $curl = curl_init($service_url); // Create REST Request
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, realpath(dirname(__FILE__)) . '/../certificates/canadapost.pem');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/vnd.cpc.ship.rate-v3+xml',
            'Accept: application/vnd.cpc.ship.rate-v3+xml',
            'Accept-language: '.$this->app['config']->get('app.locale').'-CA'
        ));
        $curl_response = curl_exec($curl); // Execute REST Request
        if(curl_errno($curl)){
            return null;
        }

        curl_close($curl);

        // Example of using SimpleXML to parse xml response
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string('<root>' . preg_replace('/<\?xml.*\?>/','',$curl_response) . '</root>');
        if(!$xml) {
            return null;
        }
        
        return $xml;
        
    }
    
    
}
