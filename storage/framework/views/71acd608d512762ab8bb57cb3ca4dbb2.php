<?php $__env->startSection('content'); ?>
<div class="p-8 max-w-5xl mx-auto">
    <h1 class="text-3xl font-semibold"><?php echo e(__('ui.admin_users')); ?></h1>

    <form method="GET" class="mt-6">
        <input type="text" name="email" value="<?php echo e($search); ?>" placeholder="<?php echo e(__('ui.search_by_email')); ?>" class="w-full sm:max-w-sm px-4 py-3 rounded-xl border">
        <button type="submit" class="mt-3 px-4 py-3 rounded-xl btn-violet"><?php echo e(__('ui.filter')); ?></button>
    </form>

    <div class="mt-8 space-y-3">
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="font-medium"><?php echo e($user->fullName()); ?></p>
                    <p class="text-sm text-muted"><?php echo e($user->email); ?> · <?php echo e($user->phone); ?></p>
                    <p class="text-xs text-muted mt-1"><?php echo e(__('ui.registrations')); ?>: <?php echo e($user->created_at->format('d.m.Y H:i')); ?></p>
                </div>
                <form method="POST" action="<?php echo e(route('admin.users.role', $user)); ?>" class="flex gap-2 items-center">
                    <?php echo csrf_field(); ?>
                    <select name="usertype" class="px-3 py-2 rounded-xl border">
                        <option value="user" <?php if($user->usertype === 'user'): echo 'selected'; endif; ?>><?php echo e(__('ui.user')); ?></option>
                        <option value="admin" <?php if($user->usertype === 'admin'): echo 'selected'; endif; ?>><?php echo e(__('ui.admin')); ?></option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl btn-violet"><?php echo e(__('ui.change')); ?></button>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/admin/users.blade.php ENDPATH**/ ?>