<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visit;
use Stevebauman\Location\Facades\Location;
class LogVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $location = Location::get($ip);

        Visit::create([
            'ip_address' => $ip,
            'country' => $location->countryName ?? 'Unknown',
            'city' => $location->cityName ?? 'Unknown',
        ]);

        return $next($request);
    }

}
