@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-8">
    <div class="card p-10 text-center">
        <h2 class="text-2xl font-semibold mb-6">Tava Loyalty Card</h2>
        <p class="text-sm text-muted mb-5">Krāj punktus katrā apmeklējumā un saņem bonusu procedūras.</p>

        @php($loyaltyCode = auth()->user()->loyalty_code ?? '')
        <div class="inline-block p-4 bg-white rounded-2xl">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($loyaltyCode !== '' ? $loyaltyCode : 'NO-CODE') !!}
        </div>

        <p class="font-mono text-3xl tracking-widest mt-6 break-all">{{ $loyaltyCode !== '' ? $loyaltyCode : 'NO-CODE' }}</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mt-12">
            @for($i = 1; $i <= 10; $i++)
                <div class="aspect-square rounded-2xl border-4 flex items-center justify-center text-4xl
                    {{ $stamp->stamps >= $i ? 'bg-violet-600 border-violet-600 text-white' : 'border-zinc-300 dark:border-zinc-700' }}">
                    {{ $stamp->stamps >= $i ? '✓' : '' }}
                </div>
            @endfor
        </div>

        @if($stamp->stamps >= 10)
            <a href="{{ route('feedback.create') }}" class="inline-block mt-8 px-6 py-3 rounded-2xl btn-primary">
                Pievienot atsauksmi (foto + komentārs)
            </a>
        @endif
    </div>
</div>
@endsection