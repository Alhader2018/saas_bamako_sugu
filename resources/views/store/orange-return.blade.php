<x-layouts.app title="Paiement Orange Money — BKO SU">

    <div class="max-w-xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl border border-[#ECECEC] p-6 sm:p-8 text-center">
            @if($order->payment_status === 'paid' || $order->status === 'confirmed')
                <div class="w-12 h-12 bg-emerald-50 text-[#16A34A] rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded">
                    Paiement Orange Money validé
                </span>
                <h1 class="text-xl font-bold text-[#1C1C1C] mt-2 mb-1">
                    Merci pour votre commande !
                </h1>
                <p class="text-xs text-[#6B7280] mb-6">
                    Votre paiement pour la commande <strong>{{ $order->order_number }}</strong> a bien été confirmé.
                </p>
            @else
                <div class="w-12 h-12 bg-amber-50 text-[#F7B500] rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[11px] font-semibold text-amber-900 bg-amber-50 px-2.5 py-0.5 rounded">
                    Validation en cours
                </span>
                <h1 class="text-xl font-bold text-[#1C1C1C] mt-2 mb-1">
                    Commande {{ $order->order_number }}
                </h1>
                <p class="text-xs text-[#6B7280] mb-6">
                    Le statut de votre paiement Orange Money est en cours de confirmation. Vous recevrez la confirmation dès validation.
                </p>
            @endif

            <div class="bg-[#F8F8F8] rounded-lg p-4 border border-[#ECECEC] text-left text-xs space-y-2 mb-6">
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Numéro de commande</span>
                    <span class="font-semibold text-[#1C1C1C]">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Destinataire</span>
                    <span class="text-[#1C1C1C]">{{ $order->customer_full_name }} ({{ $order->customer_phone }})</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Livraison à Bamako</span>
                    <span class="text-[#1C1C1C]">{{ $order->neighborhood }}</span>
                </div>
                <div class="flex justify-between pt-1 text-sm font-bold text-[#1C1C1C]">
                    <span>Montant réglé</span>
                    <span class="text-[#E31E24]">{{ $order->formatted_total }}</span>
                </div>
            </div>

            <div class="flex items-center justify-center gap-3">
                <a 
                    href="{{ route('home') }}"
                    class="h-10 px-5 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-xs rounded-lg flex items-center justify-center smooth-transition"
                >
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

</x-layouts.app>
