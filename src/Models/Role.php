<?php
namespace Tahmid\AclManager\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    // use SoftDeletes;

    protected $fillable = ['title', 'slug'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class)->withTimestamps();
    }
}
