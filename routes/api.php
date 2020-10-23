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


Route::post('login1', 'PassportController@login');
Route::post('register1', 'PassportController@register');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');


    Route::resource('products', 'ProductController');
    Route::get('search/{data}', 'ProductController@search')->name('search');

    Route::resource('vegetables', 'vegetablesController');
    Route::resource('fruits', 'fruitsController');

    Route::resource('carts', 'cartController');
    Route::resource('wishes', 'wishController');


});
