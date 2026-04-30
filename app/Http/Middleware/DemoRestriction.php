<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DemoRestriction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If demo user → block dangerous actions
        if ($user && $user->role === 'demo') {

            // Block POST, PUT, DELETE (anything that changes data)
            if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
                return response()->json([
                    'message' => 'Demo mode: Action not allowed.'
                ], 403);
            }
        }

        return $next($request);
    }
}
