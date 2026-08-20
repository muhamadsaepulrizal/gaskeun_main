<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckBaseRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Expected base role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('login');
        }

        $userRole = $user->roles->first();

        if (!$userRole) {
            throw UnauthorizedException::forRoles([$role]);
        }

        // Get the base role, fallback to the role name if base_role is null
        $baseRole = $userRole->base_role ?? $userRole->name;

        // Support multiple roles separated by pipe, e.g., 'Agen LPG|Disperindag'
        $roles = explode('|', $role);

        if (!in_array($baseRole, $roles)) {
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}
