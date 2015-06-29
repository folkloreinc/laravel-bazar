<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBazarOrdersRemoveStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(Config::get('bazar::database_prefix').'orders', function(Blueprint $table)
		{
			$table->dropColumn('status');
			$table->boolean('paid')->after('currency');
			$table->boolean('shipped')->after('paid');
			$table->boolean('refunded')->after('shipped');
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
			$table->text('status')->after('currency');
			$table->dropColumn('paid');
			$table->dropColumn('shipped');
			$table->dropColumn('refunded');
		});
	}

}
