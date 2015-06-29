<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(Config::get('bazar::database_prefix').'orders', function(Blueprint $table)
		{
			$table->text('status')->after('currency');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(Config::get('bazar::database_prefix').'orders', function(Blueprint $table)
		{
			$table->dropColumn('status');
		});
	}

}
