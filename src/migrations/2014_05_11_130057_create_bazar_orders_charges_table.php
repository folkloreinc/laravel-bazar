<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarOrdersChargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(\Config::get('bazar::database_prefix').'orders_charges', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('order_id');
			$table->float('amount');
			$table->string('provider',50);
			$table->string('provider_charge_id');
			$table->string('provider_user_id');
			$table->string('provider_status',50);
			$table->string('status',50);
			$table->timestamps();

			$table->index('order_id');
			$table->index(array('order_id','status'));
			$table->index('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('bazar::database_prefix').'orders_charges');
	}

}
