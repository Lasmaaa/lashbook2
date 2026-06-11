@extends('layouts.app')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-semibold">{{ __('ui.bookings_for_date') }}: {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</h1>

    <div class="mt-8 space-y-4">
        @forelse($bookings as $booking)
            <div class="bg-[rgb(var(--card))] p-5 rounded-2xl">
                <p><strong>{{ $booking->time }}</strong> - {{ $booking->user->name }} {{ $booking->user->surname }}</p>
                <p>{{ $booking->procedure->getName() }}</p>
                <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="mt-3 flex gap-2">
                    @csrf
                    <select name="status" class="px-3 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                        <option value="arrived" @selected($booking->status === 'arrived')>{{ __('ui.arrived') }}</option>
                        <option value="no_show" @selected($booking->status === 'no_show')>{{ __('ui.no_show') }}</option>
                    </select>
                    <button class="px-4 py-2 rounded-xl bg-violet-600 text-white" type="submit">{{ __('ui.save') }}</button>
                </form>
            </div>
        @empty
            <p>{{ __('ui.no_bookings') }}</p>
        @endforelse
    </div>
</div>
@endsection
