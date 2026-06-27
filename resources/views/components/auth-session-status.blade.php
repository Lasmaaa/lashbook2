@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm flash-success rounded-xl px-3 py-2']) }}>
        {{ $status }}
    </div>
@endif
