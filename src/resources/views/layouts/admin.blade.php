<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACL Manager - Admin Panel</title>
    <link href="{{ asset('vendor/acl/css/acl.css') }}" rel="stylesheet">
    <script defer src="{{ asset('vendor/acl/js/alpine.min.js') }}"></script>
    <style>[x-cloak]{display:none !important;}</style>
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    @php
        $navLinks = [
            ['route' => 'acl.roles.index', 'pattern' => 'acl.roles.*', 'label' => 'Roles'],
            ['route' => 'acl.permissions.index', 'pattern' => 'acl.permissions.*', 'label' => 'Permissions'],
            ['route' => 'acl.menus.index', 'pattern' => 'acl.menus.*', 'label' => 'Menus'],
            ['route' => 'acl.activity-logs.index', 'pattern' => 'acl.activity-logs.*', 'label' => 'Activity Log'],
        ];
    @endphp

    <nav x-data="{ mobile: false }" class="bg-slate-900 text-slate-200 shadow">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                {{-- Brand + desktop nav --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('acl.index') }}" class="flex items-center gap-2 text-lg font-semibold text-white">
                        <svg class="h-6 w-6 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        ACL Admin
                    </a>

                    <div class="hidden md:flex md:items-center md:gap-1">
                        @foreach ($navLinks as $link)
                            <a href="{{ route($link['route']) }}"
                               class="rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs($link['pattern']) ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Right side: back-to-dashboard + user dropdown (desktop) --}}
                <div class="hidden md:flex md:items-center md:gap-3">
                    <a href="{{ route(config('acl.dashboard_route', 'dashboard')) }}"
                       class="acl-btn acl-btn-sm acl-btn-outline border-indigo-400 text-indigo-300 hover:bg-slate-800">
                        Back to Dashboard
                    </a>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @keydown.escape.window="open = false"
                                class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm text-slate-200 hover:bg-slate-800">
                            <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span>{{ Auth::user()->name ?? 'User' }}</span>
                            <svg class="h-4 w-4 transition" :class="open && 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition @click.outside="open = false"
                             class="absolute right-0 z-20 mt-2 w-48 overflow-hidden rounded-md bg-white py-1 text-slate-700 shadow-lg ring-1 ring-black/5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm hover:bg-slate-100">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Mobile hamburger --}}
                <div class="flex md:hidden">
                    <button @click="mobile = !mobile" class="inline-flex items-center justify-center rounded-md p-2 text-slate-300 hover:bg-slate-800 hover:text-white">
                        <svg x-show="!mobile" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobile" x-cloak class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobile" x-cloak class="border-t border-slate-800 md:hidden">
            <div class="space-y-1 px-2 py-3">
                @foreach ($navLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       class="block rounded-md px-3 py-2 text-base font-medium {{ request()->routeIs($link['pattern']) ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
            <div class="border-t border-slate-800 px-4 py-3">
                <div class="mb-2 flex items-center gap-2 text-sm text-slate-300">
                    <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    {{ Auth::user()->name ?? 'User' }}
                </div>
                <a href="{{ route(config('acl.dashboard_route', 'dashboard')) }}"
                   class="mb-2 block rounded-md px-3 py-2 text-base font-medium text-slate-300 hover:bg-slate-800 hover:text-white">
                    Back to Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full rounded-md px-3 py-2 text-left text-base font-medium text-slate-300 hover:bg-slate-800 hover:text-white">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 pb-16 pt-6 sm:px-6 lg:px-8">
        @include('acl::components.alerts')

        @yield('content')

        <footer class="mt-12 border-t border-slate-200 pt-5">
            <div class="flex flex-col items-center justify-between gap-2 text-sm text-slate-500 sm:flex-row">
                <span>Laravel ACL Manager v1.1.0</span>
                <span>
                    All rights reserved. Developed by peoples @
                    <a href="https://appinionbd.com" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Appinion
                    </a>
                </span>
            </div>
        </footer>
    </main>

    @stack('scripts')
</body>
</html>
