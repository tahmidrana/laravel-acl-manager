<?php

namespace Tahmid\AclManager\Helpers;

use Illuminate\Support\Facades\Auth;
use Tahmid\AclManager\Models\Permission;

class AccessControl
{
    public function hasPermission($permissionSlug, $user = null)
    {
        $user = $user ?: Auth::user();

        if (!$user) return false;

        if ($user->{config('acl.superuser_column', 'is_superuser')}) {
            return true;
        }

        return $user->roles()
            ->wherePivot('is_active', true)
            ->whereHas('permissions', function ($q) use ($permissionSlug) {
                $q->where('slug', $permissionSlug)
                    ->orWhere('name', $permissionSlug);
            })->exists();
    }

    public function roleHasPermission($roleSlug, $permissionSlug)
    {
        return \Tahmid\AclManager\Models\Role::where('slug', $roleSlug)
            ->whereHas('permissions', function ($q) use ($permissionSlug) {
                $q->where('slug', $permissionSlug)
                    ->orWhere('name', $permissionSlug);
            })->exists();
    }

    public function can(string $permission, $user = null): bool
    {
        $user = $user ?: auth()->user();

        if (!$user) {
            return false;
        }

        // Check superuser column
        $superuserColumn = config('acl.superuser_column', 'is_superuser');
        if ($user->{$superuserColumn}) {
            return true;
        }

        // Your own permission check logic
        return method_exists($user, 'hasPermission')
            ? $user->hasPermission($permission)
            : false;
    }
}
