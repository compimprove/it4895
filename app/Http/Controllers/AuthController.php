<?php

namespace App\Http\Controllers;

use App\Enums\ApiStatusCode;
use App\Enums\CommonResponse;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private function passwordRegexFail(string $password): bool
    {
        return preg_match("/([A-Za-z0-9])\w+/", $password, $matches) != 1 || $matches[0] != $password;
    }

    public function getToken(Request $request)
    {
        $validatorRequire = Validator::make($request->query(), [
            'phonenumber' => 'required',
            'password' => 'required',
            'uuid' => 'required'
        ]);
        if ($validatorRequire->fails()) {
            return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_ENOUGH);
        }
        $phoneNumber = $request->query("phonenumber");
        $password = $request->query("password");
        if ($phoneNumber == "" && $password == "") {
            return [
                "code" => ApiStatusCode::PARAMETER_NOT_VALID,
                "message" => "Parameter is not enough"
            ];
        }
        if ($phoneNumber == $password || !str_starts_with($phoneNumber, "0")) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        }
        if ($this->passwordRegexFail($password)) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        };
        $validator = Validator::make($request->query(), [
            'phonenumber' => 'digits:10',
            'password' => 'string|max:10|min:6',
            'uuid' => 'uuid'
        ]);
        if ($validator->fails()) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid",
                "data" => $validator->errors()
            ];
        }

        $user = User::where('phone_number', $phoneNumber)->first();
        if ($user == null) {
            return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_VALID);
        }
        if ($this->checkPasswordCorrect($user, $password)) {
            $user->tokens()->delete();
            return [
                "code" => ApiStatusCode::OK,
                "message" => "OK",
                "data" => [
                    "id" => $user->id,
                    "username" => $user->name,
                    "token" => $user->createToken(env('APP_KEY'))->plainTextToken,
                    "avatar" => $user->avatar
                ]
            ];
        } else {
            return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_VALID);
        }
    }

    private function checkPasswordCorrect($user, string $password): bool
    {
        return ($user && Hash::check($password, $user->password));
    }

    public function register(Request $request)
    {
        $data = [];
        $data["phone_number"] = $request->query("phonenumber");

        $data["password"] = $request->query("password");
        if ($data["phone_number"] == "" && $data["password"] == "") {
            return [
                "code" => ApiStatusCode::PARAMETER_NOT_ENOUGH,
                "message" => "Parameter is not enough"
            ];
        }
        if ($data["phone_number"] == $data["password"]) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        }
        if ($this->passwordRegexFail($data["password"])) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        };
        if (!str_starts_with($data["phone_number"], "0")) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        }
        $data["uuid"] = $request->query("uuid");
        $data["name"] = $request->query("name");
        $data["email"] = $request->query("email");

        $validator = Validator::make($data, [
            'phone_number' => 'required|unique:users|digits:10',
            'password' => 'required|string|max:10|min:6',
            'uuid' => 'required'
        ]);
        if ($validator->fails()) {
            $phoneUniqueValidator = Validator::make($data, [
                'phone_number' => 'required|unique:users'
            ]);
            if ($phoneUniqueValidator->fails()) {
                return [
                    "code" => ApiStatusCode::USER_EXISTED,
                    "message" => "User existed"
                ];
            } else {
                return [
                    "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                    "message" => "Parameter type is invalid",
                    "data" => $validator->errors()
                ];
            }
        }
        $user = User::makeUser([
            "phone_number" => $data["phone_number"],
            "password" => $data["password"],
            "uuid" => $data["uuid"],
            "name" => $data["name"],
            "email" => $data["email"]
        ]);
        $user->save();
        return [
            "code" => ApiStatusCode::OK,
            "message" => "OK"
        ];
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
        if ($user["is_blocked"]) {
            return [
                "code" => ApiStatusCode::NOT_VALIDATE,
                "message" => "User is not validated"
            ];
        }
        $validator = Validator::make($request->query(), [
            'password' => 'required|string|max:10|min:6',
            'new_password' => 'required|string|max:10|min:6'
        ]);
        if ($validator->fails()) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid",
                "data" => $validator->errors()
            ];
        } else if ($this->passwordRegexFail($request->query("password"))) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        } else if (!$this->checkPasswordCorrect($user, $request->query("password"))) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid"
            ];
        } else if ($request->query("password") == $request->query("new_password")) {
            return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_VALID);
        } else {
            $user->changePassword($request->query("new_password"));
            $user->save();
            return [
                "code" => ApiStatusCode::OK,
                "message" => "OK"
            ];
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response([
            "code" => ApiStatusCode::OK,
            "message" => "OK"
        ]);
    }

    public function checkVerifyCode(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'phonenumber' => 'required|digits:10',
            'code_verify' => 'required',
        ]);
        if ($validator->fails()) {
            if (Validator::make($request->query(), [
                'code_verify' => 'required',
            ])->fails()) {
                return CommonResponse::getResponse(1002);
            } else {
                return [
                    "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                    "message" => "Parameter type is invalid",
                    "data" => $validator->errors()
                ];
            }
        } else {
            $user = User::where("phone_number", $request->query("phonenumber"))->first();
            if ($user == null) {
                return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_VALID);
            } else if ($user->verified_email_at != null) {
                return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_VALID);
            } else if ($user->verify_code != $request->query('code_verify')) {
                return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_VALID);
            } else {
                $user->tokens()->delete();
                $user->verified_email_at = new \DateTime();
                $user->save();
                return [
                    "code" => ApiStatusCode::OK,
                    "message" => "OK",
                    "data" => [
                        "id" => $user->id,
                        "token" => $user->createToken(env('APP_KEY'))->plainTextToken,
                    ]
                ];
            }
        }
    }

    public function getVerifyCode(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'phonenumber' => 'required|digits:10',
        ]);
        if ($validator->fails()) {
            return CommonResponse::getResponse(1004);
        }
        $phoneNumber = $request->query('phonenumber');

        $user = User::where('phone_number', $phoneNumber)->first();
        if ($user == null) {
            return CommonResponse::getResponse(1004);
        }
        if ($user->verified_email_at != null) {
            return CommonResponse::getResponse(1010);
        }
        if ($user->time_request_verify_code != null) {
            $time_request_verify_code = strtotime($user->time_request_verify_code);
            if (((new \DateTime())->getTimeStamp() - $time_request_verify_code) < 120) {
                return CommonResponse::getResponse(1010);
            }
        }

        $user->time_request_verify_code = new \DateTime();
        $user->verify_code = Str::random(6);
        $user->save();
        $response = CommonResponse::getResponse(1000);
        $response["data"] = [
            "code" => $user->verify_code
        ];
        return $response;
    }
}
