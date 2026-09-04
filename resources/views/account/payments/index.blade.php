<x-customer.layout title="Mes paiements">

    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-[#111111]">
            Historique des paiements
        </h1>
        <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
            Retrouvez le relevé de vos transactions sécurisées (Orange Money Mali et Espèces à la livraison).
        </p>
    </div>

    <!-- Bannière sécurité -->
    <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 mb-6 shadow-2xs flex items-start gap-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-2.182 7.712a10.978 10.978 0 01-6.143-3.238c-.352-.352-.676-.73-1.025-1.127a11.024 11.024 0 010-15.592c.349-.397.673-.775 1.025-1.127a10.978 10.978 0 016.143-3.238 10.978 10.978 0 016.143 3.238c.352.352.676.73 1.025 1.127a11.024 11.024 0 010 15.592c-.349.397-.673.775-1.025 1.127a10.978 10.978 0 01-6.143 3.238z" />
            </svg>
        </div>
        <div class="text-xs">
            <h4 class="font-bold text-[#111111]">Paiements 100% sécurisés</h4>
            <p class="text-[#6B7280] mt-0.5 leading-relaxed">
                Toutes vos transactions Orange Money sont chiffrées de bout en bout via la passerelle officielle Orange Mali. BKO SU ne conserve aucune donnée bancaire sensible.
            </p>
        </div>
    </div>

    @if($orders->count() > 0)
        <!-- Table des Paiements Desktop -->
        <div class="hidden md:block bg-white border border-[#E5E7EB] rounded-xl overflow-hidden shadow-2xs mb-5">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#4B5563] font-semibold">
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Commande</th>
                        <th class="py-3 px-4">Moyen de paiement</th>
                        <th class="py-3 px-4">Réf. transaction</th>
                        <th class="py-3 px-4">Montant</th>
                        <th class="py-3 px-4">Statut</th>
                        <th class="py-3 px-4 text-right">Détails</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @foreach($orders as $order)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-3.5 px-4 text-[#6B7280]">
                                {{ $order->created_at->translatedFormat('d M Y') }}
                                <span class="block text-[10px] text-[#9CA3AF]">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-[#111111]">
                                #{{ $order->order_number }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-[#111111]">
                                    {{ $order->payment_method === 'orange_money' ? 'Orange Money Mali' : 'Espèces à la livraison' }}
                                </span>
                                @if($order->orange_money_number)
                                    <span class="block text-[10px] text-[#6B7280]">{{ $order->orange_money_number }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-[#4B5563]">
                                {{ $order->orange_money_transaction_id ?: '—' }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-[#111111]">
                                {{ $order->formatted_total }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold 
                                    @if($order->payment_status === 'paid') bg-emerald-50 text-emerald-700
                                    @elseif($order->payment_status === 'failed') bg-red-50 text-red-700
                                    @else bg-amber-50 text-amber-800 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full 
                                        @if($order->payment_status === 'paid') bg-emerald-600
                                        @elseif($order->payment_status === 'failed') bg-red-600
                                        @else bg-amber-600 @endif"></span>
                                    {{ $order->payment_status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('account.orders.show', $order) }}" class="text-xs text-[#E31E24] hover:underline font-semibold">
                                    Voir →
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
                <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 shadow-2xs">
                    <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-[#F3F4F6]">
                        <div>
                            <h3 class="text-xs font-bold text-[#111111]">#{{ $order->order_number }}</h3>
                            <p class="text-[10px] text-[#6B7280]">{{ $order->created_at->translatedFormat('d M Y à H:i') }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold 
                            @if($order->payment_status === 'paid') bg-emerald-50 text-emerald-700
                            @elseif($order->payment_status === 'failed') bg-red-50 text-red-700
                            @else bg-amber-50 text-amber-800 @endif">
                            {{ $order->payment_status_label }}
                        </span>
                    </div>

                    <div class="py-2.5 space-y-1.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-[#6B7280]">Mode :</span>
                            <span class="font-medium text-[#111111]">{{ $order->payment_method === 'orange_money' ? 'Orange Money' : 'Espèces' }}</span>
                        </div>
                        @if($order->orange_money_transaction_id)
                            <div class="flex justify-between text-[11px]">
                                <span class="text-[#6B7280]">Réf :</span>
                                <span class="font-mono text-[#111111]">{{ $order->orange_money_transaction_id }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between font-bold pt-1 border-t border-[#F3F4F6]">
                            <span>Montant :</span>
                            <span class="text-[#E31E24]">{{ $order->formatted_total }}</span>
                        </div>
                    </div>

                    <div class="pt-2.5 border-t border-[#F3F4F6] text-right">
                        <a href="{{ route('account.orders.show', $order) }}" class="text-xs text-[#E31E24] font-semibold hover:underline">
                            Consulter la commande →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white border border-[#E5E7EB] rounded-xl p-10 text-center shadow-2xs">
            <div class="w-12 h-12 mx-auto rounded-full bg-neutral-100 text-[#6B7280] flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 002.25 19.5z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-[#111111]">Aucune transaction enregistrée</h3>
            <p class="text-xs text-[#6B7280] mt-1 max-w-sm mx-auto">
                Vos règlements apparaîtront ici dès votre première commande passée sur BKO SU.
            </p>
        </div>
    @endif

</x-customer.layout>
