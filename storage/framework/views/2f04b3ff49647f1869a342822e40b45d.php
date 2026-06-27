<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto prose-headings:text-[rgb(var(--text))] prose-p:text-[rgb(var(--text))]">
    <h1 class="text-heading"><?php echo e(__('ui.terms_conditions')); ?></h1>
    <p class="text-muted"><?php echo e(__('ui.terms_intro')); ?></p>
    <h2 class="text-heading"><?php echo e(__('ui.terms_booking_title')); ?></h2>
    <p><?php echo e(__('ui.terms_booking_text')); ?></p>
    <h2 class="text-heading"><?php echo e(__('ui.terms_cancel_title')); ?></h2>
    <p><?php echo e(__('ui.terms_cancel_text')); ?></p>
    <h2 class="text-heading"><?php echo e(__('ui.terms_loyalty_title')); ?></h2>
    <p><?php echo e(__('ui.terms_loyalty_text')); ?></p>
    <p class="text-sm text-muted"><?php echo e(__('ui.terms_updated')); ?></p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/pages/terms.blade.php ENDPATH**/ ?>