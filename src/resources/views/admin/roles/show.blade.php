@extends('acl::layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-shield-check text-primary me-2"></i>
                Role Configuration
            </h4>
            <p class="text-muted small mb-0">Manage role details, menus, and permissions</p>
        </div>
        <a href="{{ route('acl.roles.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Roles
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-whites py-3">
            <h5 class="mb-0">
                <i class="bi bi-person-badge text-primary me-2"></i>
                Role Details
            </h5>
        </div>
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tbody>
                    <tr>
                        <th style="width: 18%;">Title</th>
                        <td>{{ $role->title }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $role->slug }}</td>
                    </tr>
                    <tr>
                        <th>Permissions Count</th>
                        <td>{{ $role->permissions()->count() }}</td>
                    </tr>
                    <tr>
                        <th>Menu Count</th>
                        <td>{{ $role->menus()->count() }}</td>
                    </tr>
                    <tr>
                        <th>Active</th>
                        <td>
                            @if ($role->is_active)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Inactive
                                </span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="card-titles mb-0">
                <i class="bi bi-menu-button-wide text-primary me-2"></i>
                Web Menus
                <span class="text-muted fs-6">({{ $role->title }})</span>
            </h5>
            {{-- <div class="card-toolbar">
                <label for="role_menus_check_all" class="ml-3">
                    <input type="checkbox" name="role_menus_check_all" id="role_menus_check_all"
                        onclick="for(c in document.getElementsByName('role_menus[]')) document.getElementsByName('role_menus[]').item(c).checked = this.checked">
                    Check All
                </label>
            </div> --}}
        </div>
        <div class="card-body">
            <form action="{{ route('acl.roles.save-role-menus', ['role' => $role->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="">
                    @forelse ($menus as $menu)
                        @if (!$menu->parent_menu_id)
                            <div class="mb-2">
                                <label for="role_menu{{ $menu->id }}" class="mb-2">
                                    <input type="checkbox" name="role_menus[]" value="{{ $menu->id }}"
                                        class="js-menu-parent" data-menu-group="{{ $menu->id }}"
                                        id="role_menu{{ $menu->id }}"
                                        {{ $user_type_menus->contains($menu->id) ? 'checked' : '' }}>
                                    {{ $menu->title }}
                                </label>
                                @foreach ($menus as $ch_menu)
                                    @if ($ch_menu->parent_menu_id == $menu->id)
                                        <div class="ms-4 mb-2">
                                            <label for="role_menu{{ $ch_menu->id }}">
                                                <input type="checkbox" name="role_menus[]" value="{{ $ch_menu->id }}"
                                                    class="js-menu-child" data-menu-group="{{ $menu->id }}"
                                                    id="role_menu{{ $ch_menu->id }}"
                                                    {{ $user_type_menus->contains($ch_menu->id) ? 'checked' : '' }}>
                                                {{ $ch_menu->title }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @empty
                        <p>No menus found.</p>
                    @endforelse
                </div>
                @if ($menus->count())
                    <div class="mt-3 pt-2 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="card-titles mb-0">
                <i class="bi bi-key text-primary me-2"></i>
                Permissions
                <span class="text-muted fs-6">({{ $role->title }})</span>
            </h5>
            {{-- <div class="card-toolbar">
                <label for="role_permission_check_all" class="ml-3">
                    <input type="checkbox" name="role_permission_check_all" id="role_permission_check_all"
                        onclick="for(c in document.getElementsByName('role_permissions[]')) document.getElementsByName('role_permissions[]').item(c).checked = this.checked">
                    Check All
                </label>
            </div> --}}
        </div>

        <div class="card-body">

            <form action="{{ route('acl.roles.save-role-permissions', ['role' => $role->id]) }}" method="POST">
                @csrf
                @method('put')

                <div>
                    <div class="row">
                        @forelse ($permissions as $controller => $perm_arr)
                            @php $group = $loop->index; @endphp
                            <div class="col-12 mb-3">
                                <label class="mb-1" style="cursor: pointer;">
                                    <input type="checkbox" class="js-controller-check" data-group="{{ $group }}"
                                        name="checked_controllers[]" value="{{ $controller }}">
                                    <b>* <span style="text-decoration: underline;">{{ $controller }}:</span></b>
                                </label>

                                <div class="row mt-2 mb-3 ms-5">
                                    @foreach ($perm_arr as $perm)
                                        <div class="col-4">
                                            <label for="role_permission{{ $perm->id }}" class="mb-3">
                                                <input type="checkbox" name="role_permissions[]"
                                                    class="js-method-check" data-group="{{ $group }}"
                                                    value="{{ $perm->id }}" id="role_permission{{ $perm->id }}"
                                                    {{ $user_type_permissions->contains($perm->id) ? 'checked' : '' }}>
                                                {{ ucfirst(\Str::of($perm->name)->explode('@')[1] ?? \Str::of($perm->name)->explode('@')[0]) }}

                                                @if ($perm->description)
                                                    <p class="text-primary">-> {{ $perm->description }}</p>
                                                @endif
                                            </label>

                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @empty
                            <p>No permissions found.</p>
                        @endforelse
                    </div>
                </div>
                @if ($permissions->count())
                    <div class="mt-3 pt-2 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save
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
