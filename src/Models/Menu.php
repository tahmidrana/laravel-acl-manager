<?php
namespace Tahmid\AclManager\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['title', 'route_name'];
}
