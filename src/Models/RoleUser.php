<?php

namespace Tahmid\AclManager\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    protected $table = 'role_user';

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'released_at' => 'datetime',
    ];
}
