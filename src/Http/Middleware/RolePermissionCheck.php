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
        // $user = $request->user();
        $action = \Route::current()->action['controller'] ?? null;
        if (! \Acl::can($action_name)) {
            abort(404, 'Controller not found');
        }

        $action_name = explode('Controllers\\', $action)[1];
        // if (! $user->hasPermission($action_name)) {
        if (! \Acl::can($action_name)) {
            abort(401, 'You do not have permission to acccess this page');
        }

        return $next($request);
    }
}
