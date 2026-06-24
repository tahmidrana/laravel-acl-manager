@extends('acl::layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-shield-check text-primary me-2"></i>Roles
            </h4>
            <p class="text-muted small mb-0">Manage roles and their access</p>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            <i class="bi bi-plus-lg me-1"></i>Add New Role
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
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
                        <td class="fw-medium">{{ $role->title }}</td>
                        <td><code>{{ $role->slug }}</code></td>
                        <td class="text-center"><span class="badge bg-primary-subtle text-primary-emphasis">{{ $role->permissions()->count() }}</span></td>
                        <td class="text-center"><span class="badge bg-info-subtle text-info-emphasis">{{ $role->menus()->count() }}</span></td>
                        <td class="text-center">
                            @if ($role->is_active)
                                <span class="badge bg-success-subtle text-success-emphasis"><i class="bi bi-check-circle me-1"></i>Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('acl.roles.show', ['role'=> $role->id]) }}" class="btn btn-sm btn-outline-info" title="Config"><i class="bi bi-gear"></i></a>

                                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" title="Edit"
                                    data-bs-target="#updateModal_{{ $role->id }}"><i class="bi bi-pencil"></i></button>

                                <form action="{{ route('acl.roles.destroy', ['role' => $role->id]) }}" method="POST" id="delete_role_form_{{ $role->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this role?')) { document.getElementById('delete_role_form_{{ $role->id }}').submit(); }"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="updateModal_{{ $role->id }}" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('acl.roles.update', ['role'=> $role->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateModalLabel">Update Role</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="title_{{ $role->id }}" class="form-label">Role Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="title_{{ $role->id }}" value="{{ $role->title }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="remarks_{{ $role->id }}" class="form-label">Remarks</label>
                                            <input type="text" name="remarks" id="remarks_{{ $role->id }}" value="{{ $role->remarks }}" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="is_active_{{ $role->id }}" class="form-label">Is Active <span class="text-danger">*</span></label>
                                            <select name="is_active" id="is_active_{{ $role->id }}" class="form-select" required>
                                                <option value="1" {{ $role->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ $role->is_active == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Update Role</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                            No roles found.
                        </td>
                    </tr>
                @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('acl.roles.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createRoleModalLabel">Create New Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Role Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <input type="text" name="remarks" id="remarks" class="form-control">
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
                        <button type="submit" class="btn btn-success">Create Role</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
