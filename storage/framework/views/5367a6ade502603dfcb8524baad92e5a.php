<?php $__env->startSection('title', 'Transactions'); ?>
<?php $__env->startSection('content'); ?>
<div style="margin-bottom:16px;">
    <a href="?filter=daily" class="btn <?php echo e($filter==='daily' ? 'btn-primary' : ''); ?>" style="margin-right:8px;">Daily</a>
    <a href="?filter=weekly" class="btn <?php echo e($filter==='weekly' ? 'btn-primary' : ''); ?>" style="margin-right:8px;">Weekly</a>
    <a href="?filter=monthly" class="btn <?php echo e($filter==='monthly' ? 'btn-primary' : ''); ?>">Monthly</a>
</div>
<div class="stats-grid">
    <div class="stat-card"><div class="value"><?php echo e($totalTransactions); ?></div><div class="label">Total</div></div>
    <div class="stat-card"><div class="value">₱<?php echo e(number_format($totalCoins, 2)); ?></div><div class="label">Total Coins</div></div>
    <div class="stat-card"><div class="value"><?php echo e($totalMinutes); ?></div><div class="label">Total Minutes</div></div>
    <div class="stat-card"><div class="value"><?php echo e($completedTransactions); ?></div><div class="label">Completed</div></div>
</div>
<div class="card">
    <h3>Transactions</h3>
    <table>
        <thead><tr><th>ID</th><th>PC Unit</th><th>Coins</th><th>Minutes</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($txn->transaction_id); ?></td>
                <td><?php echo e($txn->pc_unit_id); ?></td>
                <td>₱<?php echo e(number_format($txn->total_coins, 2)); ?></td>
                <td><?php echo e($txn->total_minutes); ?></td>
                <td><?php echo e($txn->transaction_date); ?></td>
                <td><span class="badge <?php echo e($txn->status === 'completed' ? 'badge-success' : 'badge-warning'); ?>"><?php echo e(ucfirst($txn->status)); ?></span></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" style="text-align:center;color:#666;">No transactions yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\Centralized\CentralizedPisoNet\resources\views/transactions/index.blade.php ENDPATH**/ ?>