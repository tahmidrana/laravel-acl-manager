<?php
namespace Tahmid\AclManager\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
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

        $validated['slug'] = \Str::of($validated['title'])->slug('-');

        Role::create($validated);

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

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
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
            $role->menus()->sync($request->role_menus);
            session()->flash('success', 'Success! Successfully Updated!');
        } catch (\Exception $e) {
            session()->flash('error', 'Oops! Something went wrong!');
        }

        return back();
    }

    public function save_role_permissions(Request $request, Role $role)
    {
        $request->validate([
            'role_permissions' => 'nullable|array',
            'role_permissions.*' => 'integer|distinct|exists:permissions,id',
        ]);

        try {
            $role->permissions()->sync($request->role_permissions);

            session()->flash('success', 'Success! Successfully Updated!');
        } catch (\Exception $e) {
            session()->flash('error', 'Oops! Something went wrong!');
        }

        return back();
    }



}
