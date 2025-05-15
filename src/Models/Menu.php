<?php
namespace Tahmid\AclManager\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    // use SoftDeletes;

    protected $fillable = ['title', 'route_name', 'menu_url', 'menu_icon',  'menu_order', 'is_active', 'parent_menu_id'];

    public function parent_menu()
    {
        return $this->belongsTo(Menu::class, 'parent_menu_id', 'id');
    }

    public function sub_menus()
    {
        return $this->hasMany(Menu::class, 'parent_menu_id', 'id');
    }
}
