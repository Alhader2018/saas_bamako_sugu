@props([
    'variant' => 'primary', // primary, secondary, outline, promo
    'size' => 'md', // sm, md, lg
    'href' => null,
    'type' => 'button',
])

@php
$baseClasses = "inline-flex items-center justify-center font-semibold rounded-xl cursor-pointer smooth-transition active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none text-center";

$sizeClasses = match($size) {
    'sm' => 'h-9 px-3.5 text-xs rounded-lg gap-1.5',
    'lg' => 'h-13 px-7 text-base rounded-2xl gap-2.5 font-bold',
    default => 'h-11 px-5 text-sm rounded-xl gap-2',
};

$variantClasses = match($variant) {
    'primary' => 'bg-[#E31E24] text-white hover:bg-[#C9171D] shadow-sm shadow-red-500/20',
    'secondary' => 'bg-[#111111] text-white hover:bg-neutral-800',
    'outline' => 'bg-white text-[#1C1C1C] border border-[#ECECEC] hover:bg-neutral-50 hover:border-neutral-300',
    'promo' => 'bg-[#F7B500] text-[#111111] hover:bg-[#E0A300] font-bold shadow-sm shadow-amber-500/20',
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
