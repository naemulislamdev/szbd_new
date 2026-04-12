<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Ensures that all JSON responses encode float values like 2380.0
 * with a trailing ".0" instead of the bare integer "2380".
 *
 * This prevents "type 'int' is not a subtype of type 'double'" crashes
 * in the Flutter app, which uses strict Dart typing.
 */
class JsonFloatMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $response->setEncodingOptions(
                $response->getEncodingOptions() | JSON_PRESERVE_ZERO_FRACTION
            );
        }

        return $response;
    }
}
