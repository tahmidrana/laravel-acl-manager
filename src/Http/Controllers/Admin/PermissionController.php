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

        $permissions_not_exist = [];
        $permissions_method_not_exist = [];
        foreach ($permissions as $permission) {
            $file_path = str_replace('\\', '/', $permission->controller);
            $file_path = base_path("app/Http/Controllers/{$file_path}.php");
            if (! file_exists($file_path)) {
                $permissions_not_exist[] = $permission;

                continue;
            }

            $controller_name = "App\\Http\\Controllers\\{$permission->controller}";
            if (! class_exists($controller_name)) {
                $permissions_not_exist[] = $permission;

                continue;
            }

            // check if controller has the method
            $method_name = explode('@', $permission->name)[1] ?? null;
            if ($method_name) {
                $reflection = new \ReflectionClass($controller_name);
                if (! $reflection->hasMethod($method_name)) {
                    $permissions_method_not_exist[] = $permission; // method does not exist in the controller
                }
            }
        }

        return view('acl::admin.permissions.index', compact('permissions', 'permissions_not_exist', 'permissions_method_not_exist'));
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
                    if (! class_exists($class_name)) {
                        continue;
                    }

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

    public function sync_controller_permissions(Permission $permission)
    {
        $controller_name = $permission->controller;

        if (! $controller_name) {
            $controller_name = \Str::of($permission->name)->explode('@')[0];
            /* $method_name = \Str::of($permission->name)->explode('@')[1]; */
        }

        $class_name = 'App\\Http\\Controllers\\' . $controller_name;
        $class = new \ReflectionClass($class_name);
        $class_methods = $class->getMethods();
        $class_methods = collect($class_methods)
            ->map(fn ($item) => $item->name)
            ->filter(fn ($item) => $item !== '__construct' && $item !== 'middleware');

        foreach ($class_methods as $method_name) {
            $method = new ReflectionMethod($class_name, $method_name);
            $attributes = $method->getAttributes(PermissionAttr::class);
            $description = null;
            if (count($attributes)) {
                $attributeInstance = $attributes[0]->newInstance();
                // $name = $attributeInstance->name ?? null;
                $description = $attributeInstance->description ?? null;
            }

            $perm = Permission::query()
                ->where('name', "{$controller_name}@{$method_name}")
                ->first();

            if ($perm) {
                $perm->update([
                    'description' => $description,
                ]);
            } else {
                $slug = Str::replace('\\', ':', "{$controller_name}@{$method_name}");
                $slug = Str::snake($slug);
                $slug = preg_replace('/:_/', '/', $slug);

                Permission::create([
                    'name' => $controller_name . '@' . $method->name,
                    'slug' => $slug,
                    'controller' => $controller_name,
                    'description' => $description,
                    // 'created_at' => now(),
                    // 'updated_at' => now(),
                ]);
            }
        }

        return back()->withSuccess('Controller permission synced successfully');
    }

}
