@extends('acl::layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-speedometer2 text-primary me-2"></i>ACL Manager
            </h4>
            <p class="text-muted small mb-0">Overview of roles, permissions, and menus</p>
        </div>
        <a href="{{ route('acl.manual') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-book me-1"></i>User Manual
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <a href="{{ route('acl.roles.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary-subtle text-primary-emphasis d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <div class="h4 mb-0">{{ $stats['roles'] }}</div>
                            <div class="text-muted small">Roles</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-6">
            <a href="{{ route('acl.permissions.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-success-subtle text-success-emphasis d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bi bi-key fs-4"></i>
                        </div>
                        <div>
                            <div class="h4 mb-0">{{ $stats['permissions'] }}</div>
                            <div class="text-muted small">Permissions</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-6">
            <a href="{{ route('acl.menus.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-info-subtle text-info-emphasis d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bi bi-menu-button-wide fs-4"></i>
                        </div>
                        <div>
                            <div class="h4 mb-0">{{ $stats['menus'] }}</div>
                            <div class="text-muted small">Menus</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="h4 mb-0">{{ $stats['active_roles'] }}</div>
                        <div class="text-muted small">Active Roles</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Recent Roles</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Config</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recent_roles as $role)
                                    <tr>
                                        <td class="fw-medium">{{ $role->title }}</td>
                                        <td><code>{{ $role->slug }}</code></td>
                                        <td class="text-center">
                                            @if ($role->is_active)
                                                <span class="badge bg-success-subtle text-success-emphasis"><i class="bi bi-check-circle me-1"></i>Active</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('acl.roles.show', ['role' => $role->id]) }}" class="btn btn-sm btn-outline-info" title="Config"><i class="bi bi-gear"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                                            No roles yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-lightning-charge text-primary me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('acl.roles.index') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-shield-check me-2"></i>Manage Roles
                        </a>
                        <a href="{{ route('acl.permissions.index') }}" class="btn btn-outline-success text-start">
                            <i class="bi bi-key me-2"></i>Manage Permissions
                        </a>
                        <a href="{{ route('acl.menus.index') }}" class="btn btn-outline-info text-start">
                            <i class="bi bi-menu-button-wide me-2"></i>Manage Menus
                        </a>
                        <a href="{{ route('acl.activity-logs.index') }}" class="btn btn-outline-dark text-start">
                            <i class="bi bi-clock-history me-2"></i>View Activity Log
                        </a>
                        <a href="{{ route('acl.permissions.sync-permissions') }}" class="btn btn-outline-secondary text-start"
                            onclick="return confirm('Are you sure you want to sync permissions? This will scan your controllers.');">
                            <i class="bi bi-arrow-repeat me-2"></i>Sync Permissions
                        </a>
                        <a href="{{ route('acl.manual') }}" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-book me-2"></i>Read the User Manual
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
