<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unique()->index('user_id')->unsigned();
            $table->string('first_name', 60)->nullable();
            $table->string('last_name', 60)->nullable();
            $table->enum('sex', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('about', 255)->nullable();
            $table->string('avatar', 40)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('paypal', 255)->nullable()->index('paypal');
            $table->boolean('confirmed_paypal')->nullable()->default(0);
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
        Schema::drop('user_profiles');
    }
}
