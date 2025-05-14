<?php
namespace Tahmid\AclManager\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    // use SoftDeletes;

    protected $fillable = ['title', 'route_name'];
}
