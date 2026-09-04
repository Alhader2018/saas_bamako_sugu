<x-layouts.app title="Catalogue & Rayons — BKO SU (Bamako Supermarché)">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-[#6B7280] mb-6">
            <a href="{{ route('home') }}" class="hover:text-[#E31E24]">Accueil</a>
            <span>/</span>
            <span class="text-[#1C1C1C] font-semibold">
                {{ $activeCategory ? $activeCategory->name : 'Tous les rayons' }}
            </span>
        </nav>

        <!-- Titre & Description -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-[#1C1C1C] tracking-tight">
                    {{ $activeCategory ? $activeCategory->name : 'Tous les produits BKO SU' }}
                </h1>
                <p class="text-xs sm:text-sm text-[#6B7280] mt-1">
                    {{ $activeCategory ? $activeCategory->description : 'Explorez l\'ensemble du catalogue e-commerce disponible pour livraison à Bamako.' }}
                </p>
            </div>

            <!-- Tri -->
            <form method="GET" action="{{ route('catalog') }}" class="flex items-center gap-2 text-xs">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <label for="sort" class="font-bold text-[#1C1C1C] shrink-0">Trier par :</label>
                <select 
                    id="sort" 
                    name="sort" 
                    onchange="this.form.submit()" 
                    class="h-10 px-3 pr-8 bg-white border border-[#ECECEC] rounded-xl text-xs font-semibold text-[#1C1C1C] outline-none cursor-pointer"
                >
                    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Populaires & Avis</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Nouveautés</option>
                </select>
            </form>
        </div>

        <!-- Pill Strip des Catégories -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 no-scrollbar">
            <a 
                href="{{ route('catalog') }}" 
                class="px-4 py-2 rounded-xl text-xs font-bold shrink-0 smooth-transition {{ !$activeCategory ? 'bg-[#E31E24] text-white shadow-xs' : 'bg-white text-[#1C1C1C] border border-[#ECECEC] hover:border-neutral-400' }}"
            >
                Tous les rayons
            </a>

            @foreach($categories as $cat)
                <a 
                    href="{{ route('catalog', ['category' => $cat->slug]) }}" 
                    class="px-4 py-2 rounded-xl text-xs font-bold shrink-0 smooth-transition {{ $activeCategory && $activeCategory->id === $cat->id ? 'bg-[#E31E24] text-white shadow-xs' : 'bg-white text-[#1C1C1C] border border-[#ECECEC] hover:border-neutral-400' }}"
                >
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Notification de recherche -->
        @if(request('search'))
            <div class="mb-6 p-4 rounded-2xl bg-white border border-[#ECECEC] flex items-center justify-between">
                <p class="text-xs text-[#1C1C1C]">
                    Résultats pour le mot-clé : <strong class="text-[#E31E24]">"{{ request('search') }}"</strong>
                </p>
                <a href="{{ route('catalog', ['category' => request('category')]) }}" class="text-xs font-bold text-neutral-500 hover:text-[#1C1C1C]">
                    Effacer la recherche ✕
                </a>
            </div>
        @endif

        <!-- Grille des Produits -->
        @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white rounded-3xl border border-[#ECECEC] p-12 text-center my-8">
                <div class="w-16 h-16 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-400 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#1C1C1C] mb-1">Aucun produit ne correspond à ces critères</h3>
                <p class="text-xs text-[#6B7280] max-w-sm mx-auto mb-6">Modifiez vos filtres ou effectuez une autre recherche pour trouver vos articles.</p>
                <x-button variant="primary" href="{{ route('catalog') }}">
                    Réinitialiser les filtres
                </x-button>
            </div>
        @endif

    </div>

</x-layouts.app>
