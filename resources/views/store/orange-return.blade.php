<x-layouts.app title="Paiement Orange Money — BKO SU">

    <div class="max-w-xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl border border-[#ECECEC] p-6 sm:p-8 text-center shadow-sm">
            @if($order->payment_status === 'paid')
                {{-- SUCCÈS : Paiement confirmé --}}
                <div class="w-12 h-12 bg-emerald-50 text-[#16A34A] rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="inline-block text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 mb-2">
                    ✓ Paiement Orange Money validé
                </span>
                <h1 class="text-xl font-bold text-[#1C1C1C] mb-1">
                    Merci pour votre commande !
                </h1>
                <p class="text-xs text-[#6B7280] mb-6">
                    Votre paiement pour la commande <strong>{{ $order->order_number }}</strong> a bien été confirmé.
                    @if($order->orange_money_transaction_id)
                        <br><span class="text-[11px] text-[#9CA3AF]">Réf. transaction : {{ $order->orange_money_transaction_id }}</span>
                    @endif
                </p>

                {{-- Fichiers numériques prêts au téléchargement --}}
                @if($order->hasDigitalItems())
                    <div class="mb-6 text-left p-4 rounded-xl border bg-emerald-50/50 border-emerald-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-[#16A34A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <h3 class="text-xs font-bold text-emerald-900">
                                Vos téléchargements sont prêts !
                            </h3>
                        </div>
                        <p class="text-[11px] text-emerald-800 mb-3">
                            Cliquez sur les liens ci-dessous pour récupérer vos documents immédiatement :
                        </p>
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                                @if($item->isDigital() && $item->product)
                                    @foreach($item->product->files as $file)
                                        <div class="flex items-center justify-between bg-white p-2.5 rounded-lg border border-emerald-100 text-xs">
                                            <div class="flex items-center gap-2 min-w-0 pr-2">
                                                <span class="text-base">📄</span>
                                                <div class="truncate">
                                                    <div class="font-medium text-[#1C1C1C] truncate">{{ $file->file_name }}</div>
                                                    <div class="text-[10px] text-[#9CA3AF]">{{ $file->formatted_file_size }} • {{ strtoupper($file->file_extension) }}</div>
                                                </div>
                                            </div>
                                            <a 
                                                href="{{ route('digital.download', ['orderNumber' => $order->order_number, 'fileId' => $file->id]) }}"
                                                class="flex-shrink-0 h-8 px-3 bg-[#16A34A] hover:bg-[#15803D] text-white font-semibold text-[11px] rounded-md flex items-center gap-1.5 transition-colors shadow-sm"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                Télécharger
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

            @elseif(in_array($order->payment_status, ['failed', 'cancelled']))
                {{-- ÉCHEC OU ANNULATION --}}
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <span class="inline-block text-[11px] font-semibold text-red-800 bg-red-50 px-2.5 py-0.5 rounded-full border border-red-200 mb-2">
                    ✕ Paiement non validé ({{ $order->payment_status === 'cancelled' ? 'Annulé' : 'Échec' }})
                </span>
                <h1 class="text-xl font-bold text-[#1C1C1C] mb-1">
                    La transaction n'a pas abouti
                </h1>
                <p class="text-xs text-[#6B7280] mb-6">
                    Le paiement pour la commande <strong>{{ $order->order_number }}</strong> n'a pas été validé ou a expiré. Votre compte n'a pas été débité.
                </p>

            @else
                {{-- EN ATTENTE (PENDING) --}}
                <div class="w-12 h-12 bg-amber-50 text-[#F7B500] rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="inline-block text-[11px] font-semibold text-amber-900 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200 mb-2">
                    ⏳ Validation Orange Money en cours
                </span>
                <h1 class="text-xl font-bold text-[#1C1C1C] mb-1">
                    Commande {{ $order->order_number }}
                </h1>
                <p class="text-xs text-[#6B7280] mb-6">
                    Le statut de votre paiement Orange Money est en attente de confirmation par l'opérateur. Si vous venez de valider sur votre téléphone, cliquez sur <strong>Vérifier à nouveau</strong> ci-dessous.
                </p>
            @endif

            <div class="bg-[#F8F8F8] rounded-lg p-4 border border-[#ECECEC] text-left text-xs space-y-2 mb-6">
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Numéro de commande</span>
                    <span class="font-semibold text-[#1C1C1C]">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Statut du paiement</span>
                    <span class="font-semibold {{ $order->payment_status === 'paid' ? 'text-[#16A34A]' : (in_array($order->payment_status, ['failed', 'cancelled']) ? 'text-red-600' : 'text-amber-600') }}">
                        {{ $order->payment_status_label }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Destinataire</span>
                    <span class="text-[#1C1C1C]">{{ $order->customer_full_name }} ({{ $order->customer_phone }})</span>
                </div>
                @if($order->hasPhysicalItems())
                    <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                        <span class="text-[#6B7280]">Livraison à Bamako</span>
                        <span class="text-[#1C1C1C]">{{ $order->neighborhood }}</span>
                    </div>
                @endif
                <div class="flex justify-between pt-1 text-sm font-bold text-[#1C1C1C]">
                    <span>Montant {{ $order->payment_status === 'paid' ? 'réglé' : 'à régler' }}</span>
                    <span class="text-[#E31E24]">{{ $order->formatted_total }}</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3">
                @if($order->payment_status === 'pending')
                    <a 
                        href="{{ request()->fullUrl() }}"
                        class="h-10 px-5 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs rounded-lg flex items-center gap-2 transition-colors shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Vérifier à nouveau
                    </a>
                    <a 
                        href="{{ route('checkout.orange.retry', ['orderNumber' => $order->order_number]) }}"
                        class="h-10 px-5 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-xs rounded-lg flex items-center gap-2 transition-colors shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Réessayer le paiement
                    </a>
                @elseif(in_array($order->payment_status, ['failed', 'cancelled']))
                    <a 
                        href="{{ route('checkout.orange.retry', ['orderNumber' => $order->order_number]) }}"
                        class="h-10 px-5 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-xs rounded-lg flex items-center gap-2 transition-colors shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Réessayer le paiement
                    </a>
                @endif

                @auth
                    <a 
                        href="{{ route('account.orders.show', $order) }}"
                        class="h-10 px-5 bg-white border border-[#ECECEC] hover:bg-[#F8F8F8] text-[#1C1C1C] font-semibold text-xs rounded-lg flex items-center justify-center transition-colors"
                    >
                        Suivre ma commande
                    </a>
                @endauth

                <a 
                    href="{{ route('home') }}"
                    class="h-10 px-5 bg-white border border-[#ECECEC] hover:bg-[#F8F8F8] text-[#1C1C1C] font-semibold text-xs rounded-lg flex items-center justify-center transition-colors"
                >
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

</x-layouts.app>
