<?php namespace Folklore\Bazar\Models\Observers;

use Folklore\Bazar\Models\OrderUpdate;
use Folklore\Bazar\Models\BuyableInventory;

class OrderObserver extends Observer {

    public function created($model)
    {
        if($this->app['config']->get('bazar::create_order_update'))
        {
            $model->load('last_update');
            if(!$model->last_update)
            {
                $model->addUpdate(OrderUpdate::TYPE_CREATED);
            }
        }
        
    }
    
    public function saved($model)
    {
        
        if($model->isPaid())
        {
            /**
             * Update inventory
             */
            $ids = array();
            foreach($model->items as $item)
            {
                $inventoryItem = BuyableInventory::where('order_item_id', $item->id)
                                                    ->where('order_id', $model->id)
                                                    ->first();
                if(!$inventoryItem)
                {
                    $inventoryItem = new BuyableInventory();
                    $inventoryItem->buyable_item_id = $item->buyable_item_id;
                    $inventoryItem->order_item_id = $item->id;
                    $inventoryItem->order_id = $model->id;
                }
                
                $inventoryItem->options = $item->options_key;
                $inventoryItem->quantity = -$item->quantity;
                
                $inventoryItem->save();
                
                $ids[] = $inventoryItem->id;
            }
            
            //Delete inventory
            $deleteQuery = BuyableInventory::where('order_id', $model->id);
            if(sizeof($ids))
            {
                $deleteQuery->whereNotIn('id', $ids);
            }
            $deleteItems = $deleteQuery->get();
            foreach($deleteItems as $item)
            {
                $item->delete();
            }
        }
    }

}
