<x-layouts.app title="{{ $product->name }} — BKO SU (Bamako Supermarché)">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{ 
        mainImage: '{{ $product->image_url }}',
        quantity: 1,
        activeTab: window.location.hash === '#tab-reviews' ? 'reviews' : 'desc'
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
                        <button type="button" @click="activeTab = 'reviews'; document.getElementById('tab-reviews-section')?.scrollIntoView({behavior: 'smooth'})" class="flex items-center gap-1 text-[#6B7280] hover:text-[#E31E24] cursor-pointer text-left">
                            <span class="text-[#F7B500] font-bold">★ {{ number_format($product->rating, 1) }}</span>
                            <span>({{ $product->reviews_count }} avis)</span>
                        </button>

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
            <div x-show="activeTab === 'desc'" class="text-sm text-[#1C1C1C] leading-relaxed max-w-3xl">
                @if(!empty($product->description))
                    <div class="prose prose-neutral max-w-none text-[#1C1C1C] leading-relaxed space-y-2 [&_h1]:text-lg [&_h1]:font-bold [&_h2]:text-base [&_h2]:font-bold [&_h3]:text-sm [&_h3]:font-semibold [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_blockquote]:border-l-4 [&_blockquote]:border-[#E31E24] [&_blockquote]:pl-3 [&_blockquote]:italic [&_a]:text-[#E31E24] [&_a]:underline">
                        {!! $product->description !!}
                    </div>
                @else
                    <p class="text-[#6B7280] italic">Aucune description détaillée n'a été renseignée pour ce produit.</p>
                @endif
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
            <div x-show="activeTab === 'reviews'" id="tab-reviews-section" class="space-y-6 max-w-3xl" x-data="{ showReviewForm: false, rating: 5, hoverRating: 0 }">
                
                <!-- En-tête des avis & Note moyenne -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-[#F8F8F8] border border-[#ECECEC]">
                    <div class="flex items-center gap-4">
                        <div class="text-center sm:text-left">
                            <span class="text-3xl font-bold text-[#1C1C1C]">{{ number_format($product->rating, 1) }}</span>
                            <span class="text-xs text-[#6B7280]">/5</span>
                            <div class="flex items-center justify-center sm:justify-start gap-1 text-[#F7B500] text-sm mt-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($product->rating) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                        <div class="border-l border-[#D1D5DB] pl-4 text-xs text-[#6B7280]">
                            <p class="font-semibold text-[#1C1C1C]">{{ $product->reviews_count }} avis client{{ $product->reviews_count > 1 ? 's' : '' }}</p>
                            <p>Les avis sont vérifiés et déposés par les clients de la communauté BKO SU.</p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        @click="showReviewForm = !showReviewForm"
                        class="h-9 px-4 bg-[#E31E24] hover:bg-[#C9171D] text-white text-xs font-semibold rounded-lg flex items-center justify-center gap-1.5 smooth-transition cursor-pointer shrink-0"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span x-text="showReviewForm ? 'Masquer le formulaire' : 'Donner mon avis'"></span>
                    </button>
                </div>

                <!-- Formulaire de dépôt d'avis (toggleable) -->
                <div 
                    x-show="showReviewForm" 
                    x-cloak 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="p-5 rounded-xl border border-[#ECECEC] bg-white shadow-xs space-y-4"
                >
                    <h3 class="text-sm font-bold text-[#1C1C1C] pb-2 border-b border-[#ECECEC]">
                        Partager votre avis sur {{ $product->name }}
                    </h3>

                    <form action="{{ route('product.review.store', $product) }}" method="POST" class="space-y-4 text-xs">
                        @csrf

                        <!-- Sélecteur d'étoiles interactif -->
                        <div>
                            <label class="block font-medium text-[#1C1C1C] mb-1.5">Votre note globale <span class="text-red-500">*</span></label>
                            <input type="hidden" name="rating" :value="rating">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button 
                                        type="button" 
                                        @click="rating = {{ $i }}"
                                        @mouseenter="hoverRating = {{ $i }}"
                                        @mouseleave="hoverRating = 0"
                                        class="text-2xl cursor-pointer transition-transform hover:scale-110 focus:outline-none"
                                        :class="(hoverRating ? hoverRating >= {{ $i }} : rating >= {{ $i }}) ? 'text-[#F7B500]' : 'text-neutral-300'"
                                    >
                                        ★
                                    </button>
                                @endfor
                                <span class="ml-2 text-xs font-semibold text-[#1C1C1C]" x-text="rating + ' sur 5 étoiles'"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-medium text-[#1C1C1C] mb-1">Votre nom ou prénom <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    name="customer_name" 
                                    value="{{ old('customer_name', auth()->user()?->name ?? '') }}" 
                                    required 
                                    placeholder="Ex: Moussa Diarra" 
                                    class="w-full h-9 px-3 bg-white text-[#1C1C1C] border border-[#ECECEC] rounded-lg focus:border-[#E31E24] focus:outline-none"
                                >
                            </div>
                            <div>
                                <label class="block font-medium text-[#1C1C1C] mb-1">Votre email (facultatif)</label>
                                <input 
                                    type="email" 
                                    name="customer_email" 
                                    value="{{ old('customer_email', auth()->user()?->email ?? '') }}" 
                                    placeholder="Ex: moussa@exemple.ml" 
                                    class="w-full h-9 px-3 bg-white text-[#1C1C1C] border border-[#ECECEC] rounded-lg focus:border-[#E31E24] focus:outline-none"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-[#1C1C1C] mb-1">Votre commentaire ou retour d'expérience <span class="text-red-500">*</span></label>
                            <textarea 
                                name="comment" 
                                rows="3" 
                                required 
                                placeholder="Dites-nous ce que vous avez pensé de la qualité du produit, du goût, de la matière ou de la livraison..."
                                class="w-full p-3 bg-white text-[#1C1C1C] border border-[#ECECEC] rounded-lg focus:border-[#E31E24] focus:outline-none"
                            >{{ old('comment') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-1">
                            <button 
                                type="button" 
                                @click="showReviewForm = false"
                                class="h-9 px-4 bg-neutral-100 hover:bg-neutral-200 text-[#1C1C1C] text-xs font-semibold rounded-lg smooth-transition"
                            >
                                Annuler
                            </button>
                            <button 
                                type="submit" 
                                class="h-9 px-5 bg-[#E31E24] hover:bg-[#C9171D] text-white text-xs font-semibold rounded-lg smooth-transition cursor-pointer"
                            >
                                Publier mon avis
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Liste des avis clients -->
                <div class="space-y-3">
                    @forelse($product->reviews as $rev)
                        <div class="p-4 rounded-xl border border-[#ECECEC] bg-white text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-[#1C1C1C]">{{ $rev->customer_name }}</span>
                                    @if($rev->is_verified_purchase)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Achat vérifié
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-[#9CA3AF]">{{ $rev->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="flex items-center gap-1 text-[#F7B500] text-xs font-bold">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                @endfor
                                <span class="ml-1 text-[#6B7280] font-normal">({{ $rev->rating }}/5)</span>
                            </div>

                            <p class="text-[#374151] leading-relaxed">{{ $rev->comment }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8 px-4 bg-[#F8F8F8] rounded-xl border border-[#ECECEC] text-xs space-y-2">
                            <p class="font-semibold text-[#1C1C1C]">Aucun avis n'a encore été déposé pour ce produit.</p>
                            <p class="text-[#6B7280]">Vous l'avez acheté ou testé ? Partagez votre expérience avec les autres clients !</p>
                            <button 
                                type="button" 
                                @click="showReviewForm = true"
                                class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-[#E31E24] hover:underline cursor-pointer"
                            >
                                + Soyez le premier à donner votre avis
                            </button>
                        </div>
                    @endforelse
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
