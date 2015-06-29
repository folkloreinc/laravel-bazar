<?php namespace Folklore\Bazar\Models\Observers;

use Illuminate\Foundation\Application;

class Observer {
    
    protected $app;
    
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

}
