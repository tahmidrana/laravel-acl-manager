<?php

namespace Tahmid\AclManager;

use Illuminate\Support\ServiceProvider;
use Tahmid\AclManager\Http\Middleware\IsSuperuser;
use Illuminate\Support\Facades\Blade;
use Tahmid\AclManager\Http\Middleware\RolePermissionCheck;

class AclManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/admin.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'acl');

        $this->publishes([
            __DIR__.'/config/acl.php' => config_path('acl.php'),
        ], 'acl-config');

        // Blade directive
        Blade::if('acl', function (string $permission) {
            return \Acl::can($permission);
        });


        $this->registerMiddleware();
    }

    public function register()
    {
        $this->app->singleton('acl', function () {
            return new Helpers\AccessControl();
        });
    }

    protected function registerMiddleware()
    {
        /** @var Router $router */
        $router = $this->app['router'];

        $router->aliasMiddleware('is_superuser', IsSuperuser::class);
        $router->aliasMiddleware('role_permission_check', RolePermissionCheck::class);
    }
}
