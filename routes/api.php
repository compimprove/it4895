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


Route::middleware(['auth:sanctum', 'user-blocked'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/user/settings', "SettingsController@setPushSetting")->name("set_push_settings");
    Route::get('/user/notifications', "UserController@getNotifications")->name("get_notifications");
    Route::post('/user/notifications', "UserController@setReadNotification")->name("set_read_notification");
    Route::get('/user/settings', "SettingsController@getPushSetting")->name("get_push_settings");
    Route::get('/user/requested-friends', "UserController@getRequestedFriends")->name("get_requested_friends");
    Route::post('/user/requested-friends', "UserController@setRequestFriends")->name("set_request_friend");
    Route::get('/user/friends', "UserController@getFriends")->name("get_user_friends");
    Route::get('/user/suggested-friends', "UserController@getSuggestedFriends")->name("get_list_suggested_friends");
    Route::post('/user/friends', "UserController@setFriends")->name("set_accept_friend");
    Route::post("/logout", 'AuthController@logout');
    Route::post("/change-password", "AuthController@changePassword");
    Route::post("/device", "DeviceController@setDeviceInfo");
    Route::get("/user/{id}", "UserController@getInfo");
    Route::post('change-info-after-signup', 'UserController@changeInfoAfterSignup')->name("change_info_after_signup");
    Route::post("/set-user-info", "UserController@setUserInfo")->name("set_user_info");
    Route::get('messages/{userId2}', 'ChatController@fetchAllMessages');
    Route::post('messages/{userId2}', 'ChatController@sendMessage');
});

Route::post('login', 'AuthController@getToken');
Route::post('register', 'AuthController@register');
Route::post('check-verify-code', 'AuthController@checkVerifyCode')->name("check_verify_code");
Route::post('testSaveFile', 'UserController@testSaveFile');
Route::post('testDeleteFile', 'UserController@testDeleveFile');
Route::post('post/add', 'PostController@addPost');
Route::get('post/{id}', 'PostController@getPost');
Route::get('post/delete/{id}', 'PostController@deletePost');
