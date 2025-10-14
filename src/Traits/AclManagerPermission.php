<?php

namespace Tahmid\AclManager\Traits;

use Tahmid\AclManager\Models\Role;

trait AclManagerPermission
{
    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withTimestamps();
    }

    public function hasPermission(string $slug): bool
    {
        $slug = strtolower($slug);
        return $this->roles()
            ->wherePivot('is_active', true)
            ->whereHas('permissions', fn($q) => $q->where('slug', $slug)->orWhere('name', $slug))
            ->exists();
    }
}
