<x-customer.layout title="Mes favoris">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111111]">
                Mes favoris
            </h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                Retrouvez vos articles coup de cœur et ajoutez-les rapidement à votre panier.
            </p>
        </div>

        <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold bg-[#F9FAFB] border border-[#D1D5DB] text-[#374151] hover:bg-neutral-100 transition-colors shrink-0">
            <span>Explorer le catalogue</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>

    @if($favorites->count() > 0)
        <!-- Grille des Favoris -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            @foreach($favorites as $product)
                <div class="bg-white border border-[#E5E7EB] rounded-xl overflow-hidden shadow-2xs hover:border-[#D1D5DB] transition-all flex flex-col justify-between">
                    <div>
                        <!-- Image avec Badge et Bouton Retirer -->
                        <div class="relative aspect-square bg-[#F9FAFB] overflow-hidden border-b border-[#F3F4F6]">
                            <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </a>

                            @if($product->stock <= 0)
                                <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded text-[10px] font-bold bg-neutral-900/80 text-white backdrop-blur-xs">
                                    Rupture de stock
                                </span>
                            @elseif($product->badge)
                                <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded text-[10px] font-bold bg-[#E31E24] text-white shadow-xs">
                                    {{ $product->badge }}
                                </span>
                            @endif

                            <!-- Bouton Retirer des favoris -->
                            <form action="{{ route('account.favorites.toggle', $product) }}" method="POST" class="absolute top-2.5 right-2.5">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-full bg-white/90 hover:bg-white text-red-500 hover:text-red-600 shadow-sm flex items-center justify-center transition-transform active:scale-90" title="Retirer des favoris">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Informations Produit -->
                        <div class="p-4">
                            <span class="text-[10px] text-[#6B7280] uppercase tracking-wider block font-medium">
                                {{ $product->vendor_name ?: 'BKO SU' }}
                            </span>
                            <a href="{{ route('product.show', $product->slug) }}" class="text-xs font-bold text-[#111111] hover:text-[#E31E24] line-clamp-2 mt-1 transition-colors">
                                {{ $product->name }}
                            </a>

                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-sm font-bold text-[#111111]">{{ $product->formatted_price }}</span>
                                @if($product->original_price > $product->price)
                                    <span class="text-[11px] text-[#9CA3AF] line-through">{{ $product->formatted_original_price }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions Bas -->
                    <div class="p-4 pt-0 border-t border-[#F3F4F6] mt-auto flex items-center gap-2 pt-3">
                        <a href="{{ route('product.show', $product->slug) }}" class="flex-1 py-2 text-center text-xs font-medium text-[#374151] bg-[#F9FAFB] hover:bg-neutral-100 rounded-lg transition-colors border border-[#D1D5DB]">
                            Voir l'article
                        </a>

                        @if($product->stock > 0)
                            <button type="button" 
                                    onclick="Livewire.dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: 1 })"
                                    class="py-2 px-3 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-lg text-xs font-semibold shadow-2xs transition-colors flex items-center justify-center gap-1.5"
                                    title="Ajouter au panier">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span>Panier</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $favorites->links() }}
        </div>
    @else
        <!-- Empty State Favoris -->
        <div class="bg-white border border-[#E5E7EB] rounded-xl p-10 text-center shadow-2xs">
            <div class="w-12 h-12 mx-auto rounded-full bg-red-50 text-[#E31E24] flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-[#111111]">Votre liste de favoris est vide</h3>
            <p class="text-xs text-[#6B7280] mt-1 max-w-sm mx-auto">
                Parcourez nos rayons et cliquez sur l'icône de cœur pour sauvegarder les articles qui vous intéressent.
            </p>
            <div class="mt-4">
                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-lg text-xs font-semibold shadow-xs transition-colors">
                    <span>Explorer nos produits</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    @endif

</x-customer.layout>
