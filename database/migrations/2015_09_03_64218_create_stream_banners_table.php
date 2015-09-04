<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_stream', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('banner_id')->index('banner_id')->unsigned();
            $table->integer('stream_id')->index('stream_id')->unsigned();
            $table->integer('transfer_id')->index('transfer_id')->unsigned()->nullable();
            $table->enum('status', ['waiting', 'accepted', 'declined'])->index('status')->default('waiting');
            $table->string('client_comment')->nullable();
            $table->string('twitcher_comment')->nullable();
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
        Schema::drop('stream_banners');
    }
}