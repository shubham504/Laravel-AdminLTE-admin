<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PageController;

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


//Route::post('login', 'API\UserController@login');
Route::post('login', 'App\Http\Controllers\API\UserController@login');
Route::post('register', 'App\Http\Controllers\API\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'App\Http\Controllers\API\UserController@details');
    Route::get('/page/store', 'App\Http\Controllers\API\PageController@store')->name('page.store');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
