<x-admin.layout title="Commande #{{ $order->order_number }}" :breadcrumb="['Commandes' => route('admin.orders.index'), '#' . $order->order_number => null]">
    <!-- Header avec retour et statut -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-4 border-b border-[#E5E7EB] mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="p-1.5 text-[#6B7280] hover:text-[#111111] hover:bg-white rounded border border-[#E5E7EB] transition-colors" title="Retour aux commandes">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-[#111111] tracking-tight">Commande {{ $order->order_number }}</h1>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded {{ $order->isPurelyDigital() ? 'bg-amber-100 text-amber-900 border border-amber-200' : ($order->isMixed() ? 'bg-purple-100 text-purple-900 border border-purple-200' : 'bg-neutral-100 text-neutral-800') }}">
                        {{ $order->order_nature_label }}
                    </span>
                    <x-admin.badge :status="$order->status" type="order" />
                    <x-admin.badge :status="$order->payment_status" type="payment" />
                </div>
                <p class="text-xs text-[#6B7280] mt-0.5">
                    Passée le {{ $order->created_at->format('d F Y à H:i') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F9FAFB] transition-colors">
                <svg class="w-3.5 h-3.5 text-[#6B7280]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect width="12" height="8" x="6" y="14"></rect>
                </svg>
                <span>Imprimer bordereau</span>
            </a>
        </div>
    </div>

    <!-- Layout 2 colonnes (Section 17) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- COLONNE GAUCHE (2/3) : Articles + Données Paiement -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Articles Commandés -->
            <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden">
                <div class="p-4 border-b border-[#E5E7EB]">
                    <h2 class="text-sm font-semibold text-[#111111]">Articles de la commande ({{ $order->items->count() }})</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[13px] border-collapse">
                        <thead>
                            <tr class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#6B7280] text-[11px] uppercase tracking-wider font-semibold">
                                <th class="py-2.5 px-4">Produit</th>
                                <th class="py-2.5 px-3 text-right">Prix</th>
                                <th class="py-2.5 px-3 text-center">Quantité</th>
                                <th class="py-2.5 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB]">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" class="w-10 h-10 object-cover rounded border border-[#E5E7EB] shrink-0" alt="{{ $item->product_name }}">
                                            @else
                                                <div class="w-10 h-10 bg-[#F3F4F6] rounded border border-[#E5E7EB] flex items-center justify-center text-xs text-[#9CA3AF] shrink-0">BKO</div>
                                            @endif
                                            <div>
                                                <div class="flex items-center gap-1.5 font-medium text-[#111111]">
                                                    <span>{{ $item->product_name }}</span>
                                                    @if($item->isDigital())
                                                        <span class="px-1.5 py-0.2 text-[9px] font-bold bg-amber-100 text-amber-900 rounded">
                                                            Numérique
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($item->product)
                                                    <div class="text-[11px] text-[#6B7280]">Réf: {{ $item->product->reference }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-right text-xs whitespace-nowrap text-[#4B5563]">
                                        {{ number_format($item->price, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="py-3 px-3 text-center text-xs font-semibold whitespace-nowrap">
                                        × {{ $item->quantity }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-semibold text-[#111111] whitespace-nowrap">
                                        {{ number_format($item->total, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Récapitulatif Total -->
                <div class="p-4 bg-[#FAFAFA] border-t border-[#E5E7EB]">
                    <div class="max-w-xs ml-auto space-y-1.5 text-xs">
                        <div class="flex justify-between text-[#6B7280]">
                            <span>Sous-total :</span>
                            <span class="font-medium text-[#111111]">{{ $order->formatted_subtotal }}</span>
                        </div>
                        <div class="flex justify-between text-[#6B7280]">
                            <span>Frais de livraison (Bamako) :</span>
                            <span class="font-medium text-[#111111]">{{ $order->formatted_delivery_fee }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Remise :</span>
                                <span>- {{ number_format($order->discount, 0, ',', ' ') }} FCFA</span>
                            </div>
                        @endif
                        <div class="pt-2 border-t border-[#E5E7EB] flex justify-between text-sm font-bold text-[#111111]">
                            <span>Total TTC :</span>
                            <span class="text-[#E31E24]">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Données Détaillées de Paiement -->
            <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
                <h2 class="text-sm font-semibold text-[#111111] mb-3">Informations de Paiement</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-[#6B7280]">Mode de règlement :</span>
                        <div class="font-semibold text-[#111111] mt-0.5 flex items-center gap-1.5">
                            @if($order->payment_method === 'orange_money')
                                <span class="text-[#E31E24] font-bold">Orange Money WebPayment</span>
                            @else
                                <span>Espèces à la livraison</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="text-[#6B7280]">Statut du paiement :</span>
                        <div class="mt-0.5">
                            <x-admin.badge :status="$order->payment_status" type="payment" />
                        </div>
                    </div>

                    @if($order->payment_method === 'orange_money')
                        <div>
                            <span class="text-[#6B7280]">ID Transaction Orange Money :</span>
                            <div class="font-mono text-xs text-[#111111] mt-0.5">
                                {{ $order->orange_money_transaction_id ?: 'En attente de finalisation' }}
                            </div>
                        </div>

                        <div>
                            <span class="text-[#6B7280]">Référence Ordre Orange :</span>
                            <div class="font-mono text-xs text-[#111111] mt-0.5">
                                {{ $order->orange_money_order_id ?: $order->order_number }}
                            </div>
                        </div>

                        @if($order->orange_money_number)
                            <div>
                                <span class="text-[#6B7280]">Numéro OM saisi :</span>
                                <div class="font-medium text-[#111111] mt-0.5">
                                    {{ $order->orange_money_number }}
                                </div>
                            </div>
                        @endif

                        @if($order->orange_money_notif_token)
                            <div class="sm:col-span-2 bg-[#F9FAFB] p-2.5 rounded border border-[#E5E7EB]">
                                <span class="text-[#6B7280] block text-[11px]">Token IPN validé (Guide Sandbox Orange) :</span>
                                <span class="font-mono text-[11px] text-[#4B5563] break-all">{{ $order->orange_money_notif_token }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>

        <!-- COLONNE DROITE (1/3) : Actions Statut + Coordonnées Client & Livraison -->
        <div class="space-y-6">
            
            <!-- 1. Modification Rapide du Statut -->
            <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
                <h3 class="text-sm font-semibold text-[#111111] mb-3">Statut de la commande</h3>
                
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Statut commande :</label>
                        <select name="status" class="w-full h-8 bg-white border border-[#D1D5DB] rounded px-2 text-[#111111] focus:border-[#E31E24] focus:outline-none">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                            <option value="in_delivery" {{ $order->status === 'in_delivery' ? 'selected' : '' }}>En livraison</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Statut paiement :</label>
                        <select name="payment_status" class="w-full h-8 bg-white border border-[#D1D5DB] rounded px-2 text-[#111111] focus:border-[#E31E24] focus:outline-none">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Payé</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Échoué</option>
                            <option value="cancelled" {{ $order->payment_status === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded font-medium transition-colors shadow-xs">
                        Enregistrer les modifications
                    </button>
                </form>
            </div>

            <!-- 2. Coordonnées Client & Contact -->
            <div class="bg-white border border-[#E5E7EB] rounded-lg p-4 text-xs">
                <h3 class="text-sm font-semibold text-[#111111] mb-3">Client</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-[#6B7280]">Nom complet :</span>
                        <div class="font-medium text-[#111111] text-sm">{{ $order->customer_full_name }}</div>
                    </div>
                    <div>
                        <span class="text-[#6B7280]">Téléphone Mali :</span>
                        <div>
                            <a href="tel:{{ $order->customer_phone }}" class="text-[#E31E24] font-semibold hover:underline">
                                {{ $order->customer_phone }}
                            </a>
                        </div>
                    </div>
                    @if($order->customer_email)
                        <div>
                            <span class="text-[#6B7280]">Email :</span>
                            <div class="text-[#111111] truncate">{{ $order->customer_email }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 3. Adresse de Livraison à Bamako -->
            <div class="bg-white border border-[#E5E7EB] rounded-lg p-4 text-xs">
                <h3 class="text-sm font-semibold text-[#111111] mb-3">Livraison Bamako</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-[#6B7280]">Quartier :</span>
                        <div class="font-bold text-[#111111]">{{ $order->neighborhood ?: 'Bamako centre' }}</div>
                    </div>
                    <div>
                        <span class="text-[#6B7280]">Adresse précise / repère :</span>
                        <div class="text-[#374151] mt-0.5">{{ $order->address ?: 'Non renseigné' }}</div>
                    </div>
                    @if($order->delivery_notes)
                        <div class="bg-[#FFFBEB] p-2.5 rounded border border-amber-200 mt-2">
                            <span class="text-[11px] font-semibold text-[#92400E] block">Instructions de livraison :</span>
                            <p class="text-[11px] text-[#78350F] mt-0.5">{{ $order->delivery_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 4. Timeline Vraie (Section 18) -->
            <div class="bg-white border border-[#E5E7EB] rounded-lg p-4 text-xs">
                <h3 class="text-sm font-semibold text-[#111111] mb-3">Historique & Événements</h3>
                <div class="relative pl-4 space-y-3 border-l-2 border-[#E5E7EB]">
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-[#111111]"></div>
                        <span class="text-[#6B7280] text-[11px]">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        <p class="font-medium text-[#111111]">Commande créée</p>
                    </div>

                    @if($order->payment_status === 'paid')
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                            <span class="text-[#6B7280] text-[11px]">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                            <p class="font-medium text-emerald-700">Paiement validé ({{ $order->payment_method === 'orange_money' ? 'Orange Money' : 'Espèces' }})</p>
                        </div>
                    @endif

                    @if($order->status === 'confirmed' || $order->status === 'in_delivery' || $order->status === 'delivered')
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                            <span class="text-[#6B7280] text-[11px]">Étape suivante</span>
                            <p class="font-medium text-[#111111]">Commande {{ $order->status_label }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</x-admin.layout>
