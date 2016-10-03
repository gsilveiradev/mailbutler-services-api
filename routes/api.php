<?php

use Illuminate\Http\Request;

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

Route::group(['namespace' => 'Api'], function()
{
    /*
    |--------------------------------------------------------------------------
    | Exclusive routes for authentication service
    |--------------------------------------------------------------------------
    */
    Route::group(['namespace' => 'Authentication'], function()
    {
        Route::post('/authentication', 'AuthenticationController@authenticate');
        Route::post('/authentication/forgot_password', 'AuthenticationController@forgot_password');

        /*
        |--------------------------------------------------------------------------
        | Routes with required login
        |--------------------------------------------------------------------------
        */
        Route::group(['middleware' => ['jwt.auth']], function()
        {

            Route::put('/authentication/change_password', 'AuthenticationController@change_password');
            Route::post('/authentication/logout', 'AuthenticationController@logout');

            Route::get('/authentication/refresh_token', 'AuthenticationController@refresh_token');
        });
    });
});