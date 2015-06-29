<?php namespace Folklore\Bazar;


use Cartalyst\Cart\Cart as CartalystCart;
use Cartalyst\Conditions\Condition;

use Folklore\Bazar\Models\Order;

use Illuminate\Database\Eloquent\Collection;

class Cart extends CartalystCart
{
	public function buyableItems()
	{
		$collection = new Collection();
		$items = $this->items();
		$itemsByClassName = array();
		foreach($items as $item)
		{
			$className = $item['buyable_type'];
			if(!isset($itemsByClassName[$className]))
			{
				$itemsByClassName[$className] = array();
			}
			$itemsByClassName[$className][] = $item['buyable_id'];
		}
		
		foreach($itemsByClassName as $className => $ids)
		{
			$items = $className::whereIn('id', $ids)->get();
			foreach($items as $item)
			{
				$collection->add($item);
			}
		}
		
		return $collection;
	}
	
	public function setShipping($price = null, $name = null)
	{
		
		$this->setConditionsOrder([
		    'discount',
		    'other',
		    'tax',
		    'shipping'
		]);
		
		$this->removeConditionByType('shipping');
		
		if(!$price) {
			return;
		}
		
		$conditions = $this->conditions();

		$condition = new Condition(array(
			'name' => !$name ? 'Shipping':$name,
			'type'   => 'shipping',
			'target' => 'subtotal'
		));
		$condition->setActions(array(array(
			'value' => str_replace(',','.',(string)$price)
		)));
		$conditions[] = $condition;

		$this->condition($conditions);
		
	}

	public function setTaxes($taxes)
	{
		foreach($taxes as $tax)
		{
			$this->addTax($tax);
		}
	}

	public function addTax($tax)
	{

		$conditions = $this->conditions();

		$condition = new Condition(array(
			'name' => $tax['name'],
		    'type'   => 'tax',
		    'target' => 'subtotal'
		));
		$condition->setActions(array(array(
			'value' => $tax['value']
		)));
		$conditions[] = $condition;

		$this->condition($conditions);
	}

	public function summary()
	{

		$discount = array();
		$discount['items'] = $this->conditionsTotal('discount');
		$discount['total'] = $this->conditionsTotalSum('discount');

		$taxes = array();
		$taxes['items'] = $this->conditionsTotal('tax');
		$taxes['total'] = $this->conditionsTotalSum('tax');

		$shipping = array();
		$shipping['items'] = $this->conditionsTotal('shipping');
		$shipping['total'] = $this->conditionsTotalSum('shipping');

		$summary = array(
			'subtotal' => $this->subtotal(),
			'discount' => $discount,
			'taxes' => $taxes,
			'shipping' => $shipping,
			'total' => $this->total()
		);

		return $summary;

	}

	public function clear()
	{
		$conditions = $this->conditions();
		parent::clear();
		foreach($conditions as $condition)
		{
			$this->condition($condition);
		}
	}

}
