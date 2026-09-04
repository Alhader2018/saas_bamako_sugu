<x-layouts.app title="{{ $product->name }} — BKO SU (Bamako Supermarché)">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
        mainImage: '{{ $product->image_url }}',
        quantity: 1,
        activeTab: 'desc'
    }">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-[#6B7280] mb-6">
            <a href="{{ route('home') }}" class="hover:text-[#E31E24]">Accueil</a>
            <span>/</span>
            <a href="{{ route('catalog', ['category' => $product->category->slug]) }}" class="hover:text-[#E31E24]">
                {{ $product->category->name }}
            </a>
            <span>/</span>
            <span class="text-[#1C1C1C] font-semibold truncate">{{ $product->name }}</span>
        </nav>

        <!-- Grille Fiche Produit (Section 13) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 bg-white rounded-3xl border border-[#ECECEC] p-5 sm:p-8 shadow-xs">
            
            <!-- Colonne Galerie Gauche (7 cols) -->
            <div class="lg:col-span-7 space-y-4">
                <!-- Grande Image Principale -->
                <div class="relative aspect-square w-full rounded-2xl overflow-hidden bg-[#F8F8F8] border border-[#ECECEC]">
                    <img 
                        :src="mainImage" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-cover object-center"
                    >

                    <!-- Badges -->
                    <div class="absolute top-4 left-4 flex flex-col gap-1.5 items-start">
                        @if($product->discount_percent)
                            <span class="bg-[#F7B500] text-[#111111] text-xs font-black px-3 py-1 rounded-full shadow-sm">
                                -{{ $product->discount_percent }}% DE RÉDUCTION
                            </span>
                        @endif
                        @if($product->badge)
                            <span class="bg-[#111111] text-white text-[11px] font-bold px-3 py-1 rounded-full shadow-sm">
                                {{ $product->badge }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Miniatures -->
                @if($product->gallery && count($product->gallery) > 1)
                    <div class="flex items-center gap-3 overflow-x-auto pb-2">
                        @foreach($product->gallery as $img)
                            <button 
                                type="button" 
                                @click="mainImage = '{{ $img }}'"
                                class="w-18 h-18 rounded-xl overflow-hidden border-2 smooth-transition shrink-0 cursor-pointer"
                                :class="mainImage === '{{ $img }}' ? 'border-[#E31E24] shadow-sm' : 'border-[#ECECEC] opacity-70 hover:opacity-100'"
                            >
                                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Colonne Informations Droite (5 cols) -->
            <div class="lg:col-span-5 flex flex-col justify-between space-y-6">
                <div>
                    <!-- Vendeur & Référence -->
                    <div class="flex items-center justify-between text-xs text-[#6B7280] mb-2">
                        <span class="font-semibold text-[#1C1C1C]">{{ $product->vendor_name }}</span>
                        <span>Réf: {{ $product->reference }}</span>
                    </div>

                    <!-- Titre Produit -->
                    <h1 class="text-xl sm:text-2xl font-black text-[#1C1C1C] tracking-tight leading-snug mb-3">
                        {{ $product->name }}
                    </h1>

                    <!-- Avis & Stock -->
                    <div class="flex items-center justify-between gap-4 pb-4 border-b border-[#ECECEC] text-xs">
                        <div class="flex items-center gap-1 text-[#F7B500] font-bold">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-[#1C1C1C]">{{ $product->rating }}</span>
                            <span class="text-[#6B7280] font-normal">({{ $product->reviews_count }} avis vérifiés)</span>
                        </div>

                        <div>
                            @if($product->stock > 0)
                                <span class="inline-flex items-center gap-1.5 text-emerald-700 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-full">
                                    <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                                    En stock à Bamako ({{ $product->stock }})
                                </span>
                            @else
                                <span class="text-[#DC2626] font-bold">Rupture momentanée</span>
                            @endif
                        </div>
                    </div>

                    <!-- Prix BKO SU en FCFA -->
                    <div class="py-4">
                        <x-price 
                            :price="$product->price" 
                            :originalPrice="$product->original_price" 
                            :discountPercent="$product->discount_percent"
                            size="xl"
                        />
                        <p class="text-xs text-[#6B7280] mt-1">Tous les prix sont nets et payables en FCFA.</p>
                    </div>

                    <!-- Sélecteur de Quantité & CTAs -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-bold text-[#1C1C1C] uppercase tracking-wider">Quantité :</span>
                            <div class="inline-flex items-center border border-[#ECECEC] rounded-xl bg-neutral-50 h-10 text-sm">
                                <button 
                                    type="button" 
                                    @click="if(quantity > 1) quantity--" 
                                    class="w-10 h-full flex items-center justify-center hover:bg-white text-[#1C1C1C] smooth-transition cursor-pointer font-bold"
                                >-</button>
                                <span class="w-12 text-center font-bold text-[#1C1C1C]" x-text="quantity"></span>
                                <button 
                                    type="button" 
                                    @click="quantity++" 
                                    class="w-10 h-full flex items-center justify-center hover:bg-white text-[#1C1C1C] smooth-transition cursor-pointer font-bold"
                                >+</button>
                            </div>
                        </div>

                        <!-- CTA 1 : Ajouter au panier (Rouge BKO) -->
                        <button 
                            type="button"
                            @click="Livewire.dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: quantity })"
                            class="w-full h-13 bg-[#E31E24] hover:bg-[#C9171D] text-white font-bold text-sm sm:text-base rounded-xl flex items-center justify-center gap-2.5 shadow-md shadow-red-500/25 smooth-transition cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                            </svg>
                            <span>Ajouter au panier</span>
                        </button>

                        <!-- CTA 2 : Acheter maintenant (Conversion Directe) -->
                        <button 
                            type="button"
                            @click="Livewire.dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: quantity }); window.location.href = '{{ route('checkout') }}';"
                            class="w-full h-12 bg-[#111111] hover:bg-neutral-800 text-white font-bold text-sm rounded-xl flex items-center justify-center gap-2 smooth-transition cursor-pointer"
                        >
                            <span>Acheter maintenant (Paiement immédiat)</span>
                        </button>
                    </div>

                    <!-- Encart Spécifique Bamako (Livraison & Orange Money) -->
                    <div class="mt-6 p-4 rounded-2xl bg-[#F8F8F8] border border-[#ECECEC] space-y-3 text-xs">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-[#E31E24]/10 text-[#E31E24] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 18H3c-.6 0-1-.4-1-1V5c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
                                    <path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"/>
                                    <circle cx="7" cy="18" r="2"/>
                                    <circle cx="17" cy="18" r="2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-[#1C1C1C]">Livraison à domicile à Bamako</p>
                                <p class="text-[#6B7280]">Livré aujourd'hui ou demain dans tous les quartiers. Frais standard 1 500 FCFA (Offert dès 50 000 FCFA).</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-[#F7B500]/20 text-[#111111] font-black flex items-center justify-center shrink-0">
                                OM
                            </div>
                            <div>
                                <p class="font-bold text-[#1C1C1C]">Paiement Orange Money Mali</p>
                                <p class="text-[#6B7280]">Réglez par code USSD #144# ou en espèces au coursier lors de la livraison.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Onglets Description, Caractéristiques et Avis -->
        <div class="mt-8 bg-white rounded-3xl border border-[#ECECEC] p-6 sm:p-8">
            <div class="flex items-center gap-6 border-b border-[#ECECEC] pb-4 mb-6">
                <button 
                    type="button" 
                    @click="activeTab = 'desc'"
                    class="text-sm font-bold pb-2 -mb-4 border-b-2 smooth-transition cursor-pointer"
                    :class="activeTab === 'desc' ? 'border-[#E31E24] text-[#E31E24]' : 'border-transparent text-[#6B7280] hover:text-[#1C1C1C]'"
                >
                    Description complète
                </button>
                <button 
                    type="button" 
                    @click="activeTab = 'specs'"
                    class="text-sm font-bold pb-2 -mb-4 border-b-2 smooth-transition cursor-pointer"
                    :class="activeTab === 'specs' ? 'border-[#E31E24] text-[#E31E24]' : 'border-transparent text-[#6B7280] hover:text-[#1C1C1C]'"
                >
                    Caractéristiques
                </button>
                <button 
                    type="button" 
                    @click="activeTab = 'reviews'"
                    class="text-sm font-bold pb-2 -mb-4 border-b-2 smooth-transition cursor-pointer"
                    :class="activeTab === 'reviews' ? 'border-[#E31E24] text-[#E31E24]' : 'border-transparent text-[#6B7280] hover:text-[#1C1C1C]'"
                >
                    Avis clients ({{ $product->reviews_count }})
                </button>
            </div>

            <!-- Tab 1: Description -->
            <div x-show="activeTab === 'desc'" class="text-sm text-[#1C1C1C] leading-relaxed max-w-3xl space-y-3">
                <p>{{ $product->description }}</p>
                <p class="text-xs text-[#6B7280]">
                    Article garanti par {{ $product->vendor_name }} sous le contrôle qualité rigoureux de la plateforme BKO SU.
                </p>
            </div>

            <!-- Tab 2: Caractéristiques -->
            <div x-show="activeTab === 'specs'" class="max-w-xl">
                @if($product->features)
                    <dl class="divide-y divide-[#ECECEC] text-xs">
                        @foreach($product->features as $key => $val)
                            <div class="py-2.5 flex justify-between">
                                <dt class="font-bold text-[#6B7280]">{{ $key }}</dt>
                                <dd class="font-semibold text-[#1C1C1C]">{{ $val }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-xs text-[#6B7280]">Consultez la fiche d'emballage du produit lors de la livraison.</p>
                @endif
            </div>

            <!-- Tab 3: Avis -->
            <div x-show="activeTab === 'reviews'" class="space-y-4 max-w-2xl">
                <div class="p-4 rounded-2xl bg-neutral-50 border border-[#ECECEC]">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-xs text-[#1C1C1C]">Amadou S. (Badalabougou)</span>
                        <span class="text-[11px] text-[#F7B500] font-bold">⭐⭐⭐⭐⭐ 5/5</span>
                    </div>
                    <p class="text-xs text-[#6B7280]">Livré en 2h chrono chez moi. Produit conforme et livreur très courtois. Paiement Orange Money rapide.</p>
                </div>
                <div class="p-4 rounded-2xl bg-neutral-50 border border-[#ECECEC]">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-xs text-[#1C1C1C]">Aïssata D. (ACI 2000)</span>
                        <span class="text-[11px] text-[#F7B500] font-bold">⭐⭐⭐⭐⭐ 5/5</span>
                    </div>
                    <p class="text-xs text-[#6B7280]">Excellente qualité, très bien emballé. Je recommande vivement BKO SU !</p>
                </div>
            </div>
        </div>

        <!-- Produits Similaires -->
        @if($relatedProducts->count() > 0)
            <div class="mt-14">
                <h3 class="text-xl font-black text-[#1C1C1C] mb-6 tracking-tight">Dans le même rayon</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($relatedProducts as $rel)
                        <x-product-card :product="$rel" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</x-layouts.app>
