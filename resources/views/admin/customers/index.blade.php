<x-admin.layout title="Répertoire Clients" :breadcrumb="['Clients' => route('admin.customers.index')]">
    <!-- Header -->
    <x-admin.page-header title="Clients" description="Répertoire des clients acheteurs et historique des commandes" />

    <!-- KPIs Clients -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Clients uniques identifiés</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ number_format($totalCustomers, 0, ',', ' ') }}
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Basé sur les numéros de téléphone Mali</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Volume d'achats cumulé</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ number_format($totalCustomerRevenue, 0, ',', ' ') }} FCFA
            </div>
            <p class="text-[11px] text-emerald-600 mt-0.5">Commandes validées</p>
        </div>
    </div>

    <!-- Recherche Client -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg p-3 mb-4">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex items-center gap-2.5 text-xs">
            <div class="relative flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Rechercher par numéro de téléphone (+223...), nom ou quartier..." 
                       class="w-full h-8 pl-8 pr-3 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded focus:bg-white focus:border-[#E31E24] focus:outline-none">
                <svg class="w-3.5 h-3.5 text-[#9CA3AF] absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <button type="submit" class="h-8 px-3 bg-[#111111] hover:bg-black text-white rounded font-medium">
                Rechercher
            </button>
            @if($search)
                <a href="{{ route('admin.customers.index') }}" class="h-8 px-2 flex items-center text-[#6B7280] hover:text-[#111111]">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <!-- Tableau Clients (Section 24) -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px] border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                        <th class="py-2.5 px-4">Client</th>
                        <th class="py-2.5 px-3">Téléphone</th>
                        <th class="py-2.5 px-3">Quartier</th>
                        <th class="py-2.5 px-3 text-center">Commandes</th>
                        <th class="py-2.5 px-3">Total dépensé</th>
                        <th class="py-2.5 px-3">Dernière commande</th>
                        <th class="py-2.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($customers as $client)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-3 px-4 font-semibold text-[#111111]">
                                <a href="{{ route('admin.customers.show', urlencode($client->customer_phone)) }}" class="text-[#111111] hover:text-[#E31E24]">
                                    {{ trim($client->first_name . ' ' . $client->last_name) ?: 'Client BKO SU' }}
                                </a>
                                @if($client->email)
                                    <div class="text-[11px] text-[#6B7280] font-normal">{{ $client->email }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap font-medium text-[#E31E24]">
                                <a href="tel:{{ $client->customer_phone }}" class="hover:underline">
                                    {{ $client->customer_phone }}
                                </a>
                            </td>
                            <td class="py-3 px-3 text-xs text-[#4B5563]">
                                {{ $client->neighborhood ?: 'Bamako' }}
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <span class="bg-[#F3F4F6] text-[#374151] px-2 py-0.5 rounded text-xs font-semibold">
                                    {{ $client->orders_count }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-semibold text-[#111111] whitespace-nowrap">
                                {{ number_format($client->total_spent, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="py-3 px-3 text-xs text-[#6B7280] whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($client->last_order_date)->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.customers.show', urlencode($client->customer_phone)) }}" class="px-2.5 py-1 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded hover:bg-[#F9FAFB]">
                                    Fiche
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.empty-state title="Aucun client trouvé" message="Les clients ayant validé au moins une commande apparaîtront ici." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-3 border-t border-[#E5E7EB] bg-white">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</x-admin.layout>
