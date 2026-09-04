<x-admin.layout title="Gestion des Commandes" :breadcrumb="['Commandes' => route('admin.orders.index')]">
    <!-- Header -->
    <x-admin.page-header title="Commandes" description="Gestion des commandes et expéditions de Bamako">
        <x-slot:actions>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F9FAFB] transition-colors">
                <svg class="w-3.5 h-3.5 text-[#6B7280]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Actualiser</span>
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- 1. Tabs par statut façon WooCommerce (Section 13) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-4 border-b border-[#E5E7EB] text-xs">
        <a href="{{ route('admin.orders.index') }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ empty($status) ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Toutes <span class="ml-1 opacity-70">({{ $counts['all'] }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $status === 'pending' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            En attente <span class="ml-1 opacity-70">({{ $counts['pending'] }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $status === 'confirmed' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Confirmées <span class="ml-1 opacity-70">({{ $counts['confirmed'] }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'in_delivery']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $status === 'in_delivery' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            En livraison <span class="ml-1 opacity-70">({{ $counts['in_delivery'] }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $status === 'delivered' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Livrées <span class="ml-1 opacity-70">({{ $counts['delivered'] }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
           class="px-3 py-1.5 rounded-md font-medium whitespace-nowrap transition-colors {{ $status === 'cancelled' ? 'bg-[#111111] text-white font-semibold' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Annulées <span class="ml-1 opacity-70">({{ $counts['cancelled'] }})</span>
        </a>
    </div>

    <!-- 2. Barre de Filtres Métier (Section 32) -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg p-3 mb-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-center gap-2.5 text-xs">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <!-- Recherche -->
            <div class="relative flex-1 min-w-[200px] w-full sm:w-auto">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="N° commande, nom, tél (+223)..." 
                       class="w-full h-8 pl-8 pr-3 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded focus:bg-white focus:border-[#E31E24] focus:outline-none">
                <svg class="w-3.5 h-3.5 text-[#9CA3AF] absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <!-- Filtre Paiement -->
            <select name="payment_status" class="h-8 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded px-2 text-[#374151] focus:border-[#E31E24] focus:outline-none w-full sm:w-auto">
                <option value="">Tous les paiements</option>
                <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Payé</option>
                <option value="pending" {{ $paymentStatus === 'pending' ? 'selected' : '' }}>En attente de paiement</option>
                <option value="failed" {{ $paymentStatus === 'failed' ? 'selected' : '' }}>Échoué</option>
            </select>

            <!-- Filtre Quartier Bamako -->
            <select name="neighborhood" class="h-8 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded px-2 text-[#374151] focus:border-[#E31E24] focus:outline-none w-full sm:w-auto">
                <option value="">Tous les quartiers</option>
                @foreach($neighborhoods as $nh)
                    <option value="{{ $nh }}" {{ $neighborhood === $nh ? 'selected' : '' }}>{{ $nh }}</option>
                @endforeach
            </select>

            <button type="submit" class="h-8 px-3 bg-[#111111] hover:bg-black text-white rounded font-medium transition-colors w-full sm:w-auto">
                Filtrer
            </button>

            @if($search || $paymentStatus || $neighborhood || $status)
                <a href="{{ route('admin.orders.index') }}" class="h-8 px-2 flex items-center text-[#6B7280] hover:text-[#111111]">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <!-- 3. Formulaire avec Actions en masse (Section 16) & Tableau -->
    <form action="{{ route('admin.orders.bulk') }}" method="POST" id="bulk-orders-form">
        @csrf
        
        <div class="flex items-center gap-2 mb-3 text-xs">
            <select name="action" class="h-8 bg-white border border-[#D1D5DB] rounded px-2.5 text-[#374151] focus:border-[#E31E24] focus:outline-none">
                <option value="">Actions groupées...</option>
                <option value="confirm">Marquer comme Confirmées</option>
                <option value="in_delivery">Passer en Livraison</option>
                <option value="delivered">Marquer comme Livrées</option>
                <option value="cancel">Annuler les commandes</option>
            </select>
            <button type="submit" class="h-8 px-3 bg-white border border-[#D1D5DB] text-[#374151] hover:bg-[#F9FAFB] rounded font-medium transition-colors">
                Appliquer
            </button>
            <span class="text-[#6B7280] ml-2 hidden sm:inline">
                {{ $orders->total() }} commande(s) trouvée(s)
            </span>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[13px] border-collapse">
                    <thead>
                        <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-3 w-8 text-center">
                                <input type="checkbox" id="select-all" class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-0">
                            </th>
                            <th class="py-2.5 px-3">Commande</th>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Client</th>
                            <th class="py-2.5 px-3">Paiement</th>
                            <th class="py-2.5 px-3">Livraison</th>
                            <th class="py-2.5 px-3">Total</th>
                            <th class="py-2.5 px-3">Statut</th>
                            <th class="py-2.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($orders as $order)
                            <tr class="hover:bg-[#F9FAFB] transition-colors">
                                <td class="py-3 px-3 text-center">
                                    <input type="checkbox" name="selected_orders[]" value="{{ $order->id }}" class="order-checkbox rounded border-[#D1D5DB] text-[#E31E24] focus:ring-0">
                                </td>
                                <td class="py-3 px-3 font-semibold text-[#111111] whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-[#E31E24] hover:underline">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="py-3 px-3 text-xs text-[#6B7280] whitespace-nowrap">
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
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="px-2 py-1 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded hover:bg-[#F9FAFB] hover:text-[#111111]">
                                            Détail
                                        </a>
                                        <a href="{{ route('admin.orders.print', $order) }}" target="_blank" title="Imprimer bordereau" class="p-1 text-[#6B7280] hover:text-[#111111] rounded hover:bg-[#F3F4F6]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                                <rect width="12" height="8" x="6" y="14"></rect>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <x-admin.empty-state title="Aucune commande trouvée" message="Aucune commande ne correspond aux filtres sélectionnés." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="p-3 border-t border-[#E5E7EB] bg-white">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </form>

    <script>
        document.getElementById('select-all')?.addEventListener('change', function(e) {
            document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = e.target.checked);
        });
    </script>
</x-admin.layout>
