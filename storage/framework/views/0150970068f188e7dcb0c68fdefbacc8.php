

<?php $__env->startSection('title', 'Message Details'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .message-detail-container { max-width: 800px; margin: 0 auto; }
    .detail-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; }
    .detail-header h1 { margin: 0; font-size: 1.8rem; font-weight: 700; }
    .detail-meta { margin-top: 1rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; }
    .meta-item { padding: 0.7rem; background: rgba(255,255,255,0.1); border-radius: 6px; }
    .meta-label { font-size: 0.85rem; opacity: 0.9; }
    .meta-value { font-weight: 600; margin-top: 0.3rem; }
    .message-content { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 20px rgba(0,0,0,0.1); margin-bottom: 2rem; }
    .content-section { margin-bottom: 2rem; }
    .content-title { font-weight: 700; color: #333; margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.9rem; color: #667eea; }
    .content-text { color: #666; line-height: 1.8; }
    .status-badge { display: inline-block; padding: 0.4rem 0.8rem; border-radius: 20px; font-weight: 600; font-size: 0.85rem; margin-bottom: 1rem; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-read { background: #cfe2ff; color: #084298; }
    .status-replied { background: #d1e7dd; color: #0f5132; }
    .reply-box { background: #f0f7ff; border-left: 4px solid #0066cc; padding: 1.5rem; border-radius: 6px; margin-top: 1rem; }
    .reply-label { font-weight: 700; color: #0066cc; margin-bottom: 0.5rem; }
    .reply-text { color: #333; line-height: 1.8; }
    .action-buttons { display: flex; gap: 1rem; margin-top: 2rem; }
</style>

<div class="message-detail-container">
    <div class="detail-header">
        <h1><?php echo e($message->subject); ?></h1>
        <span class="status-badge status-<?php echo e($message->status); ?>">
            <?php echo e(ucfirst($message->status)); ?>

        </span>
        <div class="detail-meta">
            <div class="meta-item">
                <div class="meta-label">Sent On</div>
                <div class="meta-value"><?php echo e($message->created_at->format('M d, Y')); ?></div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Time</div>
                <div class="meta-value"><?php echo e($message->created_at->format('H:i A')); ?></div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->status === 'replied'): ?>
                <div class="meta-item">
                    <div class="meta-label">Replied On</div>
                    <div class="meta-value"><?php echo e($message->replied_at->format('M d, Y')); ?></div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="message-content">
        <!-- Contact Information -->
        <div class="content-section">
            <div class="content-title"><i class="bi bi-info-circle"></i> Contact Information</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <p style="color: #999; font-size: 0.9rem; margin: 0;">Email</p>
                    <p style="color: #333; font-weight: 600; margin: 0.3rem 0 0 0;">
                        <a href="mailto:<?php echo e($message->email); ?>" style="color: #667eea; text-decoration: none;">
                            <?php echo e($message->email); ?>

                        </a>
                    </p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->phone): ?>
                    <div>
                        <p style="color: #999; font-size: 0.9rem; margin: 0;">Phone</p>
                        <p style="color: #333; font-weight: 600; margin: 0.3rem 0 0 0;">
                            <a href="tel:<?php echo e($message->phone); ?>" style="color: #667eea; text-decoration: none;">
                                <?php echo e($message->phone); ?>

                            </a>
                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 2rem 0;">

        <!-- Message Content -->
        <div class="content-section">
            <div class="content-title"><i class="bi bi-envelope"></i> Your Message</div>
            <div class="content-text">
                <?php echo nl2br(e($message->message)); ?>

            </div>
        </div>

        <!-- Admin Reply -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($message->status === 'replied' && $message->admin_reply): ?>
            <div class="reply-box">
                <div class="reply-label"><i class="bi bi-chat-dots"></i> Admin Reply</div>
                <p style="color: #999; font-size: 0.85rem; margin: 0;">
                    Replied on <?php echo e($message->replied_at->format('M d, Y H:i A')); ?>

                </p>
                <div class="reply-text" style="margin-top: 1rem;">
                    <?php echo nl2br(e($message->admin_reply)); ?>

                </div>
            </div>
        <?php elseif($message->status === 'pending'): ?>
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 1rem; border-radius: 6px; margin-top: 1rem;">
                <i class="bi bi-hourglass-split"></i> <strong>Pending</strong>
                <p style="margin: 0.5rem 0 0 0; color: #856404; font-size: 0.9rem;">
                    Your message is awaiting a response from our support team. We'll get back to you soon!
                </p>
            </div>
        <?php elseif($message->status === 'read'): ?>
            <div style="background: #cfe2ff; border-left: 4px solid #0d6efd; padding: 1rem; border-radius: 6px; margin-top: 1rem;">
                <i class="bi bi-check-circle"></i> <strong>Message Received</strong>
                <p style="margin: 0.5rem 0 0 0; color: #084298; font-size: 0.9rem;">
                    Your message has been received and is being reviewed by our team.
                </p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="<?php echo e(route('contact.history')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Messages
            </a>
            <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-plus-circle"></i> Send New Message
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce_store\resources\views/contact/message-detail.blade.php ENDPATH**/ ?>