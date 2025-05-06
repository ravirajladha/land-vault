<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogHttpRequest
{
    public function handle(Request $request, Closure $next)
    {
        // dd($request->all());
        // dd($request);
        // Call the next middleware and store the response
        $response = $next($request);

        // Log the request details
        $userId = Auth::id(); // Get authenticated user ID, if any
        $ipAddress = $request->ip(); // Get the IP address
        $userAgent = $request->header('User-Agent'); // Get the user agent
        $method = $request->method(); // Get the request method
        $url = $request->fullUrl(); // Get the full URL

        DB::table('http_request_logs')->insert([
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'method' => $method,
            'url' => $url,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return the response
        return $response;
    }
}
