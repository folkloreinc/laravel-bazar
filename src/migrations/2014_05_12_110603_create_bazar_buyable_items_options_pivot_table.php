<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarBuyableItemsOptionsPivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('bazar::database_prefix').'buyable_items_options_pivot', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('buyable_item_id');
			$table->unsignedInteger('buyable_option_value_id');
			$table->float('price');
			$table->float('weight');
			$table->float('dimension_l');
			$table->float('dimension_w');
			$table->float('dimension_h');
			$table->timestamps();

			$table->index('buyable_item_id');
			$table->index('buyable_option_value_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('bazar::database_prefix').'buyable_items_options_pivot');
	}

}
