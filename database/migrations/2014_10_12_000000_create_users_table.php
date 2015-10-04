<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_active')->index('is_active')->default(1);
            $table->string('name');
            $table->string('email')->index('email');
            $table->string('password', 60);
            $table->enum('provider', ['local', 'facebook', 'twitter', 'google', 'linkedin', 'github', 'bitbucket', 'twitch'])
                ->index('provider')->nullable()->default('local');
            $table->string('oauth_id')->index('oauth_id')->nullable();
            $table->enum('role', ['user', 'admin'])->index('role')->default('user');
            $table->enum('type', ['twitcher', 'client'])->index('type')->nullable();
            $table->integer('twitch_views')->index('twitch_views')->unsigned()->nullable();
            $table->integer('twitch_followers')->index('twitch_followers')->unsigned()->nullable();
            $table->integer('twitch_videos')->index('twitch_videos')->unsigned()->nullable();
            $table->text('twitch_profile')->nullable();
            $table->text('twitch_channel')->nullable();
            $table->timestamp('last_activity')->nullable()->index('last_activity');
            $table->timestamp('twitch_updated')->nullable()->index('twitch_updated');
            $table->float('balance')->index('balance')->default(0);
            $table->float('balance_blocked')->index('balance_blocked')->default(0)->unsigned();
            $table->string('currency', 5)->nullable()->default('USD');
            $table->integer('language_id')->nullable()->unsigned()->index('language_id');
            $table->boolean('is_welcomed')->nullable()->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
