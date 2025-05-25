<?php

namespace AclManager\Tests\Unit;

use AclManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tahmid\AclManager\Models\Permission;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_create_permission()
    {
        $permissionData = [
            'name' => 'Create Users',
            'slug' => 'create-users',
            'controller_name' => 'UserController@create',
        ];
        $permission = Permission::create($permissionData);

        $this->assertInstanceOf(Permission::class, $permission);
        $this->assertEquals($permissionData['name'], $permission->name);
        $this->assertEquals($permissionData['slug'], $permission->slug);
        $this->assertEquals($permissionData['controller_name'], $permission->controller_name);
        $this->assertDatabaseHas('permissions', $permissionData);
    }

    /** @test */
    public function test_permission_fillable_attributes()
    {
        $permission = new Permission();
        $this->assertEquals(['name', 'slug', 'controller_name'], $permission->getFillable());
    }

    /** @test */
    public function test_permission_uses_soft_deletes()
    {
        $permissionData = [
            'name' => 'Delete Users',
            'slug' => 'delete-users',
            'controller_name' => 'UserController@destroy',
        ];
        $permission = Permission::create($permissionData);

        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'deleted_at' => null]);

        $permission->delete();

        $this->assertSoftDeleted($permission);
        $this->assertNull(Permission::find($permission->id));
        $this->assertNotNull(Permission::withTrashed()->find($permission->id));

        // Check if SoftDeletes trait is used in the model
        $this->assertTrue(in_array(SoftDeletes::class, class_uses_recursive(Permission::class)));
    }
}
