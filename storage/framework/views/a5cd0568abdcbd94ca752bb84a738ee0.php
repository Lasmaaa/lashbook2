<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="<?php echo e(session('theme', 'light')); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lashbook2 — pieraksti, lojalitātes karte un klientu panelis.">
    <title><?php echo e(__('ui.login')); ?> · Lashbook2</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/auth.css']); ?>
</head>
<body data-testid="app-shell">
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/layouts/auth.blade.php ENDPATH**/ ?>