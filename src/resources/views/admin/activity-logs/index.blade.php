@extends('acl::layouts.admin')

@section('content')

    @php
        $badgeMap = [
            'created' => 'acl-badge-success',
            'updated' => 'acl-badge-info',
            'deleted' => 'acl-badge-danger',
            'synced' => 'acl-badge-info',
        ];
    @endphp

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="flex items-center gap-2 text-xl font-semibold text-slate-900">
                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Activity Log
            </h1>
            <p class="mt-1 text-sm text-slate-500">Recent ACL related activities</p>
        </div>
    </div>

    <div class="acl-card">
        <div class="p-5">
            <form action="" method="get" class="mb-4">
                <div class="flex max-w-xl items-stretch gap-2">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </span>
                        <input type="text" name="search" id="search" class="acl-input pl-10" placeholder="Search by action, description or user..." value="{{ request('search') }}">
                    </div>
                    @if (request('search'))
                        <a href="{{ route('acl.activity-logs.index') }}" class="acl-btn acl-btn-secondary" title="Clear">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                    <button type="submit" class="acl-btn acl-btn-primary">Search</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="acl-table">
                    <thead>
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
                                $badgeClass = $badgeMap[$type] ?? 'acl-badge-muted';
                            @endphp
                            <tr>
                                <td><span class="acl-badge {{ $badgeClass }}">{{ $log->action }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    @if ($log->user_name)
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            {{ $log->user_name }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">System</span>
                                    @endif
                                </td>
                                <td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">{{ $log->ip_address ?? '-' }}</code></td>
                                <td>
                                    <span title="{{ $log->created_at }}">{{ $log->created_at?->diffForHumans() }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-400">
                                    <svg class="mx-auto mb-2 h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                                    </svg>
                                    No activity recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                <small class="text-slate-500">
                    Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }}
                </small>
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

@endsection
