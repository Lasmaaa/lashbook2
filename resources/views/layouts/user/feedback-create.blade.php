@extends('layouts.app')

@section('content')
<div class="p-8 max-w-3xl mx-auto">
    <h1 class="text-3xl font-semibold">Atsauksme</h1>

    <form method="POST" action="{{ route('feedback.store') }}" enctype="multipart/form-data" class="mt-8 bg-[rgb(var(--card))] p-6 rounded-2xl space-y-4">
        @csrf
        <div>
            <label class="block mb-2">Vērtējums (1-5)</label>
            <input type="number" min="1" max="5" name="rating" class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900" required>
        </div>
        <div>
            <label class="block mb-2">Komentārs</label>
            <textarea name="comment" rows="5" class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900"></textarea>
        </div>
        <div>
            <label class="block mb-2">Bilde (nav obligāti)</label>
            <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        </div>
        <button class="px-5 py-3 rounded-xl bg-violet-600 text-white" type="submit">Saglabāt</button>
    </form>
</div>
@endsection
