<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - E-Commerce Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f5f7fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        
        /* Admin Layout */
        .admin-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .admin-sidebar { 
            width: 280px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            padding: 2rem 0; 
            position: fixed; 
            height: 100vh; 
            overflow-y: auto;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .admin-sidebar::-webkit-scrollbar { width: 6px; }
        .admin-sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); }
        .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 3px; }
        
        .sidebar-header { padding: 0 1.5rem 2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.2); margin-bottom: 1.5rem; }
        .sidebar-header h2 { font-size: 1.3rem; font-weight: 700; margin-bottom: 0.3rem; }
        .sidebar-header p { font-size: 0.85rem; opacity: 0.9; margin: 0; }
        
        .sidebar-menu { list-style: none; padding: 0; }
        .sidebar-menu li { margin: 0; }
        .sidebar-menu a { 
            display: block; 
            padding: 0.8rem 1.5rem; 
            color: rgba(255,255,255,0.8); 
            text-decoration: none; 
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar-menu a:hover { 
            background: rgba(255,255,255,0.1); 
            color: white;
            border-left-color: white;
        }
        .sidebar-menu a.active { 
            background: rgba(255,255,255,0.2); 
            color: white;
            border-left-color: white;
            font-weight: 600;
        }
        
        .sidebar-menu-title { 
            padding: 1rem 1.5rem 0.5rem 1.5rem; 
            font-size: 0.75rem; 
            font-weight: 700; 
            text-transform: uppercase; 
            opacity: 0.6; 
            margin-top: 1rem;
        }
        
        /* Main Content */
        .admin-main { 
            flex: 1; 
            margin-left: 280px; 
            padding: 2rem;
        }
        
        .admin-header { 
            background: white; 
            padding: 1.5rem 2rem; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.08); 
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 { 
            font-size: 2rem; 
            font-weight: 700; 
            margin: 0; 
            color: #333;
        }
        .admin-header-actions { display: flex; gap: 1rem; align-items: center; }
        
        .admin-content { }
        
        .stat-card { 
            background: white; 
            border-radius: 10px; 
            padding: 1.5rem; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.08); 
            text-align: center;
            transition: all 0.3s ease;
        }
        .stat-card:hover { box-shadow: 0 5px 20px rgba(0,0,0,0.12); }
        .stat-number { font-size: 2rem; font-weight: 700; color: #667eea; }
        .stat-label { color: #999; margin-top: 0.5rem; font-size: 0.9rem; }
        
        .data-table { 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .data-table table { margin: 0; }
        .data-table th { 
            background: #f8f9fa; 
            border: none; 
            padding: 1rem; 
            font-weight: 700; 
            color: #333; 
            text-transform: uppercase; 
            font-size: 0.85rem;
        }
        .data-table td { 
            padding: 1rem; 
            border-color: #f0f0f0;
            color: #666;
        }
        .data-table tr:hover { background: #fafbfc; }
        
        .badge-status { 
            font-weight: 600; 
            padding: 0.4rem 0.8rem; 
            border-radius: 20px; 
            font-size: 0.85rem;
        }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-read { background: #cfe2ff; color: #084298; }
        .badge-replied { background: #d1e7dd; color: #0f5132; }
        
        .btn-action { 
            padding: 0.4rem 0.8rem; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.3s ease;
        }
        .btn-view { background: #e3f2fd; color: #1976d2; }
        .btn-view:hover { background: #1976d2; color: white; }
        
        .alert-section { 
            background: white; 
            padding: 1.5rem; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar { width: 100%; height: auto; position: relative; padding: 1rem 0; }
            .admin-main { margin-left: 0; padding: 1rem; }
            .admin-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2><i class="bi bi-shield-lock"></i> Admin</h2>
                <p>{{ auth()->user()->name }}</p>
            </div>

            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) active @endif">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a></li>

                <div class="sidebar-menu-title">Management</div>
                <li><a href="{{ route('admin.visitors.overview') }}" class="@if(request()->routeIs('admin.visitors.*')) active @endif">
                    <i class="bi bi-people"></i> Visitors
                </a></li>
                <li><a href="{{ route('admin.orders.datatable') }}" class="@if(request()->routeIs('admin.orders.datatable')) active @endif">
                    <i class="bi bi-bag"></i> Orders
                </a></li>
                <li><a href="{{ route('admin.contact.replies') }}" class="@if(request()->routeIs('admin.contact.*')) active @endif">
                    <i class="bi bi-envelope"></i> Messages
                </a></li>

                <div class="sidebar-menu-title">Content</div>
                <li><a href="{{ route('admin.products.datatable') }}" class="@if(request()->routeIs('admin.products.datatable')) active @endif">
                    <i class="bi bi-box-seam"></i> Products (DataTable)
                </a></li>
                <li><a href="{{ route('products.index') }}">
                    <i class="bi bi-box-seam"></i> Products
                </a></li>
                <li><a href="#">
                    <i class="bi bi-tag"></i> Categories
                </a></li>

                <div class="sidebar-menu-title">Other</div>
                <li><a href="{{ route('admin.settings.page') }}">
                    <i class="bi bi-gear"></i> Settings
                </a></li>
                <li><form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; width: 100%; text-align: left; padding: 0.8rem 1.5rem; color: rgba(255,255,255,0.8); cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> Please fix the following errors:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Header -->
            <div class="admin-header">
                <div>
                    <h1>@yield('page-title')</h1>
                </div>
                <div class="admin-header-actions">
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-shop"></i> View Store
                    </a>
                </div>
            </div>

            <!-- Content -->
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
