<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarOrdersItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(\Config::get('bazar::database_prefix').'orders_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('order_id');
			$table->unsignedInteger('buyable_item_id');
			$table->string('cart_row_id');
			$table->string('name');
			$table->integer('quantity');
			$table->float('price');
			$table->text('options');
			$table->timestamps();

			$table->index('order_id');
			$table->index('cart_row_id');
			$table->index('buyable_item_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('bazar::database_prefix').'orders_items');
	}

}
