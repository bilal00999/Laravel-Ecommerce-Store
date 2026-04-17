

<?php $__env->startSection('title', 'Message Reply'); ?>
<?php $__env->startSection('page-title', 'Reply to Message'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-content">
    <!-- Back Button -->
    <div style="margin-bottom: 2rem;">
        <a href="<?php echo e(route('admin.contact.replies')); ?>" style="color: #667eea; text-decoration: none; font-weight: 600;">
            <i class="bi bi-arrow-left"></i> Back to Messages
        </a>
    </div>

    <div class="row">
        <!-- Message Details -->
        <div class="col-lg-6">
            <div class="data-table" style="margin-bottom: 2rem;">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-envelope-open"></i> Message Details
                    </h5>
                </div>
                <div style="padding: 2rem;">
                    <div style="margin-bottom: 1.5rem;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">FROM</label>
                        <div style="margin-top: 0.5rem;">
                            <strong style="font-size: 1.1rem;"><?php echo e($contactMessage->user->name); ?></strong><br>
                            <a href="mailto:<?php echo e($contactMessage->email); ?>" style="color: #667eea; text-decoration: none;"><?php echo e($contactMessage->email); ?></a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactMessage->phone): ?>
                                <br><a href="tel:<?php echo e($contactMessage->phone); ?>" style="color: #667eea; text-decoration: none;"><?php echo e($contactMessage->phone); ?></a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">SUBJECT</label>
                        <div style="margin-top: 0.5rem; font-size: 1.1rem; font-weight: 600;"><?php echo e($contactMessage->subject); ?></div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">DATE</label>
                        <div style="margin-top: 0.5rem; color: #666;">
                            <?php echo e($contactMessage->created_at->format('M d, Y g:i A')); ?>

                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 700;">STATUS</label>
                        <div style="margin-top: 0.5rem;">
                            <span class="badge-status badge-<?php echo e($contactMessage->status); ?>" style="display: inline-block;">
                                <?php echo e(ucfirst($contactMessage->status)); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="data-table">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-chat-left-text"></i> Message
                    </h5>
                </div>
                <div style="padding: 2rem; color: #666; line-height: 1.8; white-space: pre-wrap;">
                    <?php echo e($contactMessage->message); ?>

                </div>
            </div>
        </div>

        <!-- Reply Form -->
        <div class="col-lg-6">
            <div class="data-table">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-reply"></i> Send Reply
                    </h5>
                </div>
                <form action="<?php echo e(route('admin.contact.reply.store', $contactMessage)); ?>" method="POST" style="padding: 2rem;">
                    <?php echo csrf_field(); ?>

                    <!-- Admin Reply -->
                    <div style="margin-bottom: 1.5rem;">
                        <label for="reply" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                            Your Reply *
                        </label>
                        <textarea name="reply" id="reply" rows="8" required
                                  style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-family: inherit; font-size: 0.95rem;"
                                  placeholder="Type your reply here..."><?php echo e(old('reply')); ?></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['reply'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small style="color: #f44336; display: block; margin-top: 0.3rem;"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" style="flex: 1; padding: 0.8rem 1.5rem; background: #667eea; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease;">
                            <i class="bi bi-send"></i> Send Reply
                        </button>
                        <a href="<?php echo e(route('admin.contact.replies')); ?>" style="padding: 0.8rem 1.5rem; background: #f0f0f0; color: #333; border: none; border-radius: 6px; font-weight: 600; text-decoration: none; text-align: center; transition: all 0.3s ease;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Previous Reply (if exists) -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contactMessage->admin_reply): ?>
                <div class="data-table" style="margin-top: 2rem; background: #f0f7ff; border: 1px solid #cfe2ff;">
                    <div style="padding: 1.5rem 2rem; background: #cfe2ff; border-bottom: 1px solid #cfe2ff;">
                        <h5 style="margin: 0; font-weight: 700; color: #084298;">
                            <i class="bi bi-check2-all"></i> Previous Reply (<?php echo e($contactMessage->replied_at?->format('M d, Y g:i A')); ?>)
                        </h5>
                    </div>
                    <div style="padding: 2rem; color: #084298; line-height: 1.8; white-space: pre-wrap;">
                        <?php echo e($contactMessage->admin_reply); ?>

                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

<style>
    .badge-status {
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-read { background: #cfe2ff; color: #084298; }
    .badge-replied { background: #d1e7dd; color: #0f5132; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce_store\resources\views/admin/contact/reply-detail.blade.php ENDPATH**/ ?>