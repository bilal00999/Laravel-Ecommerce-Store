

<?php $__env->startSection('title', 'Order Placed Successfully'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Message -->
            <div class="card border-success mb-4">
                <div class="card-body text-center py-5">
                    <div style="font-size: 4rem; color: #28a745; margin-bottom: 1rem;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h1 class="card-title text-success">Order Placed Successfully!</h1>
                    <p class="card-text text-muted fs-5">
                        Thank you for your purchase. Your order has been received and is being processed.
                    </p>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="card mb-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0"><i class="bi bi-file-text"></i> Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p>
                                <strong>Order Number:</strong><br>
                                <span style="font-family: monospace; background: #f5f5f5; padding: 5px 10px; border-radius: 3px;">
                                    #<?php echo e(str_pad($order->id, 8, '0', STR_PAD_LEFT)); ?>

                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Order Date:</strong><br>
                                <?php echo e($order->created_at->format('F d, Y \a\t h:i A')); ?>

                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p>
                                <strong>Status:</strong><br>
                                <span class="badge bg-warning text-dark"><?php echo e(ucfirst($order->status)); ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Payment Method:</strong><br>
                                <?php echo e(ucfirst(str_replace('_', ' ', $order->payment_method))); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0"><i class="bi bi-bag"></i> Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('products.show', $item->product)); ?>" class="text-decoration-none">
                                                <?php echo e($item->product->name); ?>

                                            </a>
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

            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                <strong>Subtotal:</strong><br>
                                $<?php echo e(number_format($order->total * (100/108), 2)); ?>

                            </p>
                            <p>
                                <strong>Tax (8%):</strong><br>
                                $<?php echo e(number_format($order->total - ($order->total * (100/108)), 2)); ?>

                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p style="font-size: 1.5rem;">
                                <strong>Total Amount:</strong><br>
                                <span style="color: #28a745; font-size: 1.8rem;">$<?php echo e(number_format($order->total, 2)); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <?php
                $shippingAddress = json_decode($order->shipping_address, true);
            ?>
            <div class="card mb-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Shipping Address</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <strong><?php echo e($shippingAddress['full_name']); ?></strong><br>
                        <?php echo e($shippingAddress['street_address']); ?><br>
                        <?php echo e($shippingAddress['city']); ?>, <?php echo e($shippingAddress['state']); ?> <?php echo e($shippingAddress['postal_code']); ?><br>
                        <?php echo e($shippingAddress['country']); ?><br>
                        <strong>Email:</strong> <?php echo e($shippingAddress['email']); ?><br>
                        <strong>Phone:</strong> <?php echo e($shippingAddress['phone']); ?>

                    </p>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading"><i class="bi bi-info-circle"></i> What's Next?</h4>
                <p class="mb-2">
                    <strong>Confirmation Email:</strong> A confirmation email has been sent to <code><?php echo e($shippingAddress['email']); ?></code>
                </p>
                <p class="mb-2">
                    <strong>Order Tracking:</strong> You can track your order status anytime from your account dashboard.
                </p>
                <p class="mb-0">
                    <strong>Estimated Delivery:</strong> Your order will be shipped within 2-3 business days.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2 d-md-flex justify-content-center">
                <a href="<?php echo e(route('checkout.orders')); ?>" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <i class="bi bi-list-check"></i> View My Orders
                </a>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-shop"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce_store\resources\views/checkout/success.blade.php ENDPATH**/ ?>