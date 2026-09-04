<x-admin.layout title="Livraisons Bamako" :breadcrumb="['Livraisons' => route('admin.deliveries.index')]">
    <!-- Header -->
    <x-admin.page-header title="Livraisons Bamako" description="Organisation logistique des tournées de livraison par quartier" />

    <!-- KPIs Livraisons -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">À préparer en magasin</span>
            <div class="text-xl font-bold text-blue-600 mt-1 tracking-tight">
                {{ $deliveryCounts['to_prepare'] }}
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Commandes confirmées</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">En cours de tournée</span>
            <div class="text-xl font-bold text-indigo-600 mt-1 tracking-tight">
                {{ $deliveryCounts['in_delivery'] }}
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Livreurs sur le terrain à Bamako</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Livrées avec succès</span>
            <div class="text-xl font-bold text-emerald-600 mt-1 tracking-tight">
                {{ $deliveryCounts['delivered'] }}
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Colis remis aux clients</p>
        </div>
    </div>

    <!-- Répartition par quartier de Bamako -->
    @if($neighborhoodStats->isNotEmpty())
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4 mb-6">
            <h3 class="text-xs font-semibold text-[#111111] uppercase tracking-wider mb-3">Colis en attente par quartier</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($neighborhoodStats as $stat)
                    <a href="{{ route('admin.deliveries.index', ['neighborhood' => $stat->neighborhood]) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded text-xs border transition-colors {{ $selectedNeighborhood === $stat->neighborhood ? 'bg-[#111111] text-white border-[#111111]' : 'bg-[#F9FAFB] text-[#374151] border-[#E5E7EB] hover:bg-[#F3F4F6]' }}">
                        <span class="font-medium">{{ $stat->neighborhood }}</span>
                        <span class="bg-white/20 px-1 rounded text-[10px] font-bold">{{ $stat->total_orders }}</span>
                    </a>
                @endforeach
                @if($selectedNeighborhood)
                    <a href="{{ route('admin.deliveries.index') }}" class="px-2 py-1 text-xs text-[#E31E24] hover:underline">
                        Tout voir
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- Tableau Livraisons -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden">
        <div class="p-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <h3 class="text-sm font-semibold text-[#111111]">
                Feuille de route des livraisons {{ $selectedNeighborhood ? '(' . $selectedNeighborhood . ')' : '' }}
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px] border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                        <th class="py-2.5 px-4">Commande</th>
                        <th class="py-2.5 px-3">Quartier</th>
                        <th class="py-2.5 px-3">Destinataire & Contact</th>
                        <th class="py-2.5 px-3">Adresse & Repères</th>
                        <th class="py-2.5 px-3">Encaissement</th>
                        <th class="py-2.5 px-3">Statut</th>
                        <th class="py-2.5 px-4 text-right">Action rapide</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($orders as $order)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-3 px-4 font-semibold text-[#111111] whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-[#E31E24] hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <div class="text-[11px] text-[#6B7280]">{{ $order->items->count() }} article(s)</div>
                            </td>
                            <td class="py-3 px-3 font-semibold text-[#111111] whitespace-nowrap">
                                {{ $order->neighborhood ?: 'Bamako' }}
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <div class="font-medium text-[#111111]">{{ $order->customer_full_name }}</div>
                                <div>
                                    <a href="tel:{{ $order->customer_phone }}" class="text-[#E31E24] text-xs font-semibold hover:underline">
                                        {{ $order->customer_phone }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-xs text-[#4B5563] max-w-xs">
                                <div>{{ $order->address ?: 'Non précisé' }}</div>
                                @if($order->delivery_notes)
                                    <div class="text-[11px] text-amber-700 italic mt-0.5">Note: {{ $order->delivery_notes }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <div class="font-semibold text-[#111111]">{{ $order->formatted_total }}</div>
                                <span class="text-[11px] {{ $order->payment_status === 'paid' ? 'text-emerald-700 font-medium' : 'text-amber-700 font-bold' }}">
                                    {{ $order->payment_status === 'paid' ? 'Déjà réglé (' . ($order->payment_method === 'orange_money' ? 'OM' : 'Espèces') . ')' : 'À ENCAISSER' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <x-admin.badge :status="$order->status" type="order" />
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($order->status === 'confirmed')
                                        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="in_delivery">
                                            <button type="submit" class="px-2 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded text-xs font-medium">
                                                En livraison →
                                            </button>
                                        </form>
                                    @elseif($order->status === 'in_delivery')
                                        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="delivered">
                                            <input type="hidden" name="payment_status" value="paid">
                                            <button type="submit" class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded text-xs font-medium">
                                                ✓ Livrée
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="p-1 text-[#6B7280] hover:text-[#111111] rounded hover:bg-[#F3F4F6]" title="Bordereau">
                                        🖨️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.empty-state title="Aucune livraison en cours" message="Toutes les commandes du secteur ont été traitées." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-3 border-t border-[#E5E7EB] bg-white">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-admin.layout>
