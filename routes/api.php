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
    Route::get("/user/{id}", "UserController@getInfo");
    Route::post('change-info-after-signup', 'UserController@changeInfoAfterSignup')->name("change_info_after_signup");
    Route::post("/set-user-info", "UserController@setUserInfo")->name("set_user_info");        
    Route::post('add_post', 'PostController@addPost');
    Route::post('edit_post', 'PostController@editPost');
    Route::get('get_post', 'PostController@getPost');
    Route::post('delete_post', 'PostController@deletePost');
    Route::get('get_list_posts', 'PostController@getListPost');
    Route::get('check_new_item', 'PostController@checkNewItem');
});


Route::post('login', 'AuthController@getToken');
Route::post('register', 'AuthController@register');
Route::post('check-verify-code', 'AuthController@checkVerifyCode')->name("check_verify_code");
Route::post('testSaveFile', 'UserController@testSaveFile');
Route::post('testDeleteFile', 'UserController@testDeleveFile');
	Route::get('test', 'PostController@test');

Route::get('messages', 'ChatController@fetchAllMessages');
Route::post('messages', 'ChatController@sendMessage');

