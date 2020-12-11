<?php

namespace App\Http\Middleware;

use App\Enums\ApiStatusCode;
use Closure;
use Illuminate\Http\Request;

class UserBlocked
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
        if ($request->user()->isBlocked()) {
            return response()->json([
                "code" => ApiStatusCode::NOT_VALIDATE,
                "message" => "User is blocked"
            ]);
        } else {
            return $next($request);
        }
    }
}
