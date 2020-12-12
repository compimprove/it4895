<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Enums\ApiStatusCode;
use App\Enums\CommonResponse;
use App\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function setPushSetting(Request $request)
    {
        $settings = [
            "like_comment" => null,
            "from_friends" => null,
            "requested_friend" => null,
            "suggested_friend" => null,
            "birthday" => null,
            "video" => null,
            "report" => null,
            "sound_on" => null,
            "notification_on" => null,
            "vibrant_on" => null,
            "led_on" => null
        ];
        foreach ($settings as $setting => $value) {
            $queryValue = $request->query($setting);
            if ($queryValue == '' || ((int)$queryValue != 0 && (int)$queryValue != 1)) {
                return [
                    "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                    "message" => "Parameter type is invalid: " . $setting
                ];
            } else {
                $settings[$setting] = $queryValue;
            }
        }
        $request->user()->setting()->delete();
        $settings["user_id"] = $request->user()->id;
        Settings::create($settings);
        return [
            "code" => ApiStatusCode::OK,
            "message" => "OK"
        ];
    }

    public function checkNewVersion(Request $request)
    {
        $lastUpdate = $request->query("last_update");
        $user = $request->user();
        if ($lastUpdate == "") {
            return CommonResponse::getResponse(ApiStatusCode::PARAMETER_TYPE_INVALID);
        }
        $response = CommonResponse::getResponse(ApiStatusCode::OK);
        $notifications = $user->notifications->toArray();
        $badge = array_reduce($notifications, function ($sum, $item) {
            if (!$item["is_read"]) $sum += 1;
        });
        $messages = Chat::getAllMessagesOf($user->id);
        $unreadMessage = array_reduce($messages, function ($sum, $item) {
            if (!$item["has_read"]) $sum += 1;
        });
        $response["data"] = [
            "version" => [
                "1.1.1.A",
                "0",
                "https://www.facebook.com/"
            ],
            "user" => [
                (string)$request->user()->id,
                $request->user()->isBlocked() ? "0" : "1"
            ],
            "badge" => (string)$badge,
            "unread_message" => (string)$unreadMessage,
            "now" => "0.1.0.9A"
        ];
        return $response;
    }

    public function getPushSetting(Request $request)
    {
        if ($request->user()->setting == null) {
            $newUserSetting = new Settings();
            $newUserSetting->user_id = $request->user()->id;
            $newUserSetting->save();
        }
        $userSetting = Settings::find($newUserSetting->id);
        unset($userSetting["user_id"]);
        unset($userSetting["id"]);
        unset($userSetting["created_at"]);
        unset($userSetting["updated_at"]);
        return [
            "code" => ApiStatusCode::OK,
            "message" => "OK",
            "data" => $userSetting
        ];
    }
}
