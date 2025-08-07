<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiUsage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        Log::info('Authenticated API Call', [
            'user_id' => $request->user()?->id,
            'ip_address' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'response_status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}
