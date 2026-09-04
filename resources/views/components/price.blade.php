@props([
    'price' => 0,
    'originalPrice' => null,
    'discountPercent' => null,
    'size' => 'md', // sm, md, lg, xl
])

@php
$formattedPrice = number_format($price, 0, ',', ' ') . ' FCFA';
$formattedOriginal = $originalPrice ? number_format($originalPrice, 0, ',', ' ') . ' FCFA' : null;

$sizeClasses = match($size) {
    'sm' => 'text-sm font-semibold',
    'lg' => 'text-lg font-bold',
    'xl' => 'text-2xl font-bold tracking-tight',
    default => 'text-base font-semibold',
};
@endphp

<div {{ $attributes->merge(['class' => 'flex items-baseline flex-wrap gap-x-2 gap-y-0.5']) }}>
    <span class="{{ $sizeClasses }} text-[#1C1C1C]">
        {{ $formattedPrice }}
    </span>

    @if($formattedOriginal && $originalPrice > $price)
        <span class="text-xs text-[#6B7280] line-through font-normal">
            {{ $formattedOriginal }}
        </span>
    @endif

    @if($discountPercent)
        <span class="text-[11px] font-semibold text-[#E31E24]">
            -{{ $discountPercent }}%
        </span>
    @endif
</div>
