<?php $__env->startSection('title', 'Reports'); ?>
<?php $__env->startSection('content'); ?>
<div class="stats-grid">
    <div class="stat-card"><div class="value">₱<?php echo e(number_format($totalEarningsToday, 2)); ?></div><div class="label">Earnings Today</div></div>
    <div class="stat-card"><div class="value"><?php echo e($totalCoinsToday); ?></div><div class="label">Coins Today</div></div>
    <div class="stat-card"><div class="value"><?php echo e($activeMachines); ?></div><div class="label">Active Machines</div></div>
    <div class="stat-card"><div class="value"><?php echo e($totalSessionsToday); ?></div><div class="label">Sessions Today</div></div>
</div>
<div class="card">
    <h3>Daily Earnings (Last 7 Days)</h3>
    <table>
        <thead><tr><th>Date</th><th>Total Earnings</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $dailyEarnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><?php echo e($day->date); ?></td><td>₱<?php echo e(number_format($day->total, 2)); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="2" style="text-align:center;color:#666;">No data yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="card">
    <h3>Machine Usage</h3>
    <table>
        <thead><tr><th>Machine</th><th>Code</th><th>Status</th><th>Sessions</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $machineUsage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($machine->name); ?></td>
                <td><?php echo e($machine->machine_code); ?></td>
                <td><span class="badge <?php echo e($machine->status === 'online' ? 'badge-success' : 'badge-danger'); ?>"><?php echo e(ucfirst($machine->status)); ?></span></td>
                <td><?php echo e($machine->sessions_count); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="4" style="text-align:center;color:#666;">No machines yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\Centralized\CentralizedPisoNet\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>