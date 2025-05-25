<?php

namespace AclManager\Tests;

use Tahmid\AclManager\AclManagerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tahmid\AclManager\Models\Role; // Import Role for the relationship

// Define a basic User model for testing
class TestUser extends Authenticatable
{
    protected $table = 'users'; // Ensure it uses the 'users' table created in tests
    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->withPivot('is_active')->withTimestamps();
    }

    public function hasPermission($permissionSlug)
    {
        return $this->roles()
            ->wherePivot('is_active', true)
            ->whereHas('permissions', function ($q) use ($permissionSlug) {
                $q->where('slug', $permissionSlug)
                  ->orWhere('name', $permissionSlug);
            })->exists();
    }
}

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            AclManagerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Ensure users table is created before package migrations
        $this->createUsersTable($app); // This method already exists from previous steps

        // Load package config
        $app['config']->set('acl', require __DIR__.'/../src/config/acl.php');
        // Set the superuser_column for tests
        config(['acl.superuser_column' => 'is_superuser']);
    }

    protected function createUsersTable($app)
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Load package migrations
        $this->loadMigrationsFrom(__DIR__.'/../src/database/migrations');
        // We no longer need to load from tests/database/migrations as createUsersTable handles it
    }
}
