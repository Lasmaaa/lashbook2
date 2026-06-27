@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label block font-medium text-sm']) }}>
    {{ $value ?? $slot }}
</label>
