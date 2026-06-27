<?php $__env->startSection('content'); ?>
<div class="p-8">
    <h1 class="text-3xl font-semibold tracking-tight"><?php echo e(__('ui.admin_bookings')); ?></h1>
    <p class="text-muted mt-2"><?php echo e(__('ui.admin_bookings_desc')); ?></p>

    <div class="mt-8 card p-6">
        <div class="grid grid-cols-7 gap-2 text-center text-xs uppercase tracking-wider text-muted mb-2">
            <div><?php echo e(__('ui.week_mon')); ?></div><div><?php echo e(__('ui.week_tue')); ?></div><div><?php echo e(__('ui.week_wed')); ?></div><div><?php echo e(__('ui.week_thu')); ?></div><div><?php echo e(__('ui.week_fri')); ?></div><div><?php echo e(__('ui.week_sat')); ?></div><div><?php echo e(__('ui.week_sun')); ?></div>
        </div>
        <div class="grid grid-cols-7 gap-2">
            <?php for($day = 0; $day < 90; $day++): ?>
                <?php
                    $date = \Carbon\Carbon::today()->addDays($day);
                    $key = $date->toDateString();
                    $count = $bookingsByDate[$key] ?? 0;
                ?>
                <a href="<?php echo e(route('admin.bookings.date', $key)); ?>" class="h-16 p-2 rounded-xl border hover:bg-violet-100/70 dark:hover:bg-violet-900/40 relative transition" style="border-color: rgb(var(--border));">
                    <span class="text-sm"><?php echo e($date->format('d.m')); ?></span>
                    <?php if($count > 0): ?>
                        <span class="absolute bottom-2 right-2 w-2.5 h-2.5 rounded-full bg-violet-600"></span>
                    <?php endif; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/admin/bookings.blade.php ENDPATH**/ ?>