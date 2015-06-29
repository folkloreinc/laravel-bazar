<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBazarBuyableItemsProperties extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(Config::get('bazar::database_prefix').'buyable_items', function(Blueprint $table)
		{
			$table->boolean('out_of_stock')->after('dimension_h');
			$table->boolean('on_sale')->after('out_of_stock');
			
			$table->index('out_of_stock');
			$table->index('on_sale');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(Config::get('bazar::database_prefix').'buyable_items', function(Blueprint $table)
		{
			$table->dropColumn('out_of_stock');
			$table->dropColumn('on_sale');
		});
	}

}
