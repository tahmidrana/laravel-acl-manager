## Laravel ACL Manager package v0.1

### Installation:
1. Add this code on User model:

```
use Tahmid\AclManager\Models\Role;

public function roles()
{
    return $this->belongsToMany(Role::class)
            ->withPivot(['is_active', 'is_primary', 'released_at'])
            ->withTimestamps();
}

public function hasPermission(string $slug): bool
{
    return $this->roles()
        ->whereHas('permissions', fn($q) => $q->where('slug', $slug))
        ->exists();
}
```

### Usage:

* In Controller:
```
// use Acl;

if (\Acl::hasPermission('edit-users')) {
    // Allow access
}
or
if (\Acl::can('edit-users')) {
    // Allow access
}
```

* In Blade:
```
@can('edit-users')
    <a href="/admin">Admin Panel</a>
@endcan
or
@acl('edit-users')
    <a href="/admin">Admin Panel</a>
@endacl
```
