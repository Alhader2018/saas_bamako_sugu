@props([
    'product',
])

<div class="group bg-white rounded-lg border border-[#ECECEC] p-3 flex flex-col justify-between product-card smooth-transition">
    <!-- Image & Promo Tag -->
    <div class="relative aspect-square w-full rounded-md overflow-hidden bg-[#F8F8F8] mb-2.5">
        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
            <img 
                src="{{ $product->image_url }}" 
                alt="{{ $product->name }}" 
                loading="lazy" 
                class="w-full h-full object-cover object-center product-image-zoom"
            >
        </a>

        <!-- Badge Promo Réel si réduction -->
        @if($product->discount_percent)
            <span class="absolute top-2 left-2 bg-[#F7B500] text-[#111111] text-[10px] font-bold px-1.5 py-0.5 rounded">
                -{{ $product->discount_percent }}%
            </span>
        @endif

        <!-- Bouton Favoris discret et fonctionnel -->
        <button 
            type="button" 
            @auth
                onclick="fetch('{{ route('account.favorites.toggle', $product) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                }).then(res => res.json()).then(data => {
                    const svg = this.querySelector('svg');
                    if (data.favorited) {
                        svg.classList.add('fill-current', 'text-[#E31E24]');
                    } else {
                        svg.classList.remove('fill-current', 'text-[#E31E24]');
                    }
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message } }));
                })"
            @else
                onclick="window.location.href='{{ route('login') }}'"
            @endauth
            aria-label="Ajouter aux favoris"
            class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white/90 border border-[#ECECEC] flex items-center justify-center text-neutral-400 hover:text-[#E31E24] hover:bg-white smooth-transition cursor-pointer shadow-xs"
        >
            <svg class="w-3.5 h-3.5 @if(auth()->check() && auth()->user()->favorites()->where('product_id', $product->id)->exists()) fill-current text-[#E31E24] @else fill-none stroke-current @endif" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>
    </div>

    <!-- Informations Produit -->
    <div class="flex flex-col flex-1 justify-between">
        <div>
            <!-- Boutique / Vendeur -->
            <p class="text-[11px] text-[#6B7280] mb-0.5 truncate">{{ $product->vendor_name }}</p>

            <!-- Titre -->
            <h3 class="text-sm font-semibold text-[#1C1C1C] group-hover:text-[#E31E24] smooth-transition line-clamp-2 leading-snug mb-2">
                <a href="{{ route('product.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>
        </div>

        <!-- Prix & Bouton Ajout Panier discret -->
        <div class="pt-2 border-t border-[#ECECEC] flex items-center justify-between gap-2">
            <x-price 
                :price="$product->price" 
                :originalPrice="$product->original_price" 
                :discountPercent="null"
                size="sm"
            />

            <button 
                type="button"
                wire:click="$dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: 1 })"
                class="h-7 px-2 bg-neutral-100 hover:bg-[#E31E24] hover:text-white text-[#1C1C1C] rounded text-xs font-semibold flex items-center gap-1 smooth-transition cursor-pointer"
                title="Ajouter au panier"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
                <span class="hidden sm:inline">Ajouter</span>
            </button>
        </div>
    </div>
</div>
