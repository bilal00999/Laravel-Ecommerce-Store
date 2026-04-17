@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container" style="padding: 3rem 0;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem;">
        <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">
            <i class="bi bi-gear"></i> Settings
        </h1>
        <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Manage application settings</p>
    </div>

    <div class="row">
        <!-- Settings Sections -->
        <div class="col-lg-8">
            <!-- General Settings -->
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <h5 style="margin-bottom: 1.5rem; font-weight: 700; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem;">
                    <i class="bi bi-sliders"></i> General Settings
                </h5>
                <p style="color: #999; text-align: center; padding: 2rem;">
                    <i class="bi bi-info-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i><br>
                    Settings management is coming soon. This page will allow you to configure application-wide settings.
                </p>
            </div>

            <!-- Email Settings -->
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <h5 style="margin-bottom: 1.5rem; font-weight: 700; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem;">
                    <i class="bi bi-envelope"></i> Email Settings
                </h5>
                <p style="color: #999; text-align: center; padding: 2rem;">
                    <i class="bi bi-info-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i><br>
                    Email configuration settings will be available here.
                </p>
            </div>

            <!-- Payment Settings -->
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h5 style="margin-bottom: 1.5rem; font-weight: 700; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem;">
                    <i class="bi bi-credit-card"></i> Payment Settings
                </h5>
                <p style="color: #999; text-align: center; padding: 2rem;">
                    <i class="bi bi-info-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i><br>
                    Payment gateway configuration will be available here.
                </p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="col-lg-4">
            <div style="background: #f0f7ff; border: 2px solid #cfe2ff; padding: 1.5rem; border-radius: 10px; position: sticky; top: 20px;">
                <h6 style="font-weight: 700; color: #0c5460; margin-bottom: 1rem;">
                    <i class="bi bi-lightbulb"></i> About Settings
                </h6>
                <p style="color: #0c5460; font-size: 0.9rem; line-height: 1.6; margin: 0;">
                    This is the application settings page where administrators can configure:
                </p>
                <ul style="color: #0c5460; font-size: 0.9rem; margin: 1rem 0 0 0; padding-left: 1.5rem;">
                    <li>General application settings</li>
                    <li>Email notification preferences</li>
                    <li>Payment gateway configuration</li>
                    <li>User permissions</li>
                    <li>System maintenance options</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div style="margin-top: 2rem;">
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
    </div>
</div>
@endsection
