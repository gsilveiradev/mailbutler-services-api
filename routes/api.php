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

Route::group(['namespace' => 'Api', 'middleware' => 'cors'], function () {
    
    Route::group(['namespace' => 'V1', 'prefix' => 'v1'], function () {
        /*
        |--------------------------------------------------------------------------
        | Exclusive routes for authentication service
        |--------------------------------------------------------------------------
        */
        Route::post('/authentication', 'UsersAuthenticationController@authenticate');
        Route::post('/authentication/forgot_password', 'UsersAuthenticationController@forgotPassword');

        Route::group(['middleware' => ['jwt.auth']], function () {

            Route::put('/authentication/change_password', 'UsersAuthenticationController@changePassword');
            Route::post('/authentication/logout', 'UsersAuthenticationController@logout');

            Route::get('/authentication/refresh_token', 'UsersAuthenticationController@refreshToken');
        });

        /*
        |--------------------------------------------------------------------------
        | Routes with required login
        |--------------------------------------------------------------------------
        */
        Route::group(['middleware' => ['jwt.auth']], function () {

            /*
            |--------------------------------------------------------------------------
            | Exclusive routes for users service
            |--------------------------------------------------------------------------
            */
            Route::resource('users', 'UsersController');
        });
    });
});
