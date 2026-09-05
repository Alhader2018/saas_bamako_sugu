<x-layouts.app title="{{ $product->name }} — BKO SU (Bamako Supermarché)">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{ 
        mainImage: '{{ $product->image_url }}',
        quantity: 1,
        activeTab: 'desc'
    }">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-1.5 text-xs text-[#6B7280] mb-4">
            <a href="{{ route('home') }}" class="hover:text-[#E31E24]">Accueil</a>
            <span>/</span>
            <a href="{{ route('catalog', ['category' => $product->category->slug]) }}" class="hover:text-[#E31E24]">
                {{ $product->category->name }}
            </a>
            <span>/</span>
            <span class="text-[#1C1C1C] truncate">{{ $product->name }}</span>
        </nav>

        <!-- Fiche Produit (Galerie à gauche / Infos à droite) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white rounded-xl border border-[#ECECEC] p-5 sm:p-7">
            
            <!-- Galerie (7 cols) -->
            <div class="lg:col-span-7 space-y-3">
                <!-- Grande Image Principale (8-10px radius) -->
                <div class="relative aspect-square w-full rounded-lg overflow-hidden bg-[#F8F8F8] border border-[#ECECEC]">
                    <img 
                        :src="mainImage" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-cover object-center"
                    >

                    @if($product->discount_percent)
                        <span class="absolute top-3 left-3 bg-[#F7B500] text-[#111111] text-xs font-bold px-2 py-0.5 rounded">
                            -{{ $product->discount_percent }}%
                        </span>
                    @endif
                </div>

                <!-- Miniatures -->
                @if($product->gallery && count($product->gallery) > 1)
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        @foreach($product->gallery as $img)
                            <button 
                                type="button" 
                                @click="mainImage = '{{ $img }}'"
                                class="w-16 h-16 rounded-md overflow-hidden border smooth-transition shrink-0 cursor-pointer"
                                :class="mainImage === '{{ $img }}' ? 'border-[#E31E24]' : 'border-[#ECECEC] opacity-75 hover:opacity-100'"
                            >
                                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Informations Produit (5 cols) -->
            <div class="lg:col-span-5 flex flex-col justify-between space-y-5">
                <div>
                    <!-- Vendeur & Référence -->
                    <div class="flex items-center justify-between text-xs text-[#6B7280] mb-1">
                        <span>Vendu par <strong class="text-[#1C1C1C]">{{ $product->vendor_name }}</strong></span>
                        <span>Réf: {{ $product->reference }}</span>
                    </div>

                    <!-- Titre Produit -->
                    <h1 class="text-xl sm:text-2xl font-bold text-[#1C1C1C] leading-snug mb-2">
                        {{ $product->name }}
                    </h1>

                    <!-- Avis & Stock -->
                    <div class="flex items-center justify-between gap-4 pb-3 border-b border-[#ECECEC] text-xs">
                        <div class="flex items-center gap-1 text-[#6B7280]">
                            <span class="text-[#F7B500] font-bold">★ {{ $product->rating }}</span>
                            <span>({{ $product->reviews_count }} avis)</span>
                        </div>

                        <div>
                            @if($product->isDigital())
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 text-[11px] font-bold bg-amber-100 text-amber-900 rounded">
                                        {{ $product->digital_type_label ?: 'Numérique' }}
                                    </span>
                                    <span class="text-emerald-700 font-semibold text-xs flex items-center gap-1">
                                        ⚡ Disponible immédiatement
                                    </span>
                                </div>
                            @elseif($product->stock > 0)
                                <span class="text-emerald-700 font-medium text-xs">
                                    En stock ({{ $product->stock }} disponibles)
                                </span>
                            @else
                                <span class="text-[#DC2626] font-medium text-xs">Rupture de stock</span>
                            @endif
                        </div>
                    </div>

                    <!-- Prix en FCFA -->
                    <div class="py-3">
                        <x-price 
                            :price="$product->price" 
                            :originalPrice="$product->original_price" 
                            :discountPercent="$product->discount_percent"
                            size="xl"
                        />
                        <p class="text-xs text-[#6B7280] mt-0.5">Prix en Francs CFA (XOF), toutes taxes comprises.</p>
                    </div>

                    <!-- Quantité & Boutons d'Action -->
                    <div class="space-y-3 pt-1">
                        @if($product->isDigital())
                            <div class="flex items-center gap-2 text-xs text-[#6B7280]">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Licence personnelle unique (Téléchargement direct dans votre compte)</span>
                            </div>
                        @else
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-medium text-[#1C1C1C]">Quantité :</span>
                                <div class="inline-flex items-center border border-[#ECECEC] rounded-md bg-neutral-50 h-9 text-xs">
                                    <button 
                                        type="button" 
                                        @click="if(quantity > 1) quantity--" 
                                        class="w-8 h-full flex items-center justify-center hover:bg-white text-[#1C1C1C] font-semibold cursor-pointer"
                                    >-</button>
                                    <span class="w-9 text-center font-semibold text-[#1C1C1C]" x-text="quantity"></span>
                                    <button 
                                        type="button" 
                                        @click="quantity++" 
                                        class="w-8 h-full flex items-center justify-center hover:bg-white text-[#1C1C1C] font-semibold cursor-pointer"
                                    >+</button>
                                </div>
                            </div>
                        @endif

                        <!-- CTA 1 : Ajouter au panier (Rouge BKO, 8-10px radius) -->
                        <button 
                            type="button"
                            onclick="if(window.Livewire) { Livewire.dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: {{ $product->isDigital() ? '1' : 'parseInt(document.querySelector(\'[x-text=quantity]\')?.textContent || 1)' }} }); }"
                            @click="if(window.Livewire) { Livewire.dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: {{ $product->isDigital() ? '1' : 'quantity' }} }); }"
                            class="w-full h-11 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-sm rounded-lg flex items-center justify-center gap-2 smooth-transition cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                            </svg>
                            <span>{{ $product->isDigital() ? 'Ajouter au panier' : 'Ajouter au panier' }}</span>
                        </button>

                        <!-- CTA 2 : Acheter maintenant / Acheter et télécharger -->
                        <form action="{{ route('cart.buy-now', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" :value="{{ $product->isDigital() ? '1' : 'quantity' }}" value="1">
                            <button 
                                type="submit"
                                class="w-full h-10 bg-[#111111] hover:bg-neutral-800 text-white font-semibold text-xs rounded-lg flex items-center justify-center gap-2 smooth-transition cursor-pointer"
                            >
                                @if($product->isDigital())
                                    <svg class="w-4 h-4 text-[#F7B500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span>Acheter et télécharger</span>
                                @else
                                    <span>Acheter maintenant</span>
                                @endif
                            </button>
                        </form>
                    </div>

                    <!-- Conditions Livraison Bamako & Paiement -->
                    <div class="mt-5 p-3.5 rounded-lg bg-[#F8F8F8] border border-[#ECECEC] space-y-2 text-xs text-[#6B7280]">
                        @if($product->isDigital())
                            <p><strong class="text-[#1C1C1C]">Livraison :</strong> Immédiate et gratuite. Vos fichiers seront disponibles dans votre espace client et envoyés par email.</p>
                            <p><strong class="text-[#1C1C1C]">Paiement :</strong> Orange Money Mali (#144#) sécurisé.</p>
                        @else
                            <p><strong class="text-[#1C1C1C]">Livraison :</strong> Livraison dans tous les quartiers de Bamako sous 3h (1 500 FCFA, offerte dès 50 000 FCFA).</p>
                            <p><strong class="text-[#1C1C1C]">Paiement :</strong> Orange Money Mali (#144#) ou espèces à la livraison.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Onglets Détails Produit -->
        <div class="mt-6 bg-white rounded-xl border border-[#ECECEC] p-5 sm:p-7">
            <div class="flex items-center gap-6 border-b border-[#ECECEC] pb-3 mb-5 text-sm">
                <button 
                    type="button" 
                    @click="activeTab = 'desc'"
                    class="font-semibold pb-2 -mb-3 border-b-2 smooth-transition cursor-pointer"
                    :class="activeTab === 'desc' ? 'border-[#E31E24] text-[#E31E24]' : 'border-transparent text-[#6B7280] hover:text-[#1C1C1C]'"
                >
                    Description
                </button>
                <button 
                    type="button" 
                    @click="activeTab = 'specs'"
                    class="font-semibold pb-2 -mb-3 border-b-2 smooth-transition cursor-pointer"
                    :class="activeTab === 'specs' ? 'border-[#E31E24] text-[#E31E24]' : 'border-transparent text-[#6B7280] hover:text-[#1C1C1C]'"
                >
                    Caractéristiques
                </button>
                <button 
                    type="button" 
                    @click="activeTab = 'reviews'"
                    class="font-semibold pb-2 -mb-3 border-b-2 smooth-transition cursor-pointer"
                    :class="activeTab === 'reviews' ? 'border-[#E31E24] text-[#E31E24]' : 'border-transparent text-[#6B7280] hover:text-[#1C1C1C]'"
                >
                    Avis ({{ $product->reviews_count }})
                </button>
            </div>

            <!-- Tab: Description -->
            <div x-show="activeTab === 'desc'" class="text-sm text-[#1C1C1C] leading-relaxed max-w-2xl">
                <p>{{ $product->description }}</p>
            </div>

            <!-- Tab: Caractéristiques -->
            <div x-show="activeTab === 'specs'" class="max-w-lg">
                @if($product->features)
                    <dl class="divide-y divide-[#ECECEC] text-xs">
                        @foreach($product->features as $key => $val)
                            <div class="py-2 flex justify-between">
                                <dt class="text-[#6B7280]">{{ $key }}</dt>
                                <dd class="font-medium text-[#1C1C1C]">{{ $val }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-xs text-[#6B7280]">Consultez l'emballage lors de la réception.</p>
                @endif
            </div>

            <!-- Tab: Avis -->
            <div x-show="activeTab === 'reviews'" class="space-y-3 max-w-xl text-xs">
                <div class="p-3 rounded-lg border border-[#ECECEC] bg-[#F8F8F8]">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-[#1C1C1C]">Client à Badalabougou</span>
                        <span class="text-[#F7B500] font-bold">★ 5/5</span>
                    </div>
                    <p class="text-[#6B7280]">Livraison rapide à domicile, emballage soigné.</p>
                </div>
            </div>
        </div>

        <!-- Produits Similaires -->
        @if($relatedProducts->count() > 0)
            <div class="mt-8">
                <h3 class="text-base font-bold text-[#1C1C1C] mb-4">Dans le même rayon</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                    @foreach($relatedProducts as $rel)
                        <x-product-card :product="$rel" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</x-layouts.app>
