<?php $__env->startSection('title', 'PC Units'); ?>
<?php $__env->startSection('content'); ?>
<div class="stats-grid">
    <div class="stat-card"><div class="value"><?php echo e($stats['total']); ?></div><div class="label">Total</div></div>
    <div class="stat-card"><div class="value"><?php echo e($stats['active']); ?></div><div class="label">Active</div></div>
    <div class="stat-card"><div class="value"><?php echo e($stats['available']); ?></div><div class="label">Available</div></div>
    <div class="stat-card"><div class="value"><?php echo e($stats['in_use']); ?></div><div class="label">In Use</div></div>
    <div class="stat-card"><div class="value"><?php echo e($stats['offline']); ?></div><div class="label">Offline</div></div>
</div>

<div class="card">
    <h3>Add PC Unit</h3>
    <form method="POST" action="<?php echo e(route('pc-units.store')); ?>" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;align-items:end;">
        <?php echo csrf_field(); ?>
        <div><label>PC Number</label><input type="text" name="pc_number" placeholder="e.g. PC-01" required></div>
        <div><label>IP Address</label><input type="text" name="ip_address" placeholder="e.g. 192.168.1.101" required></div>
        <div><label>Status</label>
            <select name="status">
                <option value="available">Available</option>
                <option value="offline">Offline</option>
            </select>
        </div>
        <div><button type="submit" class="btn btn-primary">Add</button></div>
    </form>
</div>

<div class="card">
    <h3>PC Units</h3>
    <table>
        <thead><tr><th>PC Number</th><th>IP Address</th><th>Status</th><th>Active</th><th>Actions</th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $pcUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($pc->pc_number); ?></td>
                <td><?php echo e($pc->ip_address); ?></td>
                <td><span class="badge <?php echo e($pc->status === 'available' ? 'badge-success' : ($pc->status === 'in_use' ? 'badge-warning' : 'badge-danger')); ?>"><?php echo e(ucfirst(str_replace('_',' ',$pc->status))); ?></span></td>
                <td><span class="badge <?php echo e($pc->is_active ? 'badge-success' : 'badge-danger'); ?>"><?php echo e($pc->is_active ? 'Yes' : 'No'); ?></span></td>
                <td>
                    <form method="POST" action="<?php echo e(route('pc-units.destroy', $pc)); ?>" style="display:inline;" onsubmit="return confirm('Delete this PC unit?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger" style="padding:4px 10px;font-size:12px;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" style="text-align:center;color:#666;">No PC units yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\Centralized\CentralizedPisoNet\resources\views/pcunits/index.blade.php ENDPATH**/ ?>