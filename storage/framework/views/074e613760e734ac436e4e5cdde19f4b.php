<?php $__env->startSection('title', 'Profile'); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <h3>Admin Profile</h3>
    <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <label>Name</label>
        <input type="text" name="name" value="<?php echo e($user->name); ?>" required>
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color:#e74c3c;font-size:12px;margin-top:-8px;margin-bottom:8px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <label>Username</label>
        <input type="text" name="username" value="<?php echo e($user->username); ?>">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo e($user->email); ?>" required>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color:#e74c3c;font-size:12px;margin-top:-8px;margin-bottom:8px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="password">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation">
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\CentralizedPisoNet\resources\views/admin/profile/show.blade.php ENDPATH**/ ?>