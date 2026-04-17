@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<style>
    .contact-container { max-width: 700px; margin: 0 auto; }
    .contact-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 3rem 2rem; border-radius: 10px; margin-bottom: 3rem; text-align: center; }
    .contact-header h1 { margin: 0; font-size: 2rem; font-weight: 700; }
    .contact-header p { margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 1.1rem; }
    .form-card { background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1rem; transition: all 0.3s ease; }
    .form-control:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
    textarea.form-control { min-height: 150px; resize: vertical; }
    .form-help { font-size: 0.85rem; color: #999; margin-top: 0.3rem; }
    .btn-submit { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.85rem 2rem; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; width: 100%; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3); }
    .btn-submit:active { transform: translateY(0); }
    .error-message { background: #ffebee; border-left: 4px solid #f44336; color: #c62828; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem; }
    .error-list { list-style: none; padding: 0; margin: 0; }
    .error-list li { padding: 0.3rem 0; }
    .error-list li:before { content: "• "; margin-right: 0.5rem; }
    .success-message { background: #e8f5e9; border-left: 4px solid #4caf50; color: #2e7d32; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .field-required { color: #f44336; margin-left: 0.2rem; }
    .back-link { display: inline-block; margin-top: 2rem; color: #667eea; text-decoration: none; font-weight: 600; }
    .back-link:hover { text-decoration: underline; }
    @media (max-width: 768px) {
        .contact-header { padding: 2rem 1rem; }
        .form-card { padding: 1.5rem; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>

<div class="contact-container">
    <div class="contact-header">
        <h1><i class="bi bi-envelope"></i> Contact Us</h1>
        <p>Have a question or suggestion? We'd love to hear from you!</p>
    </div>

    <div class="form-card">
        <!-- Success Message -->
        @if (session('success'))
            <div class="success-message">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="error-message">
                <strong><i class="bi bi-exclamation-triangle"></i> Please fix the following errors:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Contact Form -->
        <form action="{{ route('contact.store') }}" method="POST">
            @csrf

            <!-- Subject Field -->
            <div class="form-group">
                <label for="subject" class="form-label">
                    Subject
                    <span class="field-required">*</span>
                </label>
                <input 
                    type="text" 
                    id="subject" 
                    name="subject" 
                    class="form-control @error('subject') is-invalid @enderror"
                    placeholder="What is this about?"
                    value="{{ old('subject') }}"
                    required
                >
                <div class="form-help">Minimum 5 characters</div>
                @error('subject')
                    <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email" class="form-label">
                    Email Address
                    <span class="field-required">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="your.email@example.com"
                    value="{{ old('email', auth()->user()->email) }}"
                    required
                >
                @error('email')
                    <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone Field -->
            <div class="form-group">
                <label for="phone" class="form-label">Phone Number (Optional)</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    class="form-control @error('phone') is-invalid @enderror"
                    placeholder="+1 (555) 123-4567"
                    value="{{ old('phone') }}"
                >
                <div class="form-help">Optional - helps us contact you faster</div>
                @error('phone')
                    <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Message Field -->
            <div class="form-group">
                <label for="message" class="form-label">
                    Message
                    <span class="field-required">*</span>
                </label>
                <textarea 
                    id="message" 
                    name="message" 
                    class="form-control @error('message') is-invalid @enderror"
                    placeholder="Please tell us your message..."
                    required
                >{{ old('message') }}</textarea>
                <div class="form-help">Minimum 20 characters, maximum 5000 characters</div>
                @error('message')
                    <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">
                <i class="bi bi-send"></i> Send Message
            </button>
        </form>

        <!-- Back Link -->
        <a href="{{ route('products.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
    </div>

    <!-- Contact Information -->
    <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 20px rgba(0,0,0,0.1); margin-top: 2rem;">
        <h3 style="color: #333; margin-top: 0;">Other Ways to Reach Us</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
            <div>
                <i class="bi bi-envelope" style="color: #667eea; font-size: 1.5rem;"></i>
                <p style="font-weight: 600; margin: 0.5rem 0 0 0;">Email</p>
                <a href="mailto:support@ecommerce.com" style="color: #667eea; text-decoration: none;">support@ecommerce.com</a>
            </div>
            <div>
                <i class="bi bi-telephone" style="color: #667eea; font-size: 1.5rem;"></i>
                <p style="font-weight: 600; margin: 0.5rem 0 0 0;">Phone</p>
                <a href="tel:+1234567890" style="color: #667eea; text-decoration: none;">+1 (234) 567-890</a>
            </div>
            <div>
                <i class="bi bi-geo-alt" style="color: #667eea; font-size: 1.5rem;"></i>
                <p style="font-weight: 600; margin: 0.5rem 0 0 0;">Address</p>
                <p style="margin: 0; color: #666;">123 Store Street<br>New York, NY 10001</p>
            </div>
        </div>
    </div>
</div>
@endsection
