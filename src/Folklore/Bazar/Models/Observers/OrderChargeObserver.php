<?php namespace Folklore\Bazar\Models\Observers;

use Folklore\Bazar\Models\OrderUpdate;
use Folklore\Bazar\Models\OrderCharge;

class OrderChargeObserver extends Observer {

    public function created($model)
    {
        if($this->app['config']->get('bazar::create_order_update'))
        {
            if($model->order && $model->isPaid())
            {
                $model->order->addUpdate(OrderUpdate::TYPE_PAYMENT,array(
                    'id' => $model->id,
                    'amount' => $model->amount,
                    'type' => $model->type,
                    'label' => $model->label
                ));
            }
        }
    }
    
    public function saved($model)
    {
        if(!$model->order->isPaid() && $model->order->isPaidWithCharges())
        {
            $model->order->paid = 1;
            $model->order->save();
        }
    }

}
