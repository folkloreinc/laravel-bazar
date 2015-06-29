<?php namespace Folklore\Bazar\Models;

use Folklore\Bazar\Models\Collections\BuyableOptionValueCollection;

class BuyableOptionValue extends Model {

	protected $table = 'buyable_options_values';

	protected $fillable = array(
		'value'
	);
	
	protected $hidden = array(
		'created_at',
		'updated_at'
	);

	protected $with = array(
		'option'
	);

	/*
	 *
	 * Relationships
	 *
	 */
	public function option()
	{
		return $this->belongsTo('\Folklore\Bazar\Models\BuyableOption','buyable_option_id');
	}


	/*
	 *
	 * Accessors and mutators
	 *
	 */
	protected function setValueAttribute($value)
	{
		$this->attributes['value'] = is_array($value) ? json_encode($value):$value;
	}
	protected function getValueAttribute($value)
	{
		$obj = json_decode($value,false);
		return json_last_error() == JSON_ERROR_NONE ? $obj:$value;
	}

	/*
	 *
	 * Collections
	 *
	 */
	public function newCollection(array $models = array())
	{
		return new BuyableOptionValueCollection($models);
	}

}
