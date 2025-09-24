<?php

namespace App\Http\Middleware;

use Closure;

class Cors  
{   
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => 'https://luxeragift.netlify.app',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'     => 'Content-Type, X-Auth-Token, Origin, Authorization, X-CSRF-TOKEN, X-XSRF-TOKEN',
            'Access-Control-Allow-Credentials' => 'true',
        ];

        if ($request->getMethod() === "OPTIONS") {
            return response()->json('OK', 200, $headers);
        }

        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
