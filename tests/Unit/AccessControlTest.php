<?php

namespace AclManager\Tests\Unit;

use AclManager\Tests\TestCase;
use AclManager\Tests\TestUser; // Using TestUser from TestCase
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tahmid\AclManager\Models\Role;
use Tahmid\AclManager\Models\Permission;
use Tahmid\AclManager\Helpers\AccessControl;
use Illuminate\Support\Facades\Auth;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected $accessControl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accessControl = new AccessControl();

        // Create a default user for convenience in some tests
        // but ensure it's not authenticated unless explicitly done via actingAs()
        TestUser::create([
            'name' => 'Default Test User',
            'email' => 'default@example.com',
            'password' => bcrypt('password'),
            'is_superuser' => false,
        ]);
    }

    protected function createUser(array $attributes = [])
    {
        return TestUser::create(array_merge([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'is_superuser' => false,
        ], $attributes));
    }

    /** @test */
    public function test_has_permission_for_user_with_permission()
    {
        $user = $this->createUser(['email' => 'permuser@example.com']);
        $role = Role::create(['title' => 'Editor', 'slug' => 'editor']);
        $permission = Permission::create(['name' => 'Edit Articles', 'slug' => 'edit-articles']);

        $role->permissions()->attach($permission);
        $user->roles()->attach($role->id, ['is_active' => true]);

        $this->assertTrue($this->accessControl->hasPermission('edit-articles', $user));
    }

    /** @test */
    public function test_has_permission_for_user_without_permission()
    {
        $user = $this->createUser(['email' => 'nopermuser@example.com']);
        // User has no roles or permissions

        $this->assertFalse($this->accessControl->hasPermission('some-other-permission', $user));
    }

    /** @test */
    public function test_has_permission_for_superuser()
    {
        $superuser = $this->createUser([
            'email' => 'superuser@example.com',
            'is_superuser' => true
        ]);
        $this->assertTrue($this->accessControl->hasPermission('any-permission-slug', $superuser));
    }

    /** @test */
    public function test_has_permission_for_guest_user()
    {
        $this->assertFalse($this->accessControl->hasPermission('any-permission-slug', null));

        Auth::logout(); // Ensure guest state
        // The AccessControl helper's hasPermission method uses Auth::user() if $user is null
        // So, this re-tests the null user scenario implicitly if no user is passed.
        $this->assertFalse($this->accessControl->hasPermission('any-permission-slug'));
    }

    /** @test */
    public function test_role_has_permission_for_role_with_permission()
    {
        $role = Role::create(['title' => 'Manager', 'slug' => 'manager']);
        $permission = Permission::create(['name' => 'Manage Users', 'slug' => 'manage-users']);

        $role->permissions()->attach($permission);

        $this->assertTrue($this->accessControl->roleHasPermission('manager', 'manage-users'));
    }

    /** @test */
    public function test_role_has_permission_for_role_without_permission()
    {
        Role::create(['title' => 'Viewer', 'slug' => 'viewer']);
        // Role has no permissions

        $this->assertFalse($this->accessControl->roleHasPermission('viewer', 'some-other-permission'));
    }

    /** @test */
    public function test_can_method_for_user_with_permission()
    {
        $user = $this->createUser(['email' => 'canpermuser@example.com']);
        $role = Role::create(['title' => 'Writer', 'slug' => 'writer']);
        $permission = Permission::create(['name' => 'Create Posts', 'slug' => 'create-posts']);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role->id, ['is_active' => true]);

        $this->actingAs($user);

        $this->assertTrue($this->accessControl->can('create-posts'));
    }

    /** @test */
    public function test_can_method_for_user_without_permission()
    {
        $user = $this->createUser(['email' => 'cannopermuser@example.com']);
        $this->actingAs($user);

        $this->assertFalse($this->accessControl->can('non-existent-permission'));
    }

    /** @test */
    public function test_can_method_for_superuser()
    {
        $superuser = $this->createUser([
            'email' => 'cansuperuser@example.com',
            'is_superuser' => true
        ]);
        $this->actingAs($superuser);

        $this->assertTrue($this->accessControl->can('any-permission-whatsoever'));
    }

    /** @test */
    public function test_can_method_for_guest_user()
    {
        Auth::logout(); // Ensure no user is authenticated
        $this->assertFalse($this->accessControl->can('any-permission-slug'));
    }
}
