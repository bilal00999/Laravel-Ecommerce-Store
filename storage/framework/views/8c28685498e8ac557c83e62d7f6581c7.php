

<?php $__env->startSection('title', 'Message History'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .history-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; }
    .history-header h1 { margin: 0; font-size: 2rem; font-weight: 700; }
    .message-card { background: white; border-left: 4px solid #667eea; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 1rem; transition: all 0.3s ease; }
    .message-card:hover { box-shadow: 0 5px 20px rgba(0,0,0,0.15); }
    .message-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem; }
    .message-subject { font-weight: 700; color: #333; font-size: 1.1rem; }
    .message-date { color: #999; font-size: 0.9rem; }
    .status-badge { display: inline-block; padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-read { background: #cfe2ff; color: #084298; }
    .status-replied { background: #d1e7dd; color: #0f5132; }
    .message-preview { color: #666; margin: 0.5rem 0; }
    .view-link { color: #667eea; text-decoration: none; font-weight: 600; }
    .view-link:hover { text-decoration: underline; }
    .empty-state { text-align: center; padding: 3rem 1rem; }
    .empty-state i { font-size: 3rem; color: #ccc; margin-bottom: 1rem; }
</style>

<div class="container">
    <div class="history-header">
        <h1><i class="bi bi-clock-history"></i> Your Messages</h1>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="message-card">
            <div class="message-header">
                <div>
                    <div class="message-subject"><?php echo e($message->subject); ?></div>
                    <div class="message-date">
                        <i class="bi bi-calendar"></i> <?php echo e($message->created_at->format('M d, Y H:i')); ?>

                    </div>
                </div>
                <span class="status-badge status-<?php echo e($message->status); ?>">
                    <?php echo e(ucfirst($message->status)); ?>

                </span>
            </div>
            
            <div class="message-preview">
                <?php echo e(Str::limit($message->message, 150)); ?>

            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->status === 'replied'): ?>
                <div style="background: #f0f7ff; padding: 1rem; border-radius: 6px; margin-top: 1rem; margin-bottom: 1rem;">
                    <strong style="color: #0066cc;">Admin Reply:</strong>
                    <p style="margin: 0.5rem 0 0 0; color: #333;"><?php echo e(Str::limit($message->admin_reply, 200)); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div>
                <a href="<?php echo e(route('contact.message', $message)); ?>" class="view-link">
                    <i class="bi bi-arrow-right"></i> View Full Message
                </a>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No messages yet</h3>
            <p style="color: #999;">You haven't sent any contact messages yet.</p>
            <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-primary">Send Message</a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Pagination -->
    <div style="margin-top: 2rem;">
        <?php echo e($messages->links()); ?>

    </div>

    <!-- Back Link -->
    <div style="margin-top: 2rem;">
        <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-outline-primary">
            <i class="bi bi-plus-circle"></i> Send New Message
        </a>
        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce_store\resources\views/contact/history.blade.php ENDPATH**/ ?>