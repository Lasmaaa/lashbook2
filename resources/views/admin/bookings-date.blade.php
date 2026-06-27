@extends('layouts.app')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <a href="{{ route('admin.bookings') }}" class="text-sm text-link">← {{ __('ui.admin_bookings') }}</a>
    <h1 class="text-3xl font-semibold mt-4">{{ __('ui.bookings_for_date') }}: {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</h1>

    <div class="mt-8 space-y-4">
        @forelse($bookings as $booking)
            <div class="card p-5">
                <p class="text-lg font-semibold">{{ substr((string) $booking->time, 0, 5) }} — {{ $booking->client_name ?: $booking->user?->fullName() }}</p>
                <p class="text-muted mt-1">{{ $booking->getProcedureName() }}</p>
                @if($booking->details)
                    <p class="text-sm mt-2">{{ $booking->details }}</p>
                @endif
                <p class="text-xs text-muted mt-2">{{ $booking->user?->email }} · {{ $booking->user?->phone }}</p>
                <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="mt-3 flex flex-wrap gap-2">
                    @csrf
                    <select name="status" class="px-3 py-2 rounded-xl border">
                        <option value="pending" @selected($booking->status === 'pending')>Pending</option>
                        <option value="confirmed" @selected($booking->status === 'confirmed')>Confirmed</option>
                        <option value="arrived" @selected($booking->status === 'arrived')>{{ __('ui.arrived') }}</option>
                        <option value="no_show" @selected($booking->status === 'no_show')>{{ __('ui.no_show') }}</option>
                    </select>
                    <button class="px-4 py-2 rounded-xl btn-violet" type="submit">{{ __('ui.save') }}</button>
                </form>
            </div>
        @empty
            <p class="text-muted">{{ __('ui.no_bookings') }}</p>
        @endforelse
    </div>
</div>
@endsection
