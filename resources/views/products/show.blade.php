@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
    .product-detail-bg { background: #f5f5f5; padding: 3rem 0; }
    .product-hero { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 3rem; }
    .product-image { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 500px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
    .product-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
    .product-image:hover img { transform: scale(1.05); }
    .product-details { padding: 3rem; }
    .breadcrumb-custom { background: transparent; padding: 0 0 1rem 0; margin-bottom: 2rem; border-bottom: 2px solid #e0e0e0; }
    .breadcrumb-custom a { color: #667eea; text-decoration: none; }
    .breadcrumb-custom a:hover { text-decoration: underline; }
    .product-title { font-size: 2.5rem; font-weight: 700; color: #333; margin-bottom: 1rem; }
    .category-badge { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem 1rem; border-radius: 25px; font-size: 0.9rem; margin-bottom: 1.5rem; font-weight: 600; }
    .product-meta { display: flex; gap: 1rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 2px solid #f0f0f0; flex-wrap: wrap; }
    .meta-item { display: flex; align-items: center; gap: 0.5rem; color: #666; }
    .meta-item i { color: #667eea; font-size: 1.2rem; }
    .price-box { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 10px; margin: 2rem 0; }
    .price-label { font-size: 0.9rem; opacity: 0.9; }
    .price-value { font-size: 3rem; font-weight: 700; margin-bottom: 1rem; }
    .stock-badge { display: inline-block; padding: 0.5rem 1rem; border-radius: 25px; font-weight: 600; background: rgba(255,255,255,0.2); color: white; }
    .action-buttons { display: flex; gap: 1rem; margin: 2rem 0; flex-wrap: wrap; }
    .btn-add { padding: 0.75rem 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; }
    .btn-add:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3); }
    .btn-back { padding: 0.75rem 2rem; background: white; color: #667eea; border: 2px solid #667eea; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; }
    .btn-back:hover { background: #667eea; color: white; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin: 2rem 0; padding: 2rem; background: #f9f9f9; border-radius: 10px; }
    .info-item { display: flex; flex-direction: column; }
    .info-label { font-weight: 700; color: #667eea; margin-bottom: 0.5rem; font-size: 0.9rem; text-transform: uppercase; }
    .info-value { color: #333; font-size: 1.1rem; }
    .related-section { margin-top: 4rem; padding-top: 3rem; border-top: 2px solid #e0e0e0; }
    .related-title { font-size: 2rem; font-weight: 700; color: #333; margin-bottom: 2rem; }
    .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem; }
    .related-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; }
    .related-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2); }
    .related-image { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 200px; display: flex; align-items: center; justify-content: center; color: white; }
    .related-body { padding: 1.5rem; }
    .related-price { font-size: 1.5rem; font-weight: 700; color: #667eea; margin-top: 1rem; }
    .admin-box { background: #fff3cd; border-left: 4px solid #ff9800; padding: 2rem; border-radius: 8px; margin-top: 2rem; }
    .admin-box h5 { color: #e65100; margin-bottom: 1rem; }
    .admin-box p { margin: 0.5rem 0; color: #666; }
    @media (max-width: 768px) {
        .product-image { height: 300px; }
        .product-title { font-size: 1.8rem; }
        .product-details { padding: 1.5rem; }
        .price-value { font-size: 2rem; }
        .action-buttons { flex-direction: column; }
        .btn-add, .btn-back { width: 100%; justify-content: center; }
        .related-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
    }
</style>

<div class="product-detail-bg">
    <div class="container">
        <!-- Breadcrumb -->
        <nav>
            <ol class="breadcrumb breadcrumb-custom">
                <li><a href="{{ route('products.index') }}">Products</a></li>
                @if($product->category)
                    <li><a href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a></li>
                @endif
                <li>{{ $product->name }}</li>
            </ol>
        </nav>

        <!-- Product Hero -->
        <div class="product-hero">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; height: auto;">
                <!-- Image -->
                <div class="product-image">
                    @if($product->image_path && file_exists(public_path($product->image_path)))
                        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}">
                    @else
                        <div style="text-align: center; color: white;">
                            <i class="bi bi-image" style="font-size: 5rem; opacity: 0.3;"></i>
                            <p style="opacity: 0.7; margin-top: 1rem;">No image available</p>
                        </div>
                    @endif
                </div>

                <!-- Details -->
                <div class="product-details">
                    @if($product->category)
                        <div class="category-badge">
                            <i class="bi bi-tag"></i> {{ $product->category->name }}
                        </div>
                    @endif

                    <h1 class="product-title">{{ $product->name }}</h1>

                    <div class="product-meta">
                        <div class="meta-item">
                            <i class="bi bi-person-circle"></i>
                            <span>By <strong>{{ $product->user->name }}</strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-clock-history"></i>
                            <span>{{ $product->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="price-box">
                        <div class="price-label">Price</div>
                        <div class="price-value">${{ number_format($product->price, 2) }}</div>
                        @if($product->stock > 0)
                            <span class="stock-badge"><i class="bi bi-check-circle"></i> In Stock - {{ $product->stock }} available</span>
                        @else
                            <span class="stock-badge"><i class="bi bi-x-circle"></i> Out of Stock</span>
                        @endif
                    </div>

                    <div class="action-buttons">
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" style="width: 100%; max-width: 300px;">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-add" style="width: 100%; justify-content: center;">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('products.index') }}" class="btn-back">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                    @can('update', $product)
                        <div style="margin-top: 1rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="{{ route('products.edit-image', $product) }}" style="padding: 0.5rem 1rem; background: #2196F3; color: white; border-radius: 6px; text-decoration: none; font-weight: 600;">
                                <i class="bi bi-image"></i> Upload Image
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Description & Info -->
        <div style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); margin-bottom: 3rem;">
            <h5 style="font-size: 1.3rem; font-weight: 700; color: #333; margin-bottom: 1rem;">Description</h5>
            <p style="font-size: 1.1rem; line-height: 1.8; color: #666;">{{ $product->description }}</p>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Product ID</div>
                    <div class="info-value">#{{ $product->id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">SKU</div>
                    <div class="info-value">{{ $product->slug }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Stock</div>
                    <div class="info-value">{{ $product->stock }} units</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value">{{ $product->updated_at->format('M d, Y') }}</div>
                </div>
            </div>

            @can('admin')
                <div class="admin-box">
                    <h5><i class="bi bi-shield-lock"></i> Admin Info</h5>
                    <p><strong>Creator ID:</strong> {{ $product->user_id }}</p>
                    <p><strong>Category ID:</strong> {{ $product->category_id ?? 'None' }}</p>
                    <p><strong>Created:</strong> {{ $product->created_at->format('M d, Y H:i') }}</p>
                </div>
            @endcan

            @can('delete', $product)
                <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                    <a href="{{ route('products.edit', $product) }}" style="padding: 0.5rem 1rem; background: #ff9800; color: white; border-radius: 6px; text-decoration: none; font-weight: 600;">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 0.5rem 1rem; background: #f44336; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;" onclick="return confirm('Delete this product?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            @endcan
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="related-section">
                <h3 class="related-title">Related Products</h3>
                <div class="related-grid">
                    @foreach($relatedProducts as $related)
                        <div class="related-card">
                            <div class="related-image">
                                @if($related->image_path && file_exists(public_path($related->image_path)))
                                    <img src="{{ asset($related->image_path) }}" alt="{{ $related->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="bi bi-image" style="font-size: 3rem; opacity: 0.3;"></i>
                                @endif
                            </div>
                            <div class="related-body">
                                <div style="font-weight: 700; color: #333; margin-bottom: 0.5rem;">
                                    <a href="{{ route('products.show', $related) }}" style="color: inherit; text-decoration: none;">
                                        {{ Str::limit($related->name, 40) }}
                                    </a>
                                </div>
                                <p style="color: #999; font-size: 0.9rem; margin: 0.5rem 0;">{{ Str::limit($related->description, 60) }}</p>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                    <div class="related-price">${{ number_format($related->price, 2) }}</div>
                                    <span style="background: #667eea; color: white; padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.85rem;">{{ $related->stock }} stock</span>
                                </div>
                                <a href="{{ route('products.show', $related) }}" style="display: block; margin-top: 1rem; text-align: center; color: white; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 0.7rem; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
