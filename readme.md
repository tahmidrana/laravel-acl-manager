# Laravel ACL Manager Package

A complete Role-Based Access Control (RBAC) package for Laravel with an admin panel UI, permission auto-sync, and middleware protection.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Protecting Routes](#protecting-routes)
  - [Checking Permissions](#checking-permissions)
  - [Blade Directives](#blade-directives)
- [Admin Panel](#admin-panel)
- [Models & Relationships](#models--relationships)
- [API Reference](#api-reference)

---

## Installation

### 1. Install via Composer

```bash
composer require tahmid/acl-manager
```

### 2. Publish Assets

```bash
php artisan vendor:publish --tag=acl-assets
php artisan vendor:publish --tag=acl-config
```

### 3. Run Migrations

```bash
php artisan migrate
```

This will create the following tables:
- `roles` - Role definitions
- `permissions` - Permission definitions
- `menus` - Menu/navigation definitions
- `role_user` - User-Role pivot table
- `permission_role` - Role-Permission pivot table
- `menu_role` - Role-Menu pivot table

Also modifies `users` table to add `is_superuser` column.

### 4. Update User Model

```php
// app/Models/User.php
use Tahmid\AclManager\Traits\AclManagerPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use AclManagerPermission;
    // ...
}
```

---

## Configuration

Publish and modify `config/acl.php`:

```php
// config/acl.php
return [
    /*
     * Dashboard route - where "Back to Dashboard" links go
     */
    'dashboard_route' => 'dashboard',

    /*
     * Column name in users table that marks superusers
     */
    'superuser_column' => 'is_superuser',

    /*
     * Middleware applied to ACL admin routes
     */
    'middleware' => ['web', 'auth', 'is_superuser'],
];
```

---

## Usage

### Protecting Routes

#### Auto-check by Controller Method

Routes automatically check permissions based on `ControllerName@methodName`:

```php
// routes/web.php
Route::middleware('role_permission_check')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
});
```

#### Superuser Only Routes

```php
Route::middleware('is_superuser')->group(function () {
    Route::get('/admin-only', [AdminController::class, 'index']);
});
```

### Checking Permissions

#### Using Facade

```php
use Tahmid\AclManager\Facades\Acl;

// Check if current user has permission (auto checks current user)
if (Acl::can('users.create')) { // permission name or slug as paramenter
    // Allow
}

// Check specific user
if (Acl::can('users.edit', $user)) { // // permission name or slug as paramenter
    // Allow
}

// Check role has permission
if (Acl::roleHasPermission('editor', 'posts.publish')) {// permission name or slug as paramenter
    // Allow
}
```

#### Using User Trait

```php
// In User model (after adding AclManagerPermission trait)
if ($user->hasPermission('users.delete')) { // permission name or slug as paramenter
    // Allow
}
```

### Blade Directives

```blade
{{-- Using Laravel's @can directive --}}
@can('users.create') // permission name or slug as paramenter
    <a href="{{ route('users.create') }}">Create User</a>
@endcan

{{-- Using package's @acl directive --}}
@acl('users.edit') // permission name or slug as paramenter
    <a href="{{ route('users.edit', $user->id) }}">Edit</a>
@endacl

{{-- With @else --}}
@acl('users.delete') // permission name or slug as paramenter
    <a href="#">Delete</a>
@else
    <span class="text-muted">No permission</span>
@endacl
```

---

## Admin Panel

Access the admin panel at `/acl-manager` (requires superuser).

### Features

- **Roles Management** - Create, edit, delete roles
- **Permissions Management** - Auto-sync permissions from controllers, manual creation
- **Menus Management** - Define navigation menus with hierarchy support

### Sync Permissions

Visit `/acl-manager/permissions/sync-permissions` to auto-scan all controllers and create permission entries from methods.

### Permission Descriptions

Add descriptions to permissions using PHP 8 attributes:

```php
use Tahmid\AclManager\Attributes\PermissionAttr;

class UserController extends Controller
{
    #[PermissionAttr(description: 'Create new user accounts')]
    public function store(Request $request) { }

    #[PermissionAttr(description: 'Delete existing user accounts')]
    public function destroy(User $user) { }
}
```

---

## Models & Relationships

---

## API Reference

### Middleware

| Middleware | Purpose |
|------------|---------|
| `is_superuser` | Restrict to superusers only |
| `role_permission_check` | Auto-check permission by controller@method |

### Facade Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `Acl::can($permission, $user)` | `string, ?User` | Check if user can perform action |
| `Acl::hasPermission($permission, $user)` | `string, ?User` | Alias for can() |
| `Acl::roleHasPermission($roleSlug, $permSlug)` | `string, string` | Check if role has permission |

### User Trait Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `$user->hasPermission($slug)` | `string` | Check if user has permission |
| `$user->roles()` | - | Get user's roles (BelongsToMany) |

---

## License

MIT License
