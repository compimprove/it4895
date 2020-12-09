<?php

namespace App\Http\Middleware;

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
                "code" => 1004,
                "message" => "Parameter value is not valid"
            ]);
        }
    }
}
