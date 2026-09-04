<x-admin.layout title="Journal des Paiements" :breadcrumb="['Paiements' => route('admin.payments.index')]">
    <!-- Header -->
    <x-admin.page-header title="Paiements & Transactions" description="Suivi des règlements Orange Money et Espèces à la livraison" />

    <!-- KPIs Paiements -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3.5 mb-6">
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Total encaissé</span>
            <div class="text-xl font-bold text-emerald-700 mt-1 tracking-tight">
                {{ number_format($stats['total_paid'], 0, ',', ' ') }} FCFA
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Paiements confirmés</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Orange Money</span>
            <div class="text-xl font-bold text-[#E31E24] mt-1 tracking-tight">
                {{ $stats['orange_money_count'] }}
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Transactions mobiles</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Espèces à la livraison</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ $stats['cash_count'] }}
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Règlements livreurs</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">En attente de règlement</span>
            <div class="text-xl font-bold text-[#D97706] mt-1 tracking-tight">
                {{ $stats['pending_count'] }}
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">À percevoir</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg p-3 mb-4">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-center gap-2.5 text-xs">
            <div class="relative flex-1 min-w-[200px] w-full sm:w-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher transaction, ID Orange, client..." class="w-full h-8 pl-8 pr-3 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded focus:bg-white focus:border-[#E31E24] focus:outline-none">
                <svg class="w-3.5 h-3.5 text-[#9CA3AF] absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <select name="method" class="h-8 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded px-2 text-[#374151] focus:border-[#E31E24] focus:outline-none w-full sm:w-auto">
                <option value="">Tous les moyens</option>
                <option value="orange_money" {{ $method === 'orange_money' ? 'selected' : '' }}>Orange Money</option>
                <option value="cash_on_delivery" {{ $method === 'cash_on_delivery' ? 'selected' : '' }}>Espèces à la livraison</option>
            </select>

            <select name="payment_status" class="h-8 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded px-2 text-[#374151] focus:border-[#E31E24] focus:outline-none w-full sm:w-auto">
                <option value="">Tous les statuts</option>
                <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Payé</option>
                <option value="pending" {{ $paymentStatus === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="failed" {{ $paymentStatus === 'failed' ? 'selected' : '' }}>Échoué</option>
                <option value="cancelled" {{ $paymentStatus === 'cancelled' ? 'selected' : '' }}>Annulé</option>
            </select>

            <button type="submit" class="h-8 px-3 bg-[#111111] text-white rounded font-medium hover:bg-black">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Tableau Transactions (Section 26) -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px] border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                        <th class="py-2.5 px-4">Commande</th>
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Client</th>
                        <th class="py-2.5 px-3">Moyen de paiement</th>
                        <th class="py-2.5 px-3">ID Transaction</th>
                        <th class="py-2.5 px-3">Montant</th>
                        <th class="py-2.5 px-3">Statut</th>
                        <th class="py-2.5 px-4 text-right">Détail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($payments as $order)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-3 px-4 font-semibold text-[#111111]">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-[#E31E24] hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="py-3 px-3 text-xs text-[#6B7280] whitespace-nowrap">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-medium text-[#111111]">{{ $order->customer_full_name }}</div>
                                <div class="text-[11px] text-[#6B7280]">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <span class="font-medium text-xs {{ $order->payment_method === 'orange_money' ? 'text-[#E31E24]' : 'text-[#374151]' }}">
                                    {{ $order->payment_method === 'orange_money' ? 'Orange Money' : 'Espèces à la livraison' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-xs font-mono text-[#6B7280] whitespace-nowrap">
                                {{ $order->orange_money_transaction_id ?: ($order->orange_money_order_id ?: '—') }}
                            </td>
                            <td class="py-3 px-3 font-semibold text-[#111111] whitespace-nowrap">
                                {{ $order->formatted_total }}
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <x-admin.badge :status="$order->payment_status" type="payment" />
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $order) }}" class="px-2.5 py-1 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded hover:bg-[#F9FAFB]">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-admin.empty-state title="Aucune transaction trouvée" message="Les paiements enregistrés s'afficheront ici." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-3 border-t border-[#E5E7EB] bg-white">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</x-admin.layout>
