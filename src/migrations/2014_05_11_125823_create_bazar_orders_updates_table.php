<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBazarOrdersUpdatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(\Config::get('bazar::database_prefix').'orders_updates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('order_id');
			$table->string('type',20);
			$table->text('data');
			$table->timestamps();

			$table->index('order_id');
			$table->index(array('order_id','type'));
			$table->index('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(\Config::get('bazar::database_prefix').'orders_updates');
	}

}
