@extends('admin.layout')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <div class="card shadow-lg border-0">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem;">
            <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Product</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

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
                                value="{{ old('name', $product->name) }}" 
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
                            >{{ old('description', $product->description) }}</textarea>
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
                                            value="{{ old('price', $product->price) }}" 
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
                                        value="{{ old('stock', $product->stock) }}" 
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
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Product Info Box -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Product #{{ $product->id }}</h6>
                            <small>
                                <strong>Created:</strong> {{ $product->created_at->format('M d, Y') }}<br>
                                <strong>Updated:</strong> {{ $product->updated_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-8">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Update Product
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
