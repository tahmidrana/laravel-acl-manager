<?php

namespace Tahmid\AclManager\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Tahmid\AclManager\Models\Menu;
use Tahmid\AclManager\Models\Permission;
use Tahmid\AclManager\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'roles' => Role::count(),
            'active_roles' => Role::where('is_active', 1)->count(),
            'permissions' => Permission::count(),
            'menus' => Menu::count(),
        ];

        $recent_roles = Role::latest('id')->take(5)->get();

        return view('acl::admin.dashboard', compact('stats', 'recent_roles'));
    }
}
