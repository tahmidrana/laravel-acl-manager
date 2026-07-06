@extends('acl::layouts.admin')

@section('content')

<div x-data="{ createOpen: false }">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="flex items-center gap-2 text-xl font-semibold text-slate-900">
                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Roles
            </h1>
            <p class="mt-1 text-sm text-slate-500">Manage roles and their access</p>
        </div>
        <button type="button" @click="createOpen = true" class="acl-btn acl-btn-sm acl-btn-primary">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Role
        </button>
    </div>

    <div class="acl-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="acl-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th class="text-center">Permissions</th>
                        <th class="text-center">Menus</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td class="font-medium text-slate-800">{{ $role->title }}</td>
                            <td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">{{ $role->slug }}</code></td>
                            <td class="text-center"><span class="acl-badge acl-badge-info">{{ $role->permissions()->count() }}</span></td>
                            <td class="text-center"><span class="acl-badge acl-badge-info">{{ $role->menus()->count() }}</span></td>
                            <td class="text-center">
                                @if ($role->is_active)
                                    <span class="acl-badge acl-badge-success">Active</span>
                                @else
                                    <span class="acl-badge acl-badge-muted">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('acl.roles.show', ['role' => $role->id]) }}" class="acl-btn acl-btn-sm acl-btn-secondary" title="Config">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.384-.929.78-.165.398-.143.855.107 1.205l.527.737c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.712.505-.782.93l-.15.893c-.09.543-.559.94-1.109.94h-1.094c-.55 0-1.019-.397-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.856-.142-1.205.108l-.737.527a1.125 1.125 0 0 1-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.384.93-.781.165-.398.143-.855-.108-1.205l-.526-.737a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.93l.15-.894Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>

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
                                                        <form method="POST" action="{{ route('acl.roles.update', ['role' => $role->id]) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                                                                <h2 class="text-lg font-semibold text-slate-900">Update Role</h2>
                                                                <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="space-y-4 px-5 py-4">
                                                                <div>
                                                                    <label for="title_{{ $role->id }}" class="acl-label">Role Title <span class="text-red-500">*</span></label>
                                                                    <input type="text" name="title" id="title_{{ $role->id }}" value="{{ $role->title }}" class="acl-input" required>
                                                                </div>
                                                                <div>
                                                                    <label for="remarks_{{ $role->id }}" class="acl-label">Remarks</label>
                                                                    <input type="text" name="remarks" id="remarks_{{ $role->id }}" value="{{ $role->remarks }}" class="acl-input">
                                                                </div>
                                                                <div>
                                                                    <label for="is_active_{{ $role->id }}" class="acl-label">Is Active <span class="text-red-500">*</span></label>
                                                                    <select name="is_active" id="is_active_{{ $role->id }}" class="acl-select" required>
                                                                        <option value="1" {{ $role->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                                                        <option value="0" {{ $role->is_active == 0 ? 'selected' : '' }}>No</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4">
                                                                <button type="button" @click="open = false" class="acl-btn acl-btn-secondary">Cancel</button>
                                                                <button type="submit" class="acl-btn acl-btn-primary">Update Role</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <form action="{{ route('acl.roles.destroy', ['role' => $role->id]) }}" method="POST" id="delete_role_form_{{ $role->id }}" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="acl-btn acl-btn-sm acl-btn-danger" title="Delete"
                                                onclick="event.preventDefault(); if (confirm('Are you sure you want to delete this role?')) { document.getElementById('delete_role_form_{{ $role->id }}').submit(); }">
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
                                No roles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Role Modal --}}
    <div x-show="createOpen" x-cloak @keydown.escape.window="createOpen = false" class="fixed inset-0 z-40 overflow-y-auto" role="dialog" aria-modal="true">
        <div x-show="createOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50" @click="createOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="createOpen" x-transition class="relative w-full max-w-lg rounded-xl bg-white text-left shadow-xl">
                <form method="POST" action="{{ route('acl.roles.store') }}">
                    @csrf
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Create New Role</h2>
                        <button type="button" @click="createOpen = false" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4 px-5 py-4">
                        <div>
                            <label for="create_title" class="acl-label">Role Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="create_title" class="acl-input" required>
                        </div>
                        <div>
                            <label for="create_remarks" class="acl-label">Remarks</label>
                            <input type="text" name="remarks" id="create_remarks" class="acl-input">
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
                        <button type="submit" class="acl-btn acl-btn-primary">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
