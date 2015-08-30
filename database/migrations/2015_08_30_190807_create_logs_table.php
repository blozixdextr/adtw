<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip', 25)->index('ip')->nullable();
            $table->integer('user_id')->index('user_id')->unsigned()->nullable();
            $table->string('type')->index('type');
            $table->string('key_value')->index('key_value');
            $table->string('additional_value')->index('additional_value')->nullable();
            $table->dateTime('review_date')->index('review_date')->nullable();
            $table->text('var')->nullable();
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
        Schema::drop('logs');
    }
}
