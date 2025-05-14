<?php
namespace Tahmid\AclManager\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

}
