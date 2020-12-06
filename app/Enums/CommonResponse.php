<?php

namespace App\Enums;

final class CommonResponse
{
    public static function getResponse(int $code)
    {
        return [
            "code" => $code,
            "message" => ApiStatusCode::MESSAGE[$code]
        ];
    }
}
