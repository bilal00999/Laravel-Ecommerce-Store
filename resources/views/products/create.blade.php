@extends('admin.layout')

@section('title', 'Create Product')
@section('page-title', 'Create Product')

@section('content')
<div class="container-fluid">
    <div class="card shadow-lg border-0">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem;">
            <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Product</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Product Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required
                                placeholder="Enter product name"
                            >
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea 
                                class="form-control @error('description') is-invalid @enderror"
                                id="description" 
                                name="description" 
                                rows="5" 
                                required
                                placeholder="Enter product description"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price and Stock Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="price" class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">$</span>
                                        <input 
                                            type="number" 
                                            step="0.01" 
                                            class="form-control @error('price') is-invalid @enderror"
                                            id="price" 
                                            name="price" 
                                            value="{{ old('price') }}" 
                                            required
                                            placeholder="0.00"
                                            min="0"
                                        >
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="stock" class="form-label fw-semibold">Stock <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control form-control-lg @error('stock') is-invalid @enderror"
                                        id="stock" 
                                        name="stock" 
                                        value="{{ old('stock') }}" 
                                        required
                                        placeholder="0"
                                        min="0"
                                    >
                                    @error('stock')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-semibold">Category</label>
                            <select 
                                class="form-select form-select-lg @error('category_id') is-invalid @enderror"
                                id="category_id" 
                                name="category_id"
                            >
                                <option value="">-- Select a category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Required Fields</h6>
                            <small>Fill in all required fields marked with <span class="text-danger">*</span></small>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-8">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle"></i> Create Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
                                        id="stock" 
                                        name="stock" 
                                        value="{{ old('stock') }}" 
                                        required
                                        placeholder="0"
                                        min="0"
                                    >
                                    @error('stock')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-block">
                            <button type="submit" class="btn btn-primary">Create Product</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
