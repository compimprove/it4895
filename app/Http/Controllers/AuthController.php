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
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $user = User::where('email', $request->email)->first();

        if ($this->checkPasswordCorrect($user, $request->password)) {
            $user->tokens()->delete();
            return [
                'token' => $user->createToken(env('APP_KEY'))->plainTextToken
            ];
        } else {
            return response(['Not correct'], Response::HTTP_BAD_REQUEST);
        }
    }

    private function checkPasswordCorrect($user, string $password): bool
    {
        return ($user && Hash::check($password, $user->password));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) {
            return response($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $user = User::makeUser([
            "email" => $request["email"],
            "password" => $request["password"]
        ]);
        $user->save();
        return [
            "email" => $user->email
        ];
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) {
            return response($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $request->user()->changePassword($request["password"]);
        $request->user()->save();
        return [
            "email" => $request->user()->email
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response('', Response::HTTP_OK);
    }
}
