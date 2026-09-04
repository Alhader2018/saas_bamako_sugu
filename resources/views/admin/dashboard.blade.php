<x-admin.layout title="Vue d'ensemble">
    <!-- Header -->
    <x-admin.page-header title="Tableau de bord" description="Synthèse des activités et commandes en temps réel">
        <x-slot:actions>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F9FAFB] transition-colors">
                <svg class="w-3.5 h-3.5 text-[#6B7280]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                <span>Toutes les commandes</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md transition-colors shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Nouveau produit</span>
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- 1. KPI Cards sobres (Section 11) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
        <!-- Chiffre d'affaires -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Chiffre d'affaires</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA
            </div>
            <div class="flex items-center gap-1.5 text-[11px] text-[#059669] mt-1 font-medium">
                <span>Commandes confirmées/livrées</span>
            </div>
        </div>

        <!-- Commandes totales & en attente -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Commandes à traiter</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ $pendingOrdersCount }}
            </div>
            <div class="text-[11px] text-[#E31E24] mt-1 font-medium">
                Sur {{ $totalOrdersCount }} commandes enregistrées
            </div>
        </div>

        <!-- Panier moyen -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Panier moyen</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ number_format($avgBasket, 0, ',', ' ') }} FCFA
            </div>
            <div class="text-[11px] text-[#6B7280] mt-1">
                Par panier validé
            </div>
        </div>

        <!-- Stock critique -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Alertes Stock</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ $lowStockCount }}
            </div>
            <div class="text-[11px] {{ $lowStockCount > 0 ? 'text-[#D97706] font-medium' : 'text-[#6B7280]' }} mt-1">
                Articles à réapprovisionner (≤ 5)
            </div>
        </div>
    </div>

    <!-- 2. Commandes récentes (Workflow central) -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg mb-6 overflow-hidden">
        <div class="p-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-[#111111]">Commandes récentes</h2>
                <p class="text-[11px] text-[#6B7280]">Les 10 dernières commandes passées sur la boutique</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-[#E31E24] hover:underline">
                Voir tout ({{ $totalOrdersCount }}) →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px] border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                        <th class="py-2.5 px-4">Commande</th>
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Client</th>
                        <th class="py-2.5 px-3">Paiement</th>
                        <th class="py-2.5 px-3">Livraison</th>
                        <th class="py-2.5 px-3">Total</th>
                        <th class="py-2.5 px-3">Statut</th>
                        <th class="py-2.5 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-3 px-4 font-semibold text-[#111111]">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-[#E31E24] hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="py-3 px-3 text-[#6B7280] text-xs whitespace-nowrap">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-medium text-[#111111] leading-tight">{{ $order->customer_full_name }}</div>
                                <div class="text-[11px] text-[#6B7280]">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs {{ $order->payment_method === 'orange_money' ? 'font-medium text-[#E31E24]' : 'text-[#4B5563]' }}">
                                        {{ $order->payment_method === 'orange_money' ? 'Orange Money' : 'Espèces' }}
                                    </span>
                                    <x-admin.badge :status="$order->payment_status" type="payment" />
                                </div>
                            </td>
                            <td class="py-3 px-3 text-xs text-[#4B5563]">
                                <span class="font-medium">{{ $order->neighborhood ?: 'Bamako' }}</span>
                            </td>
                            <td class="py-3 px-3 font-semibold text-[#111111] whitespace-nowrap">
                                {{ $order->formatted_total }}
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <x-admin.badge :status="$order->status" type="order" />
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded hover:bg-[#F9FAFB] hover:text-[#111111]">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-admin.empty-state title="Aucune commande récente" message="Les nouvelles commandes s'afficheront ici." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Produits en stock faible & Catégories rapides -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stock faible -->
        <div class="lg:col-span-2 bg-white border border-[#E5E7EB] rounded-lg overflow-hidden">
            <div class="p-4 border-b border-[#E5E7EB] flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-[#111111]">Alertes Rupture & Stock faible</h3>
                    <p class="text-[11px] text-[#6B7280]">Produits nécessitant un réapprovisionnement urgent</p>
                </div>
                <a href="{{ route('admin.stock.index', ['filter' => 'low']) }}" class="text-xs font-medium text-[#E31E24] hover:underline">
                    Gérer l'inventaire →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[13px] border-collapse">
                    <thead>
                        <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-4">Produit</th>
                            <th class="py-2.5 px-3">SKU</th>
                            <th class="py-2.5 px-3">Prix</th>
                            <th class="py-2.5 px-3">Stock</th>
                            <th class="py-2.5 px-4 text-right">Ajuster</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @php
                            $alertProducts = \App\Models\Product::where('stock', '<=', 5)->orderBy('stock', 'asc')->take(5)->get();
                        @endphp
                        @forelse($alertProducts as $product)
                            <tr class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="py-2.5 px-4 font-medium text-[#111111]">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $product->image_url ?: 'https://placehold.co/80x80/f5f5f5/999999?text=IMG' }}" 
                                             class="w-9 h-9 object-cover rounded border border-[#E5E7EB]" 
                                             alt="{{ $product->name }}">
                                        <span class="line-clamp-1">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-xs text-[#6B7280]">{{ $product->reference }}</td>
                                <td class="py-2.5 px-3 font-semibold text-xs whitespace-nowrap">{{ $product->formatted_price }}</td>
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-bold {{ $product->stock <= 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $product->stock }} unité{{ $product->stock > 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <form action="{{ route('admin.products.stock', $product) }}" method="POST" class="inline-flex items-center gap-1">
                                        @csrf
                                        <input type="number" name="stock" value="{{ $product->stock }}" min="0" class="w-14 h-7 text-xs border border-[#D1D5DB] rounded px-1.5 text-center focus:border-[#E31E24] focus:outline-none">
                                        <button type="submit" class="px-2 py-1 bg-[#111111] hover:bg-black text-white text-[11px] rounded font-medium">OK</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-xs text-[#6B7280]">
                                    ✓ Tous les niveaux de stock sont optimaux.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Raccourcis Métier & Rayons -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-semibold text-[#111111] mb-1">Rayons BKO SU</h3>
                <p class="text-[11px] text-[#6B7280] mb-3">Répartition des articles par catégorie</p>
                <div class="space-y-2">
                    @foreach($categories as $category)
                        <div class="flex items-center justify-between py-1.5 border-b border-[#F3F4F6] text-xs">
                            <span class="text-[#374151] font-medium">{{ $category->name }}</span>
                            <span class="text-[#6B7280] font-semibold bg-[#F9FAFB] px-2 py-0.5 rounded border border-[#E5E7EB]">
                                {{ $category->products_count }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-[#E5E7EB]">
                <a href="{{ route('admin.products.index') }}" class="block text-center py-2 text-xs font-semibold text-[#E31E24] hover:bg-[#FEF2F2] rounded border border-dashed border-[#E31E24]/30 transition-colors">
                    Explorer le catalogue complet →
                </a>
            </div>
        </div>
    </div>
</x-admin.layout>
