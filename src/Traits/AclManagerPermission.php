<?php

namespace Tahmid\AclManager\Traits;

use Tahmid\AclManager\Models\Menu;
use Tahmid\AclManager\Models\Role;

trait AclManagerPermission
{
    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withTimestamps();
    }

    public function menus(bool $activeOnly = true)
    {
        if ($this->{config('acl.superuser_column', 'is_superuser')}) {
            $query = Menu::query();
        } else {
            $roleIds = $this->roles()->wherePivot('is_active', true)->wherePivotNull('released_at')->pluck('roles.id');

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
            ->wherePivot('is_active', true)
            ->wherePivotNull('released_at')
            ->whereHas('permissions', fn ($q) => $q->where('slug', $slug)->orWhere('name', $slug))
            ->exists();
    }
}
