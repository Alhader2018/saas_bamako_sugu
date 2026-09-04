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

<div class="w-full space-y-1.5">
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-semibold text-[#1C1C1C] uppercase tracking-wider">
            {{ $label }}
            @if($attributes->has('required'))
                <span class="text-[#E31E24]">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-xl">
        <input 
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full h-11 px-3.5 text-sm bg-white text-[#1C1C1C] placeholder:text-[#6B7280] border rounded-xl smooth-transition outline-none ' . 
                ($error ? 'border-[#DC2626] focus:border-[#DC2626] focus:ring-2 focus:ring-red-100' : 'border-[#ECECEC] hover:border-neutral-300 focus:border-[#E31E24] focus:ring-2 focus:ring-red-500/10')
            ]) }}
        >
    </div>

    @if($hint && !$error)
        <p class="text-[11px] text-[#6B7280]">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="text-xs text-[#DC2626] font-medium flex items-center gap-1 mt-1">
            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            {{ $error }}
        </p>
    @endif
</div>
