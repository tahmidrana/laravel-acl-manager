<?php

namespace Tahmid\AclManager\Traits;

use Tahmid\AclManager\Models\Menu;
use Tahmid\AclManager\Models\Role;
use Tahmid\AclManager\Models\RoleUser;

trait AclManagerPermission
{
    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withPivot('is_primary', 'is_active', 'released_at')
            ->withTimestamps()
            ->using(RoleUser::class);
    }

    public function menus(bool $activeOnly = true)
    {
        if ($this->{config('acl.superuser_column', 'is_superuser')}) {
            $query = Menu::query();
        } else {
            $roleIds = $this->roles()->where('roles.is_active', true)->wherePivot('is_active', true)->wherePivotNull('released_at')->pluck('roles.id');

            $query = Menu::whereHas('roles', function ($q) use ($roleIds) {
                $q->whereIn('roles.id', $roleIds);
                    // ->wherePivot('is_active', true);
            });
        }

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->orderBy('menu_order');
    }

    public function menuTree(bool $activeOnly = true)
    {
        $menus = $this->menus($activeOnly)->get();

        return $menus->whereNull('parent_menu_id')->map(function ($menu) use ($menus) {
            $menu->setRelation('sub_menus', $menus->where('parent_menu_id', $menu->id)->values());

            return $menu;
        })->values();
    }

    public function hasPermission(string $slug): bool
    {
        $slug = strtolower($slug);

        return $this->roles()
            ->where('roles.is_active', true)
            ->wherePivot('is_active', true)
            ->wherePivotNull('released_at')
            ->whereHas('permissions', function ($q) use ($slug) {
                $q->where('permissions.is_active', true)
                    ->where(fn ($sub) => $sub->where('slug', $slug)->orWhere('name', $slug));
            })
            ->exists();
    }
}
