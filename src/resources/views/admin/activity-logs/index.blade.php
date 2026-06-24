@extends('acl::layouts.admin')

@section('content')

    @php
        $badgeMap = [
            'created' => 'success',
            'updated' => 'primary',
            'deleted' => 'danger',
            'synced' => 'info',
        ];
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-clock-history text-primary me-2"></i>Activity Log
            </h4>
            <p class="text-muted small mb-0">Recent ACL related activities</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="" method="get" class="mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search by action, description or user..." value="{{ request('search') }}">
                    @if (request('search'))
                        <a href="{{ route('acl.activity-logs.index') }}" class="btn btn-outline-secondary" title="Clear"><i class="bi bi-x-lg"></i></a>
                    @endif
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Action</th>
                            <th>Description</th>
                            <th>User</th>
                            <th>IP Address</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            @php
                                $type = \Illuminate\Support\Str::afterLast($log->action, '.');
                                $color = $badgeMap[$type] ?? 'secondary';
                            @endphp
                            <tr>
                                <td><span class="badge bg-{{ $color }}-subtle text-{{ $color }}-emphasis">{{ $log->action }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    @if ($log->user_name)
                                        <i class="bi bi-person-circle me-1"></i>{{ $log->user_name }}
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td><code>{{ $log->ip_address ?? '-' }}</code></td>
                                <td>
                                    <span title="{{ $log->created_at }}">{{ $log->created_at?->diffForHumans() }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                                    No activity recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap mt-3 gap-2">
                <small class="text-muted">
                    Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }}
                </small>
                {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

@endsection
