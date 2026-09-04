@props([
    'variant' => 'primary', // primary, secondary, outline, promo
    'size' => 'md', // sm, md, lg
    'href' => null,
    'type' => 'button',
])

@php
$baseClasses = "inline-flex items-center justify-center font-semibold cursor-pointer smooth-transition active:scale-[0.99] disabled:opacity-50 disabled:pointer-events-none text-center select-none";

$sizeClasses = match($size) {
    'sm' => 'h-8 px-3 text-xs rounded-md gap-1.5',
    'lg' => 'h-12 px-6 text-sm sm:text-base rounded-lg gap-2',
    default => 'h-11 px-4 text-sm rounded-lg gap-2',
};

$variantClasses = match($variant) {
    'primary' => 'bg-[#E31E24] text-white hover:bg-[#C9171D]',
    'secondary' => 'bg-[#111111] text-white hover:bg-neutral-800',
    'outline' => 'bg-white text-[#1C1C1C] border border-[#ECECEC] hover:border-neutral-400 hover:bg-neutral-50',
    'promo' => 'bg-[#F7B500] text-[#111111] hover:bg-[#E0A300]',
    'ghost' => 'text-[#1C1C1C] hover:bg-neutral-100',
    default => 'bg-[#E31E24] text-white hover:bg-[#C9171D]',
};

$classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
