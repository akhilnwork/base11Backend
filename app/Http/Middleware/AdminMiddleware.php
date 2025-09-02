<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated. Please log in.'], 401);
            }
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isAgent()) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => 'Access denied. Admin or Agent role required.'], 403);
            }
            abort(403, 'Access denied. Admin or Agent role required.');
        }

        return $next($request);
    }
}
