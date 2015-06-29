<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBazarOrdersAddressesEmailPhone extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(Config::get('bazar::database_prefix').'orders_addresses', function(Blueprint $table)
		{
			$table->text('email')->after('company');
			$table->text('phone')->after('email');
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
			$table->dropColumn('email');
			$table->dropColumn('phone');
		});
	}

}
