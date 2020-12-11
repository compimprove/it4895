<?php

namespace App\Http\Middleware;

use App\Enums\ApiStatusCode;
use Closure;
use Illuminate\Http\Request;

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
        $token = $request->query('token');
        if ($token != "") {
            $request->headers->set("Authorization", "Bearer " . $token);
            return $next($request);
        } else {
            return response([
                "code" => ApiStatusCode::PARAMETER_NOT_VALID,
                "message" => "Parameter value is not valid"
            ]);
        }
    }
}
