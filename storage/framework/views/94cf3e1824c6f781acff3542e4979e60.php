<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="value">₱<?php echo e(number_format($todayEarnings, 2)); ?></div>
        <div class="label">Today's Earnings</div>
    </div>
    <div class="stat-card">
        <div class="value"><?php echo e($todayTransactions); ?></div>
        <div class="label">Transactions Today</div>
    </div>
    <div class="stat-card">
        <div class="value">₱<?php echo e(number_format($avgTransaction, 2)); ?></div>
        <div class="label">Avg Transaction</div>
    </div>
    <div class="stat-card">
        <div class="value"><?php echo e(count($machines)); ?></div>
        <div class="label">Total Machines</div>
    </div>
</div>

<div class="card">
    <h3>Machine Status</h3>
    <table>
        <thead><tr><th>Machine</th><th>Status</th><th>Time</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($machine['id']); ?></td>
                <td>
                    <?php if($machine['status'] === 'online'): ?>
                        <span class="badge badge-success">Online</span>
                    <?php elseif($machine['status'] === 'busy'): ?>
                        <span class="badge badge-warning">Busy</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Offline</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($machine['time']); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="3" style="text-align:center;color:#666;">No machines registered yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Recent Transactions</h3>
    <table>
        <thead><tr><th>PC Unit</th><th>Coins</th><th>Minutes</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($txn->pc_unit_id); ?></td>
                <td>₱<?php echo e(number_format($txn->total_coins, 2)); ?></td>
                <td><?php echo e($txn->total_minutes); ?> min</td>
                <td><?php echo e($txn->transaction_date); ?></td>
                <td><span class="badge <?php echo e($txn->status === 'completed' ? 'badge-success' : 'badge-warning'); ?>"><?php echo e(ucfirst($txn->status)); ?></span></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" style="text-align:center;color:#666;">No transactions yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\Centralized\CentralizedPisoNet\resources\views/dashboard.blade.php ENDPATH**/ ?>