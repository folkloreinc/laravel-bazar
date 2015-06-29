<?php namespace Folklore\Bazar\Models;


class Model extends \Illuminate\Database\Eloquent\Model
{
    public function __construct(array $attributes = array())
    {
        $this->table = \Config::get('bazar::database_prefix').$this->table;
        parent::__construct($attributes);
    }
}
