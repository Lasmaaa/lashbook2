@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto prose-headings:text-[rgb(var(--text))] prose-p:text-[rgb(var(--text))]">
    <h1 class="text-heading">{{ __('ui.terms_conditions') }}</h1>
    <p class="text-muted">{{ __('ui.terms_intro') }}</p>
    <h2 class="text-heading">{{ __('ui.terms_booking_title') }}</h2>
    <p>{{ __('ui.terms_booking_text') }}</p>
    <h2 class="text-heading">{{ __('ui.terms_cancel_title') }}</h2>
    <p>{{ __('ui.terms_cancel_text') }}</p>
    <h2 class="text-heading">{{ __('ui.terms_loyalty_title') }}</h2>
    <p>{{ __('ui.terms_loyalty_text') }}</p>
    <p class="text-sm text-muted">{{ __('ui.terms_updated') }}</p>
</div>
@endsection
