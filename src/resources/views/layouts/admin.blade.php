<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACL Manager - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">ACL Admin</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('acl.roles.*') ? 'active' : '' }}" href="{{ route('acl.roles.index') }}">Roles</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('acl.permissions.*') ? 'active' : '' }}" href="{{ route('acl.permissions.index') }}">Permissions</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('acl.menus.*') ? 'active' : '' }}" href="{{ route('acl.menus.index') }}">Menus</a></li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="{{ route(config('acl.dashboard_route', 'dashboard')) }}" class="nav-links btn btn-outline-primary">
                            Back to Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link ms-2">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        @include('acl::components.alerts')

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
