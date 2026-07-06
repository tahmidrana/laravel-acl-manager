@extends('acl::layouts.admin')

@section('content')

<div x-data="{ createOpen: false }">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="flex items-center gap-2 text-xl font-semibold text-slate-900">
                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                </svg>
                Menus
            </h1>
            <p class="mt-1 text-sm text-slate-500">Manage navigation menus and sub-menus</p>
        </div>
        <button type="button" @click="createOpen = true" class="acl-btn acl-btn-sm acl-btn-primary">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Menu
        </button>
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
                        <input type="text" name="search" id="search" class="acl-input pl-10" placeholder="Search menus..." value="{{ request('search') }}">
                    </div>
                    @if (request('search'))
                        <a href="{{ route('acl.menus.index') }}" class="acl-btn acl-btn-secondary" title="Clear">
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
                            <th>Title</th>
                            <th>Parent Menu</th>
                            <th>Route Name</th>
                            <th>Menu Icon</th>
                            <th class="text-center">Order</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menus as $menu)
                            <tr class="{{ !$menu->parent_menu_id ? 'bg-indigo-50/40' : '' }}">
                                <td class="font-medium text-slate-800 {{ !$menu->parent_menu_id ? 'border-l-4 border-l-indigo-500' : '' }}">
                                    @if (!$menu->parent_menu_id)
                                        <span class="inline-flex items-center gap-2">
                                            {{ $menu->title }}
                                            <span class="acl-badge acl-badge-info">Top level</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 pl-4 text-slate-600">
                                            <svg class="h-3.5 w-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5v9a3 3 0 0 0 3 3h12m0 0-3.75-3.75M19.5 15.75 15.75 19.5" />
                                            </svg>
                                            {{ $menu->title }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $menu->parent_menu ? $menu->parent_menu->title : '-' }}</td>
                                <td>@if ($menu->route_name)<code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">{{ $menu->route_name }}</code>@else <span class="text-slate-400">-</span>@endif</td>
                                <td>@if ($menu->menu_icon)<i class="{{ $menu->menu_icon }}"></i> <span class="text-xs text-slate-400">{{ $menu->menu_icon }}</span>@else <span class="text-slate-400">-</span>@endif</td>
                                <td class="text-center">{{ $menu->menu_order }}</td>
                                <td class="text-center">
                                    @if ($menu->is_active)
                                        <span class="acl-badge acl-badge-success">Active</span>
                                    @else
                                        <span class="acl-badge acl-badge-muted">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div x-data="{ open: false }" class="inline-flex">
                                            <button type="button" @click="open = true" class="acl-btn acl-btn-sm acl-btn-secondary" title="Edit">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                                </svg>
                                            </button>

                                            <template x-teleport="body">
                                                <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-40 overflow-y-auto" role="dialog" aria-modal="true">
                                                    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-slate-900/50" @click="open = false"></div>
                                                    <div class="flex min-h-full items-center justify-center p-4">
                                                        <div x-show="open" x-transition class="relative w-full max-w-lg rounded-xl bg-white text-left shadow-xl">
                                                            <form method="POST" action="{{ route('acl.menus.update', ['menu' => $menu->id]) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                                                                    <h2 class="text-lg font-semibold text-slate-900">Update Menu</h2>
                                                                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="space-y-4 px-5 py-4">
                                                                    <div>
                                                                        <label for="title_{{ $menu->id }}" class="acl-label">Title <span class="text-red-500">*</span></label>
                                                                        <input type="text" name="title" id="title_{{ $menu->id }}" value="{{ $menu->title }}" class="acl-input" placeholder="Menu title" required />
                                                                    </div>
                                                                    <div>
                                                                        <label for="route_name_{{ $menu->id }}" class="acl-label">Route Name</label>
                                                                        <input type="text" name="route_name" id="route_name_{{ $menu->id }}" value="{{ $menu->route_name }}" class="acl-input" placeholder="Route name" />
                                                                    </div>
                                                                    <div>
                                                                        <label for="menu_icon_{{ $menu->id }}" class="acl-label">Menu Icon</label>
                                                                        <input type="text" name="menu_icon" id="menu_icon_{{ $menu->id }}" value="{{ $menu->menu_icon }}" class="acl-input" placeholder="Menu Icon" />
                                                                    </div>
                                                                    <div>
                                                                        <label for="menu_order_{{ $menu->id }}" class="acl-label">Menu Order</label>
                                                                        <input type="number" min="1" name="menu_order" id="menu_order_{{ $menu->id }}" value="{{ $menu->menu_order }}" class="acl-input" placeholder="Menu Order" />
                                                                    </div>
                                                                    <div>
                                                                        <label for="parent_menu_id_{{ $menu->id }}" class="acl-label">Parent Menu</label>
                                                                        <select name="parent_menu_id" id="parent_menu_id_{{ $menu->id }}" class="acl-select">
                                                                            <option value="">-Select Parent Menu-</option>
                                                                            @if ($menus->whereNull('parent_menu_id')->count())
                                                                                <optgroup label="Top-level menus">
                                                                                    @foreach ($menus->whereNull('parent_menu_id') as $par_menu)
                                                                                        <option value="{{ $par_menu->id }}" {{ $menu->parent_menu_id == $par_menu->id ? 'selected' : '' }}>{{ $par_menu->title }} {{ $par_menu->sub_menus_count ? '*' : '' }}</option>
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            @endif
                                                                            @if ($menus->whereNotNull('parent_menu_id')->count())
                                                                                <optgroup label="Sub-menus">
                                                                                    @foreach ($menus->whereNotNull('parent_menu_id') as $par_menu)
                                                                                        <option value="{{ $par_menu->id }}" {{ $menu->parent_menu_id == $par_menu->id ? 'selected' : '' }}>{{ $par_menu->title }} {{ $par_menu->sub_menus_count ? '*' : '' }}</option>
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <label for="is_active_{{ $menu->id }}" class="acl-label">Is Active <span class="text-red-500">*</span></label>
                                                                        <select name="is_active" id="is_active_{{ $menu->id }}" class="acl-select" required>
                                                                            <option value="1" {{ $menu->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                                                            <option value="0" {{ $menu->is_active == 0 ? 'selected' : '' }}>No</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4">
                                                                    <button type="button" @click="open = false" class="acl-btn acl-btn-secondary">Cancel</button>
                                                                    <button type="submit" class="acl-btn acl-btn-primary">Update Menu</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <form action="{{ route('acl.menus.destroy', ['menu' => $menu->id]) }}" method="POST" id="delete_menu_form_{{ $menu->id }}" class="inline-flex">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="acl-btn acl-btn-sm acl-btn-danger" title="Delete"
                                                onclick="event.preventDefault(); if (confirm('Are you sure you want to delete this menu?')) { document.getElementById('delete_menu_form_{{ $menu->id }}').submit(); }">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-400">
                                    <svg class="mx-auto mb-2 h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                                    </svg>
                                    No Menus found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Menu Modal --}}
    <div x-show="createOpen" x-cloak @keydown.escape.window="createOpen = false" class="fixed inset-0 z-40 overflow-y-auto" role="dialog" aria-modal="true">
        <div x-show="createOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50" @click="createOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="createOpen" x-transition class="relative w-full max-w-lg rounded-xl bg-white text-left shadow-xl">
                <form method="POST" action="{{ route('acl.menus.store') }}">
                    @csrf
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Create New Menu</h2>
                        <button type="button" @click="createOpen = false" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4 px-5 py-4">
                        <div>
                            <label for="create_title" class="acl-label">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="create_title" class="acl-input" placeholder="Menu title" required />
                        </div>
                        <div>
                            <label for="create_route_name" class="acl-label">Route Name</label>
                            <input type="text" name="route_name" id="create_route_name" class="acl-input" placeholder="Route name" />
                        </div>
                        <div>
                            <label for="create_menu_icon" class="acl-label">Menu Icon</label>
                            <input type="text" name="menu_icon" id="create_menu_icon" class="acl-input" placeholder="Menu Icon" />
                        </div>
                        <div>
                            <label for="create_menu_order" class="acl-label">Menu Order</label>
                            <input type="number" min="1" name="menu_order" id="create_menu_order" class="acl-input" placeholder="Menu Order" />
                        </div>
                        <div>
                            <label for="create_parent_menu_id" class="acl-label">Parent Menu</label>
                            <select name="parent_menu_id" id="create_parent_menu_id" class="acl-select">
                                <option value="">-Select Parent Menu-</option>
                                @if ($menus->whereNull('parent_menu_id')->count())
                                    <optgroup label="Top-level menus">
                                        @foreach ($menus->whereNull('parent_menu_id') as $par_menu)
                                            <option value="{{ $par_menu->id }}">{{ $par_menu->title }} {{ $par_menu->sub_menus_count ? '*' : '' }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if ($menus->whereNotNull('parent_menu_id')->count())
                                    <optgroup label="Sub-menus">
                                        @foreach ($menus->whereNotNull('parent_menu_id') as $par_menu)
                                            <option value="{{ $par_menu->id }}">{{ $par_menu->title }} {{ $par_menu->sub_menus_count ? '*' : '' }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label for="create_is_active" class="acl-label">Is Active <span class="text-red-500">*</span></label>
                            <select name="is_active" id="create_is_active" class="acl-select" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <button type="button" @click="createOpen = false" class="acl-btn acl-btn-secondary">Cancel</button>
                        <button type="submit" class="acl-btn acl-btn-primary">Create Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
