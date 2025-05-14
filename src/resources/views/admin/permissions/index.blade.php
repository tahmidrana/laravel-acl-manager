@extends('acl::layouts.admin')

@section('content')


    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Permissions</h1>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            Add Permission
        </button>
    </div>


    <div class="table-responsive">
        <form action="" method="get">
            @csrf
            <input type="text" name="search" id="search" class="form-control mb-3" placeholder="Search Permissions" value="{{ request('search') }}">
        </form>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Controller</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $perm)
                    <tr>
                        <td>{{ $perm->name }}</td>
                        <td>{{ $perm->slug }}</td>
                        <td>{{ $perm->controller_name ?? '-' }}</td>
                        <td>{{ $perm->description ?? '-' }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#updatePermModal_{{ $perm->id }}">Edit</button>

                            <form action="{{ route('acl.permissions.destroy', ['permission' => $perm->id]) }}" method="POST" id="delete_perm_form_{{ $perm->id }}">
                                @csrf
                                @method('DELETE')
                                <input type="submit" name="" id="" class="btn btn-sm btn-danger" value="Delete"
                                    onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this permission?')) { document.getElementById('delete_perm_form_{{ $perm->id }}').submit(); }">
                            </form>
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
                        <td colspan="5" class="text-center">No permissions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $permissions->links() }}
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
