@extends('acl::layouts.admin')

@section('content')

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="flex items-center gap-2 text-xl font-semibold text-slate-900">
                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Role Configuration
            </h1>
            <p class="mt-1 text-sm text-slate-500">Manage role details, menus, and permissions</p>
        </div>
        <a href="{{ route('acl.roles.index') }}" class="acl-btn acl-btn-sm acl-btn-secondary">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Roles
        </a>
    </div>

    {{-- Role details --}}
    <div class="acl-card mb-6">
        <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4">
            <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <h2 class="font-semibold text-slate-900">Role Details</h2>
        </div>
        <div class="grid grid-cols-2 gap-4 px-5 py-4 sm:grid-cols-3 lg:grid-cols-5">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Title</div>
                <div class="mt-1 font-semibold text-slate-800">{{ $role->title }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Slug</div>
                <div class="mt-1"><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">{{ $role->slug }}</code></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Permissions</div>
                <div class="mt-1"><span class="acl-badge acl-badge-info">{{ $role->permissions()->count() }}</span></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Menus</div>
                <div class="mt-1"><span class="acl-badge acl-badge-info">{{ $role->menus()->count() }}</span></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</div>
                <div class="mt-1">
                    @if ($role->is_active)
                        <span class="acl-badge acl-badge-success">Active</span>
                    @else
                        <span class="acl-badge acl-badge-muted">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Web menus --}}
    <div class="acl-card mb-6">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="flex items-center gap-2 font-semibold text-slate-900">
                <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                </svg>
                Web Menus
                <span class="text-sm font-normal text-slate-400">({{ $role->title }})</span>
            </h2>
        </div>
        <div class="px-5 py-4">
            <form action="{{ route('acl.roles.save-role-menus', ['role' => $role->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($menus as $menu)
                        @if (!$menu->parent_menu_id)
                            <div class="rounded-lg border border-slate-200 p-4">
                                <div class="flex items-start gap-2">
                                    <input type="checkbox" name="role_menus[]" value="{{ $menu->id }}"
                                        class="acl-checkbox mt-0.5 js-menu-parent" data-menu-group="{{ $menu->id }}"
                                        id="role_menu{{ $menu->id }}"
                                        {{ $user_type_menus->contains($menu->id) ? 'checked' : '' }}>
                                    <label class="flex items-center gap-1.5 text-sm font-semibold text-slate-800" for="role_menu{{ $menu->id }}">
                                        {{ $menu->title }}
                                        <span class="acl-badge acl-badge-muted">#{{ $menu->menu_order }}</span>
                                    </label>
                                </div>
                                @php $has_children = $menus->where('parent_menu_id', $menu->id)->count(); @endphp
                                @if ($has_children)
                                    <div class="ml-6 mt-3 flex flex-col gap-2">
                                        @foreach ($menus as $ch_menu)
                                            @if ($ch_menu->parent_menu_id == $menu->id)
                                                <div class="flex items-start gap-2">
                                                    <input type="checkbox" name="role_menus[]" value="{{ $ch_menu->id }}"
                                                        class="acl-checkbox mt-0.5 js-menu-child" data-menu-group="{{ $menu->id }}"
                                                        id="role_menu{{ $ch_menu->id }}"
                                                        {{ $user_type_menus->contains($ch_menu->id) ? 'checked' : '' }}>
                                                    <label class="flex items-center gap-1.5 text-sm text-slate-600" for="role_menu{{ $ch_menu->id }}">
                                                        {{ $ch_menu->title }}
                                                        <span class="acl-badge acl-badge-muted">#{{ $ch_menu->menu_order }}</span>
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    @empty
                        <div class="col-span-full"><p class="text-sm text-slate-500">No menus found.</p></div>
                    @endforelse
                </div>
                @if ($menus->count())
                    <div class="mt-5 border-t border-slate-200 pt-4">
                        <button type="submit" class="acl-btn acl-btn-primary">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                            </svg>
                            Save
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Permissions --}}
    <div class="acl-card mb-6">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="flex items-center gap-2 font-semibold text-slate-900">
                <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
                Permissions
                <span class="text-sm font-normal text-slate-400">({{ $role->title }})</span>
            </h2>
        </div>
        <div class="px-5 py-4">
            <form action="{{ route('acl.roles.save-role-permissions', ['role' => $role->id]) }}" method="POST">
                @csrf
                @method('put')

                <div class="space-y-4">
                    @forelse ($permissions as $controller => $perm_arr)
                        @php $group = $loop->index; @endphp
                        <div class="overflow-hidden rounded-lg border border-slate-200">
                            <div class="border-b border-slate-200 bg-slate-50 px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" class="acl-checkbox js-controller-check" data-group="{{ $group }}"
                                        name="checked_controllers[]" value="{{ $controller }}" id="controller_{{ $group }}">
                                    <label class="text-sm font-semibold text-slate-800" for="controller_{{ $group }}">
                                        {{ $controller }}
                                    </label>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($perm_arr as $perm)
                                        <div class="flex items-start gap-2">
                                            <input type="checkbox" name="role_permissions[]"
                                                class="acl-checkbox mt-0.5 js-method-check" data-group="{{ $group }}"
                                                value="{{ $perm->id }}" id="role_permission{{ $perm->id }}"
                                                {{ $user_type_permissions->contains($perm->id) ? 'checked' : '' }}>
                                            <label class="text-sm text-slate-600" for="role_permission{{ $perm->id }}">
                                                {{ ucfirst(\Illuminate\Support\Str::of($perm->name)->explode('@')[1] ?? \Illuminate\Support\Str::of($perm->name)->explode('@')[0]) }}
                                                @if ($perm->description)
                                                    <span class="block text-xs text-slate-400">{{ $perm->description }}</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No permissions found.</p>
                    @endforelse
                </div>
                @if ($permissions->count())
                    <div class="mt-5 border-t border-slate-200 pt-4">
                        <button type="submit" class="acl-btn acl-btn-primary">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                            </svg>
                            Save
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function methodsOf(group) {
                return Array.prototype.slice.call(
                    document.querySelectorAll('.js-method-check[data-group="' + group + '"]')
                );
            }

            // Reflect the controller checkbox from its methods:
            // checked when at least one method is checked (so the group is
            // submitted and kept on save), indeterminate when only some are.
            function syncControllerState(group, forceController) {
                var controller = document.querySelector('.js-controller-check[data-group="' + group + '"]');
                if (!controller) return;

                var boxes = methodsOf(group);
                var checked = boxes.filter(function (b) { return b.checked; }).length;

                controller.indeterminate = checked > 0 && checked < boxes.length;
                if (forceController) {
                    controller.checked = checked > 0;
                }
            }

            document.querySelectorAll('.js-controller-check').forEach(function (controller) {
                var group = controller.dataset.group;
                syncControllerState(group, true); // reflect saved child state on load

                // Controller -> methods: checking cascades down to check all
                // methods; unchecking leaves the method boxes as-is on screen,
                // but the controller stays unchecked so the backend removes them.
                controller.addEventListener('change', function () {
                    if (controller.checked) {
                        methodsOf(group).forEach(function (b) { b.checked = true; });
                    }
                    controller.indeterminate = false;
                });
            });

            // Methods -> controller
            document.querySelectorAll('.js-method-check').forEach(function (box) {
                box.addEventListener('change', function () {
                    syncControllerState(box.dataset.group, true);
                });
            });

            // ---- Menus: parent menu <-> sub-menus ----
            function subMenusOf(group) {
                return Array.prototype.slice.call(
                    document.querySelectorAll('.js-menu-child[data-menu-group="' + group + '"]')
                );
            }

            // Reflect the parent menu from its sub-menus. When `forceParent` is
            // true (a sub-menu was toggled), the parent follows: checked when any
            // sub-menu is checked, indeterminate when only some are, unchecked when
            // none. On load we only set the indeterminate hint and keep the saved
            // parent state untouched.
            function syncParentState(group, forceParent) {
                var parent = document.querySelector('.js-menu-parent[data-menu-group="' + group + '"]');
                if (!parent) return;

                var subs = subMenusOf(group);
                if (!subs.length) return; // top-level menu with no sub-menus

                var checked = subs.filter(function (b) { return b.checked; }).length;

                parent.indeterminate = checked > 0 && checked < subs.length;
                if (forceParent) {
                    parent.checked = checked > 0;
                }
            }

            document.querySelectorAll('.js-menu-parent').forEach(function (parent) {
                var group = parent.dataset.menuGroup;
                syncParentState(group, false); // initial indeterminate hint, respect saved state

                parent.addEventListener('change', function () {
                    // Checking a parent cascades down to its sub-menus.
                    // Unchecking a parent leaves sub-menus as-is on screen;
                    // the backend removes them on save.
                    if (parent.checked) {
                        subMenusOf(group).forEach(function (b) { b.checked = true; });
                    }
                    parent.indeterminate = false;
                });
            });

            document.querySelectorAll('.js-menu-child').forEach(function (box) {
                box.addEventListener('change', function () {
                    syncParentState(box.dataset.menuGroup, true);
                });
            });
        });
    </script>

@endsection
