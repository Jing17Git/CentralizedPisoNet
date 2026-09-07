<?php $__env->startSection('title', 'Lock Screen Settings'); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <h3>Lock Screen Configuration</h3>
    <form method="POST" action="<?php echo e(route('lock')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <label>Title</label>
        <input type="text" name="lockscreen_title" value="<?php echo e($lockscreenTitle); ?>">
        <label>Message</label>
        <input type="text" name="lockscreen_message" value="<?php echo e($lockscreenMessage); ?>">
        <label>Theme</label>
        <select name="lockscreen_theme">
            <option value="dark" <?php echo e($lockscreenTheme === 'dark' ? 'selected' : ''); ?>>Dark</option>
            <option value="light" <?php echo e($lockscreenTheme === 'light' ? 'selected' : ''); ?>>Light</option>
            <option value="blue" <?php echo e($lockscreenTheme === 'blue' ? 'selected' : ''); ?>>Blue</option>
            <option value="green" <?php echo e($lockscreenTheme === 'green' ? 'selected' : ''); ?>>Green</option>
        </select>
        <label>Show Timer</label>
        <select name="lockscreen_show_timer">
            <option value="true" <?php echo e($showTimer === 'true' ? 'selected' : ''); ?>>Yes</option>
            <option value="false" <?php echo e($showTimer === 'false' ? 'selected' : ''); ?>>No</option>
        </select>
        <label>Show Price</label>
        <select name="lockscreen_show_price">
            <option value="true" <?php echo e($showPrice === 'true' ? 'selected' : ''); ?>>Yes</option>
            <option value="false" <?php echo e($showPrice === 'false' ? 'selected' : ''); ?>>No</option>
        </select>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\CentralizedPisoNet\resources\views/admin/lockscreen/index.blade.php ENDPATH**/ ?>