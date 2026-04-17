

<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-content">
    <!-- Statistics Row -->
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-people" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number"><?php echo e($stats['total_users']); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-box-seam" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number"><?php echo e($stats['total_products']); ?></div>
                <div class="stat-label">Products</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-tag" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number"><?php echo e($stats['total_categories']); ?></div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-envelope" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number"><?php echo e($stats['pending_messages']); ?></div>
                <div class="stat-label">Pending Messages</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-3">
        <!-- Recent Messages -->
        <div class="col-lg-6">
            <div class="data-table">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-chat-dots"></i> Recent Messages
                    </h5>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recent_messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($message->user->name); ?></strong><br>
                                    <small style="color: #999;"><?php echo e($message->user->email); ?></small>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.contact.show', $message)); ?>" style="color: #667eea; text-decoration: none;">
                                        <?php echo e(Str::limit($message->subject, 25)); ?>

                                    </a>
                                </td>
                                <td>
                                    <span class="badge-status badge-<?php echo e($message->status); ?>">
                                        <?php echo e(ucfirst($message->status)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #999;">No messages yet</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
                <div style="padding: 1rem 2rem; text-align: center; border-top: 1px solid #f0f0f0;">
                    <a href="<?php echo e(route('admin.contact.replies')); ?>" style="color: #667eea; text-decoration: none; font-weight: 600;">
                        View All Messages <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-lg-6">
            <div class="data-table">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-person-plus"></i> Recent Users
                    </h5>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recent_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr>
                                <td><strong><?php echo e($user->name); ?></strong></td>
                                <td style="color: #667eea;"><?php echo e($user->email); ?></td>
                                <td>
                                    <span class="badge" style="background: <?php echo e($user->is_admin ? '#4caf50' : '#2196f3'); ?>; color: white;">
                                        <?php echo e($user->is_admin ? 'Admin' : 'User'); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #999;">No users yet</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
                <div style="padding: 1rem 2rem; text-align: center; border-top: 1px solid #f0f0f0;">
                    <span style="color: #999;">Total: <?php echo e($stats['total_users']); ?> users</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div style="margin-top: 2rem;">
        <div class="data-table">
            <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                <h5 style="margin: 0; font-weight: 700;">
                    <i class="bi bi-box-seam"></i> Recent Products
                </h5>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Added By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recent_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($product->name); ?></strong>
                            </td>
                            <td><?php echo e($product->category->name ?? 'N/A'); ?></td>
                            <td>$<?php echo e(number_format($product->price, 2)); ?></td>
                            <td>
                                <span class="badge" style="background: <?php echo e($product->stock > 0 ? '#4caf50' : '#f44336'); ?>; color: white;">
                                    <?php echo e($product->stock); ?>

                                </span>
                            </td>
                            <td><?php echo e($product->user->name ?? 'System'); ?></td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #999;">No products yet</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <div style="padding: 1rem 2rem; text-align: center; border-top: 1px solid #f0f0f0;">
                <a href="<?php echo e(route('products.index')); ?>" style="color: #667eea; text-decoration: none; font-weight: 600;">
                    Manage Products <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce_store\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>