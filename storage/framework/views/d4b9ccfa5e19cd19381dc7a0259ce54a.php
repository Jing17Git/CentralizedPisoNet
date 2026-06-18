<?php $__env->startSection('title', 'Rates'); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <h3>Coin Rates & Pricing</h3>
    <form method="POST" action="<?php echo e(route('rates.update', 1)); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <label>₱1 Coin = (minutes)</label>
                <input type="number" name="coin_1_rate" value="<?php echo e($coin1Rate); ?>" min="0" step="0.1">
                <label>₱5 Coin = (minutes)</label>
                <input type="number" name="coin_5_rate" value="<?php echo e($coin5Rate); ?>" min="0" step="0.1">
                <label>₱10 Coin = (minutes)</label>
                <input type="number" name="coin_10_rate" value="<?php echo e($coin10Rate); ?>" min="0" step="0.1">
                <label>₱20 Coin = (minutes)</label>
                <input type="number" name="coin_20_rate" value="<?php echo e($coin20Rate); ?>" min="0" step="0.1">
            </div>
            <div>
                <label>Pricing Mode</label>
                <select name="pricing_mode">
                    <option value="minutes" <?php echo e($pricingMode === 'minutes' ? 'selected' : ''); ?>>Per Minute</option>
                    <option value="fixed" <?php echo e($pricingMode === 'fixed' ? 'selected' : ''); ?>>Fixed</option>
                </select>
                <label>Price Per Minute (₱)</label>
                <input type="number" name="price_per_minute" value="<?php echo e($pricePerMinute); ?>" min="0" step="0.01">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Rates</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\Centralized\CentralizedPisoNet\resources\views/admin/rates/index.blade.php ENDPATH**/ ?>