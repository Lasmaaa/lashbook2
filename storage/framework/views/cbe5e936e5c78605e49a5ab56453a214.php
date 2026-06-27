<?php $__env->startSection('content'); ?>
<div class="max-w-lg mx-auto p-8">
    <div class="card p-10 text-center">
        <h2 class="text-2xl font-semibold mb-6"><?php echo e(__('ui.loyalty_card')); ?></h2>
        <p class="text-sm text-muted mb-5"><?php echo e(__('ui.loyalty_card_desc')); ?></p>

        <?php ($loyaltyCode = auth()->user()->loyalty_code ?? ''); ?>
        <div class="inline-block p-4 bg-white rounded-2xl">
            <?php echo \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($loyaltyCode !== '' ? $loyaltyCode : 'NO-CODE'); ?>

        </div>

        <p class="font-mono text-2xl sm:text-3xl tracking-widest mt-6 break-all"><?php echo e($loyaltyCode !== '' ? $loyaltyCode : 'NO-CODE'); ?></p>

        <div class="mt-12 space-y-4">
            <?php for($row = 0; $row < 2; $row++): ?>
                <div class="grid grid-cols-5 gap-3 sm:gap-4">
                    <?php for($col = 1; $col <= 5; $col++): ?>
                        <?php ($i = $row * 5 + $col); ?>
                        <div class="aspect-square rounded-full border-4 flex items-center justify-center text-xl sm:text-2xl
                            <?php echo e($stamp->stamps >= $i ? 'bg-violet-600 border-violet-600 text-white' : 'border-[rgb(var(--border))]'); ?>">
                            <?php echo e($stamp->stamps >= $i ? '✓' : ''); ?>

                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>

        <p class="text-sm text-muted mt-6"><?php echo e($stamp->stamps); ?>/10</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/layouts/user/loyalty.blade.php ENDPATH**/ ?>