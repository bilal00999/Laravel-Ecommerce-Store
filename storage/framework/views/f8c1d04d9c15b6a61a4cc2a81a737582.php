

<?php $__env->startSection('title', 'Shopping Cart'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-cart"></i> Shopping Cart</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Continue Shopping
            </a>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->count() > 0): ?>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product->image_path && file_exists(public_path($item->product->image_path))): ?>
                                                    <img src="<?php echo e(asset($item->product->image_path)); ?>" alt="<?php echo e($item->product->name); ?>" 
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                                <?php else: ?>
                                                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                                                border-radius: 5px; display: flex; align-items: center; justify-content: center; color: white;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <div>
                                                    <a href="<?php echo e(route('products.show', $item->product)); ?>" class="text-decoration-none">
                                                        <strong><?php echo e($item->product->name); ?></strong>
                                                    </a><br>
                                                    <small class="text-muted"><?php echo e($item->product->category->name ?? 'N/A'); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>$<?php echo e(number_format($item->product->price, 2)); ?></td>
                                        <td>
                                            <form action="<?php echo e(route('cart.update', $item->id)); ?>" method="POST" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="input-group" style="width: 120px;">
                                                    <input type="number" name="quantity" value="<?php echo e($item->quantity); ?>" min="1" max="999" class="form-control" id="qty-<?php echo e($item->id); ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Update quantity">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <strong>$<?php echo e(number_format($item->product->price * $item->quantity, 2)); ?></strong>
                                        </td>
                                        <td>
                                            <form action="<?php echo e(route('cart.remove', $item->id)); ?>" method="POST" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove from cart">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Clear Cart Button -->
                <div class="mt-3">
                    <form action="<?php echo e(route('cart.clear')); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to clear your cart?')">
                            <i class="bi bi-x-circle"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-md-4">
                <div class="card sticky-top">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items (<?php echo e($items->count()); ?>):</span>
                            <strong>$<?php echo e(number_format($total, 2)); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="badge bg-success">Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <strong>$<?php echo e(number_format($total * 0.08, 2)); ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span style="font-size: 1.2rem;">Total:</span>
                            <strong style="font-size: 1.2rem; color: #667eea;">$<?php echo e(number_format($total + ($total * 0.08), 2)); ?></strong>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('checkout.show')); ?>" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-credit-card"></i> Proceed to Checkout
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login to Checkout
                            </a>
                            <small class="text-muted d-block mt-3">
                                <i class="bi bi-info-circle"></i> You must be logged in to checkout
                            </small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Empty Cart -->
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body py-5">
                        <i class="bi bi-cart-x" style="font-size: 4rem; color: #667eea; opacity: 0.5;"></i>
                        <h3 class="mt-4">Your Cart is Empty</h3>
                        <p class="text-muted">You haven't added any products yet. Start shopping now!</p>
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-shop"></i> Browse Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce_store\resources\views/cart/index.blade.php ENDPATH**/ ?>