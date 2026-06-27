<?php $__env->startSection('content'); ?>
<div class="p-8 max-w-2xl">
    <h1 class="text-3xl font-semibold text-heading"><?php echo e(__('ui.admin_loyalty_scan')); ?></h1>

    <div class="mt-6 flex gap-2">
        <button id="mode-code" type="button" class="px-4 py-2 rounded-xl btn-violet"><?php echo e(__('ui.code')); ?></button>
        <button id="mode-qr" type="button" class="px-4 py-2 rounded-xl btn-secondary"><?php echo e(__('ui.qr_code')); ?></button>
    </div>

    <form method="POST" action="<?php echo e(route('admin.loyalty.scan')); ?>" class="mt-6 card p-6">
        <?php echo csrf_field(); ?>
        <input type="hidden" id="source-input" name="source" value="code">
        <label class="form-label block mb-2"><?php echo e(__('ui.loyalty_code')); ?></label>
        <input id="code-input" type="text" name="code" class="w-full px-4 py-3 rounded-xl border" required>
        <div id="qr-reader" class="hidden mt-4"></div>
        <button class="mt-4 px-5 py-3 rounded-xl btn-violet" type="submit"><?php echo e(__('ui.save')); ?></button>
    </form>

    <form method="POST" action="<?php echo e(route('admin.loyalty.refresh')); ?>" class="mt-4 card p-6">
        <?php echo csrf_field(); ?>
        <label class="form-label block mb-2"><?php echo e(__('ui.refresh_loyalty_by_code')); ?></label>
        <input type="text" name="code" class="w-full px-4 py-3 rounded-xl border" required>
        <button class="mt-4 px-5 py-3 rounded-xl btn-accent" type="submit"><?php echo e(__('ui.refresh')); ?></button>
    </form>

    <div class="mt-6 card p-6">
        <h2 class="font-semibold mb-3 text-heading"><?php echo e(__('ui.latest_scans')); ?></h2>
        <div class="space-y-2 text-sm">
            <?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <p><?php echo e($log->created_at->format('d.m.Y H:i')); ?> - <?php echo e($log->user?->fullName()); ?> (<?php echo e($log->source); ?>)</p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted"><?php echo e(__('ui.no_records')); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    const modeCode = document.getElementById('mode-code');
    const modeQr = document.getElementById('mode-qr');
    const qrReader = document.getElementById('qr-reader');
    const sourceInput = document.getElementById('source-input');
    const codeInput = document.getElementById('code-input');
    let html5QrCode = null;

    function setMode(mode) {
        if (mode === 'qr') {
            sourceInput.value = 'qr';
            qrReader.classList.remove('hidden');
            modeQr.className = 'px-4 py-2 rounded-xl btn-violet';
            modeCode.className = 'px-4 py-2 rounded-xl btn-secondary';
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode('qr-reader');
                html5QrCode.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: 220 },
                    (decodedText) => { codeInput.value = decodedText; },
                    () => {}
                );
            }
            return;
        }

        sourceInput.value = 'code';
        qrReader.classList.add('hidden');
        modeCode.className = 'px-4 py-2 rounded-xl btn-violet';
        modeQr.className = 'px-4 py-2 rounded-xl btn-secondary';
    }

    modeCode.addEventListener('click', () => setMode('code'));
    modeQr.addEventListener('click', () => setMode('qr'));
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/admin/loyalty.blade.php ENDPATH**/ ?>