@extends('acl::layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">
            <i class="bi bi-book text-primary me-2"></i>
            User Manual
        </h4>
        <p class="text-muted small mb-0">Complete guide to using Laravel ACL Manager</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
            <div class="card-body p-0">
                <nav class="nav flex-column nav-docs">
                    <a href="#installation" class="nav-link px-4 py-3">
                        <i class="bi bi-download me-2"></i>Installation
                    </a>
                    <a href="#configuration" class="nav-link px-4 py-3">
                        <i class="bi bi-gear me-2"></i>Configuration
                    </a>
                    <a href="#protecting-routes" class="nav-link px-4 py-3">
                        <i class="bi bi-shield-lock me-2"></i>Protecting Routes
                    </a>
                    <a href="#checking-permissions" class="nav-link px-4 py-3">
                        <i class="bi bi-check-circle me-2"></i>Checking Permissions
                    </a>
                    <a href="#blade-directives" class="nav-link px-4 py-3">
                        <i class="bi bi-code me-2"></i>Blade Directives
                    </a>
                    <a href="#admin-panel" class="nav-link px-4 py-3">
                        <i class="bi bi-grid me-2"></i>Admin Panel
                    </a>
                    <a href="#api" class="nav-link px-4 py-3">
                        <i class="bi bi-box me-2"></i>API Reference
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card border-0 shadow-sm mb-4" id="installation">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-download text-primary me-2"></i>Installation</h5>
            </div>
            <div class="card-body">
                <h6 class="text-primary">1. Install via Composer</h6>
                <div class="code-block">
                    <code>composer require tahmid/acl-manager</code>
                    <button class="btn-copy" onclick="copyCode(this)" title="Copy"><i class="bi bi-clipboard"></i></button>
                </div>

                <h6 class="text-primary mt-4">2. Publish Assets</h6>
                <div class="code-block">
                    <code>php artisan vendor:publish --tag=acl-assets</code>
                    <button class="btn-copy" onclick="copyCode(this)" title="Copy"><i class="bi bi-clipboard"></i></button>
                </div>
                <div class="code-block mt-1">
                    <code>php artisan vendor:publish --tag=acl-config</code>
                    <button class="btn-copy" onclick="copyCode(this)" title="Copy"><i class="bi bi-clipboard"></i></button>
                </div>

                <h6 class="text-primary mt-4">3. Run Migrations</h6>
                <div class="code-block">
                    <code>php artisan migrate</code>
                    <button class="btn-copy" onclick="copyCode(this)" title="Copy"><i class="bi bi-clipboard"></i></button>
                </div>

                <h6 class="text-primary mt-4">4. Update User Model</h6>
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

        <div class="card border-0 shadow-sm mb-4" id="configuration">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-gear text-primary me-2"></i>Configuration</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Publish and modify <code>config/acl.php</code>:</p>
                <div class="code-block">
                    <pre>return [
    'dashboard_route' => 'dashboard',
    'superuser_column' => 'is_superuser',
    'middleware' => ['web', 'auth', 'is_superuser'],
];</pre>
                </div>

                <table class="table table-sm mt-3">
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Description</th>
                            <th>Default</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>dashboard_route</code></td>
                            <td>Route for "Back to Dashboard" links</td>
                            <td><code>dashboard</code></td>
                        </tr>
                        <tr>
                            <td><code>superuser_column</code></td>
                            <td>User column that marks superusers</td>
                            <td><code>is_superuser</code></td>
                        </tr>
                        <tr>
                            <td><code>middleware</code></td>
                            <td>Middleware for ACL admin routes</td>
                            <td><code>['web', 'auth', 'is_superuser']</code></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" id="protecting-routes">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-shield-lock text-primary me-2"></i>Protecting Routes</h5>
            </div>
            <div class="card-body">
                <h6 class="text-primary">Auto-check by Controller Method</h6>
                <p class="text-muted">Automatically checks permissions based on <code>ControllerName@methodName</code>:</p>
                <div class="code-block">
                    <pre>Route::middleware('role_permission_check')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
});</pre>
                </div>

                <h6 class="text-primary mt-4">Superuser Only Routes</h6>
                <div class="code-block">
                    <pre>Route::middleware('is_superuser')->group(function () {
    Route::get('/admin-only', [AdminController::class, 'index']);
});</pre>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" id="checking-permissions">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-check-circle text-primary me-2"></i>Checking Permissions</h5>
            </div>
            <div class="card-body">
                <h6 class="text-primary">Using Facade</h6>
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

                <h6 class="text-primary mt-4">Using User Trait</h6>
                <div class="code-block">
                    <pre>// In User model (after adding AclManagerPermission trait)
if ($user->hasPermission('users.delete')) {
    // Allow
}</pre>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" id="blade-directives">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-code text-primary me-2"></i>Blade Directives</h5>
            </div>
            <div class="card-body">
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

        <div class="card border-0 shadow-sm mb-4" id="admin-panel">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-grid text-primary me-2"></i>Admin Panel</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Access the admin panel at <code>/acl-manager</code> (requires superuser).</p>

                <h6 class="text-primary">Features</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i><strong>Roles Management</strong> - Create, edit, delete roles</li>
                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i><strong>Permissions Management</strong> - Auto-sync from controllers, manual creation</li>
                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i><strong>Menus Management</strong> - Define navigation with hierarchy support</li>
                </ul>

                <h6 class="text-primary mt-4">Sync Permissions</h6>
                <p class="text-muted">Visit <code>/acl-manager/permissions/sync-permissions</code> to auto-scan all controllers.</p>

                <h6 class="text-primary mt-4">Permission Descriptions</h6>
                <p class="text-muted">Add descriptions using PHP 8 attributes:</p>
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

        <div class="card border-0 shadow-sm mb-4" id="api">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-box text-primary me-2"></i>API Reference</h5>
            </div>
            <div class="card-body">
                <h6 class="text-primary">Middleware</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Middleware</th>
                            <th>Purpose</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>is_superuser</code></td>
                            <td>Restrict to superusers only</td>
                        </tr>
                        <tr>
                            <td><code>role_permission_check</code></td>
                            <td>Auto-check permission by controller@method</td>
                        </tr>
                    </tbody>
                </table>

                <h6 class="text-primary mt-4">Facade Methods</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Method</th>
                            <th>Parameters</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>Acl::can()</code></td>
                            <td><code>string, ?User</code></td>
                            <td>Check if user can perform action</td>
                        </tr>
                        <tr>
                            <td><code>Acl::hasPermission()</code></td>
                            <td><code>string, ?User</code></td>
                            <td>Alias for can()</td>
                        </tr>
                        <tr>
                            <td><code>Acl::roleHasPermission()</code></td>
                            <td><code>string, string</code></td>
                            <td>Check if role has permission</td>
                        </tr>
                    </tbody>
                </table>

                <h6 class="text-primary mt-4">User Trait Methods</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Method</th>
                            <th>Parameters</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>$user->hasPermission()</code></td>
                            <td><code>string</code></td>
                            <td>Check if user has permission</td>
                        </tr>
                        <tr>
                            <td><code>$user->roles()</code></td>
                            <td>-</td>
                            <td>Get user's roles (BelongsToMany)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-3 pt-2 border-tops">
    <p class="text-muted mb-2">
        Developed by <strong><a href="https://github.com/tahmidrana" target="_blank" class="text-decoration-none">Tahmidur Rahman</a></strong>
    </p>
</div>
@endsection

@push('styles')
<style>
    .nav-docs .nav-link {
        border-left: 3px solid transparent;
        color: #495057;
        font-size: 0.9rem;
    }
    .nav-docs .nav-link:hover {
        background: #f8f9fa;
        border-left-color: #0d6efd;
    }
    .nav-docs .nav-link.active {
        background: #e7f1ff;
        border-left-color: #0d6efd;
        color: #0d6efd;
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
        width: 24px;
        height: 24px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #d4d4d4;
        border-radius: 5px;
        font-size: 0.7rem;
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
        background: #198754;
        border-color: #198754;
        color: #fff;
        opacity: 1;
    }
    .btn-copy.error {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
        opacity: 1;
    }
    .sticky-top {
        z-index: 1;
    }
</style>
@endpush

@push('scripts')
<script>
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
        btn.innerHTML = '<i class="bi bi-check2"></i>';
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.innerHTML = '<i class="bi bi-clipboard"></i>';
        }, 2000);
    }

    function showCopyError(btn) {
        btn.classList.add('error');
        btn.innerHTML = '<i class="bi bi-x-lg"></i>';
        setTimeout(() => {
            btn.classList.remove('error');
            btn.innerHTML = '<i class="bi bi-clipboard"></i>';
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
        const sections = document.querySelectorAll('.card[id]');
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
