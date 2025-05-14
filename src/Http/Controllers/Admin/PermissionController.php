<?php

namespace Tahmid\AclManager\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tahmid\AclManager\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::query()
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('slug', 'like', '%' . request('search') . '%');
            })
            ->paginate(30);
        return view('acl::admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            // 'slug'  => 'required|string|max:255|unique:permissions,slug',
            'description'  => 'nullable|string|max:255',
            'is_active'  => 'required|in:0,1',
        ]);

        $validated['slug'] = \Str::of($validated['name'])->slug('-');

        Permission::create($validated);

        return back()->with('success', 'Permission created successfully.');
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            // 'slug'  => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'description'  => 'nullable|string|max:255',
            'is_active'  => 'required|in:0,1',
        ]);

        $permission->update($validated);

        return back()->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back()->with('success', 'Permission deleted successfully.');
    }

}
