<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Store - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            background: #f5f5f5;
            overflow-x: hidden;
            width: 100%;
        }

        body {
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-custom .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.9) !important;
            margin-left: 1rem;
            transition: all 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        /* Auth Buttons */
        .auth-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-login {
            background: white;
            color: #667eea;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-signup {
            background: #FFD700;
            color: #667eea;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-signup:hover {
            background: #FFC700;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
        }

        .cart-badge {
            background: #FFD700;
            color: #667eea;
            border-radius: 50%;
            padding: 0.25rem 0.6rem;
            font-weight: bold;
            font-size: 0.85rem;
            margin-left: 0.25rem;
        }

        /* Search Bar */
        .search-section {
            background: white;
            padding: 1.5rem;
            margin: 1.5rem auto;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 1200px;
            overflow-x: hidden;
        }

        .search-input {
            border: 2px solid #667eea;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .search-input:focus {
            outline: none;
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Filter Section */
        .filter-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 100%;
            overflow-x: hidden;
        }

        .filter-title {
            font-weight: 700;
            color: #667eea;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #667eea;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .form-select, .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 0.65rem 0.75rem;
            width: 100%;
            max-width: 100%;
        }

        .form-select:focus, .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Product Card */
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
        }

        .product-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            position: relative;
            overflow: hidden;
        }

        .product-image i {
            opacity: 0.3;
        }

        .product-image img {
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .product-name:hover {
            color: #667eea;
        }

        .product-category {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            flex-grow: 1;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .stock-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.75rem;
        }

        .btn-add-to-cart {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-add-to-cart:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-add-to-cart:active {
            transform: scale(0.98);
        }

        /* Grid Layout */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            width: 100%;
            overflow-x: hidden;
        }

        .pagination {
            justify-content: center;
            margin-top: 3rem;
            margin-bottom: 2rem;
        }

        .page-link {
            color: #667eea;
            border-color: #667eea;
        }

        .page-link:hover {
            background-color: #667eea;
            border-color: #667eea;
        }

        .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }

        /* Results Info */
        .results-info {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            grid-column: 1 / -1;
        }

        .results-info strong {
            color: #667eea;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 10px;
            margin-top: 2rem;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #999;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            min-width: 200px;
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown a, .user-dropdown button {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #333;
            text-decoration: none;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-dropdown form {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .user-dropdown button {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .user-dropdown a:hover, .user-dropdown button:hover {
            background: #f5f5f5;
            color: #667eea;
        }

        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 1.2rem;
            }
        }

        @media (max-width: 768px) {
            .auth-buttons {
                flex-direction: column;
                width: 100%;
                margin-top: 1rem;
            }

            .btn-login, .btn-signup {
                width: 100%;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .container-lg {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .product-image {
                height: 150px !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-custom sticky-top">
        <div class="container-lg">
            <a class="navbar-brand" href="{{ route('products.index') }}">
                <i class="bi bi-bag-check"></i> E-Commerce Store
            </a>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('cart.index') }}" class="nav-link position-relative">
                    <i class="bi bi-cart3" style="font-size: 1.3rem;"></i>
                    <span class="cart-badge" id="cartCount">0</span>
                </a>

                @auth
                    <a href="{{ route('contact.show') }}" class="nav-link" title="Contact Us">
                        <i class="bi bi-envelope" style="font-size: 1.2rem;"></i>
                    </a>
                @endauth

                @auth
                    <div class="user-menu position-relative">
                        <button class="nav-link dropdown-toggle" type="button" onclick="toggleUserMenu()" style="background: none; border: none;">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="{{ route('checkout.orders') }}">
                                <i class="bi bi-file-text"></i> My Orders
                            </a>
                            @if(auth()->user()->is_admin)
                                <hr style="margin: 0.5rem 0;">
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-gear"></i> Admin Panel
                                </a>
                                <hr style="margin: 0.5rem 0;">
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn btn-login btn-sm">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-signup btn-sm">
                            <i class="bi bi-person-plus"></i> Sign Up
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container-lg py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search Section -->
        <div class="search-section">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control search-input" placeholder="🔍 Search for products..." value="{{ $search }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="filter-card">
                        <h6 class="filter-title"><i class="bi bi-cash-coin"></i> Price Range</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ $minPrice }}" step="0.01">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ $maxPrice }}" step="0.01">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-2">Apply Price Filter</button>
                    </div>

                    <div class="filter-card">
                        <h6 class="filter-title"><i class="bi bi-sort-down"></i> Sort By</h6>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Latest Products</option>
                            <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    <div class="filter-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <p style="margin: 0; font-size: 0.9rem;"><strong>${{ number_format($minPriceAvailable, 2) }}</strong> to <strong>${{ number_format($maxPriceAvailable, 2) }}</strong></p>
                    </div>
                </form>
            </div>

            <!-- Products Section -->
            <div class="col-lg-9">
                <div class="products-grid">
                    @forelse ($products as $product)
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->image_path && file_exists(public_path($product->image_path)))
                                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="bi bi-image"></i>
                                @endif
                                @auth
                                    @if(Auth::user()->is_admin || Auth::user()->id === $product->user_id)
                                        <div style="position: absolute; top: 5px; right: 5px;">
                                            <a href="{{ route('products.edit-image', $product) }}" class="btn btn-sm btn-warning" title="Upload Image">
                                                <i class="bi bi-image"></i>
                                            </a>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                            <div class="product-info">
                                <a href="{{ route('products.show', $product) }}" class="product-name">
                                    {{ $product->name }}
                                </a>

                                @if($product->category)
                                    <span class="product-category">{{ $product->category->name }}</span>
                                @endif

                                <p class="product-description">
                                    {{ Str::limit($product->description, 100) }}
                                </p>

                                @if($product->stock > 0)
                                    <span class="badge bg-success stock-badge">In Stock •  
{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger stock-badge">Out of Stock</span>
                                @endif

                                <div class="product-footer">
                                    <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-add-to-cart btn-sm">
                                                <i class="bi bi-cart-plus"></i> Add
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h3>No Products Found</h3>
                            <p>Try adjusting your filters</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-arrow-left"></i> View All
                            </a>
                        </div>
                    @endforelse
                </div>

                <nav aria-label="Page navigation">
                    {{ $products->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleUserMenu() {
            document.getElementById('userDropdown')?.classList.toggle('show');
        }

        // Close dropdown when clicking outside, but not on form submissions
        document.addEventListener('click', function(e) {
            const userMenu = e.target.closest('.user-menu');
            const logoutForm = e.target.closest('.user-dropdown form');
            
            if (!userMenu && !logoutForm) {
                document.getElementById('userDropdown')?.classList.remove('show');
            }
        });

        // Allow form submission inside dropdown
        document.querySelectorAll('.user-dropdown form').forEach(form => {
            form.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            form.addEventListener('submit', function(e) {
                // Allow the form to submit normally
                return true;
            });
        });

        function updateCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(r => r.json())
                .then(d => document.getElementById('cartCount').textContent = d.count);
        }

        updateCartCount();
        document.querySelectorAll('form').forEach(f => {
            if (f.action.includes('cart/add')) {
                f.addEventListener('submit', () => setTimeout(updateCartCount, 500));
            }
        });
    </script>
</body>
</html>
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0">Products</h1>
                <p class="text-muted">Browse our collection</p>
            </div>
            <div class="col-md-6 text-end">
                @can('create', App\Models\Product::class)
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add Product
                    </a>
                @endcan
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-md-3">
                <div class="card bg-white mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Filters</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                            <!-- Search -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Search</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="search" 
                                    placeholder="Search products..."
                                    value="{{ $search }}"
                                    onchange="document.getElementById('filterForm').submit()"
                                >
                            </div>

                            <!-- Category Filter -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <select class="form-select" name="category" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range Filter -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Price Range</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            name="min_price" 
                                            placeholder="Min"
                                            value="{{ $minPrice }}"
                                            step="0.01"
                                        >
                                    </div>
                                    <div class="col-6">
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            name="max_price" 
                                            placeholder="Max"
                                            value="{{ $maxPrice }}"
                                            step="0.01"
                                        >
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-primary mt-2 w-100">
                                    Apply Price Filter
                                </button>
                            </div>

                            <!-- Sort -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sort By</label>
                                <select class="form-select" name="sort" onchange="document.getElementById('filterForm').submit()">
                                    <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Popular</option>
                                    <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>

                            <!-- Items Per Page -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Per Page</label>
                                <select class="form-select" name="per_page" onchange="document.getElementById('filterForm').submit()">
                                    <option value="12">12 items</option>
                                    <option value="24">24 items</option>
                                    <option value="48">48 items</option>
                                </select>
                            </div>

                            <!-- Clear Filters -->
                            @if($search || $categoryId || $minPrice || $maxPrice)
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                                    Clear Filters
                                </a>
                            @endif
                        </form>

                        <!-- Price Range Info -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <strong>Available prices:</strong><br>
                                ${{ number_format($minPriceAvailable, 2) }} - ${{ number_format($maxPriceAvailable, 2) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-md-9">
                <!-- Active Filters Display -->
                @if($search || $categoryId || $minPrice || $maxPrice)
                    <div class="alert alert-info mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Active Filters:</strong>
                            @if($search)
                                <span class="badge bg-primary">Search: {{ $search }}</span>
                            @endif
                            @if($categoryId && $categories->find($categoryId))
                                <span class="badge bg-info">Category: {{ $categories->find($categoryId)->name }}</span>
                            @endif
                            @if($minPrice)
                                <span class="badge bg-success">Min: ${{ $minPrice }}</span>
                            @endif
                            @if($maxPrice)
                                <span class="badge bg-warning">Max: ${{ $maxPrice }}</span>
                            @endif
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-light">Clear all</a>
                    </div>
                @endif

                <!-- Results Count -->
                <p class="text-muted mb-3">
                    Showing {{ $products->count() }} of {{ $products->total() }} products
                </p>

                <!-- Products Grid -->
                @forelse ($products as $product)
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card h-100 shadow-sm hover-shadow transition">
                                <div class="row g-0">
                                    <!-- Product Image Placeholder -->
                                    <div class="col-md-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 200px; display: flex; align-items: center; justify-content: center;">
                                        <div class="text-white text-center">
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                            <p class="mt-2">No image</p>
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h5 class="card-title">
                                                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                                            {{ $product->name }}
                                                        </a>
                                                    </h5>

                                                    <!-- Category Badge -->
                                                    @if($product->category)
                                                        <span class="badge bg-light text-dark mb-2">
                                                            {{ $product->category->name }}
                                                        </span>
                                                    @endif

                                                    <p class="card-text text-muted mb-2">
                                                        {{ Str::limit($product->description, 150) }}
                                                    </p>

                                                    <!-- Stock Status -->
                                                    <div class="mb-2">
                                                        @if($product->stock > 0)
                                                            <span class="badge bg-success">In Stock ({{ $product->stock }})</span>
                                                        @else
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @endif
                                                    </div>

                                                    <!-- Creator Info -->
                                                    <small class="text-muted">
                                                        <i class="bi bi-person"></i> By {{ $product->user->name }}
                                                    </small>
                                                </div>

                                                <div class="col-md-4 text-end">
                                                    <!-- Price -->
                                                    <h4 class="text-primary mb-3">${{ number_format($product->price, 2) }}</h4>

                                                    <!-- Actions -->
                                                    <div class="btn-group-vertical w-100" role="group">
                                                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-eye"></i> View Details
                                                        </a>

                                                        @can('update', $product)
                                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning btn-sm">
                                                                <i class="bi bi-pencil"></i> Edit
                                                            </a>
                                                        @endcan

                                                        @can('delete', $product)
                                                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Are you sure?')">
                                                                    <i class="bi bi-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning">
                        <h5>No products found</h5>
                        <p>Try adjusting your filters or search terms.</p>
                    </div>
                @endforelse

                <!-- Pagination -->
                @if($products->hasPages())
                    <nav class="mt-4">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </nav>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
.hover-shadow {
    transition: box-shadow 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}
</style>
