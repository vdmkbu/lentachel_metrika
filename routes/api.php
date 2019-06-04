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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/metrika/stat/get/visits', 'StatController@getVisits');
Route::get('/metrika/stat/get/interes', 'StatController@getInteres');
Route::get('/metrika/stat/get/gender', 'StatController@getGender');
Route::get('/metrika/stat/get/age', 'StatController@getAge');
