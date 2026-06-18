<?php $__env->startSection('title', 'Coin Inserts'); ?>
<?php $__env->startSection('content'); ?>
<div style="margin-bottom:16px;">
    <a href="?filter=daily" class="btn <?php echo e($filter==='daily' ? 'btn-primary' : ''); ?>" style="margin-right:8px;">Daily</a>
    <a href="?filter=weekly" class="btn <?php echo e($filter==='weekly' ? 'btn-primary' : ''); ?>" style="margin-right:8px;">Weekly</a>
    <a href="?filter=monthly" class="btn <?php echo e($filter==='monthly' ? 'btn-primary' : ''); ?>">Monthly</a>
</div>
<div class="stats-grid">
    <div class="stat-card"><div class="value"><?php echo e($totalInserts); ?></div><div class="label">Total Inserts</div></div>
    <div class="stat-card"><div class="value">₱<?php echo e(number_format($totalCoinValue, 2)); ?></div><div class="label">Total Value</div></div>
    <div class="stat-card"><div class="value"><?php echo e($totalMinutes); ?></div><div class="label">Total Minutes</div></div>
</div>
<div class="card">
    <h3>Coin Inserts</h3>
    <table>
        <thead><tr><th>Coin ID</th><th>PC Unit</th><th>Value</th><th>Minutes</th><th>Time</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $coinInserts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($coin->coin_id); ?></td>
                <td><?php echo e($coin->pc_unit_id); ?></td>
                <td>₱<?php echo e(number_format($coin->coin_value, 2)); ?></td>
                <td><?php echo e($coin->minutes_given); ?></td>
                <td><?php echo e($coin->inserted_time); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" style="text-align:center;color:#666;">No coin inserts yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\CentralizedPisoNet\resources\views/coininserts/index.blade.php ENDPATH**/ ?>