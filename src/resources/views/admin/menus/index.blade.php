@extends('acl::layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-menu-button-wide text-primary me-2"></i>Menus
            </h4>
            <p class="text-muted small mb-0">Manage navigation menus and sub-menus</p>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createMenuModal">
            <i class="bi bi-plus-lg me-1"></i>Add New Menu
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="" method="get" class="mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search menus..." value="{{ request('search') }}">
                    @if (request('search'))
                        <a href="{{ route('acl.menus.index') }}" class="btn btn-outline-secondary" title="Clear"><i class="bi bi-x-lg"></i></a>
                    @endif
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
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
                    <tr>
                        <td class="fw-medium" style="border-left: {{ !$menu->parent_menu_id ? '3px' : '0px' }} solid var(--bs-success);">{{ $menu->title }}</td>
                        <td>{{ $menu->parent_menu ? $menu->parent_menu->title : '-' }}</td>
                        <td>@if ($menu->route_name)<code>{{ $menu->route_name }}</code>@else <span class="text-muted">-</span>@endif</td>
                        <td>@if ($menu->menu_icon)<i class="{{ $menu->menu_icon }}"></i> <span class="text-muted small">{{ $menu->menu_icon }}</span>@else <span class="text-muted">-</span>@endif</td>
                        <td class="text-center">{{ $menu->menu_order }}</td>
                        <td class="text-center">
                            @if ($menu->is_active)
                                <span class="badge bg-success-subtle text-success-emphasis"><i class="bi bi-check-circle me-1"></i>Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#updateModal_{{ $menu->id }}" title="Edit"><i class="bi bi-pencil"></i></button>

                                <form action="{{ route('acl.menus.destroy', ['menu' => $menu->id]) }}" method="POST" id="delete_menu_form_{{ $menu->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this menu?')) { document.getElementById('delete_menu_form_{{ $menu->id }}').submit(); }">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="updateModal_{{ $menu->id }}" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('acl.menus.update',['menu'=> $menu->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateModalLabel">Update Menu</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="title_{{ $menu->id }}" class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="title_{{ $menu->id }}" value="{{ $menu->title }}"  class="form-control" placeholder="Menu title" required />
                                        </div>
                                        <div class="mb-3">
                                            <label for="route_name_{{ $menu->id }}" class="form-label">Route Name </label>
                                            <input type="text" name="route_name" id="route_name_{{ $menu->id }}" value="{{ $menu->route_name }}" class="form-control" placeholder="Route name" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="menu_icon_{{ $menu->id }}" class="form-label">Menu Icon</label>
                                            <input type="text" name="menu_icon" id="menu_icon_{{ $menu->id }}" value="{{ $menu->menu_icon }}" class="form-control" placeholder="Menu Icon" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="menu_order_{{ $menu->id }}" class="form-label">Menu Order</label>
                                            <input type="number" min="1" name="menu_order" id="menu_order_{{ $menu->id }}" value="{{ $menu->menu_order }}" class="form-control"
                                                placeholder="Menu Order" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="parent_menu_id_{{ $menu->id }}" class="form-label">Parent Menu</label>
                                            <select name="parent_menu_id" id="parent_menu_id_{{ $menu->id }}" class="form-select">
                                                <option value="">-Select Parent Menu-</option>
                                                @foreach ($menus as $par_menu)
                                                <option value="{{ $par_menu->id }}" {{ $menu->parent_menu_id == $par_menu->id ? 'selected' : '' }}>{{ $par_menu->title }} {{ $par_menu->sub_menus_count ? '*' : '' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="is_active_{{ $menu->id }}" class="form-label">Is Active <span class="text-danger">*</span></label>
                                            <select name="is_active" id="is_active_{{ $menu->id }}" class="form-select" required>
                                                <option value="1" {{ $menu->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ $menu->is_active == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Update Menu</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                            No Menus found.
                        </td>
                    </tr>
                @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Create Menu Modal -->
    <div class="modal fade" id="createMenuModal" tabindex="-1" aria-labelledby="createMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('acl.menus.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMenuModalLabel">Create New Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Menu title" required />
                        </div>
                        <div class="mb-3">
                            <label for="route_name" class="form-label">Route Name </label>
                            <input type="text" name="route_name" id="route_name" class="form-control" placeholder="Route name" />
                        </div>
                        <div class="mb-3">
                            <label for="menu_icon" class="form-label">Menu Icon</label>
                            <input type="text" name="menu_icon" id="menu_icon" class="form-control" placeholder="Menu Icon" />
                        </div>
                        <div class="mb-3">
                            <label for="menu_order" class="form-label">Menu Order</label>
                            <input type="number" min="1" name="menu_order" id="menu_order" class="form-control"
                                placeholder="Menu Order" />
                        </div>
                        <div class="mb-3">
                            <label for="parent_menu_id" class="form-label">Parent Menu</label>
                            <select name="parent_menu_id" id="parent_menu_id" class="form-select">
                                <option value="">-Select Parent Menu-</option>
                                @foreach ($menus as $par_menu)
                                <option value="{{ $par_menu->id }}">{{ $par_menu->title }}
                                    {{ $par_menu->sub_menus_count ? '*' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Is Active <span class="text-danger">*</span></label>
                            <select name="is_active" id="is_active" class="form-select" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Create Menu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
