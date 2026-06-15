@extends('layouts.app')

@section('content')
<div class="p-8 max-w-2xl">
    <h1 class="text-3xl font-semibold">{{ __('ui.admin_loyalty_scan') }}</h1>

    <div class="mt-6 flex gap-2">
        <button id="mode-code" type="button" class="px-4 py-2 rounded-xl bg-violet-600 text-white">{{ __('ui.code') }}</button>
        <button id="mode-qr" type="button" class="px-4 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700">{{ __('ui.qr_code') }}</button>
    </div>

    <form method="POST" action="{{ route('admin.loyalty.scan') }}" class="mt-6 bg-[rgb(var(--card))] p-6 rounded-2xl">
        @csrf
        <input type="hidden" id="source-input" name="source" value="code">
        <label class="block mb-2">{{ __('ui.loyalty_code') }}</label>
        <input id="code-input" type="text" name="code" class="w-full px-4 py-3 rounded-xl border" required>
        <div id="qr-reader" class="hidden mt-4"></div>
        <button class="mt-4 px-5 py-3 rounded-xl bg-violet-600 text-white" type="submit">{{ __('ui.save') }}</button>
    </form>

    <form method="POST" action="{{ route('admin.loyalty.refresh') }}" class="mt-4 bg-[rgb(var(--card))] p-6 rounded-2xl">
        @csrf
        <label class="block mb-2">{{ __('ui.refresh_loyalty_by_code') }}</label>
        <input type="text" name="code" class="w-full px-4 py-3 rounded-xl border" required>
        <button class="mt-4 px-5 py-3 rounded-xl bg-rose-600 text-white" type="submit">{{ __('ui.refresh') }}</button>
    </form>

    <div class="mt-6 bg-[rgb(var(--card))] p-6 rounded-2xl">
        <h2 class="font-semibold mb-3">{{ __('ui.latest_scans') }}</h2>
        <div class="space-y-2 text-sm">
            @forelse($recentLogs as $log)
                <p>{{ $log->created_at->format('d.m.Y H:i') }} - {{ $log->user?->fullName() }} ({{ $log->source }})</p>
            @empty
                <p>{{ __('ui.no_records') }}</p>
            @endforelse
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
            modeQr.className = 'px-4 py-2 rounded-xl bg-violet-600 text-white';
            modeCode.className = 'px-4 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700';
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
        modeCode.className = 'px-4 py-2 rounded-xl bg-violet-600 text-white';
        modeQr.className = 'px-4 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700';
    }

    modeCode.addEventListener('click', () => setMode('code'));
    modeQr.addEventListener('click', () => setMode('qr'));
</script>
@endsection
