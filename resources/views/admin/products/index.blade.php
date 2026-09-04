<x-admin.layout title="Catalogue Produits" :breadcrumb="['Produits' => route('admin.products.index')]">
    <!-- Header -->
    <x-admin.page-header title="Produits" description="Gestion du catalogue et des stocks de la boutique">
        <x-slot:actions>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md transition-colors shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Ajouter un produit</span>
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Tabs WooCommerce (Section 19) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-4 border-b border-[#E5E7EB] text-xs">
        <a href="{{ route('admin.products.index') }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ empty($stockFilter) ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Tous <span class="ml-1 opacity-70">({{ $counts['all'] }})</span>
        </a>
        <a href="{{ route('admin.products.index', ['stock_filter' => 'low']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $stockFilter === 'low' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Stock faible (≤ 5) <span class="ml-1 opacity-70">({{ $counts['low'] }})</span>
        </a>
        <a href="{{ route('admin.products.index', ['stock_filter' => 'out']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $stockFilter === 'out' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            En rupture (0) <span class="ml-1 opacity-70">({{ $counts['out'] }})</span>
        </a>
    </div>

    <!-- Filtres -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg p-3 mb-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-center gap-2.5 text-xs">
            @if($stockFilter)
                <input type="hidden" name="stock_filter" value="{{ $stockFilter }}">
            @endif

            <div class="relative flex-1 min-w-[200px] w-full sm:w-auto">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Rechercher produit, SKU, marque..." 
                       class="w-full h-8 pl-8 pr-3 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded focus:bg-white focus:border-[#E31E24] focus:outline-none">
                <svg class="w-3.5 h-3.5 text-[#9CA3AF] absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <select name="category_id" class="h-8 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded px-2 text-[#374151] focus:border-[#E31E24] focus:outline-none w-full sm:w-auto">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="h-8 px-3 bg-[#111111] hover:bg-black text-white rounded font-medium transition-colors w-full sm:w-auto">
                Filtrer
            </button>

            @if($search || $categoryId || $stockFilter)
                <a href="{{ route('admin.products.index') }}" class="h-8 px-2 flex items-center text-[#6B7280] hover:text-[#111111]">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <!-- Tableau Produits (Section 20 : miniatures 40-48px) -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px] border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                        <th class="py-2.5 px-4 w-14">Image</th>
                        <th class="py-2.5 px-3">Produit</th>
                        <th class="py-2.5 px-3">SKU</th>
                        <th class="py-2.5 px-3">Catégorie</th>
                        <th class="py-2.5 px-3">Prix</th>
                        <th class="py-2.5 px-3">Stock actuel</th>
                        <th class="py-2.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($products as $product)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-2.5 px-4">
                                <img src="{{ $product->image_url ?: 'https://placehold.co/80x80/f5f5f5/999999?text=IMG' }}" 
                                     class="w-11 h-11 object-cover rounded-md border border-[#E5E7EB]" 
                                     alt="{{ $product->name }}">
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-medium text-[#111111] hover:text-[#E31E24]">
                                    <a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a>
                                </div>
                                <div class="text-[11px] text-[#6B7280] flex items-center gap-2 mt-0.5">
                                    @if($product->vendor_name)
                                        <span>{{ $product->vendor_name }}</span>
                                    @endif
                                    @if($product->is_flash_deal)
                                        <span class="text-[#E31E24] font-semibold">Flash Deal</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-xs font-mono text-[#6B7280] whitespace-nowrap">
                                {{ $product->reference }}
                            </td>
                            <td class="py-2.5 px-3 text-xs text-[#374151]">
                                <span class="bg-[#F3F4F6] px-2 py-0.5 rounded text-[11px]">
                                    {{ $product->category?->name ?: 'Sans catégorie' }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 font-semibold text-[#111111] whitespace-nowrap">
                                <div>{{ $product->formatted_price }}</div>
                                @if($product->original_price)
                                    <div class="text-[11px] text-[#9CA3AF] line-through font-normal">{{ $product->formatted_original_price }}</div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <form action="{{ route('admin.products.stock', $product) }}" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    <input type="number" 
                                           name="stock" 
                                           value="{{ $product->stock }}" 
                                           min="0" 
                                           class="w-16 h-7 text-xs border border-[#D1D5DB] rounded px-1.5 text-center focus:border-[#E31E24] focus:outline-none {{ $product->stock <= 0 ? 'bg-red-50 text-red-700 font-bold border-red-300' : ($product->stock <= 5 ? 'bg-amber-50 text-amber-800 font-bold border-amber-300' : '') }}">
                                    <button type="submit" title="Enregistrer le stock" class="px-2 py-1 bg-white border border-[#D1D5DB] hover:bg-[#F9FAFB] text-[11px] rounded text-[#374151]">
                                        ✓
                                    </button>
                                </form>
                            </td>
                            <td class="py-2.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="px-2.5 py-1 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded hover:bg-[#F9FAFB] hover:text-[#111111]">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Confirmer la suppression de ce produit ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-500 hover:text-red-700 rounded hover:bg-red-50" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.empty-state title="Aucun produit trouvé" message="Modifiez les filtres ou ajoutez un nouveau produit." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-3 border-t border-[#E5E7EB] bg-white">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-admin.layout>
