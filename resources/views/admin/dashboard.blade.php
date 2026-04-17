@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="admin-content">
    <!-- Statistics Row -->
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-people" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-box-seam" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number">{{ $stats['total_products'] }}</div>
                <div class="stat-label">Products</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-tag" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number">{{ $stats['total_categories'] }}</div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-envelope" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number">{{ $stats['pending_messages'] }}</div>
                <div class="stat-label">Pending Messages</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-3">
        <!-- Recent Messages -->
        <div class="col-lg-6">
            <div class="data-table">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-chat-dots"></i> Recent Messages
                    </h5>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_messages as $message)
                            <tr>
                                <td>
                                    <strong>{{ $message->user->name }}</strong><br>
                                    <small style="color: #999;">{{ $message->user->email }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.contact.show', $message) }}" style="color: #667eea; text-decoration: none;">
                                        {{ Str::limit($message->subject, 25) }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge-status badge-{{ $message->status }}">
                                        {{ ucfirst($message->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: #999;">No messages yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="padding: 1rem 2rem; text-align: center; border-top: 1px solid #f0f0f0;">
                    <a href="{{ route('admin.contact.replies') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                        View All Messages <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-lg-6">
            <div class="data-table">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-person-plus"></i> Recent Users
                    </h5>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td style="color: #667eea;">{{ $user->email }}</td>
                                <td>
                                    <span class="badge" style="background: {{ $user->is_admin ? '#4caf50' : '#2196f3' }}; color: white;">
                                        {{ $user->is_admin ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: #999;">No users yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="padding: 1rem 2rem; text-align: center; border-top: 1px solid #f0f0f0;">
                    <span style="color: #999;">Total: {{ $stats['total_users'] }} users</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div style="margin-top: 2rem;">
        <div class="data-table">
            <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                <h5 style="margin: 0; font-weight: 700;">
                    <i class="bi bi-box-seam"></i> Recent Products
                </h5>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Added By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_products as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                            </td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="badge" style="background: {{ $product->stock > 0 ? '#4caf50' : '#f44336' }}; color: white;">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>{{ $product->user->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #999;">No products yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding: 1rem 2rem; text-align: center; border-top: 1px solid #f0f0f0;">
                <a href="{{ route('products.index') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                    Manage Products <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
