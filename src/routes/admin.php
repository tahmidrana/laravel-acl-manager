<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Tahmid\AclManager\Http\Controllers\Admin\MenuController;
use Tahmid\AclManager\Http\Controllers\Admin\PermissionController;
use Tahmid\AclManager\Http\Controllers\Admin\RoleController;

Route::middleware(Config::get('acl.middleware', ['web', 'auth', 'is_superuser']))
    ->prefix('acl-manager')
    ->name('acl.')
    ->group(function () {
        Route::resource('roles', RoleController::class)->only('index', 'show', 'store', 'update', 'destroy');
        Route::put('roles/{role}/save_role_menus', [RoleController::class, 'save_role_menus'])->name('roles.save-role-menus');
        Route::put('roles/{role}/save_role_permissions', [RoleController::class, 'save_role_permissions'])->name('roles.save-role-permissions');
        Route::resource('permissions', PermissionController::class)->only('index', 'store', 'update', 'destroy');
        Route::resource('menus', MenuController::class)->only('index', 'store', 'update', 'destroy');
    });
