<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarOrdersAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(\Config::get('bazar::database_prefix').'orders_addresses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('order_id');
			$table->string('type',20);
			$table->string('name');
			$table->string('company');
			$table->string('address');
			$table->string('city');
			$table->string('postalcode');
			$table->string('region');
			$table->string('country',2);
			$table->timestamps();

			$table->index('order_id');
			$table->index(array('order_id','type'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('bazar::database_prefix').'orders_addresses');
	}

}
