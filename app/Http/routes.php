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

Route::get('profile', 'User\ProfileController@index');
Route::get('profile/{userId}', 'User\ProfileController@user');

Route::group(['middleware' => 'role:twitcher', 'namespace' => 'User\Twitcher', 'prefix' => 'user/twitcher'], function () {

    Route::get('/', 'IndexController@index');

    Route::get('profile', 'ProfileController@index');
    Route::post('profile/save', 'ProfileController@save');

    Route::get('billing', 'BillingController@index');
    Route::post('billing/withdraw', 'BillingController@withdraw');

    Route::get('notification', 'NotificationController@index');

    Route::get('banner', 'BannerController@index');
    Route::get('banner/show/{bannerType}', 'BannerController@show');
    Route::get('banner/popup/{bannerType}', 'BannerController@popup');
    Route::get('banner/accept/{bannerId}', 'BannerController@accept');
    Route::get('banner/decline/{bannerId}', 'BannerController@decline');
    Route::get('banner/review/{bannerId}', 'BannerController@review');

    Route::get('banner/ping/{bannerType}', 'BannerController@ping');

});

Route::group(['middleware' => 'role:client', 'namespace' => 'User\Client', 'prefix' => 'user/client'], function () {

    Route::get('/', 'IndexController@index');

    Route::get('profile', 'ProfileController@index');
    Route::post('profile/save', 'ProfileController@save');

    Route::get('banner/{userId}', 'BannerController@index');
    Route::post('banner/save', 'BannerController@save');

    Route::group(['prefix' => 'billing'], function () {
        Route::get('/', 'BillingController@index');
        Route::get('log', 'BillingController@log');
        Route::post('card', 'BillingController@stripe');
        Route::post('paypal', 'BillingController@paypal');
        Route::get('paypal/callback/success/{userId}', 'BillingController@paypalSuccess');
        Route::get('paypal/callback/fail/{userId}', 'BillingController@paypalFail');
    });

    Route::get('notification', 'NotificationController@index');

    Route::get('streams', 'StreamController@index');
    Route::get('stream/{streamId}', 'StreamController@stream');
    Route::post('stream/accept/{streamId}', 'StreamController@accept');
    Route::post('stream/decline/{streamId}', 'StreamController@decline');

    Route::get('search', 'SearchController@index');

});

Route::group(['middleware' => 'role:admin', 'namespace' => 'Admin', 'prefix' => 'admin'], function () {

    Route::get('admin', 'Admin\IndexController@index');

});


