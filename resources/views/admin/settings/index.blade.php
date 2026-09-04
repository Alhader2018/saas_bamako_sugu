<x-admin.layout title="Paramètres & Intégrations" :breadcrumb="['Paramètres' => route('admin.settings.index')]">
    <!-- Header -->
    <x-admin.page-header title="Paramètres du Supermarché" description="Configuration générale, statut Orange Money et logistique Bamako" />

    <div class="space-y-6">
        
        <!-- 1. Configuration Orange Money (Spécifique Mali) -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
            <div class="flex items-center justify-between pb-4 border-b border-[#E5E7EB] mb-4">
                <div>
                    <h2 class="text-sm font-semibold text-[#111111]">Passerelle de Paiement Orange Money Mali</h2>
                    <p class="text-xs text-[#6B7280]">État de l'intégration API Web Payment officielle</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold {{ $orangeMoneyConfig['currency'] === 'OUV' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                    ● {{ $orangeMoneyConfig['mode'] }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3 bg-[#F9FAFB] rounded border border-[#E5E7EB]">
                    <span class="text-[#6B7280] block text-[11px]">Devise API active :</span>
                    <span class="font-bold text-sm text-[#111111]">{{ $orangeMoneyConfig['currency'] }}</span>
                    <span class="text-[11px] text-[#6B7280] block mt-0.5">
                        {{ $orangeMoneyConfig['currency'] === 'OUV' ? 'Requis en Sandbox selon le guide Orange' : 'XOF (Francs CFA Réel)' }}
                    </span>
                </div>

                <div class="p-3 bg-[#F9FAFB] rounded border border-[#E5E7EB]">
                    <span class="text-[#6B7280] block text-[11px]">Merchant Key configurée :</span>
                    <span class="font-mono text-xs text-[#111111] font-semibold">
                        {{ $orangeMoneyConfig['merchant_key'] ? '••••••••' . substr($orangeMoneyConfig['merchant_key'], -4) : 'Non renseigné' }}
                    </span>
                    <span class="text-[11px] text-emerald-600 block mt-0.5">Générée sur developer.orange.com</span>
                </div>

                <div class="sm:col-span-2 p-3 bg-[#F9FAFB] rounded border border-[#E5E7EB]">
                    <span class="text-[#6B7280] block text-[11px] mb-1">Endpoints API connectés :</span>
                    <div class="font-mono text-[11px] text-[#374151] space-y-1">
                        <div><strong class="text-[#111111]">WebPayment :</strong> {{ $orangeMoneyConfig['webpayment_url'] }}</div>
                        <div><strong class="text-[#111111]">OAuth 2.0 :</strong> {{ $orangeMoneyConfig['token_url'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Informations Boutique BKO SU -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
            <h2 class="text-sm font-semibold text-[#111111] mb-3">Informations de la Boutique</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-medium text-[#6B7280] mb-1">Nom commercial :</label>
                    <input type="text" readonly value="{{ $storeInfo['name'] }}" class="w-full h-8 px-2.5 bg-[#F9FAFB] border border-[#D1D5DB] rounded text-[#111111] cursor-not-allowed">
                </div>

                <div>
                    <label class="block font-medium text-[#6B7280] mb-1">Téléphone de contact Mali :</label>
                    <input type="text" readonly value="{{ $storeInfo['phone'] }}" class="w-full h-8 px-2.5 bg-[#F9FAFB] border border-[#D1D5DB] rounded text-[#111111] cursor-not-allowed">
                </div>

                <div>
                    <label class="block font-medium text-[#6B7280] mb-1">Adresse magasin :</label>
                    <input type="text" readonly value="{{ $storeInfo['address'] }}" class="w-full h-8 px-2.5 bg-[#F9FAFB] border border-[#D1D5DB] rounded text-[#111111] cursor-not-allowed">
                </div>

                <div>
                    <label class="block font-medium text-[#6B7280] mb-1">Devise storefront :</label>
                    <input type="text" readonly value="{{ $storeInfo['currency'] }}" class="w-full h-8 px-2.5 bg-[#F9FAFB] border border-[#D1D5DB] rounded text-[#111111] cursor-not-allowed">
                </div>
            </div>
        </div>

        <!-- 3. Paramètres Logistique Bamako -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
            <h2 class="text-sm font-semibold text-[#111111] mb-3">Frais de Livraison Bamako</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-medium text-[#6B7280] mb-1">Frais standard par course :</label>
                    <div class="h-8 px-2.5 bg-[#F9FAFB] border border-[#D1D5DB] rounded flex items-center font-bold text-[#111111]">
                        {{ number_format($storeInfo['standard_delivery_fee'], 0, ',', ' ') }} FCFA
                    </div>
                </div>

                <div>
                    <label class="block font-medium text-[#6B7280] mb-1">Seuil de livraison offerte :</label>
                    <div class="h-8 px-2.5 bg-[#F9FAFB] border border-[#D1D5DB] rounded flex items-center font-bold text-emerald-700">
                        {{ number_format($storeInfo['free_delivery_threshold'], 0, ',', ' ') }} FCFA
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-admin.layout>
