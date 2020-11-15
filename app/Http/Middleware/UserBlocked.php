<?php

namespace App\Http\Middleware;

use Closure;

class UserBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->isBlocked()) {
            return response()->json([
                "code" => 9995,
                "message" => "User is blocked"
            ]);
        } else {
            return $next($request);
        }
    }
}
