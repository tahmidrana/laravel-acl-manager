@extends('acl::layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Roles</h1>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            Add New Role
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Permissions Count</th>
                    <th>Menu Count</th>
                    <th>Active</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr>
                        <td>{{ $role->title }}</td>
                        <td>{{ $role->slug }}</td>
                        <td>{{ $role->permissions()->count() }}</td>
                        <td>{{ $role->menus()->count() }}</td>
                        <td>{{ $role->is_active ? 'Yes' : 'No' }}</td>
                        <td class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('acl.roles.show', ['role'=> $role->id]) }}" class="btn btn-sm btn-info" title="Config"><i class="bi bi-gear"></i></a>

                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" title="Edit"
                                data-bs-target="#updateModal_{{ $role->id }}"><i class="bi bi-pencil"></i></button>

                            <form action="{{ route('acl.roles.destroy', ['role' => $role->id]) }}" method="POST" id="delete_role_form_{{ $role->id }}" class="d-nones">
                                @csrf
                                @method('DELETE')
                                <button type="submit" name="" id="" class="btn btn-sm btn-danger" title="Delete" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this role?')) { document.getElementById('delete_role_form_{{ $role->id }}').submit(); }"><i class="bi bi-trash"></i></button>
                            </form>
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
                        <td colspan="6" class="text-center">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
