@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-cart"></i> Shopping Cart</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Continue Shopping
            </a>
        </div>
    </div>

    @if ($items->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($item->product->image_path && file_exists(public_path($item->product->image_path)))
                                                    <img src="{{ asset($item->product->image_path) }}" alt="{{ $item->product->name }}" 
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                                @else
                                                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                                                border-radius: 5px; display: flex; align-items: center; justify-content: center; color: white;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none">
                                                        <strong>{{ $item->product->name }}</strong>
                                                    </a><br>
                                                    <small class="text-muted">{{ $item->product->category->name ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->product->price, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <div class="input-group" style="width: 120px;">
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="999" class="form-control" id="qty-{{ $item->id }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Update quantity">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($item->product->price * $item->quantity, 2) }}</strong>
                                        </td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove from cart">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Clear Cart Button -->
                <div class="mt-3">
                    <form action="{{ route('cart.clear') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to clear your cart?')">
                            <i class="bi bi-x-circle"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-md-4">
                <div class="card sticky-top">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items ({{ $items->count() }}):</span>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="badge bg-success">Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <strong>${{ number_format($total * 0.08, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span style="font-size: 1.2rem;">Total:</span>
                            <strong style="font-size: 1.2rem; color: #667eea;">${{ number_format($total + ($total * 0.08), 2) }}</strong>
                        </div>

                        <button class="btn btn-primary w-100 btn-lg" disabled>
                            <i class="bi bi-credit-card"></i> Checkout (Coming Soon)
                        </button>

                        <small class="text-muted d-block mt-3">
                            <i class="bi bi-info-circle"></i> Checkout feature coming soon. For now, continue shopping!
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body py-5">
                        <i class="bi bi-cart-x" style="font-size: 4rem; color: #667eea; opacity: 0.5;"></i>
                        <h3 class="mt-4">Your Cart is Empty</h3>
                        <p class="text-muted">You haven't added any products yet. Start shopping now!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-shop"></i> Browse Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
