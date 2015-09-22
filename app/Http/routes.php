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

    Route::get('timeline', 'NotificationController@fresh');

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

    Route::get('timeline', 'NotificationController@fresh');

    Route::get('banner/{userId}', 'BannerController@index');
    Route::get('banners', 'BannerController@list');
    Route::post('banner/save', 'BannerController@save');
    Route::get('banner/{bannerId}/cancel', 'BannerController@cancel');
    Route::get('banner/{bannerId}/repeat', 'BannerController@repeat');

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
        Route::get('/', 'UserController@index');
        Route::get('{userId}', 'UserController@show');
        Route::get('{userId}/ban', 'UserController@ban');
        Route::get('{userId}/unban', 'UserController@unban');
        Route::get('{userId}/login-as', 'UserController@loginAs');
    });

    Route::group(['prefix' => 'ref'], function () {
        Route::get('/', 'RefController@index');
        Route::get('type/{type}', 'RefController@type');
        Route::get('{refId}/show', 'RefController@show');
        Route::get('{refId}/edit', 'RefController@edit');
        Route::post('{refId}/update', 'RefController@update');
        Route::get('{type}/create', 'RefController@create');
        Route::post('{type}/store', 'RefController@store');
        Route::get('{refId}/remove', 'RefController@destroy');

    });

    Route::group(['prefix' => 'withdraw'], function () {
        Route::get('/', 'WithdrawController@index');
        Route::get('all', 'WithdrawController@all');
        Route::get('{withdrawId}/show', 'WithdrawController@show');
        Route::get('{withdrawId}/accept', 'WithdrawController@accept');
        Route::get('{withdrawId}/decline', 'WithdrawController@decline');
        Route::post('{withdrawId}/decline', 'WithdrawController@declineSave');
    });

    Route::group(['prefix' => 'decline'], function () {
        Route::get('/', 'DeclineController@index');
        Route::get('{bannerStreamId}/show', 'DeclineController@show');
        Route::get('{bannerStreamId}/stream', 'DeclineController@stream');
        Route::get('{bannerStreamId}/client', 'DeclineController@client');
        Route::get('{bannerStreamId}/streamer', 'DeclineController@streamer');
    });

});

/*
Route::get('test', 'TestController@index');
Route::get('login-as/{userId}', 'TestController@loginAs');
*/
Route::get('test/streams', 'TestController@recalcStreams');

