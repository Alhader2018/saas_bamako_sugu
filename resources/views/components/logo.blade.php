@props(['class' => 'h-9 w-auto'])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    <img src="{{ asset('images/logo-bko-su.svg') }}" alt="BKO SU - Bamako Supermarché" class="{{ $class }}">
</div>
