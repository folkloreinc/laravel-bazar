<?php namespace Folklore\Bazar\Models\Observers;

use Folklore\Bazar\Models\OrderUpdate;
use Folklore\Bazar\Models\OrderShipment;

class OrderShipmentObserver extends Observer {

    public function created($model)
    {
        if($this->app['config']->get('bazar::create_order_update'))
        {
            if($model->order && $model->isShipped())
            {
                $model->order->addUpdate(OrderUpdate::TYPE_SHIPMENT,array(
                    'carrier' => $model->carrier,
                    'service' => $model->carrier_service,
                    'tracking_number' => $model->tracking_number
                ));
            }
        }
    }

}
