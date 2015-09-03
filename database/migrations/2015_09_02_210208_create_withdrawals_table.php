<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index('user_id')->unsigned();
            $table->enum('merchant', ['stripe', 'paypal'])->index('merchant');
            $table->string('account');
            $table->float('amount')->unsigned();
            $table->string('currency', 5);
            $table->enum('status', ['waiting', 'done', 'declined'])->index('status')->default('waiting');
            $table->string('transaction_number');
            $table->text('response')->nullable();
            $table->integer('admin_id')->index('admin_id')->unsigned()->nullable();
            $table->string('admin_comment')->nullable();
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
        Schema::drop('withdrawals');
    }
}
