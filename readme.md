## Laravel ACL Manager package v0.1

### Installation:
1. Install using:
```
$ composer require tahmid/acl-manager
```
2. Add this code on `User` model:

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
        ->whereHas('permissions', fn($q) => $q->where('slug', $slug)->orWhere('name', $slug))
        ->exists();
}
```

3. Publish Assets:
```
$ php artisan vendor:publish --tag=acl-manager-config
```
4. Run migrations:
```
$ php artisan migrate
```

### Usage:
* Change configs if required:
```
// config/acl.php

return [
    'dashboard_route' => '/dashboard',
    'superuser_column' => 'is_superuser',
];
```

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

* In routes use below middleware:
```
// routes/web.php

Route::middleware('role_permission_check')->group(function () {
    //
});
```
