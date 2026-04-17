@extends('admin.layout')

@section('content')
<div class="container my-5" style="max-width: 900px;">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Order #{{ $order->id }}</h1>
            <p class="text-muted">Order Date: {{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column: Order Details -->
        <div class="col-lg-7">
            <!-- Customer Info Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Customer Name</label>
                            <p class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <p class="fw-semibold">{{ $order->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Order Items</h6>
                </div>
                <div class="card-body">
                    @if ($order->items->isEmpty())
                        <p class="text-muted">No items in this order.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'Product #' . $item->product_id }}</td>
                                            <td class="text-end">{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end fw-semibold">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Address Card -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Shipping Address</h6>
                </div>
                <div class="card-body">
                    @php
                        $address = $order->shipping_address;
                        if (is_string($address)) {
                            $address = json_decode($address, true);
                        }
                    @endphp
                    @if ($address)
                        <p class="mb-1">
                            {{ $address['street'] ?? '' }}<br>
                            {{ $address['city'] ?? '' }}, {{ $address['state'] ?? '' }} {{ $address['zip'] ?? '' }}<br>
                            {{ $address['country'] ?? '' }}
                        </p>
                    @else
                        <p class="text-muted">No shipping address provided.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Order Summary & Status -->
        <div class="col-lg-5">
            <!-- Order Summary Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Order Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted">Total Amount</label>
                        </div>
                        <div class="col-6 text-end">
                            <h5 class="mb-0 text-primary">${{ number_format($order->total, 2) }}</h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted">Payment Method</label>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-0 fw-semibold">{{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label text-muted">Order Status</label>
                        </div>
                        <div class="col-6 text-end">
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                                $color = $statusColors[$order->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} text-white">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Status Card -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Select Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
