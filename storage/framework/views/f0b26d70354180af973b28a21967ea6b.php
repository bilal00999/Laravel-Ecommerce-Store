

<?php $__env->startSection('title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="<?php echo e(route('checkout.orders')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
            <h2 class="mt-2"><i class="bi bi-file-text"></i> Order #<?php echo e(str_pad($order->id, 8, '0', STR_PAD_LEFT)); ?></h2>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="mb-2">
                                <?php
                                    $statuses = ['pending', 'processing', 'completed', 'cancelled'];
                                    $currentStep = array_search($order->status, $statuses);
                                ?>
                                <i class="bi bi-hourglass-top" style="font-size: 2rem; color: #ffc107;"></i>
                            </div>
                            <p><strong><?php echo e(ucfirst($order->status)); ?></strong></p>
                            <p class="text-muted small"><?php echo e($order->created_at->format('F d, Y H:i A')); ?></p>
                        </div>
                        <div class="col-md-9">
                            <p class="text-muted mb-0">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'pending'): ?>
                                    Your order is pending confirmation. We'll update you as soon as it's confirmed.
                                <?php elseif($order->status === 'processing'): ?>
                                    Your order is being processed and will be shipped soon.
                                <?php elseif($order->status === 'completed'): ?>
                                    Your order has been delivered. Thank you for your purchase!
                                <?php elseif($order->status === 'cancelled'): ?>
                                    This order has been cancelled.
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0"><i class="bi bi-bag"></i> Order Items (<?php echo e($items->count()); ?>)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product->image_path && file_exists(public_path($item->product->image_path))): ?>
                                                    <img src="<?php echo e(asset($item->product->image_path)); ?>" alt="<?php echo e($item->product->name); ?>" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 3px;">
                                                <?php else: ?>
                                                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                                                border-radius: 3px; display: flex; align-items: center; justify-content: center; color: white;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <div>
                                                    <a href="<?php echo e(route('products.show', $item->product)); ?>" class="text-decoration-none">
                                                        <strong><?php echo e($item->product->name); ?></strong>
                                                    </a><br>
                                                    <small class="text-muted">SKU: #<?php echo e(str_pad($item->product->id, 5, '0', STR_PAD_LEFT)); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">$<?php echo e(number_format($item->unit_price, 2)); ?></td>
                                        <td class="text-center"><?php echo e($item->quantity); ?></td>
                                        <td class="text-end fw-bold">$<?php echo e(number_format($item->total, 2)); ?></td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card mb-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Shipping Address</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <strong><?php echo e($shippingAddress['full_name']); ?></strong><br>
                        <?php echo e($shippingAddress['street_address']); ?><br>
                        <?php echo e($shippingAddress['city']); ?>, <?php echo e($shippingAddress['state']); ?> <?php echo e($shippingAddress['postal_code']); ?><br>
                        <?php echo e($shippingAddress['country']); ?><br><br>
                        <strong>Email:</strong> <?php echo e($shippingAddress['email']); ?><br>
                        <strong>Phone:</strong> <?php echo e($shippingAddress['phone']); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <!-- Price Summary -->
            <div class="card sticky-top mb-4" style="top: 20px;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <?php
                        $subtotal = $order->total * (100/108);
                        $tax = $order->total - $subtotal;
                    ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>$<?php echo e(number_format($subtotal, 2)); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (8%):</span>
                        <span>$<?php echo e(number_format($tax, 2)); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span class="badge bg-success">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between" style="font-size: 1.2rem;">
                        <span class="fw-bold">Total:</span>
                        <span class="text-success fw-bold">$<?php echo e(number_format($order->total, 2)); ?></span>
                    </div>
                </div>
            </div>

            <!-- Order Info -->
            <div class="card mb-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body small">
                    <p class="mb-2">
                        <strong>Order Number:</strong><br>
                        <span style="font-family: monospace; background: #f5f5f5; padding: 3px 6px; border-radius: 3px;">
                            #<?php echo e(str_pad($order->id, 8, '0', STR_PAD_LEFT)); ?>

                        </span>
                    </p>
                    <p class="mb-2">
                        <strong>Order Date:</strong><br>
                        <?php echo e($order->created_at->format('F d, Y H:i A')); ?>

                    </p>
                    <p class="mb-2">
                        <strong>Payment Method:</strong><br>
                        <?php echo e(ucfirst(str_replace('_', ' ', $order->payment_method))); ?>

                    </p>
                    <p class="mb-0">
                        <strong>Status:</strong><br>
                        <?php
                            $statusColors = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ];
                            $color = $statusColors[$order->status] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?php echo e($color); ?>"><?php echo e(ucfirst($order->status)); ?></span>
                    </p>
                </div>
            </div>

            <!-- Additional Notes -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->notes): ?>
                <div class="card">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0">Order Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0"><?php echo e($order->notes); ?></p>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-md-12">
            <a href="<?php echo e(route('checkout.orders')); ?>" class="btn text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-shop"></i> Continue Shopping
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce_store\resources\views/checkout/order-details.blade.php ENDPATH**/ ?>