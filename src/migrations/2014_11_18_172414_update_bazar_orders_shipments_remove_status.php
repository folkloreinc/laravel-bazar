<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBazarOrdersShipmentsRemoveStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders_shipments', function(Blueprint $table)
		{
			$table->dropColumn('status');
			$table->boolean('shipped')->after('tracking_number');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('orders_shipments', function(Blueprint $table)
		{
			$table->text('status')->after('tracking_number');
			$table->dropColumn('shipped');
		});
	}

}
