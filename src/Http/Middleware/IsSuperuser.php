<?php

namespace Tahmid\AclManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsSuperuser
{
    public function handle(Request $request, Closure $next)
    {
        $column = config('acl.superuser_column', 'is_superuser');

        if (auth()->check() && auth()->user()->{$column}) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
