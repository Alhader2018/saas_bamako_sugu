@props([
    'disabled' => false,
    'error' => null,
    'label' => null,
    'id' => null,
    'hint' => null,
])

@php
$id = $id ?? 'select_' . Str::random(8);
@endphp

<div class="w-full space-y-1">
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-medium text-[#1C1C1C]">
            {{ $label }}
            @if($attributes->has('required'))
                <span class="text-[#E31E24]">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select 
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full h-10 px-3 pr-8 text-sm bg-white text-[#1C1C1C] border rounded-lg smooth-transition outline-none appearance-none ' . 
                ($error ? 'border-[#DC2626] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626]' : 'border-[#ECECEC] hover:border-neutral-300 focus:border-[#E31E24] focus:ring-1 focus:ring-[#E31E24]')
            ]) }}
        >
            {{ $slot }}
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#6B7280]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    @if($hint && !$error)
        <p class="text-[11px] text-[#6B7280]">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="text-xs text-[#DC2626] font-medium mt-1">{{ $error }}</p>
    @endif
</div>
