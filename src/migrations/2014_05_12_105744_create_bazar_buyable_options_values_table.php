<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarBuyableOptionsValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(\Config::get('bazar::database_prefix').'buyable_options_values', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('buyable_option_id');
			$table->text('value');
			$table->timestamps();

			$table->index('buyable_option_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('bazar::database_prefix').'buyable_options_values');
	}

}
