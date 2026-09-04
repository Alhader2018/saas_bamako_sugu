<div class="relative w-full max-w-2xl" x-data="{ focused: false }" @click.outside="focused = false">
    <form wire:submit.prevent="search" class="flex items-center w-full bg-[#F8F8F8] border border-[#ECECEC] hover:border-neutral-300 focus-within:border-[#E31E24] focus-within:bg-white focus-within:ring-2 focus-within:ring-red-500/10 rounded-2xl smooth-transition overflow-hidden">
        
        <!-- Category selector (desktop) -->
        <div class="hidden lg:flex items-center pl-3 pr-2 border-r border-[#ECECEC]">
            <select 
                wire:model.live="selectedCategory" 
                class="bg-transparent text-xs font-semibold text-[#1C1C1C] outline-none cursor-pointer py-2 pr-1"
            >
                <option value="">Tous les rayons</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Input -->
        <div class="relative flex-1 flex items-center">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="query"
                @focus="focused = true"
                placeholder="Rechercher à Bamako (riz, bazin, smartphone, fruits...)" 
                class="w-full h-11 px-4 text-sm bg-transparent text-[#1C1C1C] placeholder:text-[#6B7280] outline-none"
            >
            @if(!empty($query))
                <button 
                    type="button" 
                    wire:click="clear" 
                    class="p-1.5 mr-2 text-neutral-400 hover:text-[#1C1C1C] rounded-full hover:bg-neutral-200 smooth-transition cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>

        <!-- Search Submit Button -->
        <button 
            type="submit" 
            class="h-9 px-4 mr-1 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-xl flex items-center justify-center font-bold text-xs smooth-transition cursor-pointer shrink-0"
            aria-label="Lancer la recherche"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.3-4.3"></path>
            </svg>
            <span class="hidden sm:inline-block ml-1.5">Rechercher</span>
        </button>
    </form>

    <!-- Instant Dropdown Results -->
    @if($isOpen && count($results) > 0)
        <div 
            x-show="focused"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl border border-[#ECECEC] shadow-xl overflow-hidden z-40"
        >
            <div class="p-2 border-b border-[#ECECEC] bg-neutral-50 flex items-center justify-between text-[11px] font-semibold text-[#6B7280]">
                <span>Produits suggérés pour "{{ $query }}"</span>
                <span class="text-[#E31E24]">{{ count($results) }} résultats</span>
            </div>

            <div class="divide-y divide-[#ECECEC]/60 max-h-80 overflow-y-auto">
                @foreach($results as $item)
                    <a 
                        href="{{ route('product.show', $item->slug) }}" 
                        class="p-2.5 flex items-center gap-3 hover:bg-neutral-50 smooth-transition"
                    >
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-lg object-cover border border-[#ECECEC]">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-semibold text-[#1C1C1C] truncate">{{ $item->name }}</h4>
                            <p class="text-[11px] text-[#6B7280]">{{ $item->vendor_name }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-bold text-[#E31E24]">{{ $item->formatted_price }}</div>
                            @if($item->discount_percent)
                                <span class="text-[10px] bg-[#F7B500] text-[#111111] font-bold px-1.5 py-0.2 rounded">
                                    -{{ $item->discount_percent }}%
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="p-2.5 bg-[#F8F8F8] border-t border-[#ECECEC] text-center">
                <button 
                    type="button" 
                    wire:click="search" 
                    class="text-xs font-bold text-[#E31E24] hover:underline"
                >
                    Voir tous les résultats pour "{{ $query }}" →
                </button>
            </div>
        </div>
    @elseif($isOpen && count($results) === 0)
        <div 
            x-show="focused"
            class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl border border-[#ECECEC] p-6 text-center shadow-xl z-40"
        >
            <p class="text-xs font-medium text-[#6B7280]">Aucun produit trouvé pour "{{ $query }}".</p>
            <p class="text-[11px] text-[#6B7280] mt-1">Essayez avec un autre mot-clé (ex: riz, thé, samsung, bazin).</p>
        </div>
    @endif
</div>
