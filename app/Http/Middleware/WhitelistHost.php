<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WhitelistHost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Define your whitelisted hosts
        $whitelistedHosts = [
            'localhost:3000',
            'localhost:3001',
            'appointments.coderkeys.com'
        ];

        // Get the host from the request
        $host = $request->getHost();

        // Get the User-Agent from the request
        $userAgent = $request->header('User-Agent');

        // Check if the host is in the whitelist
         // Check if the User-Agent contains "PostmanRuntime"
       /*  if (!in_array($host, $whitelistedHosts) && strpos($userAgent, 'PostmanRuntime') === false) {
            // If not, return a 403 Forbidden response
            return response('Forbidden', 403);
        } */

        // If the host is whitelisted, proceed with the request
        return $next($request);
    }
}
