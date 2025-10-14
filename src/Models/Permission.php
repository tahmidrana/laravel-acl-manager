<?php
namespace Tahmid\AclManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'slug', 'controller_name', 'description', 'is_active'];
}
