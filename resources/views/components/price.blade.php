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
    'sm' => 'text-sm font-bold',
    'lg' => 'text-xl font-black',
    'xl' => 'text-2xl sm:text-3xl font-black tracking-tight',
    default => 'text-base sm:text-lg font-bold',
};
@endphp

<div {{ $attributes->merge(['class' => 'flex items-baseline flex-wrap gap-x-2 gap-y-0.5']) }}>
    <span class="{{ $sizeClasses }} text-[#1C1C1C]">
        {{ $formattedPrice }}
    </span>

    @if($formattedOriginal && $originalPrice > $price)
        <span class="text-xs sm:text-sm text-[#6B7280] line-through font-normal">
            {{ $formattedOriginal }}
        </span>
    @endif

    @if($discountPercent)
        <span class="text-[11px] font-bold text-[#E31E24] bg-red-50 px-1.5 py-0.5 rounded">
            -{{ $discountPercent }}%
        </span>
    @endif
</div>
