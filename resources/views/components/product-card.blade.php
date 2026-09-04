@props([
    'product',
])

<div class="group relative bg-white rounded-2xl border border-[#ECECEC] p-3 sm:p-4 flex flex-col justify-between product-card-hover smooth-transition">
    <!-- Image & Badges Container -->
    <div class="relative aspect-square w-full rounded-xl overflow-hidden bg-[#F8F8F8] mb-3">
        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
            <img 
                src="{{ $product->image_url }}" 
                alt="{{ $product->name }}" 
                loading="lazy" 
                class="w-full h-full object-cover object-center product-image-zoom"
            >
        </a>

        <!-- Badges Flottants -->
        <div class="absolute top-2 left-2 flex flex-col gap-1 items-start z-10 pointer-events-none">
            @if($product->discount_percent)
                <span class="bg-[#F7B500] text-[#111111] text-[11px] font-extrabold px-2 py-0.5 rounded-full shadow-sm">
                    -{{ $product->discount_percent }}%
                </span>
            @endif

            @if($product->badge && !$product->discount_percent)
                <span class="bg-[#111111] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                    {{ $product->badge }}
                </span>
            @endif

            @if($product->is_flash_deal)
                <span class="bg-[#E31E24] text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-sm flex items-center gap-1">
                    <svg class="w-3 h-3 text-[#F7B500] fill-current" viewBox="0 0 24 24">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                    Flash
                </span>
            @endif
        </div>

        <!-- Wishlist Button -->
        <button 
            type="button" 
            onclick="window.dispatchEvent(new CustomEvent('toast', {detail: {message: 'Produit ajouté aux favoris ❤️'}}))"
            aria-label="Ajouter aux favoris"
            class="absolute top-2 right-2 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm border border-[#ECECEC] flex items-center justify-center text-neutral-400 hover:text-[#E31E24] hover:bg-white smooth-transition shadow-xs z-10 cursor-pointer"
        >
            <svg class="w-4 h-4 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>

        <!-- Quick Add Overlay (Desktop) -->
        <div class="hidden sm:block absolute inset-x-2 bottom-2 translate-y-3 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 smooth-transition z-10">
            <button 
                type="button"
                wire:click="$dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: 1 })"
                class="w-full h-9 bg-[#111111] hover:bg-[#E31E24] text-white text-xs font-bold rounded-lg flex items-center justify-center gap-1.5 shadow-md smooth-transition cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                Ajout rapide
            </button>
        </div>
    </div>

    <!-- Product Info -->
    <div class="flex flex-col flex-1 justify-between">
        <div>
            <!-- Vendeur / Catégorie -->
            <div class="flex items-center justify-between text-[11px] text-[#6B7280] mb-1">
                <span class="truncate">{{ $product->vendor_name }}</span>
                @if($product->rating)
                    <div class="flex items-center gap-1 text-[#F7B500] font-bold">
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-[#1C1C1C]">{{ $product->rating }}</span>
                    </div>
                @endif
            </div>

            <!-- Titre Produit -->
            <h3 class="text-sm font-semibold text-[#1C1C1C] group-hover:text-[#E31E24] smooth-transition line-clamp-2 leading-snug mb-2">
                <a href="{{ route('product.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>
        </div>

        <!-- Prix & Bouton Mobile -->
        <div class="pt-2 border-t border-[#ECECEC]/60 flex items-center justify-between gap-2">
            <x-price 
                :price="$product->price" 
                :originalPrice="$product->original_price" 
                :discountPercent="$product->discount_percent"
                size="sm"
            />

            <!-- Mobile Add to Cart Button -->
            <button 
                type="button"
                wire:click="$dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: 1 })"
                class="sm:hidden w-8 h-8 rounded-full bg-[#E31E24] text-white flex items-center justify-center shrink-0 shadow-xs active:scale-90 smooth-transition cursor-pointer"
                aria-label="Ajouter au panier"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
    </div>
</div>
