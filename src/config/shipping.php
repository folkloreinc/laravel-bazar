<?php


return array(
    
    /**
     * Origin of the package
     */
    'from' => array(
        'address' => null,
        'city' => null,
        'postalcode' => 'H1H1H1'
    ),
    
    /**
     * Default dimensions
     */
    'dimensions' => array(
        'weight' => 1,
        'length' => 30,
        'width' => 30,
        'height' => 30
    ),
    
    /**
     * Shipping providers
     */
    'provider' => 'config',

    'providers' => array(
        
        'config' => array(
            'driver' => 'config',
            'default' => 'international',
            'services' => array(
                array(
                    'code' => 'local',
                    'name' => trans('bazar::shipping.local'),
                    'days' => 7,
                    'price' => 15,
                    'country' => 'CA'
                ),
                array(
                    'code' => 'usa',
                    'name' => trans('bazar::shipping.usa'),
                    'days' => 14,
                    'price' => 25,
                    'country' => 'US'
                ),
                array(
                    'code' => 'international',
                    'name' => trans('bazar::shipping.international'),
                    'days' => 30,
                    'price' => 60
                )
            )
        ),

        'canadapost' => array(
            'driver' => 'canadapost',
            'username' => '',
            'password' => '',
            'customer_number' => '',
            'url' => 'https://ct.soa-gw.canadapost.ca'
        )

    )

);
