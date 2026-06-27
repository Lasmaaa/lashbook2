<aside id="sidebar" data-testid="sidebar" class="w-full md:w-72 bg-[rgb(var(--card))] border-r h-full fixed md:relative overflow-y-auto" style="border-color: rgb(var(--border));">
    <div class="relative p-5 sm:p-6 min-h-full flex flex-col">
        <button id="sidebar-close" type="button" aria-label="Close menu" class="absolute top-5 right-5 inline-flex items-center justify-center h-10 w-10 rounded-xl border md:hidden" style="border-color: rgb(var(--border)); background: rgb(var(--card));">
            <span aria-hidden="true">×</span>
        </button>

        <div class="pr-10 md:pr-0">
            <span class="brand-badge mb-3">Studio</span>
            <h1 data-testid="brand-title" class="text-3xl font-bold font-display bg-clip-text text-transparent" style="background-image: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--accent)));">Lashbook2</h1>
            <p class="text-sm mt-2 text-muted leading-relaxed"><?php echo e(__('ui.professional_booking')); ?></p>
        </div>
        
        <nav class="mt-8 space-y-1.5 flex-1" aria-label="Main navigation">
            <?php if(auth()->user()->isAdmin()): ?>
                <a href="<?php echo e(route('admin.action-panel')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.action-panel') ? 'active' : ''); ?>">
                    <span aria-hidden="true">📊</span> <span><?php echo e(__('ui.admin_action_panel')); ?></span>
                </a>
                <a href="<?php echo e(route('admin.users')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>">
                    <span aria-hidden="true">👥</span> <span><?php echo e(__('ui.admin_users')); ?></span>
                </a>
                <a href="<?php echo e(route('admin.bookings')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.bookings*') ? 'active' : ''); ?>">
                    <span aria-hidden="true">📅</span> <span><?php echo e(__('ui.admin_bookings')); ?></span>
                </a>
                <a href="<?php echo e(route('admin.procedures')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.procedures*') ? 'active' : ''); ?>">
                    <span aria-hidden="true">💅</span> <span><?php echo e(__('ui.admin_procedures_calendar')); ?></span>
                </a>
                <a href="<?php echo e(route('admin.loyalty')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.loyalty*') ? 'active' : ''); ?>">
                    <span aria-hidden="true">⭐</span> <span><?php echo e(__('ui.admin_loyalty_scan')); ?></span>
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('user.index')); ?>" class="nav-link <?php echo e(request()->routeIs('user.index') ? 'active' : ''); ?>">
                    <span aria-hidden="true">🏠</span> <span><?php echo e(__('ui.dashboard')); ?></span>
                </a>
                <a href="<?php echo e(route('calendar')); ?>" class="nav-link <?php echo e(request()->routeIs('calendar*') || request()->routeIs('book.*') ? 'active' : ''); ?>">
                    <span aria-hidden="true">📅</span> <span><?php echo e(__('ui.book_appointment')); ?></span>
                </a>
                <a href="<?php echo e(route('feedback.create')); ?>" class="nav-link <?php echo e(request()->routeIs('feedback.*') ? 'active' : ''); ?>">
                    <span aria-hidden="true">💬</span> <span><?php echo e(__('ui.reviews')); ?></span>
                </a>
                <a href="<?php echo e(route('loyalty')); ?>" class="nav-link <?php echo e(request()->routeIs('loyalty') ? 'active' : ''); ?>">
                    <span aria-hidden="true">⭐</span> <span><?php echo e(__('ui.loyalty_card')); ?></span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="mt-8 space-y-3 border-t pt-5" style="border-color: rgb(var(--border));">
            <form method="POST" action="<?php echo e(route('theme.toggle')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full px-4 py-3 theme-toggle-btn transition">
                    <?php echo e(session('theme', 'light') === 'dark' ? __('ui.light_mode') : __('ui.dark_mode')); ?>

                </button>
            </form>

            <form method="POST" action="<?php echo e(route('language.set')); ?>">
                <?php echo csrf_field(); ?>
                <p class="text-xs uppercase tracking-wide text-muted mb-2"><?php echo e(__('ui.language')); ?></p>
                <div class="grid grid-cols-3 gap-2">
                    <?php $__currentLoopData = ['lv' => 'LV', 'en' => 'EN', 'ru' => 'RU']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="submit" name="lang" value="<?php echo e($code); ?>" class="lang-btn px-3 py-2 rounded-xl text-sm font-semibold transition <?php echo e(app()->getLocale() === $code ? 'is-active' : ''); ?>"><?php echo e($label); ?></button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </form>

            <form method="POST" action="<?php echo e(route('logout')); ?>" class="pt-1">
                <?php echo csrf_field(); ?>
                <button type="submit" class="logout-btn w-full px-4 py-3 rounded-2xl transition text-left font-medium">
                    🚪 <?php echo e(__('ui.logout')); ?>

                </button>
            </form>
        </div>
    </div>
</aside>
<?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>