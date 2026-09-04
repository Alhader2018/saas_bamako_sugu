<button 
    type="button" 
    wire:click="$dispatch('open-cart')"
    class="relative flex items-center gap-2.5 h-11 px-3.5 rounded-xl bg-neutral-50 hover:bg-neutral-100 border border-[#ECECEC] text-[#1C1C1C] smooth-transition cursor-pointer"
    aria-label="Ouvrir le panier"
>
    <div class="relative">
        <svg class="w-5 h-5 text-[#E31E24]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="8" cy="21" r="1"></circle>
            <circle cx="19" cy="21" r="1"></circle>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
        </svg>
        @if($count > 0)
            <span class="absolute -top-2 -right-2 min-w-5 h-5 px-1 bg-[#E31E24] text-white text-[11px] font-black rounded-full flex items-center justify-center animate-pulse">
                {{ $count }}
            </span>
        @endif
    </div>

    <span class="hidden md:inline-block text-xs font-bold text-[#1C1C1C]">
        Panier
    </span>
</button>
