<?php $__env->startSection('title', 'Sessions'); ?>
<?php $__env->startSection('content'); ?>
<div class="stats-grid">
    <div class="stat-card"><div class="value"><?php echo e($stats['total']); ?></div><div class="label">Total</div></div>
    <div class="stat-card"><div class="value"><?php echo e($stats['active']); ?></div><div class="label">Active</div></div>
    <div class="stat-card"><div class="value"><?php echo e($stats['ended']); ?></div><div class="label">Ended</div></div>
</div>

<div class="card">
    <h3>Active Sessions</h3>
    <table>
        <thead><tr><th>Session ID</th><th>PC Unit</th><th>User</th><th>Start Time</th><th>Remaining</th><th>Action</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $activeSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($session->session_id); ?></td>
                <td><?php echo e($session->pc_unit_number); ?></td>
                <td><?php echo e($session->user_session_name); ?></td>
                <td><?php echo e($session->start_time->format('H:i:s')); ?></td>
                <td><?php echo e($session->current_remaining ?? $session->remaining_time); ?> min</td>
                <td>
                    <form method="POST" action="<?php echo e(route('sessions.destroy', $session)); ?>" style="display:inline;">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger" style="padding:4px 10px;font-size:12px;">End</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" style="text-align:center;color:#666;">No active sessions.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Session History</h3>
    <table>
        <thead><tr><th>Session ID</th><th>PC Unit</th><th>User</th><th>Start</th><th>End</th><th>Status</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($session->session_id); ?></td>
                <td><?php echo e($session->pc_unit_number); ?></td>
                <td><?php echo e($session->user_session_name); ?></td>
                <td><?php echo e($session->start_time->format('Y-m-d H:i')); ?></td>
                <td><?php echo e($session->end_time ? $session->end_time->format('Y-m-d H:i') : '-'); ?></td>
                <td><span class="badge <?php echo e($session->status === 'Active' ? 'badge-success' : 'badge-info'); ?>"><?php echo e($session->status); ?></span></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" style="text-align:center;color:#666;">No sessions yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\CentralizedPisoNet\resources\views/sessions/index.blade.php ENDPATH**/ ?>