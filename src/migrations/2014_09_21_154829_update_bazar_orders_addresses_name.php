<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBazarOrdersAddressesName extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(Config::get('bazar::database_prefix').'orders_addresses', function(Blueprint $table)
		{
			$table->string('firstname')->after('name');
			$table->string('lastname')->after('firstname');
			$table->dropColumn('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(Config::get('bazar::database_prefix').'orders_addresses', function(Blueprint $table)
		{
			$table->dropColumn('firstname');
			$table->dropColumn('lastname');
			$table->string('name')->after('type');
		});
	}

}
