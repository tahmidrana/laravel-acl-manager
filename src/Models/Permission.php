<?php
namespace Tahmid\AclManager\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $fillable = ['name', 'slug', 'controller_name'];
}
