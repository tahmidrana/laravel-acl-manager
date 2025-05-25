<?php

namespace AclManager\Tests\Unit;

use AclManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tahmid\AclManager\Models\Menu;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_create_menu()
    {
        $parentMenuData = [
            'title' => 'Parent Menu',
            'route_name' => 'parent.menu',
            'menu_url' => '/parent-menu',
            'menu_icon' => 'icon-parent',
            'menu_order' => 1,
            'is_active' => true,
        ];
        $parentMenu = Menu::create($parentMenuData);

        $menuData = [
            'title' => 'Test Menu',
            'route_name' => 'test.menu',
            'menu_url' => '/test-menu',
            'menu_icon' => 'icon-test',
            'menu_order' => 1,
            'is_active' => true,
            'parent_menu_id' => $parentMenu->id,
        ];
        $menu = Menu::create($menuData);

        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals($menuData['title'], $menu->title);
        $this->assertEquals($menuData['route_name'], $menu->route_name);
        $this->assertEquals($menuData['menu_url'], $menu->menu_url);
        $this->assertEquals($menuData['menu_icon'], $menu->menu_icon);
        $this->assertEquals($menuData['menu_order'], $menu->menu_order);
        $this->assertEquals($menuData['is_active'], $menu->is_active);
        $this->assertEquals($menuData['parent_menu_id'], $menu->parent_menu_id);
        $this->assertDatabaseHas('menus', $menuData);
    }

    /** @test */
    public function test_menu_fillable_attributes()
    {
        $menu = new Menu();
        $this->assertEquals(
            ['title', 'route_name', 'menu_url', 'menu_icon', 'menu_order', 'is_active', 'parent_menu_id'],
            $menu->getFillable()
        );
    }

    /** @test */
    public function test_menu_relationships()
    {
        $parentMenu = Menu::create([
            'title' => 'Parent Menu',
            'route_name' => 'parent.menu.relations',
            'menu_url' => '/parent-menu-relations',
            'menu_order' => 1,
            'is_active' => true,
        ]);

        $childMenu = Menu::create([
            'title' => 'Child Menu',
            'route_name' => 'child.menu.relations',
            'menu_url' => '/child-menu-relations',
            'menu_order' => 1,
            'is_active' => true,
            'parent_menu_id' => $parentMenu->id,
        ]);

        $this->assertInstanceOf(Menu::class, $childMenu->parent_menu);
        $this->assertEquals($parentMenu->id, $childMenu->parent_menu->id);
        $this->assertTrue($parentMenu->sub_menus->contains($childMenu));
    }
}
