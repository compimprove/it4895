<?php

namespace App\Enums;

final class ApiStatusCode
{
    const OK = "1000";
    const HAS_DONE = "1010";
    const NOT_EXISTED = "9992";
    const NO_DATA = "9994";
    const UNKNOW_ERROR = "1005";
    const MAXIMUM_SIZE_OF_FILE = "1008";
    const REQUIRE_PERMISSION_ACCESS = "1009";
    const PARAMETER_NOT_ENOUGH = "1002";
    const PARAMETER_TYPE_INVALID = "1003";
    const PARAMETER_NOT_VALID = "1004";
    const LOST_CONNECTED = "1001";
    const NOT_VALIDATE = "9995";
    const USER_EXISTED = "9996";
    const FILE_SIZE_TOO_BIG = "1006";

    const MESSAGE = [
        self::OK => "OK",
        self::PARAMETER_TYPE_INVALID => "Parameter type is invalid",
        self::PARAMETER_NOT_VALID => "Parameter value is not valid",
        self::HAS_DONE => "Action has been done previously by this user",
        self::PARAMETER_NOT_ENOUGH => "Parameter is not enough",
    ];
}

