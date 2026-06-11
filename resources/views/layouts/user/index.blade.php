@extends('layouts.app')

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <h1 class="text-4xl font-semibold tracking-tight">Sveicināti, {{ auth()->user()->name }}!</h1>
    <p class="text-muted mt-2">Tavs personīgais skropstu pieaudzēšanas pierakstu panelis.</p>

    @if($nextBooking)
        <div class="mt-8 card p-6">
            <h3 class="text-lg font-semibold">Nākamais pieraksts</h3>
            <p class="text-3xl mt-2">{{ $nextBooking->date->format('d.m.Y') }} pl. {{ $nextBooking->time }}</p>
        </div>
    @else
        <div class="mt-8 card p-6">
            <h3 class="text-lg font-semibold">Nākamais pieraksts</h3>
            <p class="mt-2 text-muted">Pašlaik nav aktīvu pierakstu.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
        <a href="{{ route('calendar') }}" class="block text-white text-center py-16 rounded-3xl text-2xl font-semibold btn-primary shadow-lg transition">
            Pierakstīties uz procedūru
        </a>
        <a href="{{ route('loyalty') }}" class="block text-white text-center py-16 rounded-3xl text-2xl font-semibold shadow-lg transition" style="background: linear-gradient(135deg, rgb(var(--accent)), rgb(var(--primary)));">
            Loyalty Card un bonusi
        </a>
    </div>
</div>
@endsection