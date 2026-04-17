<div style="display: flex; gap: 5px; justify-content: center;">
    <a href="{{ route('orders.show', $id) }}" class="btn btn-sm btn-info" title="View Details">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('orders.edit', $id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
</div>
