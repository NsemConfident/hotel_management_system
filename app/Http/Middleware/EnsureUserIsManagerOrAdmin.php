<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsManagerOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('filament.manager.auth.login');
        }

        $user = auth()->user();

        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Access denied. Manager or Admin role required.');
        }

        return $next($request);
    }
}

