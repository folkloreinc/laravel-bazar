<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersShipments extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('bazar::database_prefix').'orders_shipments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->string('carrier');
			$table->string('carrier_service');
			$table->string('tracking_number');
			$table->string('status');
			$table->timestamps();
			
			$table->index('order_id');
			$table->index('tracking_number');
			$table->index('status');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('bazar::database_prefix').'orders_shipments');
	}

}
