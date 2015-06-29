<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBazarOrdersChargesRemoveStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(Config::get('bazar::database_prefix').'orders_charges', function(Blueprint $table)
		{
			$table->dropColumn('status');
			$table->boolean('paid')->after('provider_status');
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
			$table->text('status')->after('provider_status');
			$table->dropColumn('paid');
		});
	}

}
