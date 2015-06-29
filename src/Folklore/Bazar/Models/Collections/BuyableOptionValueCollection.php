<?php namespace Folklore\Bazar\Models\Collections;

use Folklore\Bazar\Models\BuyableOption;
use Folklore\Bazar\Models\BuyableOptionValue;

use Illuminate\Database\Eloquent\Collection;

class BuyableOptionValueCollection extends Collection {

    public function organize()
    {

        $options = with(new BuyableOption())->newCollection();
        $relations = array();
        foreach($this->items as $option) {
            $optionId = $option->option->id;
            if(!$options->contains($optionId)) {
                $newOption = $option->option->replicate();
                $newOption->id = $option->option->id;
                $options->add($newOption);
                $relations[$optionId] = array();
            }
            unset($option->option);
            $relations[$optionId][] = $option;
        }

        foreach($relations as $optionId => $values) {
            $option = $options->find($optionId);
            $collection = with(new BuyableOptionValue())->newCollection($values);
            $option->setRelation('values',$collection);
        }

        return $options;
    }

    public function uniqueCombinations() {

        $ids = array();
        foreach($this->items as $item) {
            if(!isset($ids[$item->option->id])) {
                $ids[$item->option->id] = array();
            }
            $ids[$item->option->id][] = $item->id;
        }

        $arrays = array();
        foreach($ids as $id) {
            $arrays[] = $id;
        }

        $combinations = $this->combinations($arrays);
        $keys = array();
        foreach($combinations as $combination) {
            if(!is_array($combination)) $combination = array($combination);
            $key = implode('_',$combination);
            $keys[$key] = array();
            foreach($combination as $id) {
                $keys[$key][] = $this->find($id);
            }
        }

        return $keys;
    }

    protected function combinations($arrays, $i = 0) {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = $this->combinations($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $row = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
                sort($row);
                $result[] = $row;
            }
        }

        return $result;
    }

}
