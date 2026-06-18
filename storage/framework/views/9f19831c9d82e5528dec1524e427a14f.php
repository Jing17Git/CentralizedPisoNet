<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CentralizedPisoNet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #1a1a2e; color: #eee; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-box { background: #16213e; padding: 40px; border-radius: 10px; width: 360px; }
        h2 { color: #00c8ff; text-align: center; margin-bottom: 24px; }
        label { display: block; color: #aaa; margin-bottom: 4px; font-size: 13px; }
        input { width: 100%; background: #0f3460; color: #eee; border: 1px solid #1a4a7a; padding: 10px 12px; border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
        button { width: 100%; background: #00c8ff; color: #000; border: none; padding: 12px; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; }
        .error { color: #e74c3c; font-size: 12px; margin-top: -12px; margin-bottom: 12px; }
        .register-link { text-align: center; margin-top: 16px; font-size: 13px; color: #aaa; }
        .register-link a { color: #00c8ff; text-decoration: none; }
    </style>
</head>
<body>
<div class="login-box">
    <h2>⚡ PisoNet Admin</h2>
    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <div class="register-link">Don't have an account? <a href="<?php echo e(route('register')); ?>">Register</a></div>
</div>
</body>
</html>
<?php /**PATH C:\xampp1\htdocs\CentralizedPisoNet\resources\views/auth/login.blade.php ENDPATH**/ ?>