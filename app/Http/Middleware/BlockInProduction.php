<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns a 404 when the application runs in production. Used to keep the
 * Swagger/OpenAPI documentation routes unavailable on the public prod API.
 */
class BlockInProduction
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(app()->isProduction(), 404);

        return $next($request);
    }
}
