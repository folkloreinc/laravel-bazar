<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarBuyableInventoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('bazar::database_prefix').'buyable_inventory', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('buyable_item_id');
			$table->unsignedInteger('order_item_id');
			$table->string('options');
			$table->integer('quantity');
			$table->timestamps();

			$table->index('buyable_item_id');
			$table->index('order_item_id');
			$table->index(array('buyable_item_id','options'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('bazar::database_prefix').'buyable_inventory');
	}

}
