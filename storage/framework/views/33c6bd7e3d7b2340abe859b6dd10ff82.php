<button onclick="toggleTheme()" class="theme-toggle-btn px-3 py-2 rounded-xl">🌙 / ☀️</button>

<form id="language-form" method="POST" action="<?php echo e(route('language.set')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="lang" id="language-input" />
</form>

<select onchange="document.getElementById('language-input').value = this.value; document.getElementById('language-form').submit();">
    <option value="lv" <?php echo e(app()->getLocale() === 'lv' ? 'selected' : ''); ?>>LV</option>
    <option value="en" <?php echo e(app()->getLocale() === 'en' ? 'selected' : ''); ?>>EN</option>
    <option value="ru" <?php echo e(app()->getLocale() === 'ru' ? 'selected' : ''); ?>>RU</option>
</select><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/welcome.blade.php ENDPATH**/ ?>