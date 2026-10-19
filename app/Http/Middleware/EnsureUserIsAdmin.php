<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('admin.login'))
                ->with('warning', 'Please sign in with administrator credentials to access the admin panel.');
        }

        if (! $request->user()?->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Administrator privileges required.'], 403);
            }

            abort(403, 'Access denied. You do not have administrator privileges to view this area.');
        }

        return $next($request);
    }
}
