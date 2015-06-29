<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarBuyableOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(\Config::get('bazar::database_prefix').'buyable_options', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type',50);
			$table->string('handle',50);
			$table->string('name_fr');
			$table->string('name_en');
			$table->boolean('required');
			$table->timestamps();

			$table->index('type');
			$table->index('handle');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('bazar::database_prefix').'buyable_options');
	}

}
