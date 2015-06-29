<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersChargesLabel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(Config::get('bazar::database_prefix').'orders_charges', function(Blueprint $table)
		{
			$table->string('label')->after('order_id');
			$table->string('type')->after('label');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(Config::get('bazar::database_prefix').'orders_charges', function(Blueprint $table)
		{
			$table->dropColumn('label');
			$table->dropColumn('type');
		});
	}

}
