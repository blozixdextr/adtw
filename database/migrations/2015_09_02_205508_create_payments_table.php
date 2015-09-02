<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index('user_id')->unsigned();
            $table->enum('merchant', ['stripe', 'paypal'])->index('merchant');
            $table->string('transaction_number');
            $table->string('title');
            $table->float('amount')->unsigned();
            $table->string('currency', 5);
            $table->text('response')->nullable();
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
        Schema::drop('payments');
    }
}
