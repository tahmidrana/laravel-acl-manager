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
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('menus', MenuController::class);
    });
