

<?php $__env->startSection('title', 'Visitor Overview'); ?>
<?php $__env->startSection('page-title', 'Visitor & User Analytics'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-content">
    <!-- Summary Stats -->
    <div class="row mb-4 g-3">
        <div class="col-md-4 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-people" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number"><?php echo e($stats['total_users'] ?? 0); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-person-check" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number"><?php echo e($stats['active_users'] ?? 0); ?></div>
                <div class="stat-label">Active Users</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="stat-card">
                <i class="bi bi-shield-check" style="font-size: 2rem; color: #667eea;"></i>
                <div class="stat-number"><?php echo e($stats['admin_users'] ?? 0); ?></div>
                <div class="stat-label">Admin Users</div>
            </div>
        </div>
    </div>

    <!-- User Registration Timeline -->
    <div class="row">
        <div class="col-lg-8">
            <div class="data-table">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-graph-up"></i> User Registration Timeline
                    </h5>
                </div>
                <div style="padding: 2rem; text-align: center; color: #999; min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <div>
                        <i class="bi bi-bar-chart" style="font-size: 2rem; opacity: 0.5;"></i>
                        <p style="margin-top: 1rem;">Chart visualization coming soon</p>
                        <small>Historical user registration data will be displayed here</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="data-table" style="margin-bottom: 2rem;">
                <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0;">
                    <h5 style="margin: 0; font-weight: 700;">
                        <i class="bi bi-info-circle"></i> Key Metrics
                    </h5>
                </div>
                <div style="padding: 2rem;">
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                        <div style="color: #999; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem;">NEW USERS (TODAY)</div>
                        <div style="font-size: 1.8rem; font-weight: 700; color: #667eea;"><?php echo e($stats['new_users_today'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User List -->
    <div style="margin-top: 2rem;">
        <div class="data-table">
            <div style="padding: 1.5rem 2rem; background: white; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-weight: 700;">
                    <i class="bi bi-list-ul"></i> Recent Users
                </h5>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $recent_users = \App\Models\User::orderBy('created_at', 'desc')->limit(20)->get();
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recent_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr>
                            <td><strong><?php echo e($user->name); ?></strong></td>
                            <td style="color: #667eea;"><?php echo e($user->email); ?></td>
                            <td>
                                <span class="badge" style="background: <?php echo e($user->is_admin ? '#4caf50' : '#2196f3'); ?>; color: white; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.85rem;">
                                    <?php echo e($user->is_admin ? 'Admin' : 'User'); ?>

                                </span>
                            </td>
                            <td><small style="color: #999;"><?php echo e($user->created_at->format('M d, Y')); ?></small></td>
                            <td>
                                <span class="badge" style="background: #d1e7dd; color: #0f5132; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.85rem;">
                                    Active
                                </span>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: #999;">
                                No users found
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        text-align: center;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
    }
    .stat-label {
        color: #999;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    .table th {
        background: #f8f9fa;
        border: none;
        padding: 1rem;
        font-weight: 700;
        color: #333;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    .table td {
        padding: 1rem;
        border-color: #f0f0f0;
        color: #666;
    }
    .table tr:hover {
        background: #fafbfc;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce_store\resources\views/admin/visitors/overview.blade.php ENDPATH**/ ?>