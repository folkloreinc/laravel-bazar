<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarBuyableItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(\Config::get('bazar::database_prefix').'buyable_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('buyable_id');
			$table->string('buyable_type');
			$table->float('price');
			$table->integer('quantity');
			$table->float('weight');
			$table->float('dimension_l');
			$table->float('dimension_w');
			$table->float('dimension_h');
			$table->softDeletes();
			$table->timestamps();

			$table->index('buyable_id');
			$table->index('buyable_type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('bazar::database_prefix').'buyable_items');
	}

}
