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

Route::middleware(['attach-token', 'auth:sanctum', 'user-blocked', 'validate-response'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/set_push_settings', "SettingsController@setPushSetting")->name("set_push_settings");
    Route::post('/get_notification', "UserController@getNotifications")->name("get_notification");
    Route::post('/set_read_notification', "UserController@setReadNotification")->name("set_read_notification");
    Route::post('/get_push_settings', "SettingsController@getPushSetting")->name("get_push_settings");
    Route::post('/get_requested_friends', "UserController@getRequestedFriends")->name("get_requested_friends");
    Route::post('/set_request_friend', "UserController@setRequestFriends")->name("set_request_friend");
    Route::post('/get_user_friends', "UserController@getFriends")->name("get_user_friends");
    Route::post('/get_list_suggested_friends', "UserController@getSuggestedFriends")->name("get_list_suggested_friends");
    Route::post('/set_accept_friend', "UserController@setFriends")->name("set_accept_friend");
    Route::post("/logout", 'AuthController@logout')->name("Logout");
    Route::post("/change_password", "AuthController@changePassword")->name("change_password");
    Route::post("/set_devtoken", "DeviceController@setDeviceInfo")->name("set_devtoken");
    Route::post("/get_list_blocks", "UserController@getBlock")->name("get_list_blocks");
    Route::post("/get_user_info", "UserController@getInfo")->name("get_user_info");
    Route::post('/change_info_after_signup', 'UserController@changeInfoAfterSignup')->name("change_info_after_signup");

    Route::post('add_post', 'PostController@addPost')->name("add_post");
    Route::post('edit_post', 'PostController@editPost')->name("edit_post");
    Route::post('get_post', 'PostController@getPost')->name("get_post");
    Route::post('delete_post', 'PostController@deletePost')->name("delete_post");
    Route::post('get_list_posts', 'PostController@getListPost')->name("get_list_posts");
    Route::post('check_new_item', 'PostController@checkNewItem')->name("check_new_item");

    Route::post("/set_user_info", "UserController@setUserInfo")->name("set_user_info");

    Route::post('/get_list_conversation', 'ChatController@getListConversation')->name("get_list_conversation");
    Route::post('/get_conversation', 'ChatController@getConversation')->name("get_conversation");
    Route::post('/set_read_message', 'ChatController@setReadMessage')->name("set_read_message");
    Route::post('/delete_message', 'ChatController@deleteMessage')->name("delete_message");
    Route::post('/delete_conversation', 'ChatController@deleteConversation')->name("delete_conversation");

    Route::get('message', 'ChatController@fetchAllMessages');
    Route::post('message', 'ChatController@sendMessage');

    Route::post('report_post', 'UserReportPostController@reportPost')->name("report_post");
    Route::post('/like_post', 'UserLikePostController@likePost')->name("like_post");
    Route::post('/get_comment', 'CommentController@getComment')->name("get_comment");
    Route::post('/set_comment', 'CommentController@addComment')->name("set_comment");
    Route::post("/set_block", "UserController@setBlock")->name("set_block");

    Route::post('search', 'SearchController@search')->name("search");
    Route::post('get_saved_search', 'SearchController@getSavedSearch')->name("get_saved_search");
    Route::post('del_saved_search', 'SearchController@delSavedSearch')->name("del_saved_search");

    Route::post('get_list_videos', 'VideoController@getListVideos')->name("get_list_videos");
    Route::post('check_new_version', 'SettingsController@checkNewVersion')->name("check_new_version");
});

Route::post('login', 'AuthController@getToken')->name("Login");
Route::post('signup', 'AuthController@register')->name("Signup");
Route::post('check_verify_code', 'AuthController@checkVerifyCode')->name("check_verify_code");
Route::post('get_verify_code', 'AuthController@getVerifyCode')->name("get_verify_code");


Route::post('testSaveFile', 'UserController@testSaveFile');
Route::post('testDeleteFile', 'UserController@testDeleveFile');
Route::get('test', 'PostController@test');
