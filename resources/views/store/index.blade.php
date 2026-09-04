<x-layouts.app title="BKO SU — Bamako Supermarché | Tout Bamako dans un seul panier">

    <!-- 1. Hero Commercial BKO SU (Section 8) -->
    <section class="relative bg-white border-b border-[#ECECEC] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Colonne Texte & CTA -->
                <div class="lg:col-span-6 space-y-6 text-left">
                    <!-- Badge Confiance -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 border border-red-100 text-[#E31E24] text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-[#E31E24] animate-ping"></span>
                        Marketplace N°1 à Bamako
                    </div>

                    <!-- Titre Officiel BKO SU -->
                    <h1 class="text-3xl sm:text-5xl lg:text-5.5xl font-black text-[#1C1C1C] tracking-tight leading-[1.08]">
                        Tout Bamako dans <br class="hidden sm:inline">
                        <span class="text-[#E31E24]">un seul panier.</span>
                    </h1>

                    <!-- Sous-titre Officiel -->
                    <p class="text-base sm:text-lg text-[#6B7280] leading-relaxed max-w-lg">
                        Courses, mode, high-tech et bien plus, avec livraison rapide à Bamako. Payez par Orange Money ou à la réception.
                    </p>

                    <!-- CTAs Officiels (Section 8) -->
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <!-- CTA Principal Rouge BKO -->
                        <x-button variant="primary" size="lg" href="{{ route('catalog') }}">
                            <span>Commander maintenant</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </x-button>

                        <!-- CTA Secondaire Découvrir les offres -->
                        <x-button variant="outline" size="lg" href="#offres-flash">
                            <span>Découvrir les offres</span>
                            <span class="w-2 h-2 rounded-full bg-[#F7B500]"></span>
                        </x-button>
                    </div>

                    <!-- Micro-Statistiques de Confiance -->
                    <div class="pt-6 border-t border-[#ECECEC] grid grid-cols-3 gap-4 text-left">
                        <div>
                            <p class="text-lg sm:text-xl font-black text-[#1C1C1C]">+50 000</p>
                            <p class="text-[11px] text-[#6B7280]">Clients satisfaits</p>
                        </div>
                        <div>
                            <p class="text-lg sm:text-xl font-black text-[#E31E24]">&lt; 3 Heures</p>
                            <p class="text-[11px] text-[#6B7280]">Livraison Bamako</p>
                        </div>
                        <div>
                            <p class="text-lg sm:text-xl font-black text-[#F7B500]">100%</p>
                            <p class="text-[11px] text-[#6B7280]">Paiement garanti</p>
                        </div>
                    </div>
                </div>

                <!-- Colonne Visuelle Premium -->
                <div class="lg:col-span-6 relative">
                    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-tr from-neutral-100 to-red-50/50 p-2 sm:p-4 border border-[#ECECEC] shadow-xl">
                        <img 
                            src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=85" 
                            alt="Marketplace Bamako Supermarché BKO SU" 
                            class="w-full h-72 sm:h-96 object-cover rounded-2xl shadow-inner"
                        >

                        <!-- Carte Flottante 1 : Livraison Express -->
                        <div class="absolute top-6 left-6 bg-white/95 backdrop-blur-md rounded-2xl p-3.5 border border-[#ECECEC] shadow-lg flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#E31E24] text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 18H3c-.6 0-1-.4-1-1V5c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/>
                                    <path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"/>
                                    <circle cx="7" cy="18" r="2"/>
                                    <circle cx="17" cy="18" r="2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#1C1C1C]">Livraison en cours</p>
                                <p class="text-[11px] text-[#6B7280]">ACI 2000 & Badalabougou</p>
                            </div>
                        </div>

                        <!-- Carte Flottante 2 : Paiement Orange Money -->
                        <div class="absolute bottom-6 right-6 bg-white/95 backdrop-blur-md rounded-2xl p-3.5 border border-[#ECECEC] shadow-lg flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#F7B500] text-black flex items-center justify-center font-black text-xs">
                                OM
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#1C1C1C]">Orange Money Mali</p>
                                <p class="text-[11px] text-emerald-600 font-semibold">Paiement instantané #144#</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Catégories Principales (Section 10) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="flex items-end justify-between mb-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#E31E24]">Explorez par rayon</span>
                <h2 class="text-xl sm:text-2xl font-black text-[#1C1C1C] mt-0.5 tracking-tight">Nos Catégories Principales</h2>
            </div>
            <a href="{{ route('catalog') }}" class="text-xs sm:text-sm font-bold text-[#E31E24] hover:underline flex items-center gap-1">
                <span>Tous les rayons</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 sm:gap-4">
            @foreach($categories as $category)
                <x-category-card :category="$category" />
            @endforeach
        </div>
    </section>

    <!-- 3. Offres Flash BKO SU (Section 12 : Fond Noir, Badges Jaunes, CTA Rouge) -->
    <section id="offres-flash" class="bg-[#111111] text-white py-12 sm:py-16 my-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 pb-6 border-b border-neutral-800">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F7B500] text-[#111111] text-xs font-black mb-2">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                        OFFRES FLASH DU JOUR
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Ventes Flash Spéciales Bamako
                    </h2>
                    <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                        Quantités limitées aux meilleurs prix du marché. Prix garantis jusqu'à épuisement.
                    </p>
                </div>

                <!-- Compte à Rebours Réel BKO SU (Section 12) -->
                <div class="flex items-center gap-3 bg-neutral-900/90 border border-neutral-800 px-4 py-2.5 rounded-2xl" x-data="{
                    hours: 14,
                    minutes: 32,
                    seconds: 45,
                    init() {
                        setInterval(() => {
                            if (this.seconds > 0) {
                                this.seconds--;
                            } else {
                                this.seconds = 59;
                                if (this.minutes > 0) {
                                    this.minutes--;
                                } else {
                                    this.minutes = 59;
                                    if (this.hours > 0) this.hours--;
                                }
                            }
                        }, 1000);
                    }
                }">
                    <span class="text-xs text-neutral-400 font-medium">Fin dans :</span>
                    <div class="flex items-center gap-1.5 text-sm font-mono font-black text-[#F7B500]">
                        <span class="bg-neutral-800 px-2 py-1 rounded-lg" x-text="String(hours).padStart(2, '0')"></span>
                        <span>:</span>
                        <span class="bg-neutral-800 px-2 py-1 rounded-lg" x-text="String(minutes).padStart(2, '0')"></span>
                        <span>:</span>
                        <span class="bg-neutral-800 px-2 py-1 rounded-lg" x-text="String(seconds).padStart(2, '0')"></span>
                    </div>
                </div>
            </div>

            <!-- Grille Offres Flash -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($flashDeals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. Produits Populaires -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="flex items-end justify-between mb-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#E31E24]">Plébiscité par les familles</span>
                <h2 class="text-xl sm:text-2xl font-black text-[#1C1C1C] mt-0.5 tracking-tight">Produits les plus commandés</h2>
            </div>
            <a href="{{ route('catalog', ['sort' => 'popular']) }}" class="text-xs sm:text-sm font-bold text-[#E31E24] hover:underline">
                Tout voir →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($popularProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- 5. Bannière Commerciale Fraîcheur Bamako -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="rounded-3xl bg-gradient-to-r from-amber-500 to-amber-600 p-6 sm:p-10 text-neutral-900 flex flex-col md:flex-row items-center justify-between gap-6 shadow-md">
            <div class="space-y-2 text-center md:text-left">
                <span class="bg-black text-white text-[11px] font-black px-3 py-1 rounded-full uppercase tracking-wider">
                    Fraîcheur Quotidienne
                </span>
                <h3 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Fruits et Maraîchage local récoltés à l'aube
                </h3>
                <p class="text-sm text-neutral-900 max-w-xl font-medium">
                    Commandez vos tomates de Baguineda, mangues de Sikasso et oignons frais livrés chez vous avant midi.
                </p>
            </div>
            <x-button variant="secondary" size="lg" href="{{ route('catalog', ['category' => 'fruits-legumes']) }}">
                Commander les produits frais
            </x-button>
        </div>
    </section>

    <!-- 6. Nouveautés & Recommandations -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            
            <!-- Nouveautés -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#ECECEC]">
                    <h3 class="text-lg font-black text-[#1C1C1C]">Arrivages Récentes</h3>
                    <a href="{{ route('catalog', ['sort' => 'newest']) }}" class="text-xs font-bold text-[#E31E24]">Voir tout</a>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    @foreach($newArrivals as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>

            <!-- Recommandations BKO SU -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#ECECEC]">
                    <h3 class="text-lg font-black text-[#1C1C1C]">Sélection BKO SU</h3>
                    <a href="{{ route('catalog') }}" class="text-xs font-bold text-[#E31E24]">Voir tout</a>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    @foreach($recommendedProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!-- 7. Boutiques Partenaires Locales de Bamako (Section 9.8) -->
    <section class="bg-white border-t border-[#ECECEC] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-[#E31E24]">Écosystème Local</span>
                <h2 class="text-2xl font-black text-[#1C1C1C] mt-1 tracking-tight">Nos Boutiques et Marques Partenaires</h2>
                <p class="text-xs sm:text-sm text-[#6B7280] mt-1">
                    BKO SU regroupe les commerçants réputés de Bamako pour une garantie qualité absolue.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($partnerShops as $shop)
                    <div class="bg-[#F8F8F8] rounded-2xl border border-[#ECECEC] p-4 flex items-center gap-4 hover:border-[#E31E24]/30 smooth-transition">
                        <img src="{{ $shop['image'] }}" alt="{{ $shop['name'] }}" class="w-16 h-16 rounded-xl object-cover border border-[#ECECEC] shrink-0">
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-[#E31E24] bg-red-50 px-2 py-0.5 rounded-full">
                                {{ $shop['badge'] }}
                            </span>
                            <h4 class="text-xs sm:text-sm font-bold text-[#1C1C1C] mt-1 truncate">{{ $shop['name'] }}</h4>
                            <p class="text-[11px] text-[#6B7280] truncate">{{ $shop['category'] }}</p>
                            <p class="text-[10px] text-neutral-400 truncate">{{ $shop['location'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</x-layouts.app>
