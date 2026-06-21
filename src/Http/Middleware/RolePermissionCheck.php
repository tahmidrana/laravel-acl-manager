<?php

namespace Tahmid\AclManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $action = \Route::current()->action['controller'] ?? null;

        if (! $action || ! str_contains($action, 'Controllers\\')) {
            abort(403, 'Unauthorized');
        }

        $action_name = explode('Controllers\\', $action)[1];

        if (! \Acl::can($action_name)) {
            abort(403, 'You do not have permission to access this page');
        }

        return $next($request);
    }
}
