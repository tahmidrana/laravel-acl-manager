@extends('acl::layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-xl font-semibold text-slate-900">
        <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
        </svg>
        User Manual
    </h1>
    <p class="mt-1 text-sm text-slate-500">Complete guide to using Laravel ACL Manager</p>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
    {{-- Sidebar TOC --}}
    <div class="lg:col-span-3">
        <div class="acl-card sticky top-5 overflow-hidden">
            <nav class="nav-docs flex flex-col py-1">
                <a href="#installation" class="nav-link flex items-center gap-2 px-4 py-3">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    Installation
                </a>
                <a href="#configuration" class="nav-link flex items-center gap-2 px-4 py-3">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.99l1.004.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    Configuration
                </a>
                <a href="#protecting-routes" class="nav-link flex items-center gap-2 px-4 py-3">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                    Protecting Routes
                </a>
                <a href="#checking-permissions" class="nav-link flex items-center gap-2 px-4 py-3">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Checking Permissions
                </a>
                <a href="#blade-directives" class="nav-link flex items-center gap-2 px-4 py-3">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m6.75 7.5 3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                    Blade Directives
                </a>
                <a href="#admin-panel" class="nav-link flex items-center gap-2 px-4 py-3">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                    Admin Panel
                </a>
                <a href="#api" class="nav-link flex items-center gap-2 px-4 py-3">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                    API Reference
                </a>
            </nav>
        </div>
    </div>

    {{-- Content --}}
    <div class="space-y-6 lg:col-span-9">
        <div class="acl-card doc-section scroll-mt-6" id="installation">
            <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Installation</h2>
            </div>
            <div class="space-y-4 px-5 py-4">
                <h3 class="text-sm font-semibold text-indigo-600">1. Install via Composer</h3>
                <div class="code-block">
                    <code>composer require tahmid/acl-manager</code>
                    <button class="btn-copy" onclick="copyCode(this)" title="Copy">@include('acl::admin.partials.clipboard-icon')</button>
                </div>

                <h3 class="text-sm font-semibold text-indigo-600">2. Publish Assets</h3>
                <div class="code-block">
                    <code>php artisan vendor:publish --tag=acl-assets</code>
                    <button class="btn-copy" onclick="copyCode(this)" title="Copy">@include('acl::admin.partials.clipboard-icon')</button>
                </div>
                <div class="code-block">
                    <code>php artisan vendor:publish --tag=acl-config</code>
                    <button class="btn-copy" onclick="copyCode(this)" title="Copy">@include('acl::admin.partials.clipboard-icon')</button>
                </div>

                <h3 class="text-sm font-semibold text-indigo-600">3. Run Migrations</h3>
                <div class="code-block">
                    <code>php artisan migrate</code>
                    <button class="btn-copy" onclick="copyCode(this)" title="Copy">@include('acl::admin.partials.clipboard-icon')</button>
                </div>

                <h3 class="text-sm font-semibold text-indigo-600">4. Update User Model</h3>
                <div class="code-block">
                    <pre>use Tahmid\AclManager\Traits\AclManagerPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use AclManagerPermission;
    // ...
}</pre>
                </div>
            </div>
        </div>

        <div class="acl-card doc-section scroll-mt-6" id="configuration">
            <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Configuration</h2>
            </div>
            <div class="space-y-4 px-5 py-4">
                <p class="text-sm text-slate-500">Publish and modify <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">config/acl.php</code>:</p>
                <div class="code-block">
                    <pre>return [
    'dashboard_route' => 'dashboard',
    'superuser_column' => 'is_superuser',
    'middleware' => ['web', 'auth', 'is_superuser'],
];</pre>
                </div>

                <div class="overflow-x-auto">
                    <table class="acl-table">
                        <thead>
                            <tr><th>Option</th><th>Description</th><th>Default</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">dashboard_route</code></td>
                                <td>Route for "Back to Dashboard" links</td>
                                <td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">dashboard</code></td>
                            </tr>
                            <tr>
                                <td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">superuser_column</code></td>
                                <td>User column that marks superusers</td>
                                <td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">is_superuser</code></td>
                            </tr>
                            <tr>
                                <td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">middleware</code></td>
                                <td>Middleware for ACL admin routes</td>
                                <td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">['web', 'auth', 'is_superuser']</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="acl-card doc-section scroll-mt-6" id="protecting-routes">
            <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Protecting Routes</h2>
            </div>
            <div class="space-y-4 px-5 py-4">
                <h3 class="text-sm font-semibold text-indigo-600">Auto-check by Controller Method</h3>
                <p class="text-sm text-slate-500">Automatically checks permissions based on <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">ControllerName@methodName</code>:</p>
                <div class="code-block">
                    <pre>Route::middleware('role_permission_check')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
});</pre>
                </div>

                <h3 class="text-sm font-semibold text-indigo-600">Superuser Only Routes</h3>
                <div class="code-block">
                    <pre>Route::middleware('is_superuser')->group(function () {
    Route::get('/admin-only', [AdminController::class, 'index']);
});</pre>
                </div>
            </div>
        </div>

        <div class="acl-card doc-section scroll-mt-6" id="checking-permissions">
            <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Checking Permissions</h2>
            </div>
            <div class="space-y-4 px-5 py-4">
                <h3 class="text-sm font-semibold text-indigo-600">Using Facade</h3>
                <div class="code-block">
                    <pre>use Tahmid\AclManager\Facades\Acl;

// Check if current user has permission
if (Acl::can('users.create')) {
    // Allow
}

// Check specific user
if (Acl::can('users.edit', $user)) {
    // Allow
}

// Check role has permission
if (Acl::roleHasPermission('editor', 'posts.publish')) {
    // Allow
}</pre>
                </div>

                <h3 class="text-sm font-semibold text-indigo-600">Using User Trait</h3>
                <div class="code-block">
                    <pre>// In User model (after adding AclManagerPermission trait)
if ($user->hasPermission('users.delete')) {
    // Allow
}</pre>
                </div>
            </div>
        </div>

        <div class="acl-card doc-section scroll-mt-6" id="blade-directives">
            <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Blade Directives</h2>
            </div>
            <div class="space-y-4 px-5 py-4">
                <div class="code-block">
                    <pre>@verbatim
{{-- Using Laravel's @can directive --}}
@can('users.create')
    <a href="{{ route('users.create') }}">Create User</a>
@endcan

{{-- Using package's @acl directive --}}
@acl('users.edit')
    <a href="{{ route('users.edit', $user->id) }}">Edit</a>
@endacl

{{-- With @else --}}
@acl('users.delete')
    <a href="#">Delete</a>
@else
    <span class="text-muted">No permission</span>
@endacl
@endverbatim</pre>
                </div>
            </div>
        </div>

        <div class="acl-card doc-section scroll-mt-6" id="admin-panel">
            <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Admin Panel</h2>
            </div>
            <div class="space-y-4 px-5 py-4">
                <p class="text-sm text-slate-500">Access the admin panel at <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">/acl-manager</code> (requires superuser).</p>

                <h3 class="text-sm font-semibold text-indigo-600">Features</h3>
                <ul class="space-y-2">
                    @foreach ([
                        ['Roles Management', 'Create, edit, delete roles'],
                        ['Permissions Management', 'Auto-sync from controllers, manual creation'],
                        ['Menus Management', 'Define navigation with hierarchy support'],
                    ] as [$feature, $desc])
                        <li class="flex items-start gap-2 text-sm text-slate-600">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            <span><strong class="font-semibold text-slate-800">{{ $feature }}</strong> - {{ $desc }}</span>
                        </li>
                    @endforeach
                </ul>

                <h3 class="text-sm font-semibold text-indigo-600">Sync Permissions</h3>
                <p class="text-sm text-slate-500">Visit <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">/acl-manager/permissions/sync-permissions</code> to auto-scan all controllers.</p>

                <h3 class="text-sm font-semibold text-indigo-600">Permission Descriptions</h3>
                <p class="text-sm text-slate-500">Add descriptions using PHP 8 attributes:</p>
                <div class="code-block">
                    <pre>use Tahmid\AclManager\Attributes\PermissionAttr;

class UserController extends Controller
{
    #[PermissionAttr(description: 'Create new user accounts')]
    public function store(Request $request) { }

    #[PermissionAttr(description: 'Delete existing user accounts')]
    public function destroy(User $user) { }
}</pre>
                </div>
            </div>
        </div>

        <div class="acl-card doc-section scroll-mt-6" id="api">
            <div class="flex items-center gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">API Reference</h2>
            </div>
            <div class="space-y-4 px-5 py-4">
                <h3 class="text-sm font-semibold text-indigo-600">Middleware</h3>
                <div class="overflow-x-auto">
                    <table class="acl-table">
                        <thead><tr><th>Middleware</th><th>Purpose</th></tr></thead>
                        <tbody>
                            <tr><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">is_superuser</code></td><td>Restrict to superusers only</td></tr>
                            <tr><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">role_permission_check</code></td><td>Auto-check permission by controller@method</td></tr>
                        </tbody>
                    </table>
                </div>

                <h3 class="text-sm font-semibold text-indigo-600">Facade Methods</h3>
                <div class="overflow-x-auto">
                    <table class="acl-table">
                        <thead><tr><th>Method</th><th>Parameters</th><th>Description</th></tr></thead>
                        <tbody>
                            <tr><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">Acl::can()</code></td><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">string, ?User</code></td><td>Check if user can perform action</td></tr>
                            <tr><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">Acl::hasPermission()</code></td><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">string, ?User</code></td><td>Alias for can()</td></tr>
                            <tr><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">Acl::roleHasPermission()</code></td><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">string, string</code></td><td>Check if role has permission</td></tr>
                        </tbody>
                    </table>
                </div>

                <h3 class="text-sm font-semibold text-indigo-600">User Trait Methods</h3>
                <div class="overflow-x-auto">
                    <table class="acl-table">
                        <thead><tr><th>Method</th><th>Parameters</th><th>Description</th></tr></thead>
                        <tbody>
                            <tr><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">$user->hasPermission()</code></td><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">string</code></td><td>Check if user has permission</td></tr>
                            <tr><td><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">$user->roles()</code></td><td>-</td><td>Get user's roles (BelongsToMany)</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 border-t border-slate-200 pt-4 text-center">
    <p class="text-sm text-slate-500">
        Developed by <strong><a href="https://github.com/tahmidrana" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">Tahmidur Rahman</a></strong>
    </p>
</div>
@endsection

@push('styles')
<style>
    .nav-docs .nav-link {
        border-left: 3px solid transparent;
        color: #475569;
        font-size: 0.9rem;
    }
    .nav-docs .nav-link:hover {
        background: #f8fafc;
        border-left-color: #4f46e5;
    }
    .nav-docs .nav-link.active {
        background: #eef2ff;
        border-left-color: #4f46e5;
        color: #4f46e5;
        font-weight: 500;
    }
    .code-block {
        position: relative;
        background: #1e1e1e;
        border-radius: 8px;
        padding: 16px;
        overflow-x: auto;
    }
    .code-block code,
    .code-block pre {
        color: #d4d4d4;
        font-family: 'Fira Code', 'Consolas', monospace;
        font-size: 0.85rem;
        margin: 0;
        background: none;
        padding: 0;
    }
    .code-block pre {
        white-space: pre-wrap;
    }
    .btn-copy {
        position: absolute;
        top: 8px;
        right: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #d4d4d4;
        border-radius: 5px;
        line-height: 1;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s, background 0.2s, border-color 0.2s, color 0.2s, transform 0.1s;
    }
    .code-block:hover .btn-copy {
        opacity: 1;
    }
    .btn-copy:hover {
        background: rgba(255, 255, 255, 0.18);
        border-color: rgba(255, 255, 255, 0.35);
        color: #fff;
    }
    .btn-copy:active {
        transform: scale(0.92);
    }
    .btn-copy.copied {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
        opacity: 1;
    }
    .btn-copy.error {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    var ICON_CLIPBOARD = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>';
    var ICON_CHECK = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>';
    var ICON_X = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>';

    function copyCode(btn) {
        const codeBlock = btn.closest('.code-block');
        const code = codeBlock.querySelector('code, pre');
        const text = (code.textContent || code.innerText).trim();

        copyText(text)
            .then(() => showCopied(btn))
            .catch(() => showCopyError(btn));
    }

    function copyText(text) {
        // Use the async Clipboard API when available (HTTPS / localhost only)
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }

        // Fallback for insecure (http) contexts
        return new Promise((resolve, reject) => {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.top = '-9999px';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            try {
                document.execCommand('copy') ? resolve() : reject();
            } catch (err) {
                reject(err);
            } finally {
                document.body.removeChild(textarea);
            }
        });
    }

    function showCopied(btn) {
        btn.classList.add('copied');
        btn.innerHTML = ICON_CHECK;
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.innerHTML = ICON_CLIPBOARD;
        }, 2000);
    }

    function showCopyError(btn) {
        btn.classList.add('error');
        btn.innerHTML = ICON_X;
        setTimeout(() => {
            btn.classList.remove('error');
            btn.innerHTML = ICON_CLIPBOARD;
        }, 2000);
    }

    document.querySelectorAll('.nav-docs .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('.doc-section[id]');
        let current = '';

        sections.forEach(section => {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 100) {
                current = section.getAttribute('id');
            }
        });

        document.querySelectorAll('.nav-docs .nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
</script>
@endpush
