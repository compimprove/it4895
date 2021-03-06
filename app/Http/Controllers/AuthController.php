<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function getToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|digits:10',
            'password' => 'required|string|max:10|min:6',
            'uuid' => 'required|uuid'
        ]);
        if ($validator->fails()) {
            return [
                "code" => 1003,
                "message" => "Parameter type is invalid",
                "data" => $validator->errors()
            ];
        }
        $user = User::where('phone_number', $request["phone_number"])->first();
        if ($this->checkPasswordCorrect($user, $request->password)) {
            $user->tokens()->delete();
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => [
                    "id" => $user->id,
                    "username" => $user->name,
                    "token" => $user->createToken(env('APP_KEY'))->plainTextToken,
                    "avatar" => $user->avatar
                ]
            ];
        } else {
            return [
                "code" => 1003,
                "message" => "Password is not correct"
            ];
        }
    }

    private function checkPasswordCorrect($user, string $password): bool
    {
        return ($user && Hash::check($password, $user->password));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|unique:users|digits:10',
            'password' => 'required|string|max:10|min:6',
            'uuid' => 'required'
        ]);
        if ($validator->fails()) {
            $phoneUniqueValidator = Validator::make($request->all(), [
                'phone_number' => 'required|unique:users'
            ]);
            if ($phoneUniqueValidator->fails()) {
                return [
                    "code" => 9996,
                    "message" => "User existed"
                ];
            } else {
                return [
                    "code" => 1003,
                    "message" => "Parameter type is invalid",
                    "data" => $validator->errors()
                ];
            }
        }
        $user = User::makeUser([
            "phone_number" => $request["phone_number"],
            "password" => $request["password"],
            "uuid" => $request["uuid"],
            "name" => $request["name"],
            "email" => $request["email"]
        ]);
        $user->save();
        return [
            "code" => 1000,
            "message" => "OK"
        ];
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
        if ($user["is_blocked"]) {
            return [
                "code" => 9995,
                "message" => "User is not validated"
            ];
        }
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|max:10|min:6',
            'new_password' => 'required|string|max:10|min:6'
        ]);
        if ($validator->fails()) {
            return [
                "code" => 1003,
                "message" => "Parameter type is invalid",
                "data" => $validator->errors()
            ];
        } else if (!$this->checkPasswordCorrect($user, $request['password'])) {
            return [
                "code" => 1003,
                "message" => "Old password is not correct"
            ];
        } else {
            $user->changePassword($request["new_password"]);
            $user->save();
            return [
                "code" => 1000,
                "message" => "OK"
            ];
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response([
            "code" => 1000,
            "message" => "OK"
        ]);
    }

    public function checkVerifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|digits:10',
            'code_verify' => 'required',
        ]);
        if ($validator->fails()) {
            if (Validator::make($request->all(), [
                'code_verify' => 'required',
            ])->fails()) {
                return [
                    "code" => 1002,
                    "message" => "Dont have Code Verify",
                ];
            } else {
                return [
                    "code" => 1003,
                    "message" => "Parameter type is invalid",
                    "data" => $validator->errors()
                ];
            }
        } else {
            $user = User::where("phone_number", $request["phone_number"])->first();
            if ($user == null) {
                return [
                    "code" => 1004,
                    "message" => "User didn't exist",
                ];
            } else {
                $user->tokens()->delete();
                return [
                    "code" => 1000,
                    "message" => "OK",
                    "data" => [
                        "id" => $user->id,
                        "token" => $user->createToken(env('APP_KEY'))->plainTextToken,
                    ]
                ];
            }
        }
    }
}
