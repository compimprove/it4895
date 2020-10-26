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
});


Route::post('login', 'AuthController@getToken');
Route::post('register', 'AuthController@register');
Route::post('check-verify-code', 'AuthController@checkVerifyCode')->name("check_verify_code");
Route::post('testSaveFile', 'UserController@testSaveFile');
Route::post('testDeleteFile', 'UserController@testDeleveFile');

Route::get('messages', 'ChatController@fetchAllMessages');
Route::post('messages', 'ChatController@sendMessage');

Route::post('post/add', 'PostController@addPost');
Route::get('post/{id}', 'PostController@getPost');
Route::get('post/delete/{id}', 'PostController@deletePost');


Route::post('comment/add/{id}','CommentController@addComment');
Route::get('comment/{id}','CommentController@getComment');
Route::get('comment/delete/{id}','CommentController@deleteComment');

Route::post('like/{id}','UserLikePostController@likePost');
Route::get('dislike/{id}','UserLikePostController@dislikePost');

Route::post('report/{id}','UserReportPostController@reportPost');