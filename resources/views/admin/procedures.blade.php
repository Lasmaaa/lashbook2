@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-8 max-w-6xl mx-auto">
    <h1 class="text-3xl font-semibold">{{ __('ui.admin_procedures_calendar') }}</h1>
    <p class="text-muted mt-2">{{ __('ui.admin_procedures_desc') }}</p>

    @if(session('success'))
        <div class="mt-4 flash-success">{{ session('success') }}</div>
    @endif

    <div class="mt-8 card p-6">
        <div class="relative mb-4">
            <button id="prev-month" type="button" class="absolute left-0 top-1/2 -translate-y-1/2 w-10 h-10 rounded-xl border" style="border-color: rgb(var(--border));">←</button>
            <h2 id="month-label" class="text-xl font-semibold text-center"></h2>
            <button id="next-month" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 rounded-xl border" style="border-color: rgb(var(--border));">→</button>
        </div>
        <div class="grid grid-cols-7 gap-2 text-center text-xs uppercase text-muted mb-2">
            <div>P</div><div>O</div><div>T</div><div>C</div><div>Pk</div><div>S</div><div>Sv</div>
        </div>
        <div id="calendar-grid" class="grid grid-cols-7 gap-2"></div>
    </div>

    <div id="schedule-modal" class="hidden fixed inset-0 z-40">
        <div id="schedule-overlay" class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
            <div class="w-full max-w-3xl card p-6 my-8">
                <div class="flex items-start justify-between gap-4">
                    <h2 id="schedule-date-label" class="text-2xl font-semibold"></h2>
                    <button id="schedule-close" type="button" class="w-10 h-10 rounded-xl border" style="border-color: rgb(var(--border));">✕</button>
                </div>

                <form id="schedule-form" method="POST" class="mt-6 space-y-6">
                    @csrf
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold">{{ __('ui.procedures') }}</h3>
                            <button type="button" id="add-procedure" class="text-sm px-3 py-1 rounded-lg btn-violet">+ {{ __('ui.add') }}</button>
                        </div>
                        <div id="procedures-editor" class="space-y-3"></div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold">{{ __('ui.available_times') }}</h3>
                            <button type="button" id="add-time" class="text-sm px-3 py-1 rounded-lg btn-violet">+ {{ __('ui.add') }}</button>
                        </div>
                        <div id="times-editor" class="flex flex-wrap gap-2"></div>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                        <button type="submit" class="px-5 py-3 rounded-xl btn-primary">{{ __('ui.apply_date') }}</button>
                        <button type="button" id="apply-all-btn" class="px-5 py-3 rounded-xl border" style="border-color: rgb(var(--border));">{{ __('ui.apply_all') }}</button>
                    </div>
                </form>

                <div id="apply-all-modal" class="hidden mt-6 p-4 rounded-2xl panel-muted">
                    <h4 class="font-semibold mb-2">{{ __('ui.are_you_sure') }}</h4>
                    <p class="text-sm text-muted mb-4">{{ __('ui.apply_all_desc') }}</p>
                    <div id="apply-all-preview" class="text-sm space-y-2 mb-4"></div>
                    <form id="apply-all-form" method="POST" class="grid sm:grid-cols-2 gap-3">
                        @csrf
                        <input type="date" name="from_date" id="apply-from" class="rounded-xl border p-3" required>
                        <input type="date" name="to_date" id="apply-to" class="rounded-xl border p-3" required>
                        <button type="submit" class="sm:col-span-2 px-5 py-3 rounded-xl btn-accent">{{ __('ui.confirm_apply_all') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const datesWithSchedule = @json($datesWithSchedule->keys());
    const grid = document.getElementById('calendar-grid');
    const monthLabel = document.getElementById('month-label');
    const modal = document.getElementById('schedule-modal');
    const scheduleForm = document.getElementById('schedule-form');
    const proceduresEditor = document.getElementById('procedures-editor');
    const timesEditor = document.getElementById('times-editor');
    const applyAllModal = document.getElementById('apply-all-modal');
    const applyAllForm = document.getElementById('apply-all-form');
    let cursorMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    let selectedDate = null;

    function formatDate(d) {
        return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    }

    function procedureRow(data = {}) {
        const wrap = document.createElement('div');
        wrap.className = 'grid grid-cols-12 gap-2 items-center procedure-row';
        wrap.innerHTML = `
            <input class="col-span-3 rounded-xl border p-2 text-sm" data-field="name_lv" placeholder="LV" value="${data.name_lv || ''}">
            <input class="col-span-3 rounded-xl border p-2 text-sm" data-field="name_en" placeholder="EN" value="${data.name_en || ''}">
            <input class="col-span-3 rounded-xl border p-2 text-sm" data-field="name_ru" placeholder="RU" value="${data.name_ru || ''}">
            <input class="col-span-2 rounded-xl border p-2 text-sm" data-field="price" type="number" step="0.01" placeholder="€" value="${data.price ?? ''}">
            <button type="button" class="col-span-1 text-error remove-procedure">✕</button>`;
        wrap.querySelector('.remove-procedure').addEventListener('click', () => {
            wrap.remove();
            syncProcedureFieldNames();
        });
        return wrap;
    }

    function syncProcedureFieldNames() {
        proceduresEditor.querySelectorAll('.procedure-row').forEach((row, index) => {
            row.querySelectorAll('[data-field]').forEach((input) => {
                input.name = `procedures[${index}][${input.dataset.field}]`;
            });
        });
    }

    function readProceduresFromEditor() {
        return [...proceduresEditor.querySelectorAll('.procedure-row')].map((row) => ({
            name_lv: row.querySelector('[data-field="name_lv"]')?.value || '',
            name_en: row.querySelector('[data-field="name_en"]')?.value || '',
            name_ru: row.querySelector('[data-field="name_ru"]')?.value || '',
            price: row.querySelector('[data-field="price"]')?.value || '',
        }));
    }

    function appendProcedureFields(form, procedures, className = 'copied-field') {
        procedures.forEach((procedure, index) => {
            ['name_lv', 'name_en', 'name_ru', 'price'].forEach((field) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.className = className;
                input.name = `procedures[${index}][${field}]`;
                input.value = procedure[field] ?? '';
                form.appendChild(input);
            });
        });
    }

    function timeChip(value = '09:00') {
        const wrap = document.createElement('div');
        wrap.className = 'flex items-center gap-1';
        wrap.innerHTML = `<input type="time" name="times[]" value="${value}" class="rounded-xl border p-2"><button type="button" class="text-error remove-time">✕</button>`;
        wrap.querySelector('.remove-time').addEventListener('click', () => wrap.remove());
        return wrap;
    }

    async function openSchedule(date) {
        selectedDate = date;
        document.getElementById('schedule-date-label').textContent = date;
        scheduleForm.action = `/admin/procedures/${date}`;
        applyAllForm.action = `/admin/procedures/${date}/apply-all`;
        applyAllModal.classList.add('hidden');

        const res = await fetch(`/admin/procedures/${date}`, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        proceduresEditor.innerHTML = '';
        (data.procedures.length ? data.procedures : [{ name_lv: '', name_en: '', name_ru: '', price: '' }]).forEach(p => proceduresEditor.appendChild(procedureRow(p)));
        syncProcedureFieldNames();

        timesEditor.innerHTML = '';
        (data.times.length ? data.times : ['09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00']).forEach(t => timesEditor.appendChild(timeChip(t)));

        modal.classList.remove('hidden');
    }

    function renderCalendar() {
        grid.innerHTML = '';
        monthLabel.textContent = cursorMonth.toLocaleDateString('lv-LV', { month: 'long', year: 'numeric' });
        const firstDayRaw = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth(), 1).getDay();
        const firstDay = firstDayRaw === 0 ? 7 : firstDayRaw;
        const daysInMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth() + 1, 0).getDate();
        for (let i = 1; i < firstDay; i++) grid.insertAdjacentHTML('beforeend', '<div class="h-12"></div>');
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth(), day);
            const key = formatDate(date);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'h-12 rounded-xl border text-sm relative hover:bg-violet-100/70 dark:hover:bg-violet-900/40';
            btn.style.borderColor = 'rgb(var(--border))';
            btn.textContent = String(day);
            if (datesWithSchedule.includes(key)) {
                btn.insertAdjacentHTML('beforeend', '<span class="absolute bottom-1 right-1 w-2 h-2 rounded-full bg-violet-600"></span>');
            }
            btn.addEventListener('click', () => openSchedule(key));
            grid.appendChild(btn);
        }
    }

    document.getElementById('add-procedure')?.addEventListener('click', () => {
        proceduresEditor.appendChild(procedureRow());
        syncProcedureFieldNames();
    });
    document.getElementById('add-time')?.addEventListener('click', () => timesEditor.appendChild(timeChip()));
    document.getElementById('prev-month')?.addEventListener('click', () => { cursorMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth()-1, 1); renderCalendar(); });
    document.getElementById('next-month')?.addEventListener('click', () => { cursorMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth()+1, 1); renderCalendar(); });
    document.getElementById('schedule-close')?.addEventListener('click', () => modal.classList.add('hidden'));
    document.getElementById('schedule-overlay')?.addEventListener('click', () => modal.classList.add('hidden'));

    scheduleForm?.addEventListener('submit', () => syncProcedureFieldNames());

    document.getElementById('apply-all-btn')?.addEventListener('click', () => {
        const preview = document.getElementById('apply-all-preview');
        const procLines = readProceduresFromEditor().map((procedure) => {
            const label = procedure.name_lv || procedure.name_en || procedure.name_ru;
            return label ? `${label} — ${procedure.price || 0} EUR` : null;
        }).filter(Boolean);
        const timeLines = [...timesEditor.querySelectorAll('[name="times[]"]')].map(i => i.value);
        preview.innerHTML = `<p><strong>{{ __('ui.procedures') }}:</strong> ${procLines.join(', ') || '-'}</p><p><strong>{{ __('ui.available_times') }}:</strong> ${timeLines.join(', ') || '-'}</p>`;
        document.getElementById('apply-from').value = selectedDate;
        document.getElementById('apply-to').value = selectedDate;
        applyAllModal.classList.remove('hidden');
    });

    applyAllForm?.addEventListener('submit', () => {
        applyAllForm.querySelectorAll('.copied-field').forEach(el => el.remove());
        appendProcedureFields(applyAllForm, readProceduresFromEditor());
        timesEditor.querySelectorAll('[name="times[]"]').forEach(timeInput => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.className = 'copied-field';
            input.name = 'times[]';
            input.value = timeInput.value;
            applyAllForm.appendChild(input);
        });
    });

    renderCalendar();
});
</script>
@endsection
