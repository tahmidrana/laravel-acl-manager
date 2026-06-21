<?php

namespace Tahmid\AclManager\Helpers;

use Tahmid\AclManager\Models\Menu;

class AccessControl
{
    public function hasPermission(string $permissionSlug, $user = null)
    {
        $user = $user ?: auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->{config('acl.superuser_column', 'is_superuser')}) {
            return true;
        }

        return method_exists($user, 'hasPermission')
            ? $user->hasPermission($permissionSlug)
            : false;
    }

    public function roleHasPermission(string $roleSlug, string $permissionSlug)
    {
        $roleSlug = strtolower($roleSlug);
        $permissionSlug = strtolower($permissionSlug);

        return \Tahmid\AclManager\Models\Role::where('slug', $roleSlug)
            ->whereHas('permissions', function ($q) use ($permissionSlug) {
                $q->where('slug', $permissionSlug)
                    ->orWhere('name', $permissionSlug);
            })->exists();
    }

    public function can(string $permission, $user = null): bool
    {
        $user = $user ?: auth()->user();

        if (! $user) {
            return false;
        }

        $superuserColumn = config('acl.superuser_column', 'is_superuser');
        if ($user->{$superuserColumn}) {
            return true;
        }

        return method_exists($user, 'hasPermission')
            ? $user->hasPermission($permission)
            : false;
    }

    public function getMenus($user = null, bool $activeOnly = true): \Illuminate\Database\Eloquent\Collection
    {
        $user = $user ?: auth()->user();

        if (! $user) {
            return collect();
        }

        if ($user->{config('acl.superuser_column', 'is_superuser')}) {
            $query = Menu::query();
        } else {
            $roleIds = $user->roles()
                ->wherePivot('is_active', true)
                ->wherePivotNull('released_at')
                ->pluck('roles.id');
            $query = Menu::whereHas('roles', function ($q) use ($roleIds) {
                $q->whereIn('roles.id', $roleIds);
            });
        }

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->orderBy('menu_order')->get();
    }

    public function getMenuTree($user = null, bool $activeOnly = true): \Illuminate\Database\Eloquent\Collection
    {
        $menus = $this->getMenus($user, $activeOnly);

        return $menus->whereNull('parent_menu_id')->map(function ($menu) use ($menus) {
            $menu->setRelation('sub_menus', $menus->where('parent_menu_id', $menu->id)->values());

            return $menu;
        })->values();
    }
}
