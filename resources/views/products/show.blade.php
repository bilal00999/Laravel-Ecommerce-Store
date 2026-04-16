<!-- Product Detail Page -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h2>{{ $product->name }}</h2>

            <div class="mt-3">
                <p><strong>Created by:</strong> {{ $product->user->name }}</p>
                <p><strong>Price:</strong> ${{ $product->price }}</p>
                <p><strong>Stock:</strong> {{ $product->stock }}</p>
            </div>

            <div class="mt-4">
                <h4>Description</h4>
                <p>{{ $product->description }}</p>
            </div>

            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>

                {{-- Show edit button only if user can update --}}
                @can('update', $product)
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                        Edit
                    </a>
                @endcan

                {{-- Show delete button only if user can delete --}}
                @can('delete', $product)
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        <div class="col-md-4">
            {{-- Show admin panel only to admins using Gate --}}
            @can('admin')
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        Admin Panel
                    </div>
                    <div class="card-body">
                        <p><strong>Product ID:</strong> {{ $product->id }}</p>
                        <p><strong>Created:</strong> {{ $product->created_at->format('M d, Y') }}</p>
                        <p><strong>Creator ID:</strong> {{ $product->user_id }}</p>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
