<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-8 max-w-5xl mx-auto">
    <div class="page-header">
        <span class="brand-badge"><?php echo e(__('ui.book_appointment')); ?></span>
        <h1 class="mt-3 font-display"><?php echo e(__('ui.select_date')); ?></h1>
    </div>

    <div class="card p-6">
        <div class="relative mb-4">
            <button id="prev-month" type="button" class="absolute left-0 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-xl border" style="border-color: rgb(var(--border));">←</button>
            <h2 id="month-label" class="text-2xl font-semibold text-center mx-auto"></h2>
            <button id="next-month" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-xl border" style="border-color: rgb(var(--border));">→</button>
        </div>
        <div class="grid grid-cols-7 gap-2 text-center text-xs uppercase tracking-wider text-muted">
            <div><?php echo e(__('ui.week_mon')); ?></div><div><?php echo e(__('ui.week_tue')); ?></div><div><?php echo e(__('ui.week_wed')); ?></div><div><?php echo e(__('ui.week_thu')); ?></div><div><?php echo e(__('ui.week_fri')); ?></div><div><?php echo e(__('ui.week_sat')); ?></div><div><?php echo e(__('ui.week_sun')); ?></div>
        </div>
        <div id="calendar-grid" class="grid grid-cols-7 gap-2 mt-2"></div>
    </div>

    <p class="mt-8 text-center text-sm">
        <a href="<?php echo e(route('terms')); ?>" class="text-link underline"><?php echo e(__('ui.terms_conditions')); ?></a>
    </p>

    <div id="booking-modal" class="hidden fixed inset-0 z-40">
        <div id="booking-overlay" class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-2xl card p-6 sm:p-8 relative max-h-[90vh] overflow-auto">
                <button id="booking-close" type="button" class="absolute top-4 right-4 w-10 h-10 rounded-xl border" style="border-color: rgb(var(--border));">✕</button>
                <h2 id="selected-date" class="text-2xl font-semibold pr-10"></h2>

                <form method="POST" action="<?php echo e(route('book.store')); ?>" class="space-y-5 mt-4" id="booking-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="date" id="form-date">
                    <input type="hidden" name="procedure_ref" id="procedure-ref" required>

                    <div>
                        <label class="block mb-2 font-medium"><?php echo e(__('ui.client_name')); ?></label>
                        <input type="text" name="client_name" value="<?php echo e(old('client_name', auth()->user()->name)); ?>" class="w-full border rounded-2xl p-4" style="border-color: rgb(var(--border));" required>
                        <?php $__errorArgs = ['client_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-sm mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium"><?php echo e(__('ui.procedures')); ?></label>
                        <div id="procedures-table" class="rounded-2xl border overflow-hidden" style="border-color: rgb(var(--border));">
                            <div class="grid grid-cols-2 table-head text-sm font-semibold">
                                <div class="p-3"><?php echo e(__('ui.procedure')); ?></div>
                                <div class="p-3 text-right"><?php echo e(__('ui.price')); ?></div>
                            </div>
                            <div id="procedures-body" class="divide-y" style="border-color: rgb(var(--border));"></div>
                        </div>
                        <?php $__errorArgs = ['procedure_ref'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-sm mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium"><?php echo e(__('ui.available_times')); ?></label>
                        <select id="time-select" name="time" class="w-full border rounded-2xl p-4" style="border-color: rgb(var(--border));" required>
                            <option value=""><?php echo e(__('ui.choose_date_first')); ?></option>
                        </select>
                        <?php $__errorArgs = ['time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-sm mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block mb-2"><?php echo e(__('ui.details_optional')); ?></label>
                        <textarea name="details" class="w-full border rounded-3xl p-4" style="border-color: rgb(var(--border));" rows="4"><?php echo e(old('details')); ?></textarea>
                    </div>

                    <button type="submit" class="w-full btn-primary py-4 rounded-2xl text-lg font-semibold">
                        <?php echo e(__('ui.confirm_booking')); ?>

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
    const proceduresBody = document.getElementById('procedures-body');
    const procedureRef = document.getElementById('procedure-ref');
    const monthNames = [<?php echo json_encode(__('ui.month_1'), 15, 512) ?>,<?php echo json_encode(__('ui.month_2'), 15, 512) ?>,<?php echo json_encode(__('ui.month_3'), 15, 512) ?>,<?php echo json_encode(__('ui.month_4'), 15, 512) ?>,<?php echo json_encode(__('ui.month_5'), 15, 512) ?>,<?php echo json_encode(__('ui.month_6'), 15, 512) ?>,<?php echo json_encode(__('ui.month_7'), 15, 512) ?>,<?php echo json_encode(__('ui.month_8'), 15, 512) ?>,<?php echo json_encode(__('ui.month_9'), 15, 512) ?>,<?php echo json_encode(__('ui.month_10'), 15, 512) ?>,<?php echo json_encode(__('ui.month_11'), 15, 512) ?>,<?php echo json_encode(__('ui.month_12'), 15, 512) ?>];
    const selectedDateText = <?php echo json_encode(__('ui.selected_date'), 15, 512) ?>;
    const loadingText = <?php echo json_encode(__('ui.loading'), 15, 512) ?>;
    const noTimesText = <?php echo json_encode(__('ui.no_times'), 15, 512) ?>;
    const chooseTimeText = <?php echo json_encode(__('ui.choose_time'), 15, 512) ?>;
    const timesLoadErrorText = <?php echo json_encode(__('ui.times_load_error'), 15, 512) ?>;
    const noProceduresText = <?php echo json_encode(__('ui.no_procedures'), 15, 512) ?>;
    let cursorMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1);

    function formatDate(value) {
        return `${value.getFullYear()}-${String(value.getMonth()+1).padStart(2,'0')}-${String(value.getDate()).padStart(2,'0')}`;
    }

    function formatDateLv(value) {
        return `${String(value.getDate()).padStart(2,'0')}.${String(value.getMonth()+1).padStart(2,'0')}.${value.getFullYear()}`;
    }

    function renderProcedures(procedures) {
        proceduresBody.innerHTML = '';
        procedureRef.value = '';

        if (!procedures.length) {
            proceduresBody.innerHTML = `<p class="p-4 text-sm text-muted">${noProceduresText}</p>`;
            return;
        }

        procedures.forEach((proc, index) => {
            const row = document.createElement('button');
            row.type = 'button';
            row.className = 'procedure-row w-full grid grid-cols-2 text-left p-3 transition';
            row.dataset.ref = proc.ref;
            row.innerHTML = `<span>${proc.name}</span><span class="text-right font-semibold">${proc.price} EUR</span>`;
            row.addEventListener('click', () => {
                document.querySelectorAll('.procedure-row').forEach(r => r.classList.remove('is-selected'));
                row.classList.add('is-selected');
                procedureRef.value = proc.ref;
            });
            proceduresBody.appendChild(row);
            if (index === 0) row.click();
        });
    }

    async function selectDate(dateString) {
        const [year, month, day] = dateString.split('-').map(Number);
        const date = new Date(year, month - 1, day);
        try {
            timeSelect.innerHTML = `<option value="">${loadingText}</option>`;
            const response = await fetch(`<?php echo e(route('calendar.schedule')); ?>?date=${dateString}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error('Failed');
            const data = await response.json();

            if (!data.procedures.length) {
                alert(noProceduresText);
                return;
            }

            selectedDateLabel.textContent = `${selectedDateText}: ${formatDateLv(date)}`;
            formDate.value = dateString;
            renderProcedures(data.procedures);

            timeSelect.innerHTML = '';
            if (!data.available_times.length) {
                timeSelect.insertAdjacentHTML('beforeend', `<option value="">${noTimesText}</option>`);
                timeSelect.disabled = true;
            } else {
                timeSelect.disabled = false;
                timeSelect.insertAdjacentHTML('beforeend', `<option value="">${chooseTimeText}</option>`);
                data.available_times.forEach(time => {
                    timeSelect.insertAdjacentHTML('beforeend', `<option value="${time}">${time}</option>`);
                });
            }

            bookingModal.classList.remove('hidden');
        } catch (e) {
            alert(timesLoadErrorText);
        }
    }

    function renderCalendar() {
        grid.innerHTML = '';
        monthLabel.textContent = `${monthNames[cursorMonth.getMonth()]} ${cursorMonth.getFullYear()}`;
        const firstDayRaw = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth(), 1).getDay();
        const firstDay = firstDayRaw === 0 ? 7 : firstDayRaw;
        const daysInMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth() + 1, 0).getDate();
        const today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        for (let i = 1; i < firstDay; i++) grid.insertAdjacentHTML('beforeend', '<div class="h-12"></div>');
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth(), day);
            const disabled = date < today;
            const formatted = formatDate(date);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `h-12 rounded-xl border text-sm calendar-day ${disabled ? 'opacity-40 cursor-not-allowed' : ''}`;
            btn.style.borderColor = 'rgb(var(--border))';
            btn.textContent = String(day);
            btn.disabled = disabled;
            if (!disabled) btn.addEventListener('click', () => selectDate(formatted));
            grid.appendChild(btn);
        }
    }

    document.getElementById('prev-month')?.addEventListener('click', () => { cursorMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth()-1, 1); renderCalendar(); });
    document.getElementById('next-month')?.addEventListener('click', () => { cursorMonth = new Date(cursorMonth.getFullYear(), cursorMonth.getMonth()+1, 1); renderCalendar(); });
    bookingOverlay?.addEventListener('click', () => bookingModal.classList.add('hidden'));
    bookingClose?.addEventListener('click', () => bookingModal.classList.add('hidden'));
    renderCalendar();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lasma/Desktop/programming/priv/lashbook/resources/views/layouts/user/calendar.blade.php ENDPATH**/ ?>