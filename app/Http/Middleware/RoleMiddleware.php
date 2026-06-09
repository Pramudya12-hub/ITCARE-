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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            if (auth()->check()) {
                // Redirect back to their respective dashboard
                if (auth()->user()->role === 'it_support') {
                    return redirect()->route('support.dashboard');
                }
                return redirect()->route('user.dashboard');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}