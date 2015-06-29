<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBazarBuyableInventoryOrderId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(Config::get('bazar::database_prefix').'buyable_inventory', function(Blueprint $table)
		{
			$table->unsignedInteger('order_id')->after('buyable_item_id');
			
			$table->index('order_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(Config::get('bazar::database_prefix').'buyable_inventory', function(Blueprint $table)
		{
			$table->dropColumn('order_id');
		});
	}

}
