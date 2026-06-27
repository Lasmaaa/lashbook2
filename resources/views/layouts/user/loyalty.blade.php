@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-8">
    <div class="card p-10 text-center">
        <h2 class="text-2xl font-semibold mb-6">{{ __('ui.loyalty_card') }}</h2>
        <p class="text-sm text-muted mb-5">{{ __('ui.loyalty_card_desc') }}</p>

        @php($loyaltyCode = auth()->user()->loyalty_code ?? '')
        <div class="inline-block p-4 bg-white rounded-2xl">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($loyaltyCode !== '' ? $loyaltyCode : 'NO-CODE') !!}
        </div>

        <p class="font-mono text-2xl sm:text-3xl tracking-widest mt-6 break-all">{{ $loyaltyCode !== '' ? $loyaltyCode : 'NO-CODE' }}</p>

        <div class="mt-12 space-y-4">
            @for($row = 0; $row < 2; $row++)
                <div class="grid grid-cols-5 gap-3 sm:gap-4">
                    @for($col = 1; $col <= 5; $col++)
                        @php($i = $row * 5 + $col)
                        <div class="aspect-square rounded-full border-4 flex items-center justify-center text-xl sm:text-2xl
                            {{ $stamp->stamps >= $i ? 'bg-violet-600 border-violet-600 text-white' : 'border-[rgb(var(--border))]' }}">
                            {{ $stamp->stamps >= $i ? '✓' : '' }}
                        </div>
                    @endfor
                </div>
            @endfor
        </div>

        <p class="text-sm text-muted mt-6">{{ $stamp->stamps }}/10</p>
    </div>
</div>
@endsection
