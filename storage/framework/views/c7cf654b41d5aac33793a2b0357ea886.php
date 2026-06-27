<?php $__env->startSection('content'); ?>
<div class="p-8">
    <h1 class="text-3xl font-semibold tracking-tight"><?php echo e(__('ui.admin_panel')); ?></h1>
    <p class="text-muted mt-2"><?php echo e(__('ui.admin_overview')); ?></p>

    <div class="mt-8 card p-6">
        <h2 class="text-xl font-medium mb-4"><?php echo e(__('ui.calendar')); ?></h2>
        <div class="grid grid-cols-7 gap-2 text-center text-xs uppercase tracking-wider text-muted mb-2">
            <div>Pir</div><div>Otr</div><div>Tre</div><div>Cet</div><div>Pie</div><div>Ses</div><div>Sve</div>
        </div>
        <div class="grid grid-cols-7 gap-2">
            <?php for($day = 0; $day < 60; $day++): ?>
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

    <div class="mt-8 card p-6">
        <h2 class="text-xl font-medium mb-4">Procedūru cenu tabula</h2>
        <?php if(session('success')): ?>
            <div class="mb-4 rounded-2xl bg-emerald-100 p-4 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(route('admin.procedures.update')); ?>">
            <?php echo csrf_field(); ?>
            <div class="grid gap-4">
                <div class="grid grid-cols-12 gap-3 text-xs uppercase tracking-wide text-muted">
                    <div class="col-span-2">Code</div>
                    <div class="col-span-3">LV nosaukums</div>
                    <div class="col-span-3">EN nosaukums</div>
                    <div class="col-span-3">RU nosaukums</div>
                    <div class="col-span-1 text-right">Cena</div>
                </div>
                <?php $__currentLoopData = $procedures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $procedure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="grid grid-cols-12 gap-3 items-center rounded-2xl border p-3" style="border-color: rgb(var(--border));">
                        <div class="col-span-2 text-sm font-semibold"><?php echo e($procedure->code); ?></div>
                        <div class="col-span-3">
                            <input type="text" name="procedures[<?php echo e($procedure->id); ?>][name_lv]" value="<?php echo e($procedure->name_lv); ?>" class="w-full rounded-2xl border p-3" style="border-color: rgb(var(--border));">
                        </div>
                        <div class="col-span-3">
                            <input type="text" name="procedures[<?php echo e($procedure->id); ?>][name_en]" value="<?php echo e($procedure->name_en); ?>" class="w-full rounded-2xl border p-3" style="border-color: rgb(var(--border));">
                        </div>
                        <div class="col-span-3">
                            <input type="text" name="procedures[<?php echo e($procedure->id); ?>][name_ru]" value="<?php echo e($procedure->name_ru); ?>" class="w-full rounded-2xl border p-3" style="border-color: rgb(var(--border));">
                        </div>
                        <div class="col-span-1">
                            <input type="number" step="0.01" name="procedures[<?php echo e($procedure->id); ?>][price]" value="<?php echo e($procedure->price); ?>" class="w-full rounded-2xl border p-3 text-right" style="border-color: rgb(var(--border));">
                        </div>
                        <input type="hidden" name="procedures[<?php echo e($procedure->id); ?>][code]" value="<?php echo e($procedure->code); ?>">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <button type="submit" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-[rgb(var(--primary))] px-6 py-3 text-white shadow-lg transition hover:brightness-110">
                Saglabāt procedūru cenas
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/admin/index.blade.php ENDPATH**/ ?>