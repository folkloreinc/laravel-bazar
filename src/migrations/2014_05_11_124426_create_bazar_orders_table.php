<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(\Config::get('bazar::database_prefix').'orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('user_id');
			$table->string('user_name');
			$table->string('user_email');
			$table->boolean('shipping_same_as_billing');
			$table->float('subtotal');
			$table->float('discount');
			$table->float('taxes');
			$table->text('taxes_conditions');
			$table->float('shipping');
			$table->float('total');
			$table->string('currency',3);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('bazar::database_prefix').'orders');
	}

}
