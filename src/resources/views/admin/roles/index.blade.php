@extends('acl::layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Roles</h1>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">Add Role</a>
    </div>

    @if($roles->count())
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
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->title }}</td>
                            <td>{{ $role->slug }}</td>
                            <td>{{ $role->permissions()->count() }}</td>
                            <td>{{ $role->menus()->count() }}</td>
                            <td>{{ $role->is_active ? 'Yes' : 'No' }}</td>
                            <td class="text-center">
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">Edit</a>
                                <!-- You can add delete logic here -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No roles found.</p>
    @endif
@endsection
