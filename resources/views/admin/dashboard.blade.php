<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration BKO SU — Bamako Supermarché</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bko-su.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F8F8] text-[#1C1C1C] min-h-screen flex antialiased">

    <!-- Sidebar Admin BKO SU -->
    <aside class="w-64 bg-[#111111] text-white flex flex-col justify-between shrink-0 hidden lg:flex">
        <div>
            <!-- Header Sidebar -->
            <div class="p-6 border-b border-neutral-800 flex items-center justify-between">
                <a href="{{ route('home') }}" class="block">
                    <x-logo class="h-8 w-auto invert brightness-200" />
                </a>
                <span class="text-[10px] font-bold bg-[#E31E24] text-white px-2 py-0.5 rounded-full">ADMIN</span>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-[#E31E24] text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                        <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                        <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                        <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                    </svg>
                    <span>Tableau de bord</span>
                </a>

                <a href="#commandes" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-neutral-300 hover:text-white hover:bg-neutral-800 smooth-transition">
                    <span class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                        </svg>
                        <span>Commandes</span>
                    </span>
                    <span class="bg-neutral-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ $totalOrdersCount }}</span>
                </a>

                <a href="#produits" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-neutral-300 hover:text-white hover:bg-neutral-800 smooth-transition">
                    <span class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="m7.5 4.27 9 5.15"></path>
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                        </svg>
                        <span>Produits & Stock</span>
                    </span>
                    <span class="bg-neutral-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ $totalProductsCount }}</span>
                </a>

                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-neutral-300 hover:text-white hover:bg-neutral-800 smooth-transition">
                    <svg class="w-4 h-4 text-[#F7B500]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Voir la marketplace</span>
                </a>
            </nav>
        </div>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-neutral-800 text-xs text-neutral-400">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                <span class="text-white font-bold">Orange Money API</span>
            </div>
            <p class="text-[11px]">BKO SU Version 1.0 (Bamako)</p>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Topbar Admin -->
        <header class="h-16 bg-white border-b border-[#ECECEC] px-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="lg:hidden">
                    <x-logo class="h-7 w-auto" />
                </a>
                <h1 class="text-base font-black text-[#1C1C1C]">Tableau de bord Administration</h1>
            </div>

            <div class="flex items-center gap-3 text-xs">
                <span class="text-[#6B7280]">Connecté : <strong>Admin BKO</strong></span>
                <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-xl border border-[#ECECEC] hover:bg-neutral-50 font-semibold text-[#1C1C1C]">
                    Boutique en ligne ↗
                </a>
            </div>
        </header>

        <!-- Message Flash de Succès -->
        @if(session('success'))
            <div class="mx-6 mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#16A34A]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Body Container -->
        <div class="p-6 space-y-8 flex-1 overflow-y-auto">
            
            <!-- 1. KPI Cards (Section 23: Statistiques denses) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- KPI 1 : CA Total -->
                <div class="bg-white rounded-2xl border border-[#ECECEC] p-5 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-[#6B7280] mb-2">
                        <span class="font-bold">Chiffre d'Affaires</span>
                        <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-bold">+18.5%</span>
                    </div>
                    <div class="text-2xl font-black text-[#1C1C1C]">
                        {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-1">Total des commandes validées</p>
                </div>

                <!-- KPI 2 : Commandes -->
                <div class="bg-white rounded-2xl border border-[#ECECEC] p-5 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-[#6B7280] mb-2">
                        <span class="font-bold">Commandes Totales</span>
                        <span class="bg-red-50 text-[#E31E24] px-2 py-0.5 rounded font-bold">{{ $pendingOrdersCount }} en attente</span>
                    </div>
                    <div class="text-2xl font-black text-[#1C1C1C]">
                        {{ $totalOrdersCount }}
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-1">{{ $deliveredOrdersCount }} livrées avec succès</p>
                </div>

                <!-- KPI 3 : Panier Moyen -->
                <div class="bg-white rounded-2xl border border-[#ECECEC] p-5 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-[#6B7280] mb-2">
                        <span class="font-bold">Panier Moyen</span>
                        <span class="text-[#F7B500] font-bold">Mali</span>
                    </div>
                    <div class="text-2xl font-black text-[#1C1C1C]">
                        {{ number_format($avgBasket, 0, ',', ' ') }} FCFA
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-1">Par client Bamako</p>
                </div>

                <!-- KPI 4 : Stock & Alertes -->
                <div class="bg-white rounded-2xl border border-[#ECECEC] p-5 shadow-xs">
                    <div class="flex items-center justify-between text-xs text-[#6B7280] mb-2">
                        <span class="font-bold">Catalogue Actif</span>
                        @if($lowStockCount > 0)
                            <span class="bg-amber-50 text-amber-800 px-2 py-0.5 rounded font-bold">{{ $lowStockCount }} stock bas</span>
                        @else
                            <span class="text-emerald-700 font-bold">Stock optimal</span>
                        @endif
                    </div>
                    <div class="text-2xl font-black text-[#1C1C1C]">
                        {{ $totalProductsCount }} Articles
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-1">Répartis sur {{ $categories->count() }} rayons</p>
                </div>

            </div>

            <!-- 2. Gestion des Commandes Récentes (Tableau Dense & Professionnel) -->
            <div id="commandes" class="bg-white rounded-2xl border border-[#ECECEC] overflow-hidden shadow-xs">
                <div class="p-5 border-b border-[#ECECEC] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-black text-[#1C1C1C]">Commandes Récentes</h2>
                        <p class="text-xs text-[#6B7280]">Suivez et mettez à jour les statuts de livraison dans les quartiers de Bamako.</p>
                    </div>

                    <!-- Filtres par Statut -->
                    <div class="flex items-center gap-1.5 overflow-x-auto text-xs">
                        <a href="{{ route('admin.dashboard') }}" class="px-2.5 py-1 rounded-lg font-bold {{ !$statusFilter ? 'bg-[#111111] text-white' : 'bg-neutral-100 text-[#6B7280]' }}">
                            Toutes
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="px-2.5 py-1 rounded-lg font-bold {{ $statusFilter === 'pending' ? 'bg-[#E31E24] text-white' : 'bg-neutral-100 text-[#6B7280]' }}">
                            En attente
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'confirmed']) }}" class="px-2.5 py-1 rounded-lg font-bold {{ $statusFilter === 'confirmed' ? 'bg-[#F7B500] text-[#111111]' : 'bg-neutral-100 text-[#6B7280]' }}">
                            Confirmées
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'in_delivery']) }}" class="px-2.5 py-1 rounded-lg font-bold {{ $statusFilter === 'in_delivery' ? 'bg-blue-600 text-white' : 'bg-neutral-100 text-[#6B7280]' }}">
                            En cours
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'delivered']) }}" class="px-2.5 py-1 rounded-lg font-bold {{ $statusFilter === 'delivered' ? 'bg-[#16A34A] text-white' : 'bg-neutral-100 text-[#6B7280]' }}">
                            Livrées
                        </a>
                    </div>
                </div>

                <!-- Tableau Commandes -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-neutral-50 text-[#6B7280] font-semibold border-b border-[#ECECEC]">
                            <tr>
                                <th class="p-3.5">N° Commande</th>
                                <th class="p-3.5">Client & Contact</th>
                                <th class="p-3.5">Quartier (Bamako)</th>
                                <th class="p-3.5">Paiement</th>
                                <th class="p-3.5">Montant Total</th>
                                <th class="p-3.5">Statut Actuel</th>
                                <th class="p-3.5 text-right">Mise à jour Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#ECECEC]/70">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-neutral-50/80">
                                    <td class="p-3.5 font-bold text-[#1C1C1C]">
                                        {{ $order->order_number }}
                                        <p class="text-[10px] text-[#6B7280] font-normal">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </td>
                                    <td class="p-3.5">
                                        <p class="font-bold text-[#1C1C1C]">{{ $order->customer_full_name }}</p>
                                        <p class="text-[11px] text-[#6B7280]">{{ $order->customer_phone }}</p>
                                    </td>
                                    <td class="p-3.5">
                                        <span class="font-semibold text-[#1C1C1C]">{{ $order->neighborhood }}</span>
                                        <p class="text-[11px] text-[#6B7280] truncate max-w-xs">{{ $order->address }}</p>
                                    </td>
                                    <td class="p-3.5">
                                        @if($order->payment_method === 'orange_money')
                                            <span class="inline-flex items-center gap-1 font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#F7B500]"></span> Orange Money
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 font-bold text-neutral-700 bg-neutral-100 border border-neutral-200 px-2 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span> Espèces
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 font-black text-[#E31E24]">
                                        {{ $order->formatted_total }}
                                    </td>
                                    <td class="p-3.5">
                                        @php
                                            $badgeColor = match($order->status) {
                                                'pending' => 'bg-red-50 text-[#E31E24] border-red-200',
                                                'confirmed' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'in_delivery' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'delivered' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                'cancelled' => 'bg-neutral-100 text-neutral-600 border-neutral-200',
                                                default => 'bg-neutral-100 text-neutral-800 border-neutral-200',
                                            };
                                        @endphp
                                        <span class="inline-block px-2 py-0.5 rounded-full font-bold border {{ $badgeColor }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-right">
                                        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="inline-flex items-center gap-1">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" class="h-8 px-2 bg-white border border-[#ECECEC] rounded-lg text-xs font-semibold outline-none cursor-pointer">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                                <option value="in_delivery" {{ $order->status === 'in_delivery' ? 'selected' : '' }}>En livraison</option>
                                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-[#6B7280]">
                                        Aucune commande trouvée pour ce statut.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Gestion du Stock & Produits -->
            <div id="produits" class="bg-white rounded-2xl border border-[#ECECEC] overflow-hidden shadow-xs">
                <div class="p-5 border-b border-[#ECECEC] flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black text-[#1C1C1C]">Stock & Produits Actifs</h2>
                        <p class="text-xs text-[#6B7280]">Mise à jour rapide des inventaires disponibles à Bamako.</p>
                    </div>
                    <span class="text-xs font-bold text-[#E31E24]">Mise à jour directe</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-neutral-50 text-[#6B7280] font-semibold border-b border-[#ECECEC]">
                            <tr>
                                <th class="p-3.5">Produit</th>
                                <th class="p-3.5">Rayon</th>
                                <th class="p-3.5">Boutique Partenaire</th>
                                <th class="p-3.5">Prix FCFA</th>
                                <th class="p-3.5">Stock Actuel</th>
                                <th class="p-3.5 text-right">Modifier Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#ECECEC]/70">
                            @foreach($products as $product)
                                <tr class="hover:bg-neutral-50/80">
                                    <td class="p-3.5 flex items-center gap-3">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover border border-[#ECECEC]">
                                        <div>
                                            <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="font-bold text-[#1C1C1C] hover:text-[#E31E24]">
                                                {{ $product->name }}
                                            </a>
                                            <p class="text-[10px] text-[#6B7280]">Réf: {{ $product->reference }}</p>
                                        </div>
                                    </td>
                                    <td class="p-3.5 font-semibold text-[#1C1C1C]">
                                        {{ $product->category->name }}
                                    </td>
                                    <td class="p-3.5 text-[#6B7280]">
                                        {{ $product->vendor_name }}
                                    </td>
                                    <td class="p-3.5 font-bold text-[#1C1C1C]">
                                        {{ $product->formatted_price }}
                                    </td>
                                    <td class="p-3.5">
                                        @if($product->stock <= 5)
                                            <span class="font-bold text-[#DC2626] bg-red-50 px-2 py-0.5 rounded">
                                                {{ $product->stock }} (Stock Faible)
                                            </span>
                                        @else
                                            <span class="font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded">
                                                {{ $product->stock }} unités
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-right">
                                        <form method="POST" action="{{ route('admin.products.stock', $product->id) }}" class="inline-flex items-center gap-1 justify-end">
                                            @csrf
                                            <input type="number" name="stock" value="{{ $product->stock }}" min="0" class="w-16 h-8 px-2 bg-white border border-[#ECECEC] rounded-lg text-xs font-bold text-center outline-none">
                                            <button type="submit" class="h-8 px-2.5 bg-[#111111] hover:bg-[#E31E24] text-white text-xs font-bold rounded-lg smooth-transition cursor-pointer">
                                                Valider
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
