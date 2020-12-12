<?php

namespace App\Http\Controllers;

use App\Enums\ApiStatusCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    public function setDeviceInfo(Request $request)
    {
        if ($request->user()["is_blocked"]) {
            return [
                "code" => ApiStatusCode::NOT_VALIDATE,
                "message" => "User is not validated"
            ];
        }
        $validator = Validator::make($request->query(), [
            'devtype' => 'required|uuid',
            'devtoken' => 'required'
        ]);
        if ($validator->fails()) {
            return [
                "code" => ApiStatusCode::PARAMETER_TYPE_INVALID,
                "message" => "Parameter type is invalid",
                "data" => $validator->errors()
            ];
        } else {
            return [
                "code" => ApiStatusCode::OK,
                "message" => "OK"
            ];
        }
    }
}
