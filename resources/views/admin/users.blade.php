@extends('layouts.app')

@section('content')
<div class="p-8 max-w-7xl mx-auto">
    <h1 class="text-3xl font-semibold">{{ __('ui.admin_procedures') }}</h1>

    <form method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
        <input type="text" name="email" value="{{ $search }}" placeholder="{{ __('ui.search_by_email') }}" class="w-full sm:max-w-sm px-4 py-3 rounded-xl border">
        <div class="flex flex-wrap gap-3 items-center">
            <label class="flex items-center gap-2"><input type="checkbox" name="ranges[]" value="week" @checked($filters->contains('week'))> {{ __('ui.week') }}</label>
            <label class="flex items-center gap-2"><input type="checkbox" name="ranges[]" value="month" @checked($filters->contains('month'))> {{ __('ui.month') }}</label>
            <label class="flex items-center gap-2"><input type="checkbox" name="ranges[]" value="year" @checked($filters->contains('year'))> {{ __('ui.year') }}</label>
        </div>
        <button type="submit" class="w-full sm:w-auto px-4 py-3 rounded-xl bg-violet-600 text-white">{{ __('ui.filter') }}</button>
    </form>

    <div class="mt-6 bg-[rgb(var(--card))] rounded-2xl p-4">
        <canvas id="clients-chart" height="110"></canvas>
    </div>

    <div class="mt-8 space-y-3">
        @foreach($users as $user)
            <div class="bg-[rgb(var(--card))] rounded-2xl p-4 flex items-center justify-between gap-4">
                <div>
                    <p class="font-medium">{{ $user->name }} {{ $user->surname }}</p>
                    <p class="text-sm opacity-75">{{ $user->email }} | {{ $user->phone }}</p>
                </div>
                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex gap-2 items-center">
                    @csrf
                    <select name="usertype" class="px-3 py-2 rounded-xl border">
                        <option value="user" @selected($user->usertype === 'user')>{{ __('ui.user') }}</option>
                        <option value="admin" @selected($user->usertype === 'admin')>{{ __('ui.admin') }}</option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-violet-600 text-white">{{ __('ui.change') }}</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);
    const colors = { week: '#6366f1', month: '#10b981', year: '#f59e0b' };
    const rangeLabels = {
        week: @json(__('ui.week')),
        month: @json(__('ui.month')),
        year: @json(__('ui.year'))
    };
    const labels = Object.values(chartData)[0]?.labels ?? [];
    const datasets = Object.entries(chartData).map(([key, data]) => ({
        label: rangeLabels[key] || key,
        data: data.series,
        borderColor: colors[key] || '#8b5cf6',
        backgroundColor: 'transparent',
        tension: 0.3
    }));
    if (datasets.length) {
        new Chart(document.getElementById('clients-chart'), {
            type: 'line',
            data: { labels, datasets },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }
</script>
@endsection
