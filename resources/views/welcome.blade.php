<button onclick="toggleTheme()" class="theme-toggle-btn px-3 py-2 rounded-xl">🌙 / ☀️</button>

<form id="language-form" method="POST" action="{{ route('language.set') }}">
    @csrf
    <input type="hidden" name="lang" id="language-input" />
</form>

<select onchange="document.getElementById('language-input').value = this.value; document.getElementById('language-form').submit();">
    <option value="lv" {{ app()->getLocale() === 'lv' ? 'selected' : '' }}>LV</option>
    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>EN</option>
    <option value="ru" {{ app()->getLocale() === 'ru' ? 'selected' : '' }}>RU</option>
</select>