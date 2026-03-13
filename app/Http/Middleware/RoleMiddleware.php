<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->role !== $role) {
            // If user is admin, allow access to admin routes
            if ($request->user()->isAdmin() && $role === 'admin') {
                return $next($request);
            }
            
            // If user is regular user trying to access admin routes
            if ($role === 'admin') {
                return redirect()->route('dashboard')->with('error', 'Access denied. Admin privileges required.');
            }
            
            // If admin trying to access user-only routes
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}

