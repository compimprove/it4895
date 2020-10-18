<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post("/logout", 'AuthController@logout');
    Route::post("/change-password", "AuthController@changePassword");
    Route::post("/device", "DeviceController@setDeviceInfo");
});
Route::post('login', 'AuthController@getToken');
Route::post('register', 'AuthController@register');

Route::get('messages', 'ChatController@fetchAllMessages');
Route::post('messages', 'ChatController@sendMessage');

Route::post('post/add', 'PostController@addPost');
Route::get('post/{id}', 'PostController@getPost');
Route::get('post/delete/{id}', 'PostController@deletePost');
