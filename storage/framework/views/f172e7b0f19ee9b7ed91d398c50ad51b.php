<?php $__env->startSection('content'); ?>
<div class="p-8 max-w-4xl mx-auto">
    <a href="<?php echo e(route('admin.bookings')); ?>" class="text-sm text-link">← <?php echo e(__('ui.admin_bookings')); ?></a>
    <h1 class="text-3xl font-semibold mt-4"><?php echo e(__('ui.bookings_for_date')); ?>: <?php echo e(\Carbon\Carbon::parse($date)->format('d.m.Y')); ?></h1>

    <div class="mt-8 space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card p-5">
                <p class="text-lg font-semibold"><?php echo e(substr((string) $booking->time, 0, 5)); ?> — <?php echo e($booking->client_name ?: $booking->user?->fullName()); ?></p>
                <p class="text-muted mt-1"><?php echo e($booking->getProcedureName()); ?></p>
                <?php if($booking->details): ?>
                    <p class="text-sm mt-2"><?php echo e($booking->details); ?></p>
                <?php endif; ?>
                <p class="text-xs text-muted mt-2"><?php echo e($booking->user?->email); ?> · <?php echo e($booking->user?->phone); ?></p>
                <form method="POST" action="<?php echo e(route('admin.bookings.status', $booking)); ?>" class="mt-3 flex flex-wrap gap-2">
                    <?php echo csrf_field(); ?>
                    <select name="status" class="px-3 py-2 rounded-xl border">
                        <option value="pending" <?php if($booking->status === 'pending'): echo 'selected'; endif; ?>>Pending</option>
                        <option value="confirmed" <?php if($booking->status === 'confirmed'): echo 'selected'; endif; ?>>Confirmed</option>
                        <option value="arrived" <?php if($booking->status === 'arrived'): echo 'selected'; endif; ?>><?php echo e(__('ui.arrived')); ?></option>
                        <option value="no_show" <?php if($booking->status === 'no_show'): echo 'selected'; endif; ?>><?php echo e(__('ui.no_show')); ?></option>
                    </select>
                    <button class="px-4 py-2 rounded-xl btn-violet" type="submit"><?php echo e(__('ui.save')); ?></button>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted"><?php echo e(__('ui.no_bookings')); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/admin/bookings-date.blade.php ENDPATH**/ ?>