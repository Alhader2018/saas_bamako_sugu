<x-layouts.app title="BKO SU — Bamako Supermarché | Tout Bamako dans un seul panier">

    <!-- 1. Hero Commercial E-commerce BKO SU (Section 8 & 13) -->
    <section class="bg-white border-b border-[#ECECEC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Colonne Texte & Actions -->
                <div class="lg:col-span-6 space-y-5">
                    <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-bold text-[#1C1C1C] tracking-tight leading-[1.12]">
                        Tout Bamako dans <br class="hidden sm:inline">
                        <span class="text-[#E31E24]">un seul panier.</span>
                    </h1>

                    <p class="text-sm sm:text-base text-[#6B7280] leading-relaxed max-w-md">
                        Courses quotidiennes, bazin malien, high-tech et produits frais livrés directement chez vous ou au bureau à Bamako.
                    </p>

                    <!-- CTAs fonctionnels -->
                    <div class="flex items-center gap-3 pt-1">
                        <x-button variant="primary" size="lg" href="{{ route('catalog') }}">
                            Commander maintenant
                        </x-button>

                        <x-button variant="outline" size="lg" href="#offres-flash">
                            Voir les offres flash
                        </x-button>
                    </div>

                    <!-- Engagements commerciaux sobres -->
                    <div class="pt-5 border-t border-[#ECECEC] flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-[#6B7280]">
                        <span class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                            Livraison en moins de 3h
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#F7B500]"></span>
                            Orange Money & Espèces
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                            Marchands vérifiés
                        </span>
                    </div>
                </div>

                <!-- Photographie Commerciale Réelle (Pas de cartes flottantes SaaS) -->
                <div class="lg:col-span-6">
                    <div class="rounded-xl overflow-hidden border border-[#ECECEC] bg-[#F8F8F8]">
                        <img 
                            src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1000&q=80" 
                            alt="Marketplace Bamako Supermarché" 
                            class="w-full h-64 sm:h-80 object-cover"
                        >
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Catégories Principales (Section 10) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg sm:text-xl font-bold text-[#1C1C1C]">Rayons principaux</h2>
            <a href="{{ route('catalog') }}" class="text-xs font-semibold text-[#E31E24] hover:underline">
                Tous les rayons →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2.5 sm:gap-3">
            @foreach($categories as $category)
                <x-category-card :category="$category" />
            @endforeach
        </div>
    </section>

    <!-- 3. Offres Flash BKO SU (Section 12 : Fond Noir, Badges Jaunes, CTA Rouge) -->
    <section id="offres-flash" class="bg-[#111111] text-white py-10 my-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-neutral-800">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-white">
                        Offres Flash
                    </h2>
                    <p class="text-xs text-neutral-400 mt-0.5">
                        Promotions limitées au stock disponible.
                    </p>
                </div>

                <!-- Compte à Rebours Sobre (Section 12) -->
                <div class="flex items-center gap-2 text-xs" x-data="{
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
                    <span class="text-neutral-400">Temps restant :</span>
                    <span class="font-mono font-bold text-[#F7B500] bg-neutral-900 px-2 py-1 rounded border border-neutral-800">
                        <span x-text="String(hours).padStart(2, '0')"></span>h 
                        <span x-text="String(minutes).padStart(2, '0')"></span>m 
                        <span x-text="String(seconds).padStart(2, '0')"></span>s
                    </span>
                </div>
            </div>

            <!-- Grille Produits Flash -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($flashDeals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. Produits Populaires -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg sm:text-xl font-bold text-[#1C1C1C]">Produits populaires</h2>
            <a href="{{ route('catalog', ['sort' => 'popular']) }}" class="text-xs font-semibold text-[#E31E24] hover:underline">
                Voir tout →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            @foreach($popularProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- 5. Bannière Marché Frais Locale (Sobre, sans gradient tape-à-l'œil) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="rounded-xl border border-[#ECECEC] bg-white p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="space-y-1 text-center sm:text-left">
                <h3 class="text-base sm:text-lg font-bold text-[#1C1C1C]">
                    Fruits & Légumes frais de Baguineda et Sikasso
                </h3>
                <p class="text-xs sm:text-sm text-[#6B7280] max-w-lg">
                    Approvisionnement quotidien auprès des producteurs maraîchers locaux avec livraison express le matin.
                </p>
            </div>
            <x-button variant="primary" size="md" href="{{ route('catalog', ['category' => 'fruits-legumes']) }}">
                Découvrir le rayon frais
            </x-button>
        </div>
    </section>

    <!-- 6. Arrivages Récents & Recommandations -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Arrivages récents -->
            <div>
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-[#ECECEC]">
                    <h3 class="text-base font-bold text-[#1C1C1C]">Arrivages récents</h3>
                    <a href="{{ route('catalog', ['sort' => 'newest']) }}" class="text-xs font-semibold text-[#E31E24]">Voir tout</a>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($newArrivals as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>

            <!-- Recommandations -->
            <div>
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-[#ECECEC]">
                    <h3 class="text-base font-bold text-[#1C1C1C]">Sélection du moment</h3>
                    <a href="{{ route('catalog') }}" class="text-xs font-semibold text-[#E31E24]">Voir tout</a>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($recommendedProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!-- 7. Boutiques et Vendeurs Partenaires (Sobre et crédible) -->
    <section class="bg-white border-t border-[#ECECEC] py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-5">
                <h2 class="text-lg font-bold text-[#1C1C1C]">Boutiques partenaires à Bamako</h2>
                <p class="text-xs text-[#6B7280]">Commerçants et distributeurs agréés sur BKO SU.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($partnerShops as $shop)
                    <div class="bg-[#F8F8F8] rounded-lg border border-[#ECECEC] p-3 flex items-center gap-3">
                        <img src="{{ $shop['image'] }}" alt="{{ $shop['name'] }}" class="w-12 h-12 rounded object-cover border border-[#ECECEC] shrink-0">
                        <div class="min-w-0 text-xs">
                            <h4 class="font-semibold text-[#1C1C1C] truncate">{{ $shop['name'] }}</h4>
                            <p class="text-[#6B7280] text-[11px] truncate">{{ $shop['category'] }}</p>
                            <p class="text-neutral-400 text-[10px] truncate">{{ $shop['location'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</x-layouts.app>
