@extends('admin.layout')

@section('title', 'Admin Settings')
@section('page-title', 'Settings')

@section('content')
<div class="admin-content">
    <div class="row">
        <div class="col-md-6">
            <div class="data-table" style="margin-bottom: 2rem;">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-info-circle"></i> Store Information
                    </h5>
                </div>
                <div style="padding: 2rem;">
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">STORE NAME</label>
                        <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">E-Commerce Store</div>
                    </div>
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">TOTAL PRODUCTS</label>
                        <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">{{ \App\Models\Product::count() }}</div>
                    </div>
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">TOTAL USERS</label>
                        <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">{{ \App\Models\User::count() }}</div>
                    </div>
                    <div style="margin-bottom: 0;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">TOTAL CATEGORIES</label>
                        <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">{{ \App\Models\Category::count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="data-table" style="margin-bottom: 2rem;">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-speedometer2"></i> Quick Stats
                    </h5>
                </div>
                <div style="padding: 2rem;">
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">REGISTERED USERS</label>
                        <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">{{ \App\Models\User::count() }}</div>
                    </div>
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">ADMIN USERS</label>
                        <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">{{ \App\Models\User::where('is_admin', true)->count() }}</div>
                    </div>
                    <div style="margin-bottom: 0;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">LAST UPDATE</label>
                        <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">{{ now()->format('M d, Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="data-table" style="margin-bottom: 2rem;">
        <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
            <h5 style="margin: 0; font-weight: 700;">
                <i class="bi bi-info-circle"></i> System Information
            </h5>
        </div>
        <div style="padding: 2rem;">
            <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                <label style="color: #999; font-size: 0.85rem; font-weight: 700;">APPLICATION</label>
                <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">Laravel E-Commerce Store</div>
            </div>
            <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                <label style="color: #999; font-size: 0.85rem; font-weight: 700;">FRAMEWORK</label>
                <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">Laravel 11</div>
            </div>
            <div style="margin-bottom: 0;">
                <label style="color: #999; font-size: 0.85rem; font-weight: 700;">VERSION</label>
                <div style="margin-top: 0.5rem; color: #333; font-weight: 600;">1.0.0</div>
            </div>
        </div>
    </div>
</div>
@endsection
