<?php

namespace App\Http\Middleware;

use App\Enums\ApiStatusCode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $bodyData = json_decode($response->getContent());
        // Perform action
        foreach (ApiStatusCode::MESSAGE as $code => $message) {
            if ($bodyData->code == $code) {
                $bodyData->message = $message;
                $response->setContent(json_encode($bodyData));
                return $response;
            }
        }
        return $response;
    }
}
