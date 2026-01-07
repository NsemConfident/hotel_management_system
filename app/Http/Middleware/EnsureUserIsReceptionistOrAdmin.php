<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsReceptionistOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('filament.receptionist.auth.login');
        }

        $user = auth()->user();

        if (!$user->isReceptionist() && !$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Access denied. Receptionist, Manager, or Admin role required.');
        }

        return $next($request);
    }
}

