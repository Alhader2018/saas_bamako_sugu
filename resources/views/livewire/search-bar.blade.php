<div class="relative w-full max-w-xl" x-data="{ focused: false }" @click.outside="focused = false">
    <form wire:submit.prevent="search" class="flex items-center w-full bg-white border border-[#ECECEC] hover:border-neutral-300 focus-within:border-[#E31E24] rounded-lg smooth-transition overflow-hidden">
        
        <!-- Rayon (Desktop) -->
        <div class="hidden lg:flex items-center pl-2.5 pr-1.5 border-r border-[#ECECEC]">
            <select 
                wire:model.live="selectedCategory" 
                class="bg-transparent text-xs font-medium text-[#1C1C1C] outline-none cursor-pointer py-1.5"
            >
                <option value="">Tous les rayons</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Champ Recherche -->
        <div class="relative flex-1 flex items-center">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="query"
                @focus="focused = true"
                placeholder="Rechercher un produit, une marque..." 
                class="w-full h-9 px-3 text-xs sm:text-sm bg-transparent text-[#1C1C1C] placeholder:text-[#9CA3AF] outline-none"
            >
            @if(!empty($query))
                <button 
                    type="button" 
                    wire:click="clear" 
                    class="p-1 mr-1 text-neutral-400 hover:text-[#1C1C1C] cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>

        <!-- Bouton Valider -->
        <button 
            type="submit" 
            class="h-9 px-3 bg-[#E31E24] hover:bg-[#C9171D] text-white flex items-center justify-center font-medium text-xs smooth-transition cursor-pointer shrink-0"
            aria-label="Lancer la recherche"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.3-4.3"></path>
            </svg>
        </button>
    </form>

    <!-- Résultats Suggérés -->
    @if($isOpen && count($results) > 0)
        <div 
            x-show="focused"
            class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg border border-[#ECECEC] shadow-sm overflow-hidden z-40"
        >
            <div class="px-3 py-2 border-b border-[#ECECEC] bg-neutral-50 flex items-center justify-between text-[11px] text-[#6B7280]">
                <span>Suggestions pour "{{ $query }}"</span>
                <span>{{ count($results) }} résultats</span>
            </div>

            <div class="divide-y divide-[#ECECEC] max-h-72 overflow-y-auto">
                @foreach($results as $item)
                    <a 
                        href="{{ route('product.show', $item->slug) }}" 
                        class="p-2.5 flex items-center gap-3 hover:bg-neutral-50 smooth-transition"
                    >
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-10 h-10 rounded object-cover border border-[#ECECEC]">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-medium text-[#1C1C1C] truncate">{{ $item->name }}</h4>
                            <p class="text-[11px] text-[#6B7280]">{{ $item->vendor_name }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-semibold text-[#1C1C1C]">{{ $item->formatted_price }}</div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="p-2 bg-[#F8F8F8] border-t border-[#ECECEC] text-center">
                <button 
                    type="button" 
                    wire:click="search" 
                    class="text-xs font-semibold text-[#E31E24] hover:underline"
                >
                    Voir tous les résultats →
                </button>
            </div>
        </div>
    @elseif($isOpen && count($results) === 0)
        <div 
            x-show="focused"
            class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg border border-[#ECECEC] p-4 text-center shadow-sm z-40"
        >
            <p class="text-xs text-[#6B7280]">Aucun résultat pour "{{ $query }}".</p>
        </div>
    @endif
</div>
