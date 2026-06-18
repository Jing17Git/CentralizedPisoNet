<?php $__env->startSection('title', 'Settings'); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <h3>Network & Hardware Settings</h3>
    <form method="POST" action="<?php echo e(route('settings.update', 1)); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <label>Network Mode</label>
                <select name="network_mode">
                    <option value="dhcp" <?php echo e($networkMode === 'dhcp' ? 'selected' : ''); ?>>DHCP</option>
                    <option value="static" <?php echo e($networkMode === 'static' ? 'selected' : ''); ?>>Static</option>
                </select>
                <label>Server IP</label>
                <input type="text" name="server_ip" value="<?php echo e($serverIp); ?>">
                <label>Subnet Mask</label>
                <input type="text" name="subnet_mask" value="<?php echo e($subnetMask); ?>">
                <label>Gateway</label>
                <input type="text" name="gateway" value="<?php echo e($gateway); ?>">
            </div>
            <div>
                <label>DNS Server</label>
                <input type="text" name="dns_server" value="<?php echo e($dnsServer); ?>">
                <label>DHCP Range Start</label>
                <input type="text" name="dhcp_range_start" value="<?php echo e($dhcpRangeStart); ?>">
                <label>DHCP Range End</label>
                <input type="text" name="dhcp_range_end" value="<?php echo e($dhcpRangeEnd); ?>">
                <label>Coin Pin</label>
                <input type="text" name="coin_pin" value="<?php echo e($coinPin); ?>">
                <label>Set Pin</label>
                <input type="text" name="set_pin" value="<?php echo e($setPin); ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\CentralizedPisoNet\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>