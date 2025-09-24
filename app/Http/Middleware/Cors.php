<?php

namespace App\Http\Middleware;

use Closure;

class Cors  
{   
    public function handle($request, Closure $next)
    {
        $allowedOrigins = [
            'https://luxeragift.netlify.app',
            'http://localhost:3000', // for local dev
        ];

        $origin = $request->headers->get('Origin');

        $headers = [
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'     => 'Content-Type, X-Auth-Token, Origin, Authorization, X-CSRF-TOKEN, X-XSRF-TOKEN',
            'Access-Control-Allow-Credentials' => 'true',
        ];

        if (in_array($origin, $allowedOrigins)) {
            $headers['Access-Control-Allow-Origin'] = $origin;
        }

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
