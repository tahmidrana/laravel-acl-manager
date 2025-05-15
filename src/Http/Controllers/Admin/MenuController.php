<?php


namespace Tahmid\AclManager\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tahmid\AclManager\Models\Menu;
use Tahmid\AclManager\Models\Role;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::query()
            ->with('parent_menu')
            ->when(request('search'), function ($query) {
                $query->where('title', 'like', '%' . request('search') . '%');
            })
            ->withCount('sub_menus')
            ->get();

        return view('acl::admin.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:menus,title',
            'route_name'  => 'nullable|string|max:255',
            'menu_icon'  => 'nullable|string|max:255',
            'menu_order'   => 'nullable|integer|min:1',
            'parent_menu_id' => 'nullable|integer',
            'is_active'  => 'required|in:0,1',
        ]);

        try {
            $menu_order = $request->menu_order;

            if (! $menu_order) {
                $last_order_menu = Menu::query()
                    ->when($request->parent_menu_id, function ($query) {
                        $query->where('parent_menu_id', request()->parent_menu_id);
                    })
                    ->when(! $request->parent_menu_id, function ($query) {
                        $query->whereNull('parent_menu_id');
                    })
                    ->latest('menu_order')
                    ->first();

                $menu_order = $last_order_menu ? $last_order_menu->menu_order + 1 : 1;
            }

            $validated['menu_order'] = $menu_order;
            Menu::create($validated);

            return back()->withSuccess('Menu saved successfully');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:menus,title,' . $menu->id,
            'route_name'  => 'nullable|string|max:255',
            'menu_icon'  => 'nullable|string|max:255',
            'menu_order'   => 'nullable|integer|min:1',
            'parent_menu_id' => 'nullable|integer',
            'is_active'  => 'required|in:0,1',
        ]);

        try {
            $menu->update($validated);

            return back()->withSuccess('Menu updated successfully');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function destroy(Menu $menu)
    {
        try {
            $menu->delete();
            return back()->withSuccess('Menu deleted successfully');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}

