@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-credit-card"></i> Checkout</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Shipping & Billing Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                       id="full_name" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required>
                                @error('full_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000" required>
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="street_address" class="form-label">Street Address *</label>
                            <input type="text" class="form-control @error('street_address') is-invalid @enderror" 
                                   id="street_address" name="street_address" value="{{ old('street_address') }}" 
                                   placeholder="123 Main Street" required>
                            @error('street_address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State/Province *</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state') }}" required>
                                @error('state')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Postal Code *</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                @error('postal_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country *</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country') }}" required>
                                @error('country')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" 
                                       type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                <label class="form-check-label" for="credit_card">
                                    <i class="bi bi-credit-card"></i> Credit Card
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" 
                                       type="radio" name="payment_method" id="paypal" value="paypal">
                                <label class="form-check-label" for="paypal">
                                    <i class="bi bi-paypal"></i> PayPal
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" 
                                       type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                <label class="form-check-label" for="bank_transfer">
                                    <i class="bi bi-bank"></i> Bank Transfer
                                </label>
                            </div>
                            @error('payment_method')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="card mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Order Notes (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Add any special instructions for your order...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" id="terms" name="terms" value="1" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" target="_blank">Terms & Conditions</a> 
                                and <a href="#" target="_blank">Privacy Policy</a> *
                            </label>
                            @error('terms')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Back to Cart
                    </a>
                    <button type="submit" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-check-circle"></i> Place Order
                    </button>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card sticky-top">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0"><i class="bi bi-list-check"></i> Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="mb-2">Items in Order ({{ $items->count() }})</h6>
                            <div style="max-height: 300px; overflow-y: auto;">
                                @foreach ($items as $item)
                                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                        <div>
                                            <small class="d-block">{{ $item->product->name }}</small>
                                            <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                        </div>
                                        <small class="fw-bold">${{ number_format($item->product->price * $item->quantity, 2) }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8%):</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping:</span>
                            <span class="badge bg-success">Free</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between" style="font-size: 1.2rem;">
                            <span class="fw-bold">Total:</span>
                            <span class="text-success fw-bold">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .sticky-top {
        top: 20px;
    }
</style>
@endsection
