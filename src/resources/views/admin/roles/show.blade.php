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
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-person-badge text-primary me-2"></i>
                Role Details
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="text-muted small text-uppercase">Title</div>
                    <div class="fw-semibold">{{ $role->title }}</div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-muted small text-uppercase">Slug</div>
                    <div><code>{{ $role->slug }}</code></div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="text-muted small text-uppercase">Permissions</div>
                    <span class="badge bg-primary-subtle text-primary-emphasis">{{ $role->permissions()->count() }}</span>
                </div>
                <div class="col-md-2 col-4">
                    <div class="text-muted small text-uppercase">Menus</div>
                    <span class="badge bg-info-subtle text-info-emphasis">{{ $role->menus()->count() }}</span>
                </div>
                <div class="col-md-2 col-4">
                    <div class="text-muted small text-uppercase">Status</div>
                    @if ($role->is_active)
                        <span class="badge bg-success-subtle text-success-emphasis"><i class="bi bi-check-circle me-1"></i>Active</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                    @endif
                </div>
            </div>
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
                <div class="row g-4">
                    @forelse ($menus as $menu)
                        @if (!$menu->parent_menu_id)
                            <div class="col-lg-4 col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="role_menus[]" value="{{ $menu->id }}"
                                        class="form-check-input js-menu-parent" data-menu-group="{{ $menu->id }}"
                                        id="role_menu{{ $menu->id }}"
                                        {{ $user_type_menus->contains($menu->id) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="role_menu{{ $menu->id }}">
                                        {{ $menu->title }}
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis ms-1">#{{ $menu->menu_order }}</span>
                                    </label>
                                </div>
                                @php $has_children = $menus->where('parent_menu_id', $menu->id)->count(); @endphp
                                @if ($has_children)
                                    <div class="ms-4 d-flex flex-column gap-2">
                                        @foreach ($menus as $ch_menu)
                                            @if ($ch_menu->parent_menu_id == $menu->id)
                                                <div class="form-check mb-0">
                                                    <input type="checkbox" name="role_menus[]" value="{{ $ch_menu->id }}"
                                                        class="form-check-input js-menu-child" data-menu-group="{{ $menu->id }}"
                                                        id="role_menu{{ $ch_menu->id }}"
                                                        {{ $user_type_menus->contains($ch_menu->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="role_menu{{ $ch_menu->id }}">
                                                        {{ $ch_menu->title }}
                                                        <span class="badge bg-secondary-subtle text-secondary-emphasis ms-1">#{{ $ch_menu->menu_order }}</span>
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    @empty
                        <div class="col-12"><p class="text-muted mb-0">No menus found.</p></div>
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
                    @forelse ($permissions as $controller => $perm_arr)
                        @php $group = $loop->index; @endphp
                        <div class="border rounded mb-3">
                            <div class="bg-light px-3 py-2 border-bottom">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input js-controller-check" data-group="{{ $group }}"
                                        name="checked_controllers[]" value="{{ $controller }}" id="controller_{{ $group }}">
                                    <label class="form-check-label fw-semibold" for="controller_{{ $group }}">
                                        {{ $controller }}
                                    </label>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="row g-3">
                                    @foreach ($perm_arr as $perm)
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-check">
                                                <input type="checkbox" name="role_permissions[]"
                                                    class="form-check-input js-method-check" data-group="{{ $group }}"
                                                    value="{{ $perm->id }}" id="role_permission{{ $perm->id }}"
                                                    {{ $user_type_permissions->contains($perm->id) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_permission{{ $perm->id }}">
                                                    {{ ucfirst(\Str::of($perm->name)->explode('@')[1] ?? \Str::of($perm->name)->explode('@')[0]) }}
                                                    @if ($perm->description)
                                                        <span class="d-block text-muted small">{{ $perm->description }}</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No permissions found.</p>
                    @endforelse
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
