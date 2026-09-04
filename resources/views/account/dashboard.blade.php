<x-customer.layout title="Tableau de bord">
    
    <!-- En-tête de bienvenue -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-[#111111]">
            Bonjour, {{ explode(' ', $user->name)[0] }} 👋
        </h1>
        <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
            Retrouvez vos commandes et gérez votre compte.
        </p>
    </div>

    <!-- 3 Cartes Métriques Essentielles (Zéro graphique inutile) -->
    <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-6">
        <!-- Total Commandes -->
        <a href="{{ route('account.orders.index') }}" class="bg-white border border-[#E5E7EB] rounded-xl p-4 hover:border-[#D1D5DB] transition-all shadow-2xs group">
            <span class="text-[11px] font-medium text-[#6B7280] block">Mes commandes</span>
            <div class="text-xl sm:text-2xl font-bold text-[#111111] mt-1 group-hover:text-[#E31E24] transition-colors">
                {{ $ordersCount }}
            </div>
        </a>

        <!-- En cours -->
        <a href="{{ route('account.orders.index', ['status' => 'in_progress']) }}" class="bg-white border border-[#E5E7EB] rounded-xl p-4 hover:border-[#D1D5DB] transition-all shadow-2xs group">
            <span class="text-[11px] font-medium text-[#6B7280] block">En cours</span>
            <div class="text-xl sm:text-2xl font-bold text-[#E31E24] mt-1">
                {{ $inProgressCount }}
            </div>
        </a>

        <!-- Favoris -->
        <a href="{{ route('account.favorites.index') }}" class="bg-white border border-[#E5E7EB] rounded-xl p-4 hover:border-[#D1D5DB] transition-all shadow-2xs group">
            <span class="text-[11px] font-medium text-[#6B7280] block">Mes favoris</span>
            <div class="text-xl sm:text-2xl font-bold text-[#111111] mt-1 group-hover:text-[#E31E24] transition-colors">
                {{ $favoritesCount }}
            </div>
        </a>
    </div>

    <!-- COMMANDE ACTIVE (Composant Principal) -->
    @if($activeOrder)
        <div class="bg-white border-2 border-[#E31E24]/20 rounded-xl p-5 sm:p-6 mb-6 shadow-xs">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-[#F3F4F6]">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-[#E31E24]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#E31E24] animate-pulse"></span>
                            Commande en cours
                        </span>
                        <h2 class="text-base font-bold text-[#111111]">
                            #{{ $activeOrder->order_number }}
                        </h2>
                    </div>
                    <p class="text-xs text-[#6B7280] mt-1">
                        Passée le {{ $activeOrder->created_at->translatedFormat('d F Y à H:i') }} • {{ $activeOrder->items->sum('quantity') }} article(s)
                    </p>
                </div>

                <div class="text-right">
                    <div class="text-base sm:text-lg font-bold text-[#111111]">
                        {{ $activeOrder->formatted_total }}
                    </div>
                    <div class="text-[11px] text-[#6B7280]">
                        {{ $activeOrder->payment_method === 'orange_money' ? 'Orange Money Mali' : 'Espèces à la livraison' }}
                    </div>
                </div>
            </div>

            <!-- Estimation Livraison & Statut -->
            <div class="my-5 p-3 rounded-lg bg-[#F9FAFB] border border-[#ECECEC] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-red-100 text-[#E31E24] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25V3.75m0 3.75h-9a1.125 1.125 0 00-1.125 1.125v6.75" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[#6B7280] block text-[11px]">Livraison estimée :</span>
                        <strong class="text-[#111111] font-semibold">{{ $activeOrder->estimated_delivery }}</strong>
                    </div>
                </div>

                <div>
                    <span class="text-[#6B7280] block text-[11px]">Adresse de destination :</span>
                    <span class="text-[#111111] font-medium">{{ $activeOrder->neighborhood }} • {{ Str::limit($activeOrder->address, 35) }}</span>
                </div>
            </div>

            <!-- TIMELINE VISUELLE À 5 ÉTAPES -->
            <div class="my-6">
                <div class="relative">
                    <!-- Barre de progression de fond -->
                    <div class="hidden sm:block absolute top-1/2 left-4 right-4 -translate-y-1/2 h-0.5 bg-[#E5E7EB] z-0"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 sm:gap-1 relative z-10">
                        @foreach($activeOrder->getTimelineSteps() as $index => $step)
                            <div class="flex sm:flex-col items-center sm:text-center gap-3 sm:gap-2">
                                <!-- Point d'étape -->
                                @if($step['state'] === 'completed')
                                    <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-xs ring-4 ring-white">
                                        ✓
                                    </div>
                                @elseif($step['state'] === 'active')
                                    <div class="w-7 h-7 rounded-full bg-[#E31E24] text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-xs ring-4 ring-red-100 animate-pulse">
                                        ●
                                    </div>
                                @else
                                    <div class="w-7 h-7 rounded-full bg-white border-2 border-[#D1D5DB] text-[#9CA3AF] flex items-center justify-center text-xs shrink-0 ring-4 ring-white">
                                        ○
                                    </div>
                                @endif

                                <!-- Libellés -->
                                <div class="min-w-0 flex-1 sm:flex-none">
                                    <h4 class="text-xs font-bold {{ $step['state'] === 'active' ? 'text-[#E31E24]' : ($step['state'] === 'completed' ? 'text-[#111111]' : 'text-[#9CA3AF]') }}">
                                        {{ $step['name'] }}
                                    </h4>
                                    <p class="text-[10px] text-[#6B7280] truncate mt-0.5">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Bouton d'action proéminent -->
            <div class="pt-3 border-t border-[#F3F4F6] flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs text-[#6B7280]">
                    Statut actuel : <strong class="text-[#111111]">{{ $activeOrder->status_label }}</strong>
                </span>

                <a href="{{ route('account.orders.show', $activeOrder) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-lg text-xs font-semibold shadow-xs transition-colors">
                    <span>Suivre ma commande</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    @endif

    <!-- COMMANDES RÉCENTES -->
    <div class="bg-white border border-[#E5E7EB] rounded-xl p-5 sm:p-6 mb-6 shadow-2xs">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm sm:text-base font-bold text-[#111111]">
                Commandes récentes
            </h2>
            @if($ordersCount > 0)
                <a href="{{ route('account.orders.index') }}" class="text-xs font-semibold text-[#E31E24] hover:underline inline-flex items-center gap-1">
                    <span>Voir toutes mes commandes</span>
                    <span>→</span>
                </a>
            @endif
        </div>

        @if($recentOrders->count() > 0)
            <div class="divide-y divide-[#F3F4F6]">
                @foreach($recentOrders as $order)
                    @php
                        $firstItem = $order->items->first();
                    @endphp
                    <div class="py-3.5 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Miniature premier produit -->
                            <div class="w-12 h-12 rounded-lg bg-[#F3F4F6] border border-[#E5E7EB] shrink-0 overflow-hidden flex items-center justify-center">
                                @if($firstItem && $firstItem->product_image)
                                    <img src="{{ $firstItem->product_image }}" alt="{{ $firstItem->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-5 h-5 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-[#111111]">#{{ $order->order_number }}</span>
                                    <span class="text-[10px] text-[#6B7280]">• {{ $order->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                                <div class="text-[11px] text-[#6B7280] truncate mt-0.5">
                                    {{ $order->items->sum('quantity') }} article(s) • <strong class="text-[#111111] font-semibold">{{ $order->formatted_total }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Statut et Action Voir -->
                        <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold 
                                @if($order->status === 'delivered') bg-emerald-50 text-emerald-700
                                @elseif($order->status === 'cancelled') bg-neutral-100 text-[#6B7280]
                                @elseif($order->status === 'in_delivery') bg-blue-50 text-blue-700
                                @else bg-amber-50 text-amber-800 @endif">
                                <span class="w-1.5 h-1.5 rounded-full 
                                    @if($order->status === 'delivered') bg-emerald-600
                                    @elseif($order->status === 'cancelled') bg-neutral-400
                                    @elseif($order->status === 'in_delivery') bg-blue-600
                                    @else bg-amber-600 @endif"></span>
                                {{ $order->status_label }}
                            </span>

                            <a href="{{ route('account.orders.show', $order) }}" class="px-3 py-1.5 bg-[#F9FAFB] hover:bg-neutral-100 border border-[#D1D5DB] rounded-md text-xs font-medium text-[#374151] transition-colors">
                                Voir la commande
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- EMPTY STATE SANS COMMANDE -->
            <div class="text-center py-10 px-4">
                <div class="w-12 h-12 mx-auto rounded-full bg-neutral-100 text-[#6B7280] flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#111111]">Vous n'avez pas encore de commande</h3>
                <p class="text-xs text-[#6B7280] mt-1 max-w-sm mx-auto">
                    Découvrez nos rayons frais, épicerie, bazin et mode pour passer votre première commande à Bamako.
                </p>
                <div class="mt-4">
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-lg text-xs font-semibold shadow-xs transition-colors">
                        <span>Découvrir les produits</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Raccourcis Utiles -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('account.favorites.index') }}" class="bg-white border border-[#E5E7EB] rounded-xl p-3.5 hover:border-[#D1D5DB] transition-all flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-red-50 text-[#E31E24] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </div>
            <div class="min-w-0">
                <span class="text-xs font-semibold text-[#111111] block truncate">Mes favoris</span>
                <span class="text-[10px] text-[#6B7280]">{{ $favoritesCount }} article(s)</span>
            </div>
        </a>

        <a href="{{ route('account.addresses.index') }}" class="bg-white border border-[#E5E7EB] rounded-xl p-3.5 hover:border-[#D1D5DB] transition-all flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
            <div class="min-w-0">
                <span class="text-xs font-semibold text-[#111111] block truncate">Adresses</span>
                <span class="text-[10px] text-[#6B7280]">Livraison Bamako</span>
            </div>
        </a>

        <a href="{{ route('account.payments.index') }}" class="bg-white border border-[#E5E7EB] rounded-xl p-3.5 hover:border-[#D1D5DB] transition-all flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 002.25 19.5z" />
                </svg>
            </div>
            <div class="min-w-0">
                <span class="text-xs font-semibold text-[#111111] block truncate">Paiements</span>
                <span class="text-[10px] text-[#6B7280]">Historique</span>
            </div>
        </a>

        <a href="{{ route('account.profile.index') }}" class="bg-white border border-[#E5E7EB] rounded-xl p-3.5 hover:border-[#D1D5DB] transition-all flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <div class="min-w-0">
                <span class="text-xs font-semibold text-[#111111] block truncate">Mon profil</span>
                <span class="text-[10px] text-[#6B7280]">Coordonnées</span>
            </div>
        </a>
    </div>

</x-customer.layout>
