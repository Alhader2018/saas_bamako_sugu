<x-layouts.app title="Catalogue & Rayons — BKO SU (Bamako Supermarché)">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-1.5 text-xs text-[#6B7280] mb-4">
            <a href="{{ route('home') }}" class="hover:text-[#E31E24]">Accueil</a>
            <span>/</span>
            <span class="text-[#1C1C1C]">
                {{ $activeCategory ? $activeCategory->name : 'Tous les rayons' }}
            </span>
        </nav>

        <!-- Titre & Tri -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-[#1C1C1C]">
                    {{ $activeCategory ? $activeCategory->name : 'Catalogue des produits' }}
                </h1>
                <p class="text-xs text-[#6B7280] mt-0.5">
                    {{ $activeCategory ? $activeCategory->description : 'Articles disponibles pour livraison à Bamako.' }}
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
                <label for="sort" class="text-[#6B7280] shrink-0">Trier par :</label>
                <select 
                    id="sort" 
                    name="sort" 
                    onchange="this.form.submit()" 
                    class="h-9 px-2.5 pr-7 bg-white border border-[#ECECEC] rounded-md text-xs font-medium text-[#1C1C1C] outline-none cursor-pointer"
                >
                    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Populaires</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Nouveautés</option>
                </select>
            </form>
        </div>

        <!-- Filtres Catégories (Boutons 6-8px, pas de grosses capsules) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 no-scrollbar">
            <a 
                href="{{ route('catalog') }}" 
                class="px-3 py-1.5 rounded-md text-xs font-medium shrink-0 smooth-transition {{ !$activeCategory ? 'bg-[#111111] text-white' : 'bg-white text-[#1C1C1C] border border-[#ECECEC] hover:border-neutral-300' }}"
            >
                Tous les rayons
            </a>

            @foreach($categories as $cat)
                <a 
                    href="{{ route('catalog', ['category' => $cat->slug]) }}" 
                    class="px-3 py-1.5 rounded-md text-xs font-medium shrink-0 smooth-transition {{ $activeCategory && $activeCategory->id === $cat->id ? 'bg-[#111111] text-white' : 'bg-white text-[#1C1C1C] border border-[#ECECEC] hover:border-neutral-300' }}"
                >
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- État de recherche -->
        @if(request('search'))
            <div class="mb-5 p-3 rounded-lg bg-white border border-[#ECECEC] flex items-center justify-between text-xs">
                <p class="text-[#1C1C1C]">
                    Résultats pour : <strong>"{{ request('search') }}"</strong>
                </p>
                <a href="{{ route('catalog', ['category' => request('category')]) }}" class="text-[#6B7280] hover:text-[#1C1C1C]">
                    Effacer ✕
                </a>
            </div>
        @endif

        <!-- Grille Produits -->
        @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg border border-[#ECECEC] p-10 text-center my-6">
                <p class="text-sm font-semibold text-[#1C1C1C] mb-1">Aucun produit trouvé</p>
                <p class="text-xs text-[#6B7280] mb-4">Modifiez vos critères de recherche.</p>
                <x-button variant="primary" size="sm" href="{{ route('catalog') }}">
                    Voir tout le catalogue
                </x-button>
            </div>
        @endif

    </div>

</x-layouts.app>
