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

Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {

    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    Route::get('logout', 'AuthController@getLogout');

    Route::get('twitch', 'AuthController@twitch');
    Route::get('twitch/callback', 'AuthController@twitchCallback');

    Route::post('client', 'AuthController@client');
    Route::get('client/{userId}/{token}', 'AuthController@clientConfirm');

});

Route::get('profile/{userId}', 'User\ProfileController@index');

Route::group(['middleware' => 'role:twitcher', 'namespace' => 'User\Twitcher', 'prefix' => 'user/twitcher'], function () {

    Route::get('/', 'IndexController@index');

    Route::get('profile', 'ProfileController@index');
    Route::post('profile', 'ProfileController@save');

    Route::get('billing', 'BillingController@index');
    Route::post('billing/withdraw', 'BillingController@withdraw');

    Route::get('notification', 'NotificationController@index');

    Route::get('banner', 'BannerController@index');
    Route::get('banner/popup', 'BannerController@popup');
    Route::get('banner/accept/{bannerId}', 'BannerController@accept');
    Route::get('banner/decline/{bannerId}', 'BannerController@decline');
    Route::get('banner/remove/{bannerId}', 'BannerController@decline');

});

Route::group(['middleware' => 'role:client', 'namespace' => 'User\Client', 'prefix' => 'user/client'], function () {

    Route::get('/', 'IndexController@index');

    Route::get('profile', 'ProfileController@index');
    Route::post('profile', 'ProfileController@save');

    Route::get('banner', 'BannerController@index');
    Route::post('banner', 'BannerController@save');

    Route::get('billing', 'BillingController@index');
    Route::post('billing/pay', 'BillingController@pay');

    Route::get('notification', 'NotificationController@index');

});

Route::group(['middleware' => 'role:admin', 'namespace' => 'Admin', 'prefix' => 'admin'], function () {

    Route::get('admin', 'Admin\IndexController@index');

});