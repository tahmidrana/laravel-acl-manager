@extends('acl::layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-key text-primary me-2"></i>Permissions
            </h4>
            <p class="text-muted small mb-0">Manage and sync controller permissions</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('acl.permissions.sync-permissions') }}" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Are you sure you want to sync permissions? This will overwrite existing permissions.');">
                <i class="bi bi-arrow-repeat me-1"></i>Sync Permissions
            </a>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                <i class="bi bi-plus-lg me-1"></i>Add Permission
            </button>
        </div>
    </div>

    @if (count($permissions_not_exist))
        <div class="alert alert-warning border-0 shadow-sm">
            <strong class="d-block mb-2"><i class="bi bi-exclamation-triangle me-1"></i>These permission files don't exist:</strong>
            <ul class="mb-0">
                @foreach ($permissions_not_exist as $p)
                    <li class="mb-1">
                        <code>App\Http\Controllers\{{ $p->controller_name }}</code>
                        <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 ms-2" title="Delete"
                            onclick="event.preventDefault(); if(confirm('Delete this permission?')) document.getElementById('notExistsPermDelete_{{ $p->id }}').submit();">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form method="POST" action="{{ route('acl.permissions.destroy-not-exists', ['permission' => $p->id]) }}" id="notExistsPermDelete_{{ $p->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (count($permissions_method_not_exist))
        <div class="alert alert-warning border-0 shadow-sm">
            <strong class="d-block mb-2"><i class="bi bi-exclamation-triangle me-1"></i>These controller methods don't exist:</strong>
            <ul class="mb-0">
                @foreach ($permissions_method_not_exist as $p)
                    <li class="mb-1">
                        <code>App\Http\Controllers\{{ $p->name }}</code>
                        <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 ms-2" title="Delete"
                            onclick="event.preventDefault(); if(confirm('Delete this permission?')) document.getElementById('methodNotExistsPermDelete_{{ $p->id }}').submit();">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form method="POST" action="{{ route('acl.permissions.destroy-not-exists', ['permission' => $p->id]) }}" id="methodNotExistsPermDelete_{{ $p->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="" method="get" class="mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search permissions by name or slug..." value="{{ request('search') }}">
                    @if (request('search'))
                        <a href="{{ route('acl.permissions.index') }}" class="btn btn-outline-secondary" title="Clear"><i class="bi bi-x-lg"></i></a>
                    @endif
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
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
                        <td class="fw-medium">{{ $perm->name }}</td>
                        <td><code>{{ $perm->slug }}</code></td>
                        <td>
                            @if ($perm->controller_name)
                                <span class="badge bg-light text-dark border">{{ $perm->controller_name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($perm->description)
                                {{ $perm->description }}
                            @else
                                <a href="{{ route('acl.permissions.sync-controller-permissions', ['permission'=> $perm->id]) }}" onclick="return confirm('Are you sure want to proceed?')" title="Resync this controller methods" class="text-decoration-none small"><i class="bi bi-arrow-repeat me-1"></i>Resync</a>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($perm->is_active)
                                <span class="badge bg-success-subtle text-success-emphasis"><i class="bi bi-check-circle me-1"></i>Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#updatePermModal_{{ $perm->id }}" title="Edit"><i class="bi bi-pencil"></i></button>

                                <form action="{{ route('acl.permissions.destroy', ['permission' => $perm->id]) }}" method="POST" id="delete_perm_form_{{ $perm->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                        onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this permission?')) { document.getElementById('delete_perm_form_{{ $perm->id }}').submit(); }"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Update Permission Modal -->
                    <div class="modal fade" id="updatePermModal_{{ $perm->id }}" tabindex="-1" aria-labelledby="updatePermModallLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('acl.permissions.update', ['permission'=> $perm->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updatePermModallLabel">Update Permission</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name_{{ $perm->id }}" class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name_{{ $perm->id }}" value="{{ $perm->name }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description_{{ $perm->id }}" class="form-label">Description</label>
                                            <input type="text" name="description" id="description_{{ $perm->id }}" value="{{ $perm->description }}" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="is_active_{{ $perm->id }}" class="form-label">Is Active <span class="text-danger">*</span></label>
                                            <select name="is_active" id="is_active_{{ $perm->id }}" class="form-select" required>
                                                <option value="1" {{ $perm->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ $perm->is_active == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Update Permission</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                            No permissions found.
                        </td>
                    </tr>
                @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap mt-3 gap-2">
                <small class="text-muted">
                    Showing {{ $permissions->firstItem() ?? 0 }}–{{ $permissions->lastItem() ?? 0 }} of {{ $permissions->total() }}
                </small>
                {{ $permissions->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Create Permission Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('acl.permissions.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createRoleModalLabel">Create New Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control">
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
                        <button type="submit" class="btn btn-success">Create Permission</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
