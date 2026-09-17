# Known Issues

Open items identified during the September 2026 review of `tahmid/laravel-acl-manager`.
Each entry states the impact, where it lives, and the suggested fix.

Issues already resolved are listed at the bottom for reference.

---

## 1. No test suite and no CI

**Severity:** High — this is the most significant gap.

The package has no `tests/` directory and no `.github/` workflows. For a package
whose responsibility is deciding who may do what, an untested permission check is
the highest-risk code in any consuming application: a regression in
`hasPermission()` fails open or closed silently, with no signal.

This is not theoretical. Three separate defects found during the review would each
have been caught by a single basic test:

- `AccessControl::getMenus()` threw a `TypeError` for guests (return type mismatch)
- `PermissionController::destroy_not_exists()` was routed but never defined
- `Permission` had no `roles()` inverse relation

**Suggested fix:** add [Orchestra Testbench](https://packages.tools/testbench) and a
GitHub Actions matrix across PHP 8.1–8.4 × Laravel 10–12. The matrix doubles as
proof of the backward-compatibility range declared in `composer.json`, which is
currently asserted but never exercised.

Minimum worthwhile coverage:

- `hasPermission()` — grants, denies, inactive role, inactive permission, released role
- `AccessControl::can()` — superuser bypass, guest, user without the trait
- `syncPermissions()` — ignore lists, nested controllers, attribute descriptions
- Every admin route returns 200 for a superuser and 403 otherwise

---

## 2. Soft-delete support is inconsistent between schema and models

**Severity:** Medium — silent data loss.

The migration creates `deleted_at` columns for `menus`, `roles` and `permissions`,
but `SoftDeletes` is commented out on two of the three models:

| Model | `deleted_at` column | `SoftDeletes` trait |
|---|---|---|
| `Permission` | yes | **enabled** |
| `Role` | yes | commented out (`src/Models/Role.php`) |
| `Menu` | yes | commented out (`src/Models/Menu.php`) |

So deleting a role or menu is permanent, the `deleted_at` column sits permanently
unused, and the `onDelete('cascade')` foreign keys drop the pivot rows
irreversibly — a mis-click on "delete role" cannot be undone.

**Suggested fix:** decide one way and make schema and models agree. Enabling
`SoftDeletes` on `Role` and `Menu` matches the existing schema and is the
lower-risk option, but note it changes delete semantics for existing consumers, so
it belongs in a minor version bump with a changelog note.

---

## 3. `Role` has no `users()` inverse relation

**Severity:** Low — missing feature rather than a defect.

`AclManagerPermission::roles()` defines the User → Role side of the `role_user`
pivot, but `Role` has no corresponding `users()` method, so there is no way to ask
"who holds this role?" without dropping to a raw query.

It was deliberately left out because the package cannot know the consuming
application's User model. Resolving it needs a configurable model class rather than
a hardcoded `App\Models\User`.

**Suggested fix:** add a `user_model` key to `config/acl.php` defaulting to
`App\Models\User`, then define:

```php
public function users()
{
    return $this->belongsToMany(config('acl.user_model', \App\Models\User::class))
        ->withPivot('is_primary', 'is_active', 'released_at')
        ->withTimestamps()
        ->using(RoleUser::class);
}
```

---

## 4. `Permission::insert()` bypasses model events and casts

**Severity:** Low — works correctly today.

`PermissionController::syncPermissions()` uses raw `Permission::insert()` rather
than mass model creation. Raw insert skips model events, mutators and attribute
casting, and does not set `is_active` (it relies on the database column default).

It is correct as written — the `$saved_permissions` guard properly uses
`withTrashed()` to avoid duplicate-key errors — but it is fragile if model-level
behaviour is ever added to `Permission`.

**Suggested fix:** leave as-is unless `Permission` gains model events. If changed,
`upsert()` on the unique `name` column would be the closest equivalent that still
performs a single query.

---

## 5. `role_user.user_id` has no foreign key, and column types may mismatch

**Severity:** Low in practice, but a real portability trap.

The foreign key on `role_user.user_id` is deliberately commented out in the
migration, because the package cannot know the consuming application's user key
type. However the column is declared `unsignedInteger`, while the Laravel default
`users` table uses `bigIncrements` (`unsignedBigInteger`).

On MySQL this works by implicit widening for id values below 2^31, so it is
invisible in most projects — but it will fail on a large users table, and an
explicit foreign key could never be added without an `ALTER`.

The same `increments()` / `unsignedInteger` pattern is used across all the package
tables, so they are internally consistent; only the join to `users` is exposed.

**Suggested fix:** change `role_user.user_id` to `unsignedBigInteger` in a new
migration. Do not edit the original migration — consumers have already run it.

---

## 6. No `Menu` boolean cast

**Severity:** Cosmetic.

`Menu` has no `$casts`, so `$menu->is_active` returns `1`/`0` rather than a
boolean, unlike `Role` and `Permission` which both cast it. Strict comparisons
(`=== true`) against menu state therefore fail.

**Suggested fix:** add `protected $casts = ['is_active' => 'boolean'];` to
`src/Models/Menu.php`.

---

## Compatibility notes

The current constraints (`php >=8.1`, `illuminate/support ^10.0|^11.0|^12.0`) are
correct and forward-compatible. Two small packaging improvements are worth making:

- Change `php` from `>=8.1` to `^8.1`. The current form would wrongly permit
  PHP 9; the caret form is the correct package idiom.
- Add `illuminate/database`, `illuminate/routing` and `illuminate/view` to
  `require`. The package uses Eloquent, `Illuminate\Routing\Controller` and Blade
  but only requires `illuminate/support` — it currently resolves those by accident
  via the host application.

**Deliberately not adopted**, because they would break the declared support range:

| Modern idiom | Blocked by |
|---|---|
| `casts()` method instead of `$casts` property | Laravel 11+ only; package supports 10 |
| `#[\Override]` attribute | PHP 8.3+ only; package supports 8.1 |

Supporting Laravel 10 and PHP 8.1 is the right trade-off for this package's
audience. These idioms should be adopted only if the floor is raised.

---

## Resolved

Fixed during the September 2026 review:

- **Root-namespace facade aliases** — `\Acl::`, `\Str::`, `\File::`, `\Log::` and
  `\Route::` were used throughout. These resolve only via the consuming app's
  alias list, which Laravel 11/12 skeletons no longer guarantee. All replaced with
  imported fully-qualified class names. Verified by booting with the alias table
  emptied.
- **Unscoped `orWhere` in search filters** — `PermissionController::index()` and
  `ActivityLogController::index()` chained `orWhere` without a nested closure, so
  any additional constraint would leak out of the `OR` group and widen results.
  Both now wrapped.
- **`AccessControl::getMenus()` guest crash** — declared
  `: Eloquent\Collection` but returned `collect()` (a `Support\Collection`),
  throwing a `TypeError` on any unauthenticated call to `acl_menus()`.
- **Missing `destroy_not_exists()`** — routed and rendered as a button in the
  permissions view, but never defined, so the "delete stale permission" action
  returned a 500. Implemented as a force delete, so that `syncPermissions()`
  (which compares against `withTrashed()`) can recreate the permission if the
  controller is later restored.
- **Missing `Permission::roles()` inverse relation** and `is_active` cast.
- **Config never merged** — `mergeConfigFrom()` was absent, so `config('acl.*')`
  returned `null` until the consumer published `config/acl.php`.
- **Missing pivot columns** — `roles()` filtered on the `is_active` and
  `released_at` pivot columns without declaring them via `withPivot()`, so
  `$role->pivot->is_active` read as `null` in userland. Now declared, with a
  `RoleUser` pivot model providing boolean and datetime casts.
