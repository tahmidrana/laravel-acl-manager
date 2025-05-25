<?php

namespace AclManager\Tests\Unit;

use AclManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tahmid\AclManager\Models\Menu;
use Tahmid\AclManager\Models\Permission;
use Tahmid\AclManager\Models\Role;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_create_role()
    {
        $roleData = [
            'title' => 'Admin',
            'slug' => 'admin',
        ];
        $role = Role::create($roleData);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals($roleData['title'], $role->title);
        $this->assertEquals($roleData['slug'], $role->slug);
        $this->assertDatabaseHas('roles', $roleData);
    }

    /** @test */
    public function test_role_can_have_permissions()
    {
        $role = Role::create(['title' => 'Editor', 'slug' => 'editor']);
        $permission = Permission::create(['name' => 'Edit Posts', 'slug' => 'edit-posts']);

        $role->permissions()->attach($permission);

        $this->assertTrue($role->permissions->contains($permission));
    }

    /** @test */
    public function test_role_can_have_menus()
    {
        $role = Role::create(['title' => 'User', 'slug' => 'user']);
        $menu = Menu::create([
            'title' => 'Dashboard',
            'route_name' => 'dashboard',
            'menu_url' => '/dashboard',
            'menu_order' => 1,
            'is_active' => true,
        ]);

        $role->menus()->attach($menu);

        $this->assertTrue($role->menus->contains($menu));
    }

    /** @test */
    public function test_role_fillable_attributes()
    {
        $role = new Role();
        $this->assertEquals(['title', 'slug'], $role->getFillable());
    }
}
