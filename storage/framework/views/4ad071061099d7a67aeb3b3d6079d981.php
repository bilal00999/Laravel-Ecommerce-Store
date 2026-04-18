<div style="display: flex; gap: 5px; justify-content: center;">
    <a href="<?php echo e(route('products.show', $id)); ?>" class="btn btn-sm btn-info" title="View">
        <i class="bi bi-eye"></i>
    </a>
    <a href="<?php echo e(route('products.edit', $id)); ?>" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="<?php echo e(route('products.destroy', $id)); ?>" method="POST" style="display: inline;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>
<?php /**PATH C:\laragon\www\ecommerce_store\resources\views/products/action.blade.php ENDPATH**/ ?>