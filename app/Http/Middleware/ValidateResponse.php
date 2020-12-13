<?php

namespace App\Http\Middleware;

use App\Enums\ApiStatusCode;
use Closure;
use Illuminate\Http\Request;

class ValidateResponse
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
        $response = $next($request);
        // Perform action
        foreach (ApiStatusCode::MESSAGE as $code => $message) {
            if ($response["code"] === $code) {
                $response["message"] = $message;
            }
            return $response;
        }
    }
}
