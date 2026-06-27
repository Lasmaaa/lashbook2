@extends('layouts.app')

@section('content')
<div class="p-8 max-w-3xl mx-auto">
    <h1 class="text-3xl font-semibold">{{ __('ui.reviews') }}</h1>
    <p class="text-muted mt-2">{{ __('ui.reviews_desc') }}</p>

    <form method="POST" action="{{ route('feedback.store') }}" enctype="multipart/form-data" class="mt-8 card p-6 space-y-4">
        @csrf
        <div>
            <label class="block mb-2">{{ __('ui.rating') }} (1-5)</label>
            <input type="number" min="1" max="5" name="rating" value="{{ old('rating', 5) }}" class="w-full px-4 py-3 rounded-xl border" required>
            @error('rating')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block mb-2">{{ __('ui.comment') }}</label>
            <textarea name="comment" rows="5" class="w-full px-4 py-3 rounded-xl border">{{ old('comment') }}</textarea>
        </div>
        <div>
            <label class="block mb-2">{{ __('ui.photo_optional') }}</label>
            <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 rounded-xl border">
        </div>
        <button class="px-5 py-3 rounded-xl btn-primary w-full sm:w-auto" type="submit">{{ __('ui.save') }}</button>
    </form>
</div>
@endsection
