<?php $__env->startSection('content'); ?>
<div class="p-8 space-y-6 max-w-5xl mx-auto">
    <h1 class="text-3xl font-semibold"><?php echo e(__('ui.admin_action_panel')); ?></h1>

    <form method="GET" class="card p-4 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm text-muted mb-1"><?php echo e(__('ui.filter_category')); ?></label>
            <select name="category" class="rounded-xl border p-2">
                <option value="all" <?php if($category === 'all'): echo 'selected'; endif; ?>><?php echo e(__('ui.all')); ?></option>
                <option value="registration" <?php if($category === 'registration'): echo 'selected'; endif; ?>><?php echo e(__('ui.registrations')); ?></option>
                <option value="booking" <?php if($category === 'booking'): echo 'selected'; endif; ?>><?php echo e(__('ui.bookings')); ?></option>
                <option value="loyalty" <?php if($category === 'loyalty'): echo 'selected'; endif; ?>><?php echo e(__('ui.loyalty_scans_codes')); ?></option>
                <option value="feedback" <?php if($category === 'feedback'): echo 'selected'; endif; ?>><?php echo e(__('ui.reviews')); ?></option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-muted mb-1"><?php echo e(__('ui.filter_sort')); ?></label>
            <select name="sort" class="rounded-xl border p-2">
                <option value="newest" <?php if($sort === 'newest'): echo 'selected'; endif; ?>><?php echo e(__('ui.newest_first')); ?></option>
                <option value="oldest" <?php if($sort === 'oldest'): echo 'selected'; endif; ?>><?php echo e(__('ui.oldest_first')); ?></option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 rounded-xl btn-violet"><?php echo e(__('ui.filter')); ?></button>
    </form>

    <div class="card p-6">
        <div class="space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-start gap-3 p-3 rounded-xl border" style="border-color: rgb(var(--border));">
                    <span class="text-xs uppercase tracking-wide px-2 py-1 rounded-lg event-badge shrink-0">
                        <?php echo e(__('ui.event_type_' . $event['type'])); ?>

                    </span>
                    <div class="min-w-0">
                        <p class="text-sm"><?php echo e($event['label']); ?></p>
                        <p class="text-xs text-muted mt-1"><?php echo e($event['at']->format('d.m.Y H:i')); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted"><?php echo e(__('ui.no_data')); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/admin/action-panel.blade.php ENDPATH**/ ?>