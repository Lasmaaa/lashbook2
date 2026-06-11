@extends('layouts.app')

@section('content')
<div class="p-8 space-y-6">
    <h1 class="text-3xl font-semibold">{{ __('ui.admin_action_panel') }}</h1>

    <div class="bg-[rgb(var(--card))] rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-3">{{ __('ui.bookings') }}</h2>
        <div class="space-y-2 text-sm">
            @forelse($bookings as $booking)
                <p>
                    {{ $booking->created_at->format('d.m.Y H:i') }} -
                    {{ $booking->user?->fullName() }} ({{ $booking->user?->email }}, {{ $booking->user?->phone }}) -
                    {{ $booking->date?->format('d.m.Y') }} {{ substr((string) $booking->time, 0, 5) }} -
                    {{ $booking->procedure?->getName() }}
                </p>
            @empty
                <p>{{ __('ui.no_data') }}</p>
            @endforelse
        </div>
    </div>

    <div class="bg-[rgb(var(--card))] rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-3">{{ __('ui.loyalty_scans_codes') }}</h2>
        <div class="space-y-2 text-sm">
            @forelse($loyaltyLogs as $log)
                <p>
                    {{ $log->created_at->format('d.m.Y H:i') }} -
                    {{ strtoupper($log->action) }} -
                    {{ $log->user?->fullName() }} ({{ $log->code }}) -
                    {{ $log->source }}
                </p>
            @empty
                <p>{{ __('ui.no_data') }}</p>
            @endforelse
        </div>
    </div>

    <div class="bg-[rgb(var(--card))] rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-3">{{ __('ui.registrations') }}</h2>
        <div class="space-y-2 text-sm">
            @forelse($registrations as $user)
                <p>{{ $user->created_at->format('d.m.Y H:i') }} - {{ $user->fullName() }} ({{ $user->email }}, {{ $user->phone }})</p>
            @empty
                <p>{{ __('ui.no_data') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
