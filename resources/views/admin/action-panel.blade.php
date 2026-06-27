@extends('layouts.app')

@section('content')
<div class="p-8 space-y-6 max-w-5xl mx-auto">
    <h1 class="text-3xl font-semibold">{{ __('ui.admin_action_panel') }}</h1>

    <form method="GET" class="card p-4 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm text-muted mb-1">{{ __('ui.filter_category') }}</label>
            <select name="category" class="rounded-xl border p-2">
                <option value="all" @selected($category === 'all')>{{ __('ui.all') }}</option>
                <option value="registration" @selected($category === 'registration')>{{ __('ui.registrations') }}</option>
                <option value="booking" @selected($category === 'booking')>{{ __('ui.bookings') }}</option>
                <option value="loyalty" @selected($category === 'loyalty')>{{ __('ui.loyalty_scans_codes') }}</option>
                <option value="feedback" @selected($category === 'feedback')>{{ __('ui.reviews') }}</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-muted mb-1">{{ __('ui.filter_sort') }}</label>
            <select name="sort" class="rounded-xl border p-2">
                <option value="newest" @selected($sort === 'newest')>{{ __('ui.newest_first') }}</option>
                <option value="oldest" @selected($sort === 'oldest')>{{ __('ui.oldest_first') }}</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 rounded-xl btn-violet">{{ __('ui.filter') }}</button>
    </form>

    <div class="card p-6">
        <div class="space-y-3">
            @forelse($events as $event)
                <div class="flex items-start gap-3 p-3 rounded-xl border" style="border-color: rgb(var(--border));">
                    <span class="text-xs uppercase tracking-wide px-2 py-1 rounded-lg event-badge shrink-0">
                        {{ __('ui.event_type_' . $event['type']) }}
                    </span>
                    <div class="min-w-0">
                        <p class="text-sm">{{ $event['label'] }}</p>
                        <p class="text-xs text-muted mt-1">{{ $event['at']->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-muted">{{ __('ui.no_data') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
