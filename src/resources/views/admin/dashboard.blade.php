@extends('acl::layouts.admin')

@section('content')

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="flex items-center gap-2 text-xl font-semibold text-slate-900">
                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                </svg>
                ACL Manager
            </h1>
            <p class="mt-1 text-sm text-slate-500">Overview of roles, permissions, and menus</p>
        </div>
        <a href="{{ route('acl.manual') }}" class="acl-btn acl-btn-sm acl-btn-secondary">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            User Manual
        </a>
    </div>

    {{-- Stat cards --}}
    @php
        $cards = [
            ['label' => 'Roles', 'value' => $stats['roles'], 'route' => 'acl.roles.index', 'ring' => 'bg-indigo-50 text-indigo-600',
             'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
            ['label' => 'Permissions', 'value' => $stats['permissions'], 'route' => 'acl.permissions.index', 'ring' => 'bg-green-50 text-green-600',
             'icon' => 'M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z'],
            ['label' => 'Menus', 'value' => $stats['menus'], 'route' => 'acl.menus.index', 'ring' => 'bg-sky-50 text-sky-600',
             'icon' => 'M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5'],
            ['label' => 'Active Roles', 'value' => $stats['active_roles'], 'route' => null, 'ring' => 'bg-amber-50 text-amber-600',
             'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
        ];
    @endphp

    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($cards as $card)
            @php $tag = $card['route'] ? 'a' : 'div'; @endphp
            <{{ $tag }} @if ($card['route']) href="{{ route($card['route']) }}" @endif
                class="acl-card acl-card-body flex items-center gap-4 transition @if ($card['route']) hover:shadow-md @endif">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full {{ $card['ring'] }}">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                    </svg>
                </span>
                <div>
                    <div class="text-2xl font-semibold text-slate-900">{{ $card['value'] }}</div>
                    <div class="text-sm text-slate-500">{{ $card['label'] }}</div>
                </div>
            </{{ $tag }}>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Recent roles --}}
        <div class="lg:col-span-7">
            @php
                $badgeMap = [
                    'created' => 'acl-badge-success',
                    'updated' => 'acl-badge-info',
                    'deleted' => 'acl-badge-danger',
                    'synced' => 'acl-badge-info',
                ];
            @endphp
            <div class="acl-card h-full">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h2 class="flex items-center gap-2 font-semibold text-slate-900">
                        <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Recent Activity
                    </h2>
                    <a href="{{ route('acl.activity-logs.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
                </div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($recent_logs as $log)
                        @php $type = \Illuminate\Support\Str::afterLast($log->action, '.'); @endphp
                        <li class="px-5 py-3">
                            <div>
                                <span class="acl-badge {{ $badgeMap[$type] ?? 'acl-badge-muted' }}">{{ $log->action }}</span>
                            </div>
                            <p class="mt-1.5 text-sm text-slate-700">{{ $log->description ?? '—' }}</p>
                            <p class="mt-0.5 text-xs text-slate-400">
                                {{ $log->user_name ?? 'System' }}
                                <span aria-hidden="true">&middot;</span>
                                <span title="{{ $log->created_at }}">{{ $log->created_at?->diffForHumans() }}</span>
                            </p>
                        </li>
                    @empty
                        <li class="py-10 text-center text-slate-400">
                            <svg class="mx-auto mb-2 h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                            </svg>
                            No activity recorded yet.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="lg:col-span-5">
            <div class="acl-card h-full">
                <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4">
                    <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                    <h2 class="font-semibold text-slate-900">Quick Actions</h2>
                </div>
                <div class="flex flex-col gap-2 p-5">
                    <a href="{{ route('acl.roles.index') }}" class="acl-btn acl-btn-secondary justify-start">Manage Roles</a>
                    <a href="{{ route('acl.permissions.index') }}" class="acl-btn acl-btn-secondary justify-start">Manage Permissions</a>
                    <a href="{{ route('acl.menus.index') }}" class="acl-btn acl-btn-secondary justify-start">Manage Menus</a>
                    <a href="{{ route('acl.activity-logs.index') }}" class="acl-btn acl-btn-secondary justify-start">View Activity Log</a>
                    <a href="{{ route('acl.permissions.sync-permissions') }}" class="acl-btn acl-btn-secondary justify-start"
                       onclick="return confirm('Are you sure you want to sync permissions? This will scan your controllers.');">
                        Sync Permissions
                    </a>
                    <a href="{{ route('acl.manual') }}" class="acl-btn acl-btn-secondary justify-start">Read the User Manual</a>
                </div>
            </div>
        </div>
    </div>

@endsection
