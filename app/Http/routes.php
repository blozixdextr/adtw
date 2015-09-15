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

    Route::get('admin', 'AuthController@admin');
    Route::post('admin', 'AuthController@postAdmin');


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

    Route::get('streams', 'StreamController@index');
    Route::get('ads', 'StreamController@index');
    Route::get('stream/{streamId}', 'StreamController@stream');
    Route::get('stream/{streamId}/{bannerId}/accept-decline', 'StreamController@acceptDecline');
    Route::get('stream/{streamId}/{bannerId}/complain-decline', 'StreamController@complainDecline');
    Route::post('stream/{streamId}/{bannerId}/complain-decline', 'StreamController@complainDeclineSave');

    Route::group(['prefix' => 'billing'], function () {
        Route::get('/', 'BillingController@index');
        Route::get('log', 'BillingController@log');
        Route::get('transfers', 'BillingController@transfers');
        Route::post('withdraw', 'BillingController@withdraw');
    });

});

Route::group(['middleware' => 'role:client', 'namespace' => 'User\Client', 'prefix' => 'user/client'], function () {

    Route::get('/', 'IndexController@index');

    Route::get('profile', 'ProfileController@index');
    Route::post('profile/save', 'ProfileController@save');

    Route::get('banner/{userId}', 'BannerController@index');
    Route::get('banners', 'BannerController@list');
    Route::post('banner/save', 'BannerController@save');

    Route::group(['prefix' => 'billing'], function () {
        Route::get('/', 'BillingController@index');
        Route::get('log', 'BillingController@log');
        Route::get('transfers', 'BillingController@transfers');
        Route::post('card', 'BillingController@stripe');
        Route::post('paypal', 'BillingController@paypal');
        Route::get('paypal/callback/success/{userId}', 'BillingController@paypalSuccess');
        Route::get('paypal/callback/fail/{userId}', 'BillingController@paypalFail');
    });

    Route::get('notification', 'NotificationController@index');

    Route::get('streams', 'StreamController@index');
    Route::get('ads', 'StreamController@index');
    Route::get('stream/{streamId}', 'StreamController@stream');
    Route::get('stream/{streamId}/{bannerId}/accept', 'StreamController@accept');
    Route::get('stream/{streamId}/{bannerId}/decline', 'StreamController@decline');
    Route::post('stream/{streamId}/{bannerId}/decline', 'StreamController@declineSave');

    Route::get('search', 'SearchController@index');

});

Route::group(['middleware' => 'admin', 'namespace' => 'Admin', 'prefix' => 'admin'], function () {

    Route::get('/', 'IndexController@index');

    Route::group(['prefix' => 'user'], function () {
        Route::get('list', 'UserController@index');
        Route::get('{userId}', 'UserController@show');
        Route::get('{userId}/billing', 'UserController@billing');
        Route::get('{userId}/ban', 'UserController@ban');
        Route::get('{userId}/unban', 'UserController@unban');
        Route::get('{userId}/login-as', 'UserController@loginAs');
    });

    Route::group(['prefix' => 'ref'], function () {
        Route::get('{type}', 'RefController@index');
        Route::get('{type}/{userId}', 'RefController@show');
        Route::post('{type}/{userId}', 'RefController@save');
    });

    Route::get('withdraw', 'Admin\WithdrawController@index');
    Route::get('withdraw/{withdrawId}', 'Admin\WithdrawController@show');
    Route::get('withdraw/{withdrawId}/accept', 'Admin\WithdrawController@accept');

    Route::get('decline', 'Admin\DeclineController@index');
    Route::get('decline/{bannerStreamId}', 'Admin\DeclineController@show');
    Route::get('decline/{bannerStreamId}/accept', 'Admin\DeclineController@accept');
    Route::get('decline/{bannerStreamId}/decline', 'Admin\DeclineController@decline');

});


Route::get('test', 'TestController@index');

Route::get('login-as/{userId}', 'TestController@loginAs');

