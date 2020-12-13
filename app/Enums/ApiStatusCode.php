<?php

namespace App\Enums;

final class ApiStatusCode
{
    const OK = "1000";
    const HAS_DONE = "1010";
    const NOT_EXISTED = "9992";
    const NO_DATA = "9994";
    const CODE_VERIFY_INCORRECT = "9993";
    const UNKNOW_ERROR = "1005";
    const UPDATE_FAILED = "1007";
    const MAXIMUM_NUMBER_OF_FILE = "1008";
    const REQUIRE_PERMISSION_ACCESS = "1009";
    const PARAMETER_NOT_ENOUGH = "1002";
    const PARAMETER_TYPE_INVALID = "1003";
    const PARAMETER_NOT_VALID = "1004";
    const LOST_CONNECTED = "1001";
    const NOT_VALIDATE = "9995";
    const USER_EXISTED = "9996";
    const FILE_SIZE_TOO_BIG = "1006";
    const TOKEN_INVALID = "9998";

    const MESSAGE = [
        self::OK => "OK",
        self::PARAMETER_TYPE_INVALID => "Parameter type is invalid",
        self::PARAMETER_NOT_VALID => "Parameter value is not valid",
        self::HAS_DONE => "Action has been done previously by this user",
        self::PARAMETER_NOT_ENOUGH => "Parameter is not enough",
        self::NO_DATA => "No Data or end of list data",
        self::NOT_EXISTED => "Post is not existed",
        self::CODE_VERIFY_INCORRECT => "Code verify is incorrect",
        self::NOT_VALIDATE => "User is not validated",
        self::USER_EXISTED => "User existed",
        self::TOKEN_INVALID => "Token is invalid",
        self::LOST_CONNECTED => "Can not connect to DB",
        self::UPDATE_FAILED => "Upload File Failed!",
        self::MAXIMUM_NUMBER_OF_FILE => "Maximum number of images",

    ];
}

