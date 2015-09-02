<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buyer_id')->index('buyer_id')->unsigned();
            $table->integer('seller_id')->index('seller_id')->unsigned();
            $table->string('title');
            $table->float('amount');
            $table->string('currency', 5);
            $table->text('cart')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_transfers');
    }
}
