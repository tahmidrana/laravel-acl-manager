@extends('acl::layouts.admin')

@section('content')

<div x-data="{ createOpen: false }">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="flex items-center gap-2 text-xl font-semibold text-slate-900">
                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
                Permissions
            </h1>
            <p class="mt-1 text-sm text-slate-500">Manage and sync controller permissions</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('acl.permissions.sync-permissions') }}" class="acl-btn acl-btn-sm acl-btn-secondary" onclick="return confirm('Are you sure you want to sync permissions? This will overwrite existing permissions.');">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Sync Permissions
            </a>
            <button type="button" @click="createOpen = true" class="acl-btn acl-btn-sm acl-btn-primary">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Permission
            </button>
        </div>
    </div>

    @if (count($permissions_not_exist))
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
            <strong class="mb-2 flex items-center gap-1.5 font-semibold">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                These permission files don't exist:
            </strong>
            <ul class="space-y-1">
                @foreach ($permissions_not_exist as $p)
                    <li class="flex items-center gap-2">
                        <code class="rounded bg-amber-100 px-1.5 py-0.5 text-xs">App\Http\Controllers\{{ $p->controller_name }}</code>
                        <button type="button" class="text-red-600 hover:text-red-800" title="Delete"
                            onclick="event.preventDefault(); if (confirm('Delete this permission?')) document.getElementById('notExistsPermDelete_{{ $p->id }}').submit();">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('acl.permissions.destroy-not-exists', ['permission' => $p->id]) }}" id="notExistsPermDelete_{{ $p->id }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (count($permissions_method_not_exist))
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
            <strong class="mb-2 flex items-center gap-1.5 font-semibold">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                These controller methods don't exist:
            </strong>
            <ul class="space-y-1">
                @foreach ($permissions_method_not_exist as $p)
                    <li class="flex items-center gap-2">
                        <code class="rounded bg-amber-100 px-1.5 py-0.5 text-xs">App\Http\Controllers\{{ $p->name }}</code>
                        <button type="button" class="text-red-600 hover:text-red-800" title="Delete"
                            onclick="event.preventDefault(); if (confirm('Delete this permission?')) document.getElementById('methodNotExistsPermDelete_{{ $p->id }}').submit();">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('acl.permissions.destroy-not-exists', ['permission' => $p->id]) }}" id="methodNotExistsPermDelete_{{ $p->id }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

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
                        <input type="text" name="search" id="search" class="acl-input pl-10" placeholder="Search permissions by name or slug..." value="{{ request('search') }}">
                    </div>
                    @if (request('search'))
                        <a href="{{ route('acl.permissions.index') }}" class="acl-btn acl-btn-secondary" title="Clear">
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
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Controller</th>
                            <th>Description</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $perm)
                            <tr>
                                <td class="font-medium text-slate-800">{{ $perm->name }}</td>
                                <td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">{{ $perm->slug }}</code></td>
                                <td>
                                    @if ($perm->controller_name)
                                        <span class="acl-badge acl-badge-muted">{{ $perm->controller_name }}</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($perm->description)
                                        {{ $perm->description }}
                                    @else
                                        <a href="{{ route('acl.permissions.sync-controller-permissions', ['permission' => $perm->id]) }}" onclick="return confirm('Are you sure want to proceed?')" title="Resync this controller methods" class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-500">
                                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            </svg>
                                            Resync
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($perm->is_active)
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
                                                            <form method="POST" action="{{ route('acl.permissions.update', ['permission' => $perm->id]) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                                                                    <h2 class="text-lg font-semibold text-slate-900">Update Permission</h2>
                                                                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="space-y-4 px-5 py-4">
                                                                    <div>
                                                                        <label for="name_{{ $perm->id }}" class="acl-label">Name <span class="text-red-500">*</span></label>
                                                                        <input type="text" name="name" id="name_{{ $perm->id }}" value="{{ $perm->name }}" class="acl-input" required>
                                                                    </div>
                                                                    <div>
                                                                        <label for="description_{{ $perm->id }}" class="acl-label">Description</label>
                                                                        <input type="text" name="description" id="description_{{ $perm->id }}" value="{{ $perm->description }}" class="acl-input">
                                                                    </div>
                                                                    <div>
                                                                        <label for="is_active_{{ $perm->id }}" class="acl-label">Is Active <span class="text-red-500">*</span></label>
                                                                        <select name="is_active" id="is_active_{{ $perm->id }}" class="acl-select" required>
                                                                            <option value="1" {{ $perm->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                                                            <option value="0" {{ $perm->is_active == 0 ? 'selected' : '' }}>No</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4">
                                                                    <button type="button" @click="open = false" class="acl-btn acl-btn-secondary">Cancel</button>
                                                                    <button type="submit" class="acl-btn acl-btn-primary">Update Permission</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <form action="{{ route('acl.permissions.destroy', ['permission' => $perm->id]) }}" method="POST" id="delete_perm_form_{{ $perm->id }}" class="inline-flex">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="acl-btn acl-btn-sm acl-btn-danger" title="Delete"
                                                onclick="event.preventDefault(); if (confirm('Are you sure you want to delete this permission?')) { document.getElementById('delete_perm_form_{{ $perm->id }}').submit(); }">
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
                                <td colspan="6" class="py-10 text-center text-slate-400">
                                    <svg class="mx-auto mb-2 h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                                    </svg>
                                    No permissions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                <small class="text-slate-500">
                    Showing {{ $permissions->firstItem() ?? 0 }}–{{ $permissions->lastItem() ?? 0 }} of {{ $permissions->total() }}
                </small>
                {{ $permissions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    {{-- Create Permission Modal --}}
    <div x-show="createOpen" x-cloak @keydown.escape.window="createOpen = false" class="fixed inset-0 z-40 overflow-y-auto" role="dialog" aria-modal="true">
        <div x-show="createOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50" @click="createOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="createOpen" x-transition class="relative w-full max-w-lg rounded-xl bg-white text-left shadow-xl">
                <form method="POST" action="{{ route('acl.permissions.store') }}">
                    @csrf
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Create New Permission</h2>
                        <button type="button" @click="createOpen = false" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4 px-5 py-4">
                        <div>
                            <label for="create_name" class="acl-label">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="create_name" class="acl-input" required>
                        </div>
                        <div>
                            <label for="create_description" class="acl-label">Description</label>
                            <input type="text" name="description" id="create_description" class="acl-input">
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
                        <button type="submit" class="acl-btn acl-btn-primary">Create Permission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
