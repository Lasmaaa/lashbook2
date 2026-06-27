<?php $__env->startSection('content'); ?>
<div class="p-8 max-w-3xl mx-auto">
    <h1 class="text-3xl font-semibold"><?php echo e(__('ui.reviews')); ?></h1>
    <p class="text-muted mt-2"><?php echo e(__('ui.reviews_desc')); ?></p>

    <form method="POST" action="<?php echo e(route('feedback.store')); ?>" enctype="multipart/form-data" class="mt-8 card p-6 space-y-4">
        <?php echo csrf_field(); ?>
        <div>
            <label class="block mb-2"><?php echo e(__('ui.rating')); ?> (1-5)</label>
            <input type="number" min="1" max="5" name="rating" value="<?php echo e(old('rating', 5)); ?>" class="w-full px-4 py-3 rounded-xl border" required>
            <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-sm mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label class="block mb-2"><?php echo e(__('ui.comment')); ?></label>
            <textarea name="comment" rows="5" class="w-full px-4 py-3 rounded-xl border"><?php echo e(old('comment')); ?></textarea>
        </div>
        <div>
            <label class="block mb-2"><?php echo e(__('ui.photo_optional')); ?></label>
            <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 rounded-xl border">
        </div>
        <button class="px-5 py-3 rounded-xl btn-primary w-full sm:w-auto" type="submit"><?php echo e(__('ui.save')); ?></button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/layouts/user/feedback-create.blade.php ENDPATH**/ ?>