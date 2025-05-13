<?php

namespace Tahmid\AclManager\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Tahmid\AclManager\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('acl::admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('acl::admin.roles.create');
    }

    // Add store, edit, update, destroy...
}
