@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .admin-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; }
    .admin-header h1 { margin: 0; font-size: 2rem; font-weight: 700; }
    .admin-header p { margin: 0.5rem 0 0 0; opacity: 0.9; }
    .stat-card { background: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
    .stat-number { font-size: 2.5rem; font-weight: 700; color: #667eea; }
    .stat-label { color: #666; margin-top: 0.5rem; font-size: 0.9rem; }
    .admin-section { background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem; }
    .section-title { font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
    .btn-action { padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; }
    .btn-add { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .btn-add:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); }
    .btn-edit { background: #ff9800; color: white; }
    .btn-edit:hover { background: #e68900; }
    .btn-delete { background: #f44336; color: white; }
    .btn-delete:hover { background: #da190b; }
    .products-table { width: 100%; border-collapse: collapse; }
    .products-table th { background: #f5f5f5; padding: 1rem; text-align: left; font-weight: 700; border-bottom: 2px solid #e0e0e0; }
    .products-table td { padding: 1rem; border-bottom: 1px solid #e0e0e0; }
    .products-table tr:hover { background: #f9f9f9; }
    .product-image { width: 50px; height: 50px; border-radius: 5px; object-fit: cover; }
    .actions { display: flex; gap: 0.5rem; }
</style>

<div class="admin-header">
    <h1><i class="bi bi-shield-lock"></i> Admin Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}! Manage your e-commerce store.</p>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $totalProducts ?? 0 }}</div>
            <div class="stat-label">Total Products</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $totalCategories ?? 0 }}</div>
            <div class="stat-label">Categories</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $totalOrders ?? 0 }}</div>
            <div class="stat-label">Orders</div>
        </div>
    </div>
</div>

<!-- Product Management Section -->
<div class="admin-section">
    <div class="section-title">
        <i class="bi bi-box-seam"></i> Product Management
    </div>
    
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('products.create') }}" class="btn-action btn-add">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
    </div>

    <!-- Products List -->
    <div style="overflow-x: auto;">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Creator</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products ?? [] as $product)
                    <tr>
                        <td>
                            @if($product->image_path && file_exists(public_path($product->image_path)))
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            <span style="background: {{ $product->stock > 0 ? '#4caf50' : '#f44336' }}; color: white; padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.85rem;">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>{{ $product->user->name ?? 'System' }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('products.edit', $product) }}" class="btn-action btn-edit" title="Edit Product">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Delete this product?')" title="Delete Product">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                            <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                            <p style="margin-top: 0.5rem;">No products found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-6">
        <div class="admin-section">
            <div class="section-title">
                <i class="bi bi-gear"></i> Quick Actions
            </div>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 1rem;">
                    <a href="{{ route('products.create') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                        <i class="bi bi-plus"></i> Add New Product
                    </a>
                </li>
                <li style="margin-bottom: 1rem;">
                    <a href="{{ route('products.index') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                        <i class="bi bi-eye"></i> View All Products
                    </a>
                </li>
                <li style="margin-bottom: 1rem;">
                    <a href="{{ route('admin.users') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                        <i class="bi bi-people"></i> Manage Users
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-section">
            <div class="section-title">
                <i class="bi bi-info-circle"></i> Information
            </div>
            <p style="color: #666; line-height: 1.6; margin: 0;">
                <strong>Welcome to your Admin Panel!</strong><br>
                From here you can:
                <ul style="margin-top: 0.5rem;">
                    <li>Create, edit, and delete products</li>
                    <li>Manage product categories and inventory</li>
                    <li>Upload product images</li>
                    <li>View store statistics</li>
                </ul>
            </p>
        </div>
    </div>
</div>

<script>
    // Auto-load data if this was a fresh page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Admin Dashboard loaded successfully');
    });
</script>
@endsection
