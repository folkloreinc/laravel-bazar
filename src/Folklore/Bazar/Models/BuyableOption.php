<?php namespace Folklore\Bazar\Models;

class BuyableOption extends Model {

	protected $table = 'buyable_options';

	protected $fillable = array(
		'type',
		'handle',
		'required',
		'name_fr',
		'name_en'
	);

	/*
	*
	* Relationships
	*
	*/
	public function values()
	{
		return $this->hasMany('\Folklore\Bazar\Models\BuyableOptionValue','buyable_option_id');
	}


	/*
	 *
	 * Sync methods
	 *
	 */

    public function syncValues($items = array())
    {
        //Save items
        if(is_array($items) && sizeof($items)) {

            $ids = array();
            foreach($items as $data)
            {
                $model = isset($data['id']) && (int)$data['id'] > 0 ? BuyableOptionValue::find($data['id']):new BuyableOptionValue();
				if(!$model) {
					continue;
				}
                $model->fill($data);
                $model->save();
                $this->values()->save($model);
                $ids[] = $model->id;
            }

            $itemsToDelete = $this->values()->whereNotIn('id',$ids)->get();
            foreach($itemsToDelete as $item) {
                $item->delete();
            }
        } else {
            foreach($this->values as $item) {
                $item->delete();
            }
        }
    }
}

BuyableOption::created(function($item)
{
	if(empty($item->handle)) {
		$item->handle = $item->type.'_'.$item->id;
		$item->save();
	}
});
