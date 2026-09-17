<?php

namespace Tahmid\AclManager\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tahmid\AclManager\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%' . $search . '%')
                        ->orWhere('action', 'like', '%' . $search . '%')
                        ->orWhere('user_name', 'like', '%' . $search . '%');
                });
            })
            ->latest('id')
            ->paginate(30);

        return view('acl::admin.activity-logs.index', compact('logs'));
    }
}
