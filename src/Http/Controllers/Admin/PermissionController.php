<?php

namespace Tahmid\AclManager\Http\Controllers\Admin;

use App\Attributes\PermissionAttr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tahmid\AclManager\Models\Permission;
use ReflectionMethod;

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

    public function syncPermissions()
    {
        $saved_permissions = Permission::withTrashed()->get()->pluck('name')->toArray();
        try {
            $permissions = [];
            foreach (\Route::getRoutes()->getRoutes() as $route) {
                $action = $route->getAction();
                if (array_key_exists('controller', $action)) {
                    // You can also use explode('@', $action['controller']); here
                    // to separate the class name from the method
                    $action_name = $action['controller'];
                    $method_name = explode('@', $action_name)[1] ?? null;

                    $class_name = explode('@', $action_name)[0];
                    $reflection = new \ReflectionClass($class_name);

                    $class_methods = $reflection->getMethods();
                    $class_methods = collect($class_methods)->map(fn ($item) => $item->name);

                    if (substr($action_name, 0, 3) === 'App') {
                        $action_name = explode('Controllers\\', $action_name)[1];

                        if (! in_array($action_name, $saved_permissions)) {
                            $slug = Str::replace('\\', ':', $action_name);
                            $slug = Str::snake($slug);
                            $slug = preg_replace('/:_/', '/', $slug);

                            if (! Str::startsWith($action_name, ['Auth', 'AdminConsole', 'Api', 'Dashboard']) && $class_methods->contains($method_name)) {
                                $description = null;
                                $method = new ReflectionMethod($class_name, $method_name);
                                $attributes = $method->getAttributes(PermissionAttr::class);
                                if (count($attributes)) {
                                    $attributeInstance = $attributes[0]->newInstance();
                                    // $name = $attributeInstance->name ?? null;
                                    $description = $attributeInstance->description ?? null;
                                }

                                $permissions[] = [
                                    'name' => $action_name,
                                    'slug' => $slug,
                                    'controller_name' => explode('@', $action_name)[0],
                                    'description' => $description,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                    }
                }
            }

            if (count($permissions)) {
                Permission::insert($permissions);
            }

            session()->flash('success', 'Successfully synced permissions');
        } catch (\Throwable $th) {
            session()->flash('error', 'Permission sync failed');
        }

        return back();
    }

}
