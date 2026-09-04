<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration BKO SU — Bamako Supermarché</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bko-su.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F8F8] text-[#1C1C1C] min-h-screen flex antialiased text-xs">

    <!-- Sidebar Admin BKO SU -->
    <aside class="w-60 bg-[#111111] text-white flex flex-col justify-between shrink-0 hidden lg:flex">
        <div>
            <!-- Header Sidebar -->
            <div class="p-5 border-b border-neutral-800 flex items-center justify-between">
                <a href="{{ route('home') }}" class="block">
                    <x-logo class="h-7 w-auto invert brightness-200" />
                </a>
                <span class="text-[10px] font-semibold bg-[#E31E24] text-white px-1.5 py-0.5 rounded">ADMIN</span>
            </div>

            <!-- Liens Navigation -->
            <nav class="p-3 space-y-1 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-md bg-[#E31E24] text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                        <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                        <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                        <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                    </svg>
                    <span>Tableau de bord</span>
                </a>

                <a href="#commandes" class="flex items-center justify-between px-3 py-2 rounded-md text-neutral-300 hover:text-white hover:bg-neutral-800 smooth-transition">
                    <span class="flex items-center gap-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                        </svg>
                        <span>Commandes</span>
                    </span>
                    <span class="bg-neutral-800 text-[10px] px-1.5 py-0.2 rounded font-semibold">{{ $totalOrdersCount }}</span>
                </a>

                <a href="#produits" class="flex items-center justify-between px-3 py-2 rounded-md text-neutral-300 hover:text-white hover:bg-neutral-800 smooth-transition">
                    <span class="flex items-center gap-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path d="m7.5 4.27 9 5.15"></path>
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                        </svg>
                        <span>Stocks</span>
                    </span>
                    <span class="bg-neutral-800 text-[10px] px-1.5 py-0.2 rounded font-semibold">{{ $totalProductsCount }}</span>
                </a>

                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2.5 px-3 py-2 rounded-md text-neutral-300 hover:text-white hover:bg-neutral-800 smooth-transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Storefront ↗</span>
                </a>
            </nav>
        </div>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-neutral-800 text-neutral-400 text-[11px]">
            <p class="font-medium text-white">BKO SU • Bamako</p>
            <p class="mt-0.5">Orange Money intégré</p>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Topbar Admin -->
        <header class="h-14 bg-white border-b border-[#ECECEC] px-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="lg:hidden">
                    <x-logo class="h-7 w-auto" />
                </a>
                <h1 class="text-sm font-bold text-[#1C1C1C]">Tableau de bord Administration</h1>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-[#6B7280]">Connecté : <strong>Admin</strong></span>
                <a href="{{ route('home') }}" class="px-2.5 py-1 rounded border border-[#ECECEC] hover:bg-neutral-50 font-medium text-[#1C1C1C]">
                    Boutique ↗
                </a>
            </div>
        </header>

        <!-- Message Flash -->
        @if(session('success'))
            <div class="mx-6 mt-4 p-3 rounded-md bg-emerald-50 border border-emerald-200 font-medium text-emerald-800 flex items-center gap-2">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Body -->
        <div class="p-6 space-y-6 flex-1 overflow-y-auto">
            
            <!-- 1. KPIs Denses -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                
                <div class="bg-white rounded-lg border border-[#ECECEC] p-4">
                    <span class="text-[#6B7280]">Chiffre d'Affaires</span>
                    <div class="text-xl font-bold text-[#1C1C1C] mt-1">
                        {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-0.5">Commandes validées</p>
                </div>

                <div class="bg-white rounded-lg border border-[#ECECEC] p-4">
                    <span class="text-[#6B7280]">Commandes</span>
                    <div class="text-xl font-bold text-[#1C1C1C] mt-1">
                        {{ $totalOrdersCount }}
                    </div>
                    <p class="text-[11px] text-[#E31E24] mt-0.5">{{ $pendingOrdersCount }} en attente</p>
                </div>

                <div class="bg-white rounded-lg border border-[#ECECEC] p-4">
                    <span class="text-[#6B7280]">Panier Moyen</span>
                    <div class="text-xl font-bold text-[#1C1C1C] mt-1">
                        {{ number_format($avgBasket, 0, ',', ' ') }} FCFA
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-0.5">Moyenne par panier</p>
                </div>

                <div class="bg-white rounded-lg border border-[#ECECEC] p-4">
                    <span class="text-[#6B7280]">Articles au catalogue</span>
                    <div class="text-xl font-bold text-[#1C1C1C] mt-1">
                        {{ $totalProductsCount }}
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-0.5">{{ $lowStockCount }} stock faible</p>
                </div>

            </div>

            <!-- 2. Tableau Commandes -->
            <div id="commandes" class="bg-white rounded-lg border border-[#ECECEC] overflow-hidden">
                <div class="p-4 border-b border-[#ECECEC] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-[#1C1C1C]">Commandes Récentes</h2>
                    </div>

                    <!-- Filtres -->
                    <div class="flex items-center gap-1.5 overflow-x-auto text-[11px]">
                        <a href="{{ route('admin.dashboard') }}" class="px-2 py-0.5 rounded font-medium {{ !$statusFilter ? 'bg-[#111111] text-white' : 'bg-neutral-100 text-[#6B7280]' }}">
                            Toutes
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="px-2 py-0.5 rounded font-medium {{ $statusFilter === 'pending' ? 'bg-[#E31E24] text-white' : 'bg-neutral-100 text-[#6B7280]' }}">
                            En attente
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'confirmed']) }}" class="px-2 py-0.5 rounded font-medium {{ $statusFilter === 'confirmed' ? 'bg-[#F7B500] text-[#111111]' : 'bg-neutral-100 text-[#6B7280]' }}">
                            Confirmées
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'in_delivery']) }}" class="px-2 py-0.5 rounded font-medium {{ $statusFilter === 'in_delivery' ? 'bg-blue-600 text-white' : 'bg-neutral-100 text-[#6B7280]' }}">
                            En livraison
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'delivered']) }}" class="px-2 py-0.5 rounded font-medium {{ $statusFilter === 'delivered' ? 'bg-[#16A34A] text-white' : 'bg-neutral-100 text-[#6B7280]' }}">
                            Livrées
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-neutral-50 text-[#6B7280] font-medium border-b border-[#ECECEC]">
                            <tr>
                                <th class="p-3">N° Commande</th>
                                <th class="p-3">Client</th>
                                <th class="p-3">Quartier</th>
                                <th class="p-3">Paiement</th>
                                <th class="p-3">Total</th>
                                <th class="p-3">Statut</th>
                                <th class="p-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#ECECEC]">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-neutral-50">
                                    <td class="p-3 font-semibold text-[#1C1C1C]">
                                        {{ $order->order_number }}
                                        <p class="text-[10px] text-[#6B7280] font-normal">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </td>
                                    <td class="p-3">
                                        <p class="font-medium text-[#1C1C1C]">{{ $order->customer_full_name }}</p>
                                        <p class="text-[11px] text-[#6B7280]">{{ $order->customer_phone }}</p>
                                    </td>
                                    <td class="p-3">
                                        <p class="font-medium text-[#1C1C1C]">{{ $order->neighborhood }}</p>
                                        <p class="text-[11px] text-[#6B7280] truncate max-w-xs">{{ $order->address }}</p>
                                    </td>
                                    <td class="p-3">
                                        @if($order->payment_method === 'orange_money')
                                            <span class="text-amber-800 font-medium">Orange Money</span>
                                        @else
                                            <span class="text-neutral-700 font-medium">Espèces</span>
                                        @endif
                                    </td>
                                    <td class="p-3 font-bold text-[#E31E24]">
                                        {{ $order->formatted_total }}
                                    </td>
                                    <td class="p-3">
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-neutral-100 text-[#1C1C1C]">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="inline-block">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" class="h-7 px-2 bg-white border border-[#ECECEC] rounded text-xs outline-none cursor-pointer">
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
                                    <td colspan="7" class="p-6 text-center text-[#6B7280]">
                                        Aucune commande trouvée.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Gestion du Stock -->
            <div id="produits" class="bg-white rounded-lg border border-[#ECECEC] overflow-hidden">
                <div class="p-4 border-b border-[#ECECEC]">
                    <h2 class="font-bold text-[#1C1C1C]">Inventaire & Stocks</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-neutral-50 text-[#6B7280] font-medium border-b border-[#ECECEC]">
                            <tr>
                                <th class="p-3">Produit</th>
                                <th class="p-3">Rayon</th>
                                <th class="p-3">Boutique</th>
                                <th class="p-3">Prix</th>
                                <th class="p-3">Stock</th>
                                <th class="p-3 text-right">Ajuster</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#ECECEC]">
                            @foreach($products as $product)
                                <tr class="hover:bg-neutral-50">
                                    <td class="p-3 flex items-center gap-2.5">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-8 h-8 rounded object-cover border border-[#ECECEC]">
                                        <span class="font-medium text-[#1C1C1C]">{{ $product->name }}</span>
                                    </td>
                                    <td class="p-3 text-[#6B7280]">{{ $product->category->name }}</td>
                                    <td class="p-3 text-[#6B7280]">{{ $product->vendor_name }}</td>
                                    <td class="p-3 font-semibold text-[#1C1C1C]">{{ $product->formatted_price }}</td>
                                    <td class="p-3">
                                        @if($product->stock <= 5)
                                            <span class="text-[#DC2626] font-semibold">{{ $product->stock }} (Faible)</span>
                                        @else
                                            <span class="text-[#1C1C1C]">{{ $product->stock }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-right">
                                        <form method="POST" action="{{ route('admin.products.stock', $product->id) }}" class="inline-flex items-center gap-1 justify-end">
                                            @csrf
                                            <input type="number" name="stock" value="{{ $product->stock }}" min="0" class="w-14 h-7 px-1.5 bg-white border border-[#ECECEC] rounded text-center outline-none">
                                            <button type="submit" class="h-7 px-2 bg-[#111111] hover:bg-[#E31E24] text-white font-medium rounded smooth-transition cursor-pointer">
                                                OK
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
