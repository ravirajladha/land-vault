<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$item, $key) {
            // If the item is an empty string, change it to null
            $item = $item === '' ? null : $item;
        });

        $request->merge($input);

        return $next($request);
    }
}
