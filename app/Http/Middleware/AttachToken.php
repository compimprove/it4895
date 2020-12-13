<?php

namespace App\Http\Middleware;

use App\Enums\ApiStatusCode;
use App\Enums\CommonResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttachToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $validatorRequire = Validator::make($request->query(), [
            'token' => 'required',
        ]);
        if ($validatorRequire->fails()) {
            return response()->json(CommonResponse::getResponse(ApiStatusCode::PARAMETER_NOT_ENOUGH));
        }
        $token = $request->query('token');
        if ($token != "") {
            $request->headers->set("Authorization", "Bearer " . $token);
            return $next($request);
        } else {
            return response()->json(
                CommonResponse::getResponse(ApiStatusCode::TOKEN_INVALID)
            );
        }
    }
}
