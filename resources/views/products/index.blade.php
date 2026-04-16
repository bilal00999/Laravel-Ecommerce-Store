<!-- Products Index - Example: Using @can('create') -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Products</h1>

            {{-- Show "Create Product" button only to admins using Gate --}}
            @can('create', App\Models\Product::class)
                <div class="mb-3">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        Create Product
                    </a>
                </div>
            @endcan

            {{-- Alternative: Using Gate directly --}}
            @if (Gate::allows('admin'))
                <div class="alert alert-info">
                    You are viewing as an admin
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <p class="card-text"><strong>${{ $product->price }}</strong></p>

                        <div class="btn-group">
                            {{-- Show button to all (view product) --}}
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                View
                            </a>

                            {{-- Show edit button only if user can update --}}
                            @can('update', $product)
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            @endcan

                            {{-- Show delete button only if user can delete --}}
                            @can('delete', $product)
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <p>No products available</p>
            </div>
        @endforelse
    </div>
</div>
