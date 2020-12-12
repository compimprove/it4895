<?php

namespace App\Http\Controllers;

use App\Enums\ApiStatusCode;
use App\Enums\CommonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    public function setDeviceInfo(Request $request)
    {
        $validatorRequire = Validator::make($request->query(), [
            'devtype' => 'required',
            'devtoken' => 'required'
        ]);
        if ($validatorRequire->fails()) {
            return CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_ENOUGH);
        }
        $validator = Validator::make($request->query(), [
            'devtoken' => 'uuid'
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
