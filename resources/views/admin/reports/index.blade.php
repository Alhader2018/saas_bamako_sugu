<x-admin.layout title="Rapports & Ventes" :breadcrumb="['Rapports' => route('admin.reports.index')]">
    <!-- Header -->
    <x-admin.page-header title="Rapports & Ventes" description="Analyse de la performance commerciale de BKO SU" />

    <!-- Sélecteur de Période (Section 29) -->
    <div class="flex items-center gap-2 mb-6 border-b border-[#E5E7EB] pb-3 text-xs">
        <span class="text-[#6B7280] font-medium mr-1">Période :</span>
        <a href="{{ route('admin.reports.index', ['period' => 'today']) }}" 
           class="px-3 py-1.5 rounded-md font-medium transition-colors {{ $period === 'today' ? 'bg-[#111111] text-white' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Aujourd'hui
        </a>
        <a href="{{ route('admin.reports.index', ['period' => '7days']) }}" 
           class="px-3 py-1.5 rounded-md font-medium transition-colors {{ $period === '7days' ? 'bg-[#111111] text-white' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            7 derniers jours
        </a>
        <a href="{{ route('admin.reports.index', ['period' => '30days']) }}" 
           class="px-3 py-1.5 rounded-md font-medium transition-colors {{ $period === '30days' ? 'bg-[#111111] text-white' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            30 derniers jours
        </a>
        <a href="{{ route('admin.reports.index', ['period' => 'year']) }}" 
           class="px-3 py-1.5 rounded-md font-medium transition-colors {{ $period === 'year' ? 'bg-[#111111] text-white' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6]' }}">
            Cette année
        </a>
    </div>

    <!-- KPIs Période -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Chiffre d'affaires net</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Sur la période sélectionnée</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Total Commandes</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ $totalOrders }}
            </div>
            <p class="text-[11px] text-emerald-600 mt-0.5">{{ $successfulOrders }} payées avec succès</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Panier moyen</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ number_format($avgBasket, 0, ',', ' ') }} FCFA
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Moyenne par panier</p>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <span class="text-xs text-[#6B7280] font-medium">Taux de succès paiement</span>
            <div class="text-xl font-bold text-[#111111] mt-1 tracking-tight">
                {{ $totalOrders > 0 ? round(($successfulOrders / $totalOrders) * 100) : 0 }} %
            </div>
            <p class="text-[11px] text-[#6B7280] mt-0.5">Commandes honorées</p>
        </div>
    </div>

    <!-- 2 Grilles : Top Produits & Répartition Paiement -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Produits Vendus -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
            <h3 class="text-sm font-semibold text-[#111111] mb-3">Top 5 Produits les plus vendus</h3>
            <div class="divide-y divide-[#F3F4F6] text-xs">
                @forelse($topProducts as $prod)
                    <div class="py-2.5 flex items-center justify-between">
                        <div>
                            <span class="font-medium text-[#111111]">{{ $prod->product_name }}</span>
                            <span class="text-[11px] text-[#6B7280] block">{{ $prod->total_qty }} unités écoulées</span>
                        </div>
                        <div class="font-semibold text-[#111111]">
                            {{ number_format($prod->total_amount, 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#6B7280] py-4 text-center">Aucune vente enregistrée sur cette période.</p>
                @endforelse
            </div>
        </div>

        <!-- Répartition par méthode de paiement -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
            <h3 class="text-sm font-semibold text-[#111111] mb-3">Répartition des Moyens de Paiement</h3>
            <div class="space-y-3 text-xs">
                @forelse($paymentBreakdown as $pay)
                    <div class="p-3 bg-[#F9FAFB] rounded-md border border-[#E5E7EB] flex items-center justify-between">
                        <div>
                            <span class="font-bold text-[#111111] block">
                                {{ $pay->payment_method === 'orange_money' ? 'Orange Money WebPayment' : 'Espèces à la livraison' }}
                            </span>
                            <span class="text-[11px] text-[#6B7280]">{{ $pay->count }} transaction(s)</span>
                        </div>
                        <div class="text-right font-bold text-[#111111]">
                            {{ number_format($pay->revenue, 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#6B7280] py-4 text-center">Aucune donnée disponible pour cette période.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-admin.layout>
