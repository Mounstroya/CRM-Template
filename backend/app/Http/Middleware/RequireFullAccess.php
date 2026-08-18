<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts a route to Propietario/Encargado (owner/manager) users.
 *
 * Assumption (rescued HTML didn't spell out exact role boundaries): Vendedor
 * accounts can see everything operational (sales, credits, warranties, cash
 * register, client list, inventory) but not user management, system
 * configuration, branch audits, or client create/edit — matching the owner's
 * "es el mismo sistema, solo ellos no pueden ver algunas cosas" description.
 */
class RequireFullAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->type, ['Propietario', 'Encargado'], true)) {
            if ($request->expectsJson()) {
                abort(403, 'No tienes permiso para esta sección.');
            }
            abort(403, 'No tienes permiso para esta sección.');
        }

        return $next($request);
    }
}
