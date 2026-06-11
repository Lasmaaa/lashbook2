@extends('layouts.app')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-semibold tracking-tight">{{ __('ui.admin_panel') }}</h1>
    <p class="text-muted mt-2">{{ __('ui.admin_overview') }}</p>

    <div class="mt-8 card p-6">
        <h2 class="text-xl font-medium mb-4">{{ __('ui.calendar') }}</h2>
        <div class="grid grid-cols-7 gap-2 text-center text-xs uppercase tracking-wider text-muted mb-2">
            <div>Pir</div><div>Otr</div><div>Tre</div><div>Cet</div><div>Pie</div><div>Ses</div><div>Sve</div>
        </div>
        <div class="grid grid-cols-7 gap-2">
            @for($day = 0; $day < 60; $day++)
                @php
                    $date = \Carbon\Carbon::today()->addDays($day);
                    $key = $date->toDateString();
                    $count = $bookingsByDate[$key] ?? 0;
                @endphp
                <a href="{{ route('admin.bookings.date', $key) }}" class="h-16 p-2 rounded-xl border hover:bg-violet-100/70 dark:hover:bg-violet-900/40 relative transition" style="border-color: rgb(var(--border));">
                    <span class="text-sm">{{ $date->format('d.m') }}</span>
                    @if($count > 0)
                        <span class="absolute bottom-2 right-2 w-2.5 h-2.5 rounded-full bg-violet-600"></span>
                    @endif
                </a>
            @endfor
        </div>
    </div>
</div>
@endsection
