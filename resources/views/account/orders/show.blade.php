<x-customer.layout title="Commande #{{ $order->order_number }}">

    <!-- En-tête avec Fil d'Ariane et Actions -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-xs text-[#6B7280] mb-2">
            <a href="{{ route('account.orders.index') }}" class="hover:text-[#111111] hover:underline">Mes commandes</a>
            <span>/</span>
            <span class="text-[#111111] font-semibold">#{{ $order->order_number }}</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl sm:text-2xl font-bold text-[#111111]">
                        Commande #{{ $order->order_number }}
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold 
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
                </div>
                <p class="text-xs text-[#6B7280] mt-1">
                    Passée le {{ $order->created_at->translatedFormat('l d F Y à H:i') }}
                </p>
            </div>

            <!-- Actions Contextuelles Haut -->
            <div class="flex flex-wrap items-center gap-2">
                @if($order->isCancellable())
                    <form action="{{ route('account.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 rounded-lg bg-white border border-red-200 text-red-600 hover:bg-red-50 text-xs font-semibold transition-colors">
                            Annuler la commande
                        </button>
                    </form>
                @endif

                @if($order->isDelivered())
                    <form action="{{ route('account.orders.reorder', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 rounded-lg bg-[#E31E24] hover:bg-[#C9171D] text-white text-xs font-semibold transition-colors shadow-2xs">
                            Acheter à nouveau
                        </button>
                    </form>
                @endif

                <a href="https://wa.me/22370000000?text={{ urlencode('Bonjour BKO SU, j\'ai une question concernant ma commande #' . $order->order_number) }}" 
                   target="_blank" 
                   class="px-3.5 py-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 text-xs font-semibold transition-colors inline-flex items-center gap-1.5">
                    <span>Aide & Support</span>
                </a>
            </div>
        </div>
    </div>

    <!-- TIMELINE VISUELLE IMMERSIVE -->
    <div class="bg-white border border-[#E5E7EB] rounded-xl p-5 sm:p-6 mb-6 shadow-2xs">
        <h2 class="text-xs font-bold text-[#111111] uppercase tracking-wider mb-4">
            Suivi de votre commande
        </h2>

        <div class="relative my-4">
            <!-- Ligne de fond sur desktop -->
            <div class="hidden sm:block absolute top-1/2 left-4 right-4 -translate-y-1/2 h-0.5 bg-[#E5E7EB] z-0"></div>

            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 sm:gap-2 relative z-10">
                @foreach($order->getTimelineSteps() as $step)
                    <div class="flex sm:flex-col items-center sm:text-center gap-3 sm:gap-2">
                        @if($step['state'] === 'completed')
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-xs ring-4 ring-white">
                                ✓
                            </div>
                        @elseif($step['state'] === 'active')
                            <div class="w-8 h-8 rounded-full bg-[#E31E24] text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-xs ring-4 ring-red-100 animate-pulse">
                                ●
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-white border-2 border-[#D1D5DB] text-[#9CA3AF] flex items-center justify-center text-xs shrink-0 ring-4 ring-white">
                                ○
                            </div>
                        @endif

                        <div class="min-w-0 flex-1 sm:flex-none">
                            <h4 class="text-xs font-bold {{ $step['state'] === 'active' ? 'text-[#E31E24]' : ($step['state'] === 'completed' ? 'text-[#111111]' : 'text-[#9CA3AF]') }}">
                                {{ $step['name'] }}
                            </h4>
                            <p class="text-[11px] text-[#6B7280] truncate mt-0.5">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-[#F3F4F6] text-xs text-[#6B7280] flex items-center justify-between">
            <span>Délai estimé : <strong class="text-[#111111]">{{ $order->estimated_delivery }}</strong></span>
            <span>Mise à jour : {{ $order->updated_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <!-- 2 Colonnes : Articles (Gauche) & Livraison/Paiement/Totaux (Droite) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Colonne Gauche : Articles de la commande (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-[#E5E7EB] rounded-xl p-5 sm:p-6 shadow-2xs">
                <h2 class="text-sm font-bold text-[#111111] mb-4 flex items-center justify-between">
                    <span>Articles commandés ({{ $order->items->sum('quantity') }})</span>
                </h2>

                <div class="divide-y divide-[#F3F4F6]">
                    @foreach($order->items as $item)
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-center gap-4">
                            <!-- Image produit -->
                            <div class="w-14 h-14 rounded-lg bg-[#F9FAFB] border border-[#E5E7EB] shrink-0 overflow-hidden flex items-center justify-center">
                                @if($item->product_image)
                                    <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs text-[#9CA3AF]">BKO</span>
                                @endif
                            </div>

                            <!-- Nom & Quantité -->
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs font-bold text-[#111111] leading-snug">
                                    {{ $item->product_name }}
                                </h3>
                                <div class="text-[11px] text-[#6B7280] mt-1 flex items-center gap-3">
                                    <span>Quantité : <strong class="text-[#111111]">{{ $item->quantity }}</strong></span>
                                    <span>Prix unit. : {{ number_format($item->price, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>

                            <!-- Sous-total ligne -->
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold text-[#111111]">
                                    {{ number_format($item->total, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Colonne Droite : Livraison, Paiement & Récapitulatif (1 col) -->
        <div class="space-y-6">
            
            <!-- Adresse de Livraison -->
            <div class="bg-white border border-[#E5E7EB] rounded-xl p-5 shadow-2xs">
                <h3 class="text-xs font-bold text-[#111111] uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#E31E24]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <span>Livraison à Bamako</span>
                </h3>

                <div class="text-xs space-y-1.5 text-[#4B5563]">
                    <p class="font-bold text-[#111111]">{{ $order->customer_full_name }}</p>
                    <p class="font-medium text-[#111111]">{{ $order->customer_phone }}</p>
                    <p class="text-[#111111]">{{ $order->neighborhood }}, Bamako</p>
                    <p class="text-[11px] leading-relaxed text-[#6B7280]">{{ $order->address }}</p>

                    @if($order->delivery_notes)
                        <div class="mt-2 p-2.5 rounded-md bg-[#F9FAFB] border border-[#ECECEC] text-[11px] text-[#4B5563]">
                            <strong>Instructions :</strong> {{ $order->delivery_notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mode de Paiement -->
            <div class="bg-white border border-[#E5E7EB] rounded-xl p-5 shadow-2xs">
                <h3 class="text-xs font-bold text-[#111111] uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 002.25 19.5z" />
                    </svg>
                    <span>Paiement</span>
                </h3>

                <div class="text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[#6B7280]">Méthode :</span>
                        <strong class="text-[#111111]">
                            {{ $order->payment_method === 'orange_money' ? 'Orange Money Mali' : 'Espèces à la livraison' }}
                        </strong>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[#6B7280]">Statut du paiement :</span>
                        <span class="font-semibold 
                            @if($order->payment_status === 'paid') text-emerald-600
                            @elseif($order->payment_status === 'failed') text-red-600
                            @else text-amber-600 @endif">
                            {{ $order->payment_status_label }}
                        </span>
                    </div>

                    @if($order->orange_money_transaction_id)
                        <div class="flex items-center justify-between pt-1 border-t border-[#F3F4F6] text-[11px]">
                            <span class="text-[#6B7280]">Réf. Orange :</span>
                            <span class="font-mono text-[#111111]">{{ $order->orange_money_transaction_id }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Résumé Financier -->
            <div class="bg-white border border-[#E5E7EB] rounded-xl p-5 shadow-2xs">
                <h3 class="text-xs font-bold text-[#111111] uppercase tracking-wider mb-3">
                    Récapitulatif
                </h3>

                <div class="text-xs space-y-2 text-[#4B5563]">
                    <div class="flex justify-between">
                        <span>Sous-total articles :</span>
                        <span class="font-medium text-[#111111]">{{ $order->formatted_subtotal }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Livraison Bamako :</span>
                        <span class="font-medium text-[#111111]">
                            @if($order->delivery_fee == 0)
                                <span class="text-emerald-600 font-semibold">Gratuit</span>
                            @else
                                {{ $order->formatted_delivery_fee }}
                            @endif
                        </span>
                    </div>

                    @if($order->discount > 0)
                        <div class="flex justify-between text-emerald-600">
                            <span>Réduction :</span>
                            <span>-{{ number_format($order->discount, 0, ',', ' ') }} FCFA</span>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-[#E5E7EB] flex justify-between items-center text-sm font-bold text-[#111111]">
                        <span>Total payé :</span>
                        <span class="text-[#E31E24] text-base">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-customer.layout>
