@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-list-check"></i> My Orders</h2>
        </div>
    </div>

    @if ($orders->count() > 0)
        <div class="row">
            @foreach ($orders as $order)
                @php
                    $shippingAddress = json_decode($order->shipping_address, true);
                @endphp
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <p class="mb-1"><strong>Order #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</strong></p>
                                    <p class="mb-0 text-muted">{{ $order->created_at->format('F d, Y') }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1"><strong>Items:</strong> {{ $order->items->count() }}</p>
                                    <p class="mb-0 text-muted">
                                        @foreach ($order->items->take(2) as $item)
                                            <small>• {{ $item->product->name }}</small><br>
                                        @endforeach
                                        @if ($order->items->count() > 2)
                                            <small class="text-muted">+{{ $order->items->count() - 2 }} more</small>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1"><strong>Total:</strong> <span style="color: #28a745; font-weight: bold;">${{ number_format($order->total, 2) }}</span></p>
                                    <p class="mb-0">
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $color = $statusColors[$order->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($order->status) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <a href="{{ route('checkout.order-details', $order) }}" class="btn btn-sm text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                        <i class="bi bi-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-md-12">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @else
        <!-- Empty Orders -->
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #667eea; opacity: 0.5;"></i>
                        <h3 class="mt-4">No Orders Yet</h3>
                        <p class="text-muted">You haven't placed any orders yet. Start shopping now!</p>
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
