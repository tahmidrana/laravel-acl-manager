@extends('acl::layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="h4">Role config</h5>
        <a href="{{ route('acl.roles.index') }}" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            <i class="bi bi-arrow-left"></i> Back to Roles
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="h5">Role Details</h2>
            <p><strong>Title:</strong> {{ $role->title }}</p>
            <p><strong>Slug:</strong> {{ $role->slug }}</p>
            <p><strong>Permissions Count:</strong> {{ $role->permissions()->count() }}</p>
            <p><strong>Menu Count:</strong> {{ $role->menus()->count() }}</p>
            <p><strong>Active:</strong> {{ $role->is_active ? 'Yes' : 'No' }}</p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Web menus for ({{ $role->title }})</h5>
            {{-- <div class="card-toolbar">
                <label for="role_menus_check_all" class="ml-3">
                    <input type="checkbox" name="role_menus_check_all" id="role_menus_check_all"
                        onclick="for(c in document.getElementsByName('role_menus[]')) document.getElementsByName('role_menus[]').item(c).checked = this.checked">
                    Check All
                </label>
            </div> --}}
        </div>
        <div class="card-body">
            <form action="{{ route('acl.roles.save-role-menus', ['role'=> $role->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="">
                    @foreach ($menus as $menu)
                        @if (!$menu->parent_menu_id)
                            <div class="mb-2">
                                <label for="role_menu{{ $menu->id }}" class="mb-2">
                                    <input type="checkbox" name="role_menus[]" value="{{ $menu->id }}"
                                        id="role_menu{{ $menu->id }}"
                                        {{ $user_type_menus->contains($menu->id) ? 'checked' : '' }}>
                                    {{ $menu->title }}
                                </label>
                                @foreach ($menus as $ch_menu)
                                    @if ($ch_menu->parent_menu_id == $menu->id)
                                        <div class="ms-4 mb-2">
                                            <label for="role_menu{{ $ch_menu->id }}">
                                                <input type="checkbox" name="role_menus[]"
                                                    value="{{ $ch_menu->id }}" id="role_menu{{ $ch_menu->id }}"
                                                    {{ $user_type_menus->contains($ch_menu->id) ? 'checked' : '' }}>
                                                {{ $ch_menu->title }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="">
                    <button type="submit" class="btn btn-sm fw-bold btn-primary mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mt-4 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Permissions for ({{ $role->title }})</h5>
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
                        @foreach ($permissions as $controller => $perm_arr)
                            <div class="col-12 mb-3">
                                <b>* <span style="text-decoration: underline;">{{ $controller }}:</span></b>

                                <div class="row mt-2 mb-3 ms-5">
                                    @foreach ($perm_arr as $perm)
                                        <div class="col-4">
                                            <label for="role_permission{{ $perm->id }}" class="mb-3">
                                                <input type="checkbox" name="role_permissions[]"
                                                    value="{{ $perm->id }}"
                                                    id="role_permission{{ $perm->id }}"
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
                        @endforeach
                    </div>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-sm fw-bold btn-primary mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>


@endsection
