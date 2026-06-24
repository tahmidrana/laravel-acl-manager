<?php

namespace Tahmid\AclManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $table = 'acl_activity_logs';

    protected $fillable = ['user_id', 'user_name', 'action', 'description', 'ip_address'];

    /**
     * Record an ACL related activity.
     */
    public static function record(string $action, ?string $description = null): void
    {
        $user = Auth::user();

        static::create([
            'user_id' => $user?->getKey(),
            'user_name' => $user?->name ?? null,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
