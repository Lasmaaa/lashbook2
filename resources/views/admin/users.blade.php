@extends('layouts.app')

@section('content')
<div class="p-8 max-w-5xl mx-auto">
    <h1 class="text-3xl font-semibold">{{ __('ui.admin_users') }}</h1>

    <form method="GET" class="mt-6">
        <input type="text" name="email" value="{{ $search }}" placeholder="{{ __('ui.search_by_email') }}" class="w-full sm:max-w-sm px-4 py-3 rounded-xl border">
        <button type="submit" class="mt-3 px-4 py-3 rounded-xl btn-violet">{{ __('ui.filter') }}</button>
    </form>

    <div class="mt-8 space-y-3">
        @foreach($users as $user)
            <div class="card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="font-medium">{{ $user->fullName() }}</p>
                    <p class="text-sm text-muted">{{ $user->email }} · {{ $user->phone }}</p>
                    <p class="text-xs text-muted mt-1">{{ __('ui.registrations') }}: {{ $user->created_at->format('d.m.Y H:i') }}</p>
                </div>
                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex gap-2 items-center">
                    @csrf
                    <select name="usertype" class="px-3 py-2 rounded-xl border">
                        <option value="user" @selected($user->usertype === 'user')>{{ __('ui.user') }}</option>
                        <option value="admin" @selected($user->usertype === 'admin')>{{ __('ui.admin') }}</option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl btn-violet">{{ __('ui.change') }}</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
