

<?php $__env->startSection('content'); ?>
<a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary mb-3">Back</a>
<div class="card mb-4">
    <?php if($post->image_path): ?>
        <img src="<?php echo e(asset('storage/' . $post->image_path)); ?>" class="card-img-top" style="max-height:300px;object-fit:cover;">
    <?php endif; ?>
    <div class="card-header">
        <?php echo e(ucfirst($post->type)); ?>: <?php echo e($post->item_name); ?>

    </div>
    <div class="card-body">
        <p><strong>Description:</strong> <?php echo e($post->description); ?></p>
        <p><strong>Category:</strong> <?php echo e($post->category); ?></p>
        <p><strong>Location:</strong> <?php echo e($post->location); ?></p>
        <p><strong>Contact Name:</strong> <?php echo e($post->contact_name); ?></p>
        <p><strong>Contact Info:</strong> <?php echo e($post->contact_info); ?></p>
        <p><strong>Status:</strong> <?php echo e(ucfirst($post->status)); ?></p>
        <p><strong>Reference Number:</strong> <?php echo e($post->reference_number); ?></p>
        <a href="<?php echo e(route('posts.index')); ?>" class="btn btn-secondary">Back to List</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">Report Inappropriate Post</div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('reports.store', $post->id)); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Your Name (optional)</label>
                <input type="text" name="reporter_name" class="form-control">
            </div>
            <div class="form-group">
                <label>Your Contact (optional)</label>
                <input type="text" name="reporter_contact" class="form-control">
            </div>
            <div class="form-group">
                <label>Reason</label>
                <textarea name="reason" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger">Report</button>
        </form>
    </div>
</div>

<?php if($post->status == 'open'): ?>
    <div class="card mb-4">
        <div class="card-header">Mark as Claimed or Returned</div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('claims.store', $post->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" name="claimer_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Your Contact</label>
                    <input type="text" name="claimer_contact" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Action</label>
                    <select name="claim_type" class="form-control" required>
                        <option value="claimed">Claimed</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Notes (optional)</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-csu">Submit</button>
            </form>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\csu-lost-and-found1\resources\views/posts/show.blade.php ENDPATH**/ ?>