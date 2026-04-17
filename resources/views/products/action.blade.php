<div style="display: flex; gap: 5px; justify-content: center;">
    <a href="{{ route('products.show', $id) }}" class="btn btn-sm btn-info" title="View">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('products.edit', $id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('products.destroy', $id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>
