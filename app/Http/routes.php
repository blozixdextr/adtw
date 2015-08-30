<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'IndexController@index');

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/twitch', 'Auth\AuthController@twitch');
Route::get('auth/twitch/callback', 'Auth\AuthController@twitchCallback');

Route::get('profile', ['middleware' => 'auth', 'uses' => 'User\ProfileController@index']);

Route::get('profile/{userId}', 'User\ProfileController@index');

Route::group(['middleware' => 'role:twitcher', 'namespace' => 'User\Twitcher', 'prefix' => 'user/twitcher'], function () {

    Route::get('/', 'IndexController@index');
    Route::get('banners', 'IndexController@banners');

});

Route::group(['middleware' => 'role:client'], function () {
    Route::get('user/client', 'User\Client\IndexController@index');
});

Route::group(['middleware' => 'role:admin'], function () {
    Route::get('admin', 'Admin\IndexController@index');
});