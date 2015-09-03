<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->index('client_id')->unsigned();
            $table->integer('twitcher_id')->index('twitcher_id')->unsigned();
            $table->integer('type')->index('type')->unsigned();
            $table->string('title');
            $table->string('description');
            $table->string('file');
            $table->boolean('is_active')->index('is_active');
            $table->enum('status', ['waiting', 'accepted', 'declined', 'finished'])->index('status')->default('waiting');
            $table->float('amount_limit')->unsigned()->default(0);
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
        Schema::drop('banners');
    }
}
