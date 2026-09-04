@props([
    'disabled' => false,
    'error' => null,
    'label' => null,
    'id' => null,
    'hint' => null,
])

@php
$id = $id ?? 'input_' . Str::random(8);
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
        <input 
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full h-10 px-3 text-sm bg-white text-[#1C1C1C] placeholder:text-[#9CA3AF] border rounded-lg smooth-transition outline-none ' . 
                ($error ? 'border-[#DC2626] focus:border-[#DC2626] focus:ring-1 focus:ring-[#DC2626]' : 'border-[#ECECEC] hover:border-neutral-300 focus:border-[#E31E24] focus:ring-1 focus:ring-[#E31E24]')
            ]) }}
        >
    </div>

    @if($hint && !$error)
        <p class="text-[11px] text-[#6B7280]">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="text-xs text-[#DC2626] font-medium mt-1">
            {{ $error }}
        </p>
    @endif
</div>
