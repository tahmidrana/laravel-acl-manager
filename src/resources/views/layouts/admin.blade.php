<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACL Manager - Admin Panel</title>
    <link href="{{ asset('vendor/acl/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/acl/css/bootstrap-icons.min.css') }}" rel="stylesheet">
    @stack('styles')
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->name ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            {{-- <li>
                                <a class="dropdown-item" href="{{ route('acl.manual') }}">
                                    <i class="bi bi-book me-2"></i>User Manual
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li> --}}
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        @include('acl::components.alerts')

        @yield('content')

        <footer class="border-top mt-5 pt-4 pb-3">
            <div class="d-flex justify-content-between align-items-center text-muted">
                <small>Laravel ACL Manager v1.0.8</small>
                <small>
                    All rights reserved. Developed by peoples @
                    <a href="https://appinionbd.com" target="_blank" class="text-decoration-none">
                        Appinion
                    </a>
                </small>
            </div>
        </footer>
    </main>

    <script src="{{ asset('vendor/acl/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
