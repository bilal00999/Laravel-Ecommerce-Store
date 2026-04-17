

<?php $__env->startSection('title', 'Contact Replies'); ?>
<?php $__env->startSection('page-title', 'Contact Message Replies'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-content">
    <!-- Filter Tabs -->
    <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 2rem; padding: 1.5rem;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="<?php echo e(route('admin.contact.replies')); ?>" 
               class="btn btn-sm <?php if(!request('status')): ?> btn-primary <?php else: ?> btn-outline-primary <?php endif; ?>">
                <i class="bi bi-list-ul"></i> All (<?php echo e($counts['all'] ?? 0); ?>)
            </a>
            <a href="<?php echo e(route('admin.contact.replies', ['status' => 'pending'])); ?>"
               class="btn btn-sm <?php if(request('status') === 'pending'): ?> btn-warning <?php else: ?> btn-outline-warning <?php endif; ?>">
                <i class="bi bi-clock-history"></i> Pending (<?php echo e($counts['pending'] ?? 0); ?>)
            </a>
            <a href="<?php echo e(route('admin.contact.replies', ['status' => 'read'])); ?>"
               class="btn btn-sm <?php if(request('status') === 'read'): ?> btn-info <?php else: ?> btn-outline-info <?php endif; ?>">
                <i class="bi bi-check"></i> Read (<?php echo e($counts['read'] ?? 0); ?>)
            </a>
            <a href="<?php echo e(route('admin.contact.replies', ['status' => 'replied'])); ?>"
               class="btn btn-sm <?php if(request('status') === 'replied'): ?> btn-success <?php else: ?> btn-outline-success <?php endif; ?>">
                <i class="bi bi-check2-all"></i> Replied (<?php echo e($counts['replied'] ?? 0); ?>)
            </a>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Message Preview</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($message->user->name); ?></strong><br>
                            <small style="color: #999;"><?php echo e($message->email); ?></small>
                        </td>
                        <td>
                            <strong><?php echo e($message->subject); ?></strong>
                        </td>
                        <td>
                            <small style="color: #666;"><?php echo e(Str::limit($message->message, 50, '...')); ?></small>
                        </td>
                        <td>
                            <small style="color: #999;"><?php echo e($message->created_at->format('M d, Y')); ?><br><?php echo e($message->created_at->format('g:i A')); ?></small>
                        </td>
                        <td>
                            <span class="badge-status badge-<?php echo e($message->status); ?>">
                                <?php echo e(ucfirst($message->status)); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.contact.show', $message)); ?>" class="btn-action btn-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #999;">
                            <i class="bi bi-inbox" style="font-size: 2.5rem; opacity: 0.5;"></i>
                            <p style="margin-top: 1rem; font-size: 1.1rem;">No messages found</p>
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        <?php echo e($messages->appends(request()->query())->links()); ?>

    </div>
</div>

<style>
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .btn-primary {
        background: #667eea;
        color: white;
    }
    .btn-primary:hover {
        background: #5568d3;
    }
    .btn-outline-primary {
        background: white;
        color: #667eea;
        border-color: #667eea;
    }
    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
    }
    .btn-warning {
        background: #ffc107;
        color: #000;
    }
    .btn-outline-warning {
        background: white;
        color: #ffc107;
        border-color: #ffc107;
    }
    .btn-info {
        background: #17a2b8;
        color: white;
    }
    .btn-outline-info {
        background: white;
        color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-success {
        background: #28a745;
        color: white;
    }
    .btn-outline-success {
        background: white;
        color: #28a745;
        border-color: #28a745;
    }
    .btn-view {
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 6px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .btn-view:hover {
        background: #1976d2;
        color: white;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce_store\resources\views/admin/contact/replies.blade.php ENDPATH**/ ?>