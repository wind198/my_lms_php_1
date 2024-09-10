<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class SetSharedProps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $sharedProps): Response
    {
        $propsArray = [];

        // Split the string by commas to separate different key-value pairs
        $pairs = explode(';', $sharedProps);

        // Iterate over each pair
        foreach ($pairs as $pair) {
            // Split each pair by the '=' sign to separate the key and value
            [$key, $value] = explode('=', $pair);

            // Add the key-value pair to the array
            $propsArray[trim($key)] = trim($value);
        }

        foreach ($propsArray as $key => $value) {
            $request->attributes->set($key, $value);
        }
        // Share the key-value pairs with Inertia
        Inertia::share($propsArray);
        return $next($request);
    }
}
