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
            ->when($request->search, function ($query) use ($request) {
                $query->where('description', 'like', '%' . $request->search . '%')
                    ->orWhere('action', 'like', '%' . $request->search . '%')
                    ->orWhere('user_name', 'like', '%' . $request->search . '%');
            })
            ->latest('id')
            ->paginate(30);

        return view('acl::admin.activity-logs.index', compact('logs'));
    }
}
