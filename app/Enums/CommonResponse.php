<?php

namespace App\Enums;

final class CommonResponse
{
    public static function getResponse($code): array
    {
        $code = (string) $code;
        return [
            "code" => $code,
            "message" => ApiStatusCode::MESSAGE[$code]
        ];
    }
}
