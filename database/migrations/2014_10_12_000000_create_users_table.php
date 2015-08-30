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
            $table->string('email')->unique();
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
