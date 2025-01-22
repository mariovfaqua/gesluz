<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login'); // Redirigir si no está autenticado
        }

        if (Auth::user()->role !== $role) {
            abort(403, 'No tienes permiso para acceder a esta página'); // Error 403 si no es del rol adecuado
        }
        
        return $next($request);
    }
}
