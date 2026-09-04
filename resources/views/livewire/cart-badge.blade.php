<button 
    type="button" 
    wire:click="$dispatch('open-cart')"
    class="relative flex items-center gap-2 h-9 px-3 rounded-md border border-[#ECECEC] hover:border-neutral-300 bg-white text-[#1C1C1C] smooth-transition cursor-pointer"
    aria-label="Ouvrir le panier"
>
    <div class="relative flex items-center">
        <svg class="w-4 h-4 text-[#1C1C1C]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
            <circle cx="8" cy="21" r="1"></circle>
            <circle cx="19" cy="21" r="1"></circle>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
        </svg>
        @if($count > 0)
            <span class="ml-1.5 px-1.5 py-0.2 bg-[#E31E24] text-white text-[10px] font-bold rounded-full">
                {{ $count }}
            </span>
        @endif
    </div>

    <span class="hidden md:inline-block text-xs font-medium text-[#1C1C1C]">
        Panier
    </span>
</button>
