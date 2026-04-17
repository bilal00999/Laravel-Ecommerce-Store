@extends('layouts.app')

@section('title', 'Admin Settings')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem;">
    <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">
        <i class="bi bi-gear"></i> Admin Settings
    </h1>
    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Configure your store settings</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h5 style="font-weight: 700; margin-bottom: 1rem;">Store Information</h5>
            <p style="color: #666; margin: 0.5rem 0;">
                <strong>Store Name:</strong> E-Commerce Store
            </p>
            <p style="color: #666; margin: 0.5rem 0;">
                <strong>Total Products:</strong> {{ \App\Models\Product::count() }}
            </p>
            <p style="color: #666; margin: 0.5rem 0;">
                <strong>Total Users:</strong> {{ \App\Models\User::count() }}
            </p>
            <p style="color: #666; margin: 0.5rem 0;">
                <strong>Total Categories:</strong> {{ \App\Models\Category::count() }}
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h5 style="font-weight: 700; margin-bottom: 1rem;">Quick Stats</h5>
            <p style="color: #666; margin: 0.5rem 0;">
                <strong>Registered Users:</strong> {{ \App\Models\User::count() }}
            </p>
            <p style="color: #666; margin: 0.5rem 0;">
                <strong>Admin Users:</strong> {{ \App\Models\User::where('is_admin', true)->count() }}
            </p>
            <p style="color: #666; margin: 0.5rem 0;">
                <strong>Last Update:</strong> {{ now()->format('M d, Y H:i') }}
            </p>
        </div>
    </div>
</div>

<div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
    <h5 style="font-weight: 700; margin-bottom: 1rem;">System Information</h5>
    <p style="color: #666; margin: 0.5rem 0;">
        <strong>Application:</strong> Laravel Ecommerce Store
    </p>
    <p style="color: #666; margin: 0.5rem 0;">
        <strong>Framework:</strong> Laravel 11
    </p>
    <p style="color: #666; margin: 0.5rem 0;">
        <strong>Version:</strong> 1.0.0
    </p>
</div>

<div style="margin-top: 2rem;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>
@endsection
