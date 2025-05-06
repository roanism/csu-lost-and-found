

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="csu-header mb-4">Admin Login</h2>
        <form method="POST" action="<?php echo e(route('admin.login.submit')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <?php if($errors->any()): ?>
                <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-csu">Login</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\csu-lost-and-found1\resources\views/admin/login.blade.php ENDPATH**/ ?>