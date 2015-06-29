<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarBuyablePricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('bazar::database_prefix').'buyable_prices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('buyable_item_id');
			$table->string('type');
			$table->float('price');
			$table->softDeletes();
			$table->timestamps();

			$table->index('buyable_item_id');
			$table->index('type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('bazar::database_prefix').'buyable_prices');
	}

}
