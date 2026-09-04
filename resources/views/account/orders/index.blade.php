<x-customer.layout title="Mes commandes">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111111]">
                Mes commandes
            </h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                Consultez l'historique et l'état d'acheminement de vos commandes à Bamako.
            </p>
        </div>

        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold bg-[#E31E24] text-white hover:bg-[#C9171D] transition-colors shrink-0 shadow-2xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Nouvelle commande</span>
        </a>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 mb-5 shadow-2xs">
        <form action="{{ route('account.orders.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
            
            <!-- Recherche par numéro -->
            <div class="relative flex-1 w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#9CA3AF]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Rechercher par numéro (ex: BKO-2026)..." 
                       class="w-full h-9 pl-9 pr-3 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none transition-colors">
            </div>

            <!-- Filtre Statut -->
            <div class="w-full md:w-44">
                <select name="status" 
                        onchange="this.form.submit()" 
                        class="w-full h-9 px-3 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none cursor-pointer">
                    <option value="all" @selected($status === 'all')>Tous les statuts</option>
                    <option value="in_progress" @selected($status === 'in_progress')>En cours</option>
                    <option value="delivered" @selected($status === 'delivered')>Livrées</option>
                    <option value="cancelled" @selected($status === 'cancelled')>Annulées</option>
                </select>
            </div>

            <!-- Filtre Période -->
            <div class="w-full md:w-40">
                <select name="period" 
                        onchange="this.form.submit()" 
                        class="w-full h-9 px-3 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none cursor-pointer">
                    <option value="all" @selected($period === 'all')>Toutes les dates</option>
                    <option value="30_days" @selected($period === '30_days')>30 derniers jours</option>
                    <option value="3_months" @selected($period === '3_months')>3 derniers mois</option>
                    <option value="year" @selected($period === 'year')>Cette année</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto px-4 h-9 bg-[#111111] hover:bg-black text-white text-xs font-semibold rounded-lg transition-colors">
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'status', 'period']) && (request('status') !== 'all' || request('period') !== 'all' || request('search')))
                    <a href="{{ route('account.orders.index') }}" class="px-3 h-9 flex items-center text-xs text-[#6B7280] hover:text-[#111111] hover:bg-neutral-100 rounded-lg transition-colors" title="Réinitialiser">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- LISTE DES COMMANDES -->
    @if($orders->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block bg-white border border-[#E5E7EB] rounded-xl overflow-hidden shadow-2xs mb-5">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#4B5563] font-semibold">
                        <th class="py-3 px-4">Commande</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Articles</th>
                        <th class="py-3 px-4">Paiement</th>
                        <th class="py-3 px-4">Montant</th>
                        <th class="py-3 px-4">Statut</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @foreach($orders as $order)
                        @php $firstItem = $order->items->first(); @endphp
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#F3F4F6] border border-[#E5E7EB] shrink-0 overflow-hidden flex items-center justify-center">
                                        @if($firstItem && $firstItem->product_image)
                                            <img src="{{ $firstItem->product_image }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs font-bold text-[#9CA3AF]">BKO</span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('account.orders.show', $order) }}" class="font-bold text-[#111111] hover:text-[#E31E24] transition-colors">
                                            #{{ $order->order_number }}
                                        </a>
                                        <span class="block text-[11px] text-[#6B7280]">{{ $order->neighborhood }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-[#6B7280]">
                                {{ $order->created_at->translatedFormat('d M Y') }}
                                <span class="block text-[10px] text-[#9CA3AF]">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-[#374151]">
                                {{ $order->items->sum('quantity') }} article(s)
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block text-[11px] font-medium text-[#4B5563]">
                                    {{ $order->payment_method === 'orange_money' ? 'Orange Money' : 'Espèces' }}
                                </span>
                                <span class="block text-[10px] 
                                    @if($order->payment_status === 'paid') text-emerald-600 font-medium
                                    @elseif($order->payment_status === 'failed') text-red-600
                                    @else text-amber-600 @endif">
                                    {{ $order->payment_status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-[#111111]">{{ $order->formatted_total }}</span>
                            </td>
                            <td class="py-3.5 px-4">
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
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('account.orders.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#F9FAFB] hover:bg-neutral-100 border border-[#D1D5DB] rounded-md text-xs font-semibold text-[#374151] transition-colors">
                                    <span>Voir</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards List -->
        <div class="md:hidden space-y-3 mb-5">
            @foreach($orders as $order)
                @php $firstItem = $order->items->first(); @endphp
                <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 shadow-2xs">
                    <div class="flex items-start justify-between gap-3 pb-3 border-b border-[#F3F4F6]">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-[#F3F4F6] border border-[#E5E7EB] shrink-0 overflow-hidden flex items-center justify-center">
                                @if($firstItem && $firstItem->product_image)
                                    <img src="{{ $firstItem->product_image }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs font-bold text-[#9CA3AF]">BKO</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-[#111111]">#{{ $order->order_number }}</h3>
                                <p class="text-[11px] text-[#6B7280]">{{ $order->created_at->translatedFormat('d M Y à H:i') }}</p>
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold 
                            @if($order->status === 'delivered') bg-emerald-50 text-emerald-700
                            @elseif($order->status === 'cancelled') bg-neutral-100 text-[#6B7280]
                            @elseif($order->status === 'in_delivery') bg-blue-50 text-blue-700
                            @else bg-amber-50 text-amber-800 @endif">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="py-3 grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-[10px] text-[#6B7280] block">Articles</span>
                            <span class="font-medium text-[#111111]">{{ $order->items->sum('quantity') }} article(s)</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#6B7280] block">Montant total</span>
                            <span class="font-bold text-[#111111]">{{ $order->formatted_total }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-[#F3F4F6] flex items-center justify-between">
                        <span class="text-[11px] text-[#6B7280]">
                            {{ $order->neighborhood }}
                        </span>
                        <a href="{{ route('account.orders.show', $order) }}" class="px-3 py-1.5 bg-[#E31E24] text-white rounded-md text-xs font-semibold">
                            Détails de la commande →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white border border-[#E5E7EB] rounded-xl p-10 text-center shadow-2xs">
            <div class="w-12 h-12 mx-auto rounded-full bg-neutral-100 text-[#6B7280] flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-[#111111]">Aucune commande trouvée</h3>
            <p class="text-xs text-[#6B7280] mt-1 max-w-sm mx-auto">
                Aucune commande ne correspond à vos critères de recherche actuels.
            </p>
            <div class="mt-4">
                <a href="{{ route('account.orders.index') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-[#F9FAFB] border border-[#D1D5DB] text-[#374151] rounded-lg text-xs font-semibold hover:bg-neutral-100 transition-colors">
                    Effacer les filtres
                </a>
            </div>
        </div>
    @endif

</x-customer.layout>
