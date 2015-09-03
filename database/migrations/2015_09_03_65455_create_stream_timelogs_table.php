<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamTimelogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stream_timelogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stream_id')->index('stream_id')->unsigned();
            $table->dateTime('timeslot_start')->index('timeslot_start');
            $table->dateTime('timeslot_end')->index('timeslot_end');
            $table->integer('viewers')->index('viewers')->unsigned();
            $table->enum('status', ['live', 'died'])->index('status');
            $table->string('screenshot')->nullable();
            $table->text('response');
            $table->softDeletes();
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
        Schema::drop('stream_timelogs');
    }
}
