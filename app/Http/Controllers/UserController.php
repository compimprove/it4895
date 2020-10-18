<?php

namespace App\Http\Controllers;

use App\Service\IFileService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $fileService;

    public function __construct(IFileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getInfo(Request $request, $id)
    {
        if ($request->user()["is_blocked"]) {
            return [
                "code" => 9995,
                "message" => "User is not validated"
            ];
        } else {
            $user = User::find($id);
            if ($user == null) {
                return [
                    "code" => 9994,
                    "message" => "User not found"
                ];
            } else if (false) {
                // nguoi dung $id chan tai khoan nguoi dung request
            } else {
                return [
                    "code" => 1000,
                    "message" => "OK",
                    "data" => [
                        "id" => $user["id"],
                        "username" => $user["name"],
                        "created" => $user["created_at"],
                        "avatar" => $user["avatar"],
                        "cover_image" => $user["cover_image"],
                        "address" => $user["address"],
                        "city" => $user["city"],
                        "country" => $user["country"],
                        "listing" => -1, // list friends
                        "is_friend" => -1,
                        "online" => false
                    ]
                ];
            }
        }
    }

    public function setUserInfo(Request $request)
    {
        $user = $request->user();
        if ($user->isBlocked()) {
            return [
                "code" => 9995,
                "message" => "User is not validated"
            ];
        }
        $validator = Validator::make($request->all(), [
            'username' => 'string',
            "description" => "string|max:150",
            'avatar' => 'file|max:1024',
            "address" => "string",
            "city" => "string",
            "country" => "string",
            'cover_image' => 'file|max:1024',
            "link" => "url",
        ]);
        if ($validator->fails()) {
            return [
                "code" => 1003,
                "message" => "Parameter type is invalid",
                "data" => $validator->errors()
            ];
        } else {
            if ($user->avatar != null) {
                $this->fileService->deleteFile($user->avatar);
            }
            if ($user->cover_image != null) {
                $this->fileService->deleteFile($user->cover_image);
            }
            $linkAvatar = $this->fileService->saveFile($request->file("avatar"));
            $user->avatar = $linkAvatar;
            $linkCoverImage = $this->fileService->saveFile($request->file("cover_image"));
            $user->cover_image = $linkCoverImage;
            $user["name"] = $request["username"];
            $user["description"] = $request["description"];
            $user["address"] = $request["address"];
            $user["city"] = $request["city"];
            $user["country"] = $request["country"];
            $user["link"] = $request["link"];
            $user->save();
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => [
                    "avatar" => Storage::url($linkAvatar),
                    "cover_image" => Storage::url($linkCoverImage),
                    "link" => $user->link,
                    "city" => $user->city,
                    "country" => $user->country,
                ]
            ];
        }
    }

    public function changeInfoAfterSignup(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'avatar' => 'file|max:1024',
        ]);
        if ($validator->fails()) {
            return [
                "code" => 1006,
                "message" => "File Too Large",
            ];
        } else if (strcmp($user->phone_number, $request["username"]) == 0) {
            return [
                "code" => 1004,
                "message" => "User name is invalid",
            ];
        } else {
            $linkAvatar = $this->fileService->saveFile($request->file("avatar"));
            $user->name = $request["username"];
            if ($user->avatar != null) {
                $this->fileService->deleteFile($user->avatar);
            };
            $user->avatar = $linkAvatar;
            $user->save();
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => [
                    "id" => $user->id,
                    "username" => $user->name,
                    "phonenumber" => $user["phone_number"],
                    "created" => $user["created_at"],
                    "avatar" => Storage::url($linkAvatar),
                ]
            ];
        }
    }

    public function testSaveFile(Request $request)
    {
        return Storage::url($this->fileService->saveFile($request->file("file")));
    }

    public function testDeleveFile(Request $request)
    {
        $this->fileService->deleteFile($request["link"]);
        return "OK";
    }
}
