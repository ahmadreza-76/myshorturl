<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function () {

    Route::post('register','AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout')->middleware('auth:api');
    Route::post('refresh', 'AuthController@refresh')->middleware('auth:api');
    Route::get('failed','AuthController@failed')->name('login');

});

Route::group([
    'prefix' => 'url',
    'middleware' => ['auth:api'] ,

], function () {

    Route::post('create','URLController@create'); //create new short url
    Route::get('all','URLController@getAll'); //create all short url's created by himself
    Route::get('analytic/{short_url}','URLController@analytic')->middleware('checkUrlOwner');//get analytics of this short url


});


