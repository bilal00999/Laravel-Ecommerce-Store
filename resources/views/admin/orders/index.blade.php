@extends('admin.layout')

@section('title', 'Orders')
@section('page-title', 'Order Management')

@section('content')
<div class="admin-content">
    <!-- Filter Options -->
    <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 2rem; padding: 1.5rem;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <input type="text" placeholder="Search orders..." style="flex: 1; min-width: 200px; padding: 0.6rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
            <select style="padding: 0.6rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
                <option>All Statuses</option>
                <option>Pending</option>
                <option>Processing</option>
                <option>Shipped</option>
                <option>Delivered</option>
                <option>Cancelled</option>
            </select>
            <button style="padding: 0.6rem 1.5rem; background: #667eea; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 4rem; color: #999;">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p style="margin-top: 1rem; font-size: 1.1rem;">No orders yet</p>
                        <small style="color: #bbb;">Orders will appear here once customers make purchases</small>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Coming Soon Section -->
    <div style="background: linear-gradient(135deg, #e0f2ff 0%, #f0e7ff 100%); border-radius: 10px; padding: 2rem; margin-top: 2rem; text-align: center;">
        <i class="bi bi-info-circle" style="font-size: 2rem; color: #667eea;"></i>
        <h4 style="margin-top: 1rem; color: #333;">Order Management Coming Soon</h4>
        <p style="color: #666; margin-top: 0.5rem;">
            Order management features will be available once the order system is fully integrated into your e-commerce platform.
        </p>
        <div style="margin-top: 1.5rem;">
            <button style="padding: 0.6rem 1.5rem; background: #667eea; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                <i class="bi bi-plus"></i> Create Order
            </button>
        </div>
    </div>
</div>

<style>
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    .table th {
        background: #f8f9fa;
        border: none;
        padding: 1rem;
        font-weight: 700;
        color: #333;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    .table td {
        padding: 1rem;
        border-color: #f0f0f0;
        color: #666;
    }
    .table tr:hover {
        background: #fafbfc;
    }
</style>
@endsection
