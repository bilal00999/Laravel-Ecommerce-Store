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
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 2px solid transparent;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.25);
            border-color: #667eea;
        }

        .product-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 260px;
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
            font-size: 4.5rem;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s cubic-bezier(0.23, 1, 0.320, 1);
            display: block;
        }

        .product-card:hover .product-image img {
            transform: scale(1.12) rotate(2deg);
        }

        .product-info {
            padding: 1.8rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background: linear-gradient(to bottom, #ffffff 0%, #fafafa 100%);
        }

        .product-name {
            font-weight: 700;
            color: #222;
            margin-bottom: 0.8rem;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 1.1rem;
            line-height: 1.4;
            min-height: 2.8rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-name:hover {
            color: #667eea;
        }

        .product-category {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.45rem 1rem;
            border-radius: 25px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            font-weight: 600;
            width: fit-content;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.25);
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            flex-grow: 1;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.2rem;
            border-top: 2px solid #efefef;
            gap: 0.75rem;
        }

        .product-price {
            font-size: 1.7rem;
            font-weight: 700;
            color: #667eea;
            white-space: nowrap;
        }

        .stock-badge {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .stock-badge.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .stock-badge.bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        }

        .btn-add-to-cart {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.65rem 1.3rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
            cursor: pointer;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            white-space: nowrap;
        }

        .btn-add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-add-to-cart:active {
            transform: translateY(0);
        }

        /* Grid Layout */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 2.5rem;
            margin-top: 1rem;
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
            transition: all 0.3s ease;
            border-radius: 6px;
            margin: 0 4px;
        }

        .page-link:hover {
            background-color: #667eea;
            border-color: #667eea;
        }

        .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }

        .product-card:hover .product-edit-btn {
            opacity: 1 !important;
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
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 1.5rem;
            }

            .product-image {
                height: 200px !important;
            }

            .product-info {
                padding: 1.2rem;
            }

            .product-name {
                font-size: 1rem;
            }

            .product-price {
                font-size: 1.4rem;
            }

            /* Collapse filters on mobile */
            > div:nth-child(1) {
                width: 100% !important;
                margin-bottom: 2rem;
            }

            /* Full width products on mobile */
            > div:nth-child(2) {
                flex: 1;
                min-width: 100% !important;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .product-image {
                height: 160px !important;
            }

            .product-info {
                padding: 1rem;
            }

            .product-name {
                font-size: 0.95rem;
            }

            .btn-add-to-cart {
                padding: 0.4rem 0.7rem;
                font-size: 0.85rem;
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
                            <a href="{{ route('contact.history') }}">
                                <i class="bi bi-chat-dots"></i> Messages & Replies
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

    <div class="container-lg py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <div>
                <h1 style="font-size: 2.5rem; font-weight: 700; color: #333; margin: 0;">Products</h1>
                <p style="color: #666; margin: 0.5rem 0 0 0; font-size: 1.1rem;">Browse our collection</p>
            </div>
            @can('create', App\Models\Product::class)
                <a href="{{ route('products.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.8rem 1.8rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
                    <i class="bi bi-plus-circle"></i> Add Product
                </a>
            @endcan
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <form action="{{ route('products.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <input type="text" name="search" class="form-control search-input" placeholder="🔍 Search for products..." value="{{ $search }}" style="border: 2px solid #667eea; border-radius: 25px; padding: 0.75rem 1.5rem; font-size: 1rem;">
                </div>
                <div style="min-width: 200px;">
                    <select name="category" class="form-select" style="border: 2px solid #667eea; border-radius: 8px; padding: 0.75rem;">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 8px; padding: 0.75rem 1.5rem; font-weight: 600; white-space: nowrap;">
                    <i class="bi bi-search"></i> Search
                </button>
            </form>
        </div>

        <!-- Main Content Row -->
        <div style="display: flex; gap: 2rem; margin-top: 2rem;">
            <!-- Sidebar Filters (Collapsible on Mobile) -->
            <div style="width: 280px; flex-shrink: 0;">
                <div style="position: sticky; top: 20px;">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="filter-card">
                            <h6 class="filter-title"><i class="bi bi-cash-coin"></i> Price Range</h6>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                                <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ $minPrice }}" step="0.01" style="border-radius: 6px;">
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ $maxPrice }}" step="0.01" style="border-radius: 6px;">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 6px; padding: 0.6rem;">Apply</button>
                        </div>

                        <div class="filter-card">
                            <h6 class="filter-title"><i class="bi bi-sort-down"></i> Sort By</h6>
                            <select name="sort" class="form-select" onchange="this.form.submit()" style="border-radius: 6px; border: 1px solid #ddd;">
                                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Latest Products</option>
                                <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>

                        <div class="filter-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px;">
                            <p style="margin: 0; font-size: 0.95rem;"><i class="bi bi-tag"></i> <strong>${{ number_format($minPriceAvailable, 2) }}</strong> to <strong>${{ number_format($maxPriceAvailable, 2) }}</strong></p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Section (Main) -->
            <div style="flex: 1; min-width: 0;">
                <!-- Results Info -->
                <p style="color: #666; margin-bottom: 1.5rem; font-size: 1rem;"><strong>Showing {{ $products->count() }} of {{ $products->total() }} products</strong></p>

                <!-- Products Grid -->
                <div class="products-grid">
                    @forelse ($products as $product)
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->image_path && !empty(trim($product->image_path)))
                                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="bi bi-image"></i>
                                @endif
                                @auth
                                    @if(Auth::user()->is_admin || Auth::user()->id === $product->user_id)
                                        <div style="position: absolute; top: 10px; right: 10px; opacity: 0; transition: opacity 0.3s ease;" class="product-edit-btn">
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
                                    <span class="badge bg-success stock-badge"><i class="bi bi-check-circle"></i> In Stock • {{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger stock-badge"><i class="bi bi-x-circle"></i> Out of Stock</span>
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

                <!-- Pagination -->
                <nav aria-label="Page navigation" style="margin-top: 2rem;">
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
