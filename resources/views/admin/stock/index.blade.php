<x-admin.layout title="Inventaire & Stock" :breadcrumb="['Stock' => route('admin.stock.index')]">
    <!-- Header -->
    <x-admin.page-header title="Inventaire & Stock" description="Suivi des niveaux de stock et réapprovisionnements">
        <x-slot:actions>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md transition-colors shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Nouveau produit</span>
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Résumé de l'inventaire -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-6">
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Unités physiques en rayon</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ number_format($totalStockUnits, 0, ',', ' ') }}
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Total cumulé en magasin</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Valeur marchande du stock</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ number_format($totalStockValue, 0, ',', ' ') }} FCFA
            </div>
            <p class="text-[11px] text-emerald-600 mt-0.5">Valeur aux prix actuels</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Urgences réassort</span>
            <div class="text-xl font-bold {{ ($counts['low'] + $counts['out']) > 0 ? 'text-[#D97706]' : 'text-[#111111]' }} mt-1 tracking-tight">
                {{ $counts['low'] + $counts['out'] }} références
            </div>
            <p class="text-[11px] text-[#E31E24] mt-0.5">{{ $counts['out'] }} en rupture immédiate</p>
        </div>
    </div>

    <!-- Tabs WooCommerce (Section 23) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-4 border-b border-[#E5E7EB] text-xs">
        <a href="{{ route('admin.stock.index') }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $filter === 'all' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Tous les articles <span class="ml-1 opacity-70">({{ $counts['all'] }})</span>
        </a>
        <a href="{{ route('admin.stock.index', ['filter' => 'out']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $filter === 'out' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            En rupture <span class="ml-1 opacity-70">({{ $counts['out'] }})</span>
        </a>
        <a href="{{ route('admin.stock.index', ['filter' => 'low']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $filter === 'low' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Stock critique (≤ 5) <span class="ml-1 opacity-70">({{ $counts['low'] }})</span>
        </a>
        <a href="{{ route('admin.stock.index', ['filter' => 'available']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $filter === 'available' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Stock suffisant (> 5) <span class="ml-1 opacity-70">({{ $counts['available'] }})</span>
        </a>
    </div>

    <!-- Recherche -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg p-3 mb-4">
        <form action="{{ route('admin.stock.index') }}" method="GET" class="flex items-center gap-2.5 text-xs">
            @if($filter !== 'all')
                <input type="hidden" name="filter" value="{{ $filter }}">
            @endif
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher par nom ou SKU..." class="w-full h-8 pl-8 pr-3 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded focus:bg-white focus:border-[#E31E24] focus:outline-none">
                <svg class="w-3.5 h-3.5 text-[#9CA3AF] absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <button type="submit" class="h-8 px-3 bg-[#111111] text-white rounded font-medium hover:bg-black">
                Rechercher
            </button>
        </form>
    </div>

    <!-- Tableau Stock -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px] border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                        <th class="py-2.5 px-4">Produit</th>
                        <th class="py-2.5 px-3">SKU</th>
                        <th class="py-2.5 px-3">Rayon</th>
                        <th class="py-2.5 px-3">Prix unitaire</th>
                        <th class="py-2.5 px-3">Statut stock</th>
                        <th class="py-2.5 px-4 text-right">Ajuster le stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($products as $product)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-3 px-4 font-medium text-[#111111]">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url ?: 'https://placehold.co/80x80/f5f5f5/999999?text=IMG' }}" class="w-10 h-10 object-cover rounded border border-[#E5E7EB] shrink-0" alt="{{ $product->name }}">
                                    <div>
                                        <div class="font-medium text-[#111111]">{{ $product->name }}</div>
                                        <div class="text-[11px] text-[#6B7280]">{{ $product->vendor_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-xs font-mono text-[#6B7280] whitespace-nowrap">{{ $product->reference }}</td>
                            <td class="py-3 px-3 text-xs text-[#374151]">{{ $product->category?->name ?: 'Général' }}</td>
                            <td class="py-3 px-3 text-xs font-semibold whitespace-nowrap">{{ $product->formatted_price }}</td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                @if($product->stock <= 0)
                                    <x-admin.badge status="out" type="stock" />
                                @elseif($product->stock <= 5)
                                    <x-admin.badge status="low" type="stock" />
                                @else
                                    <x-admin.badge status="in_stock" type="stock" />
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <form action="{{ route('admin.stock.quick-update', $product) }}" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    <input type="number" 
                                           name="stock" 
                                           value="{{ $product->stock }}" 
                                           min="0" 
                                           class="w-16 h-7 text-xs border border-[#D1D5DB] rounded px-1.5 text-center focus:border-[#E31E24] focus:outline-none font-bold {{ $product->stock <= 0 ? 'bg-red-50 text-red-700 border-red-300' : ($product->stock <= 5 ? 'bg-amber-50 text-amber-800 border-amber-300' : '') }}">
                                    <button type="submit" class="px-2.5 py-1 bg-[#111111] hover:bg-black text-white text-[11px] rounded font-medium">
                                        Mettre à jour
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-admin.empty-state title="Aucun produit" message="Aucun produit ne correspond à ces critères d'inventaire." />
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
