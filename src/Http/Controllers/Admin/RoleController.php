<?php
namespace Tahmid\AclManager\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tahmid\AclManager\Models\ActivityLog;
use Tahmid\AclManager\Models\Menu;
use Tahmid\AclManager\Models\Permission;
use Tahmid\AclManager\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('acl::admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:roles,title',
            'remarks'  => 'nullable|string|max:255',
            'is_active'  => 'required|in:0,1',
        ]);

        $validated['slug'] = Str::of($validated['title'])->slug('-');

        $role = Role::create($validated);

        ActivityLog::record('role.created', "Created role '{$role->title}'");

        return back()->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:roles,title,' . $role->id,
            'remarks'  => 'nullable|string|max:255',
            'is_active'  => 'required|in:0,1',
        ]);

        $role->update($validated);

        ActivityLog::record('role.updated', "Updated role '{$role->title}'");

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $title = $role->title;
        $role->delete();

        ActivityLog::record('role.deleted', "Deleted role '{$title}'");

        return back()->with('success', 'Role deleted successfully.');
    }

    public function show(Role $role)
    {
        $menus = Menu::query()
            ->with('sub_menus')
            ->where('is_active', 1)
            ->oldest('menu_order')
            ->get();

        $permissions = Permission::latest('id')->get()->groupBy('controller_name');

        $user_type_menus = DB::table('menu_role')
            ->where('role_id', $role->id)
            ->select('menu_id')
            ->get()
            ->pluck('menu_id');

        $user_type_permissions = DB::table('permission_role')
            ->where('role_id', $role->id)
            ->select('permission_id')
            ->get()
            ->pluck('permission_id');

        return view('acl::admin.roles.show', compact('role', 'menus', 'permissions', 'user_type_menus', 'user_type_permissions'));
    }

    public function save_role_menus(Request $request, Role $role)
    {
        $request->validate([
            'role_menus' => 'nullable|array',
            'role_menus.*' => 'integer|distinct|exists:menus,id',
        ]);

        try {
            $selected = Menu::whereIn('id', $request->role_menus ?? [])
                ->get(['id', 'parent_menu_id']);

            $selectedIds = $selected->pluck('id');

            // Keep top-level menus when checked; keep a sub-menu only if its
            // parent menu is also selected. So unchecking a main menu removes
            // it and all of its sub-menus, even if a child was left checked.
            $menuIds = $selected
                ->filter(fn ($menu) => is_null($menu->parent_menu_id) || $selectedIds->contains($menu->parent_menu_id))
                ->pluck('id')
                ->all();

            $role->menus()->sync($menuIds);

            ActivityLog::record('role.menus_updated', "Updated menus for role '{$role->title}'");

            session()->flash('success', 'Success! Successfully Updated!');
        } catch (\Exception $e) {
            Log::error($e);
            session()->flash('error', 'Oops! Something went wrong!');
        }

        return back();
    }

    public function save_role_permissions(Request $request, Role $role)
    {
        $request->validate([
            'role_permissions' => 'nullable|array',
            'role_permissions.*' => 'integer|distinct|exists:permissions,id',
            'checked_controllers' => 'nullable|array',
        ]);

        try {
            $selected = Permission::whereIn('id', $request->role_permissions ?? [])
                ->get(['id', 'controller_name']);

            $checkedControllers = collect($request->checked_controllers ?? []);

            // Keep a permission only if its controller group is checked. So
            // unchecking a controller (parent) removes all of its permissions
            // on save, even if a child checkbox was left checked on screen.
            $permissionIds = $selected
                ->filter(fn ($perm) => $checkedControllers->contains($perm->controller_name))
                ->pluck('id')
                ->all();

            $role->permissions()->sync($permissionIds);

            ActivityLog::record('role.permissions_updated', "Updated permissions for role '{$role->title}'");

            session()->flash('success', 'Success! Successfully Updated!');
        } catch (\Exception $e) {
            Log::error($e);
            session()->flash('error', 'Oops! Something went wrong!');
        }

        return back();
    }



}
