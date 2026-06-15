@extends('layouts.app')

@section('content')
<div class="p-8 max-w-5xl mx-auto">
    <h1 class="text-4xl font-bold mb-8">{{ __('ui.select_date') }}</h1>

    <div class="card p-6">
        <div class="relative mb-4">
            <button id="prev-month" type="button" aria-label="Previous month" class="absolute left-0 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl border text-lg" style="border-color: rgb(var(--border));">←</button>
            <h2 id="month-label" class="text-2xl font-semibold text-center mx-auto"></h2>
            <button id="next-month" type="button" aria-label="Next month" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl border text-lg" style="border-color: rgb(var(--border));">→</button>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2 text-center text-xs uppercase tracking-wider text-muted">
            <div>{{ __('ui.week_mon') }}</div><div>{{ __('ui.week_tue') }}</div><div>{{ __('ui.week_wed') }}</div><div>{{ __('ui.week_thu') }}</div><div>{{ __('ui.week_fri') }}</div><div>{{ __('ui.week_sat') }}</div><div>{{ __('ui.week_sun') }}</div>
        </div>
        <div id="calendar-grid" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2 mt-2"></div>
    </div>

    <div id="booking-modal" class="hidden fixed inset-0 z-40">
        <div id="booking-overlay" class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl card p-8 relative max-h-[90vh] overflow-auto modal-text">
                <button id="booking-close" type="button" class="absolute top-4 right-4 w-10 h-10 rounded-xl border" style="border-color: rgb(var(--border));">✕</button>
                <h2 id="selected-date" class="text-2xl font-semibold"></h2>
                <form method="POST" action="{{ route('book.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="date" id="form-date">

                    <div>
                        <label class="block mt-6 mb-2 font-medium modal-text">{{ __('ui.procedures') }}</label>
                        <select id="procedure-select" name="procedure_id[]" multiple class="w-full border rounded-2xl p-4 min-h-40 modal-text" style="border-color: rgb(var(--border));">
                            @forelse($procedures as $proc)
                                <option value="{{ $proc->id }}" data-name="{{ mb_strtolower($proc->name_lv) }}" data-code="{{ $proc->code }}">
                                    {{ $proc->getName() }} ({{ $proc->duration }} min)
                                </option>
                            @empty
                                <option value="" disabled>Nav pievienotu procedūru</option>
                            @endforelse
                        </select>
                        <p class="text-xs text-muted mt-2">Ctrl/Command + click, lai izvēlētos vairākas procedūras.</p>
                        @error('procedure_id')
                            <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div id="volume-option-wrapper" class="hidden mt-4">
                            <label class="block mb-2 font-medium modal-text">Izvēlies apjoma veidu</label>
                            <select id="volume-option-select" name="volume_option" class="w-full border rounded-2xl p-4 modal-text" style="border-color: rgb(var(--border));">
                                <option value="">Izvēlies apjomu</option>
                                @foreach($volumeOptions as $option)
                                    <option value="{{ $option->id }}" data-price="{{ $option->price }}">
                                        {{ $option->getName() }} ({{ number_format($option->price, 2) }} EUR)
                                    </option>
                                @endforeach
                            </select>
                            @error('volume_option')
                                <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 rounded-3xl border border-zinc-200 dark:border-zinc-700 p-4 bg-[rgb(var(--card))]">
                            <h3 class="font-semibold mb-3">Procedūru cenrādis</h3>
                            <div class="space-y-3 text-sm">
                                @foreach($procedures as $procedure)
                                    @if($procedure->code !== 'volume')
                                        <div class="flex items-center justify-between rounded-2xl border p-3" style="border-color: rgb(var(--border));">
                                            <span>{{ $procedure->getName() }}</span>
                                            <span class="font-semibold">{{ number_format($procedure->price, 2) }} EUR</span>
                                        </div>
                                    @else
                                        <div class="rounded-2xl border p-3" style="border-color: rgb(var(--border));">
                                            <div class="font-semibold">{{ $procedure->getName() }}</div>
                                            <div class="mt-2 space-y-2">
                                                @foreach($volumeOptions as $volumeOption)
                                                    <div class="flex items-center justify-between rounded-xl bg-zinc-50 dark:bg-zinc-900 p-3" style="border-color: rgb(var(--border));">
                                                        <span>{{ $volumeOption->getName() }}</span>
                                                        <span class="font-semibold">{{ number_format($volumeOption->price, 2) }} EUR</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mt-6 mb-2 font-medium modal-text">{{ __('ui.available_times') }}</label>
                        <select id="time-select" name="time" class="w-full border rounded-2xl p-4 modal-text" style="border-color: rgb(var(--border));" required>
                            <option value="">{{ __('ui.choose_date_first') }}</option>
                        </select>
                        @error('time')
                            <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="block mt-6">{{ __('ui.details_optional') }}</label>
                    <textarea name="details" class="w-full border rounded-3xl p-4" style="border-color: rgb(var(--border));" rows="4"></textarea>

                    <button type="submit" class="mt-8 w-full btn-primary py-5 rounded-2xl text-xl">
                        {{ __('ui.confirm_booking') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const grid = document.getElementById('calendar-grid');
        const monthLabel = document.getElementById('month-label');
        const selectedDateLabel = document.getElementById('selected-date');
        const formDate = document.getElementById('form-date');
        const bookingModal = document.getElementById('booking-modal');
        const bookingOverlay = document.getElementById('booking-overlay');
        const bookingClose = document.getElementById('booking-close');
        const timeSelect = document.getElementById('time-select');
        const prevMonthBtn = document.getElementById('prev-month');
        const nextMonthBtn = document.getElementById('next-month');
        const monthNames = [
            @json(__('ui.month_1')),
            @json(__('ui.month_2')),
            @json(__('ui.month_3')),
            @json(__('ui.month_4')),
            @json(__('ui.month_5')),
            @json(__('ui.month_6')),
            @json(__('ui.month_7')),
            @json(__('ui.month_8')),
            @json(__('ui.month_9')),
            @json(__('ui.month_10')),
            @json(__('ui.month_11')),
            @json(__('ui.month_12'))
        ];
        const selectedDateText = @json(__('ui.selected_date'));
        const loadingText = @json(__('ui.loading'));
        const noTimesText = @json(__('ui.no_times'));
        const chooseTimeText = @json(__('ui.choose_time'));
        const timesLoadErrorText = @json(__('ui.times_load_error'));
        const cannotMixText = @json(__('ui.cannot_mix_volume_classic'));
        let cursorMonth = new Date();
        cursorMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth(), 1);

        function formatDate(value) {
            const yyyy = value.getFullYear();
            const mm = String(value.getMonth() + 1).padStart(2, '0');
            const dd = String(value.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }

        function formatDateLv(value) {
            const dd = String(value.getDate()).padStart(2, '0');
            const mm = String(value.getMonth() + 1).padStart(2, '0');
            const yyyy = value.getFullYear();
            return `${dd}.${mm}.${yyyy}`;
        }

        async function selectDate(dateString) {
            const [year, month, day] = dateString.split('-').map(Number);
            const date = new Date(year, month - 1, day);
            const formatted = formatDate(date);

            try {
                timeSelect.innerHTML = `<option value="">${loadingText}</option>`;
                const response = await fetch(`{{ route('calendar.available-times') }}?date=${formatted}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Failed to load times');
                }

                const data = await response.json();
                timeSelect.innerHTML = '';
                if (!data.available_times.length) {
                    bookingModal.classList.add('hidden');
                    alert(noTimesText);
                    return;
                }

                selectedDateLabel.textContent = `${selectedDateText}: ${formatDateLv(date)}`;
                formDate.value = formatted;
                timeSelect.insertAdjacentHTML('beforeend', `<option value="">${chooseTimeText}</option>`);
                data.available_times.forEach(time => {
                    timeSelect.insertAdjacentHTML('beforeend', `<option value="${time}">${time}</option>`);
                });
                bookingModal.classList.remove('hidden');
            } catch (error) {
                bookingModal.classList.add('hidden');
                alert(timesLoadErrorText);
            }
        }

        function renderCalendar() {
            grid.innerHTML = '';
            monthLabel.textContent = `${monthNames[cursorMonth.getMonth()]} ${cursorMonth.getFullYear()}`;

            const firstDayRaw = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth(), 1).getDay();
            const firstDay = firstDayRaw === 0 ? 7 : firstDayRaw;
            const daysInMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth() + 1, 0).getDate();
            const today = new Date();
            const todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

            for (let i = 1; i < firstDay; i++) {
                grid.insertAdjacentHTML('beforeend', '<div class="h-12"></div>');
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth(), day);
                const disabled = date < todayDate;
                const formatted = formatDate(date);
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.dataset.date = formatted;
                btn.className = `h-12 rounded-xl border text-sm calendar-day ${disabled ? 'opacity-40 cursor-not-allowed border-zinc-300 dark:border-zinc-700' : 'border-zinc-300 dark:border-zinc-700'}`;
                btn.textContent = String(day);
                btn.disabled = disabled;
                if (!disabled) {
                    btn.addEventListener('click', () => selectDate(formatted));
                }
                grid.appendChild(btn);
            }
        }

        const procedureSelect = document.getElementById('procedure-select');
        const volumeOptionWrapper = document.getElementById('volume-option-wrapper');
        const volumeOptionSelect = document.getElementById('volume-option-select');

        const updateVolumeOptionVisibility = () => {
            const selectedOptions = [...procedureSelect.selectedOptions];
            const selectedCodes = selectedOptions.map(item => item.dataset.code);
            const showVolumeOptions = selectedCodes.includes('volume');

            if (volumeOptionWrapper) {
                volumeOptionWrapper.classList.toggle('hidden', !showVolumeOptions);
            }

            if (volumeOptionSelect) {
                volumeOptionSelect.required = showVolumeOptions;
            }
        };

        procedureSelect?.addEventListener('change', () => {
            const selectedOptions = [...procedureSelect.selectedOptions];
            const names = selectedOptions.map(item => item.dataset.name);
            if (names.includes('apjoma pieaudzējums') && names.includes('klasiskais pieaudzējums')) {
                const lastSelected = selectedOptions[selectedOptions.length - 1];
                if (lastSelected) {
                    lastSelected.selected = false;
                }
                alert(cannotMixText);
            }
            updateVolumeOptionVisibility();
        });

        updateVolumeOptionVisibility();

        prevMonthBtn?.addEventListener('click', () => {
            cursorMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth() - 1, 1);
            renderCalendar();
        });
        nextMonthBtn?.addEventListener('click', () => {
            cursorMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth() + 1, 1);
            renderCalendar();
        });
        bookingOverlay?.addEventListener('click', () => bookingModal.classList.add('hidden'));
        bookingClose?.addEventListener('click', () => bookingModal.classList.add('hidden'));

        renderCalendar();
    });
</script>
@endsection