<x-admin.layout title="Client : {{ $customer->name }}" :breadcrumb="['Clients' => route('admin.customers.index'), $customer->name => null]">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-4 border-b border-[#E5E7EB] mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" class="p-1.5 text-[#6B7280] hover:text-[#111111] hover:bg-white rounded border border-[#E5E7EB]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#111111]">{{ $customer->name }}</h1>
                <p class="text-xs text-[#6B7280]">Client BKO SU • Tél : {{ $customer->phone }}</p>
            </div>
        </div>

        <div>
            <a href="tel:{{ $customer->phone }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md shadow-xs">
                Appeler le client
            </a>
        </div>
    </div>

    <!-- Coordonnées & Stats Client (Section 25) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4 text-xs">
            <span class="text-[#6B7280] block mb-1">Coordonnées :</span>
            <div class="font-bold text-sm text-[#111111]">{{ $customer->name }}</div>
            <div class="text-[#E31E24] font-medium mt-1">{{ $customer->phone }}</div>
            @if($customer->email)
                <div class="text-[#6B7280] mt-0.5">{{ $customer->email }}</div>
            @endif
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4 text-xs">
            <span class="text-[#6B7280] block mb-1">Adresse habituelle :</span>
            <div class="font-bold text-sm text-[#111111]">{{ $customer->neighborhood ?: 'Bamako' }}</div>
            <div class="text-[#4B5563] mt-1">{{ $customer->address ?: 'Adresse non renseignée' }}</div>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4 text-xs">
            <span class="text-[#6B7280] block mb-1">Historique commercial :</span>
            <div class="text-xl font-bold text-[#111111] mt-0.5">{{ number_format($customer->total_spent, 0, ',', ' ') }} FCFA</div>
            <p class="text-[11px] text-[#6B7280] mt-1">{{ $customer->orders_count }} commande(s) enregistrée(s)</p>
        </div>
    </div>

    <!-- Historique des commandes du client -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden">
        <div class="p-4 border-b border-[#E5E7EB]">
            <h2 class="text-sm font-semibold text-[#111111]">Historique des commandes ({{ $customer->orders->count() }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px] border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                        <th class="py-2.5 px-4">Commande</th>
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Paiement</th>
                        <th class="py-2.5 px-3">Articles</th>
                        <th class="py-2.5 px-3">Total</th>
                        <th class="py-2.5 px-3">Statut</th>
                        <th class="py-2.5 px-4 text-right">Détail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @foreach($customer->orders as $order)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-3 px-4 font-semibold text-[#111111]">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-[#E31E24] hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="py-3 px-3 text-xs text-[#6B7280]">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <span class="text-xs text-[#4B5563]">
                                    {{ $order->payment_method === 'orange_money' ? 'Orange Money' : 'Espèces' }}
                                </span>
                                <x-admin.badge :status="$order->payment_status" type="payment" />
                            </td>
                            <td class="py-3 px-3 text-xs text-[#6B7280]">
                                {{ $order->items->count() }} article(s)
                            </td>
                            <td class="py-3 px-3 font-semibold text-[#111111]">
                                {{ $order->formatted_total }}
                            </td>
                            <td class="py-3 px-3">
                                <x-admin.badge :status="$order->status" type="order" />
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="px-2.5 py-1 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded hover:bg-[#F9FAFB]">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin.layout>
