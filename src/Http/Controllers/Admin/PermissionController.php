<?php

namespace Tahmid\AclManager\Http\Controllers\Admin;

use Tahmid\AclManager\Attributes\PermissionAttr;
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
            ->paginate(15);

        $permissions_not_exist = [];
        $permissions_method_not_exist = [];
        foreach ($permissions as $permission) {
            $file_path = str_replace('\\', '/', $permission->controller_name);
            $file_path = base_path("app/Http/Controllers/{$file_path}.php");
            if (! file_exists($file_path)) {
                $permissions_not_exist[] = $permission;

                continue;
            }

            $controller_name = "App\\Http\\Controllers\\{$permission->controller_name}";
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

            $controllers_path = app_path('Http/Controllers');

            if (! is_dir($controllers_path)) {
                session()->flash('error', 'Controllers directory not found');

                return back();
            }

            // Controllers to skip, configured in config/acl.php. Directories are
            // matched as a path prefix; controllers are matched exactly. Both are
            // normalised to the backslash form used by the relative class path.
            $ignored_directories = collect(config('acl.ignore_controller_directories', []))
                ->map(fn ($dir) => trim(str_replace('/', '\\', $dir), '\\'))
                ->filter();

            $ignored_controllers = collect(config('acl.ignore_controllers', []))
                ->map(fn ($ctrl) => trim(str_replace(['/', '.php'], ['\\', ''], $ctrl), '\\'))
                ->filter();

            // Scan the controllers directory directly so newly added
            // controllers are picked up even when they have no routes yet.
            foreach (\File::allFiles($controllers_path) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                // Build the FQCN from the path relative to app/Http/Controllers
                $relative_class = str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());
                $class_name = 'App\\Http\\Controllers\\' . $relative_class;

                if (! class_exists($class_name)) {
                    continue;
                }

                $reflection = new \ReflectionClass($class_name);
                if ($reflection->isAbstract() || $reflection->isInterface()) {
                    continue;
                }

                // Permission names use the path relative to Controllers,
                // e.g. "Blog\PostController" -> "Blog\PostController@index".
                if ($ignored_controllers->contains($relative_class)) {
                    continue;
                }

                if ($ignored_directories->contains(fn ($dir) => Str::startsWith($relative_class, $dir . '\\'))) {
                    continue;
                }

                foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                    // Only methods declared on the controller itself, skipping
                    // inherited base/trait methods and magic methods.
                    if ($method->getDeclaringClass()->getName() !== $class_name) {
                        continue;
                    }

                    $method_name = $method->getName();
                    if (Str::startsWith($method_name, '__') || $method_name === 'middleware') {
                        continue;
                    }

                    $action_name = "{$relative_class}@{$method_name}";

                    if (in_array($action_name, $saved_permissions)) {
                        continue;
                    }

                    $slug = Str::replace('\\', ':', $action_name);
                    $slug = Str::snake($slug);
                    $slug = preg_replace('/:_/', '/', $slug);

                    $description = null;
                    $attributes = $method->getAttributes(PermissionAttr::class);
                    if (! empty($attributes)) {
                        $attributeInstance = $attributes[0]->newInstance();
                        $description = $attributeInstance->description ?? null;
                    }

                    $permissions[$action_name] = [
                        'name' => $action_name,
                        'slug' => $slug,
                        'controller_name' => $relative_class,
                        'description' => $description,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (count($permissions)) {
                Permission::insert(array_values($permissions));
            }

            session()->flash('success', 'Successfully synced permissions');
        } catch (\Throwable $th) {
            \Log::error($th);
            session()->flash('error', 'Permission sync failed');
        }

        return back();
    }

    public function sync_controller_permissions(Permission $permission)
    {
        $controller_name = $permission->controller_name;

        if (! $controller_name) {
            $controller_name = \Str::of($permission->name)->explode('@')[0];
            /* $method_name = \Str::of($permission->name)->explode('@')[1]; */
        }

        $class_name = 'App\\Http\\Controllers\\' . $controller_name;

        if (! class_exists($class_name)) {
            return back()->withErrors('Controller class not found: ' . $class_name);
        }
        $class = new \ReflectionClass($class_name);
        $class_methods = collect($class->getMethods(\ReflectionMethod::IS_PUBLIC))
            ->filter(fn ($item) => $item->getDeclaringClass()->getName() === $class_name)
            ->map(fn ($item) => $item->name)
            ->filter(fn ($item) => ! Str::startsWith($item, '__') && $item !== 'middleware')
            ->values();

        foreach ($class_methods as $method_name) {
            $method = new ReflectionMethod($class_name, $method_name);
            $attributes = $method->getAttributes(PermissionAttr::class);
            $description = null;
            if (!empty($attributes)) {
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
                    'controller_name' => $controller_name,
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->withSuccess('Controller permission synced successfully');
    }

}
