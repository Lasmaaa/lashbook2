<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <div class="page-header">
        <span class="brand-badge"><?php echo e(__('ui.dashboard')); ?></span>
        <h1 class="mt-3 font-display"><?php echo e(__('ui.professional_booking')); ?></h1>
        <p><?php echo e(__('ui.book_appointment_desc')); ?></p>
    </div>

    <section class="relative overflow-hidden rounded-[1.75rem] card" data-testid="home-carousel">
        <div id="carousel" class="relative h-56 sm:h-72 md:h-[22rem]">
            <?php $__currentLoopData = [
                ['img' => 'https://picsum.photos/seed/lashbook1/1400/700', 'title' => __('ui.slide_1_title'), 'text' => __('ui.slide_1_text')],
                ['img' => 'https://picsum.photos/seed/lashbook2/1400/700', 'title' => __('ui.slide_2_title'), 'text' => __('ui.slide_2_text')],
                ['img' => 'https://picsum.photos/seed/lashbook3/1400/700', 'title' => __('ui.slide_3_title'), 'text' => __('ui.slide_3_text')],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-slide absolute inset-0 transition-opacity duration-700 <?php echo e($index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'); ?>" data-index="<?php echo e($index); ?>">
                    <img src="<?php echo e($slide['img']); ?>" alt="<?php echo e($slide['title']); ?>" class="w-full h-full object-cover" loading="<?php echo e($index === 0 ? 'eager' : 'lazy'); ?>">
                    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(16,12,24,0.82)] via-[rgba(16,12,24,0.28)] to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8 text-white">
                        <h2 class="text-2xl sm:text-4xl font-display"><?php echo e($slide['title']); ?></h2>
                        <p class="mt-2 max-w-2xl text-sm sm:text-base text-white/85"><?php echo e($slide['text']); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <button type="button" id="carousel-prev" aria-label="Previous slide" class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 text-zinc-800 shadow-lg">←</button>
            <button type="button" id="carousel-next" aria-label="Next slide" class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 text-zinc-800 shadow-lg">→</button>
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                <?php for($i = 0; $i < 3; $i++): ?>
                    <button type="button" aria-label="Slide <?php echo e($i + 1); ?>" class="carousel-dot w-2.5 h-2.5 rounded-full transition <?php echo e($i === 0 ? 'bg-white scale-110' : 'bg-white/45'); ?>" data-index="<?php echo e($i); ?>"></button>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <section class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4" aria-label="Highlights">
        <?php $__currentLoopData = [
            ['title' => __('ui.slide_1_title'), 'text' => __('ui.slide_1_text')],
            ['title' => __('ui.slide_2_title'), 'text' => __('ui.slide_2_text')],
            ['title' => __('ui.slide_3_title'), 'text' => __('ui.slide_3_text')],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $panel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="carousel-panel card p-5 transition-all duration-300 <?php echo e($index === 0 ? 'is-active' : ''); ?>" data-index="<?php echo e($index); ?>">
                <h3 class="text-lg font-display font-semibold"><?php echo e($panel['title']); ?></h3>
                <p class="text-muted mt-2 text-sm leading-relaxed"><?php echo e($panel['text']); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>

    <section class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5" data-testid="home-actions">
        <a href="<?php echo e(route('calendar')); ?>" class="action-card card group">
            <div class="text-4xl mb-4" aria-hidden="true">📅</div>
            <h3 class="text-xl font-display font-semibold"><?php echo e(__('ui.book_appointment')); ?></h3>
            <p class="text-muted text-sm mt-2"><?php echo e(__('ui.book_appointment_desc')); ?></p>
        </a>
        <a href="<?php echo e(route('feedback.create')); ?>" class="action-card card group">
            <div class="text-4xl mb-4" aria-hidden="true">💬</div>
            <h3 class="text-xl font-display font-semibold"><?php echo e(__('ui.reviews')); ?></h3>
            <p class="text-muted text-sm mt-2"><?php echo e(__('ui.reviews_desc')); ?></p>
        </a>
        <a href="<?php echo e(route('loyalty')); ?>" class="action-card card group sm:col-span-2 lg:col-span-1">
            <div class="text-4xl mb-4" aria-hidden="true">⭐</div>
            <h3 class="text-xl font-display font-semibold"><?php echo e(__('ui.loyalty_card')); ?></h3>
            <p class="text-muted text-sm mt-2"><?php echo e(__('ui.loyalty_card_desc')); ?></p>
        </a>
    </section>

    <?php if($nextBooking ?? null): ?>
        <div class="mt-10 card p-6">
            <h3 class="text-lg font-display font-semibold"><?php echo e(__('ui.next_booking')); ?></h3>
            <p class="text-2xl sm:text-3xl mt-2 font-semibold"><?php echo e($nextBooking->date->format('d.m.Y')); ?> · <?php echo e(substr((string) $nextBooking->time, 0, 5)); ?></p>
            <p class="text-muted mt-1"><?php echo e($nextBooking->getProcedureName()); ?></p>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const slides = [...document.querySelectorAll('.carousel-slide')];
    const panels = [...document.querySelectorAll('.carousel-panel')];
    const dots = [...document.querySelectorAll('.carousel-dot')];
    let current = 0;
    let timer;

    function show(index) {
        current = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => {
            slide.classList.toggle('opacity-100', i === current);
            slide.classList.toggle('z-10', i === current);
            slide.classList.toggle('opacity-0', i !== current);
            slide.classList.toggle('z-0', i !== current);
        });
        panels.forEach((panel, i) => {
            panel.classList.toggle('is-active', i === current);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-white', i === current);
            dot.classList.toggle('scale-110', i === current);
            dot.classList.toggle('bg-white/45', i !== current);
        });
    }

    function next() { show(current + 1); }
    function prev() { show(current - 1); }

    document.getElementById('carousel-next')?.addEventListener('click', () => { next(); resetTimer(); });
    document.getElementById('carousel-prev')?.addEventListener('click', () => { prev(); resetTimer(); });
    dots.forEach(dot => dot.addEventListener('click', () => { show(Number(dot.dataset.index)); resetTimer(); }));

    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(next, 7000);
    }

    resetTimer();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/layouts/user/index.blade.php ENDPATH**/ ?>