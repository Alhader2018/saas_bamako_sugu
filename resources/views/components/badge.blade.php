@props([
    'variant' => 'promo', // promo, danger, success, dark, neutral
    'size' => 'sm',
])

@php
$baseClasses = "inline-flex items-center font-bold tracking-tight rounded-full";

$sizeClasses = match($size) {
    'xs' => 'px-2 py-0.5 text-[10px]',
    'md' => 'px-3 py-1 text-xs',
    default => 'px-2.5 py-0.5 text-xs',
};

$variantClasses = match($variant) {
    'promo' => 'bg-[#F7B500] text-[#111111]',
    'danger' => 'bg-[#E31E24] text-white',
    'success' => 'bg-[#16A34A]/10 text-[#16A34A] border border-[#16A34A]/20',
    'dark' => 'bg-[#111111] text-white',
    'neutral' => 'bg-neutral-100 text-[#1C1C1C] border border-[#ECECEC]',
    default => 'bg-[#F7B500] text-[#111111]',
};

$classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
