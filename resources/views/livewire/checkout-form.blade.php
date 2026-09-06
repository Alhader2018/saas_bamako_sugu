<div>
    @php
        $isPurelyDigital = \App\Services\CartService::isPurelyDigital();
        $hasDigital = \App\Services\CartService::hasDigitalItems();
        $hasPhysical = \App\Services\CartService::hasPhysicalItems();
    @endphp

    @if($orderCompleted && $createdOrder)
        <!-- Écran de Confirmation de Commande Immédiate -->
        <div class="max-w-xl mx-auto bg-white rounded-xl border border-[#ECECEC] p-6 sm:p-8 text-center my-6">
            <div class="w-12 h-12 bg-emerald-50 text-[#16A34A] rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-xl sm:text-2xl font-bold text-[#1C1C1C] mb-1">
                Merci {{ $createdOrder->customer_first_name }} !
            </h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mb-6">
                @if($createdOrder->isPurelyDigital())
                    Votre commande numérique <strong class="text-[#1C1C1C]">{{ $createdOrder->order_number }}</strong> a bien été enregistrée.
                @else
                    Votre commande <strong class="text-[#1C1C1C]">{{ $createdOrder->order_number }}</strong> est enregistrée pour livraison à <strong>{{ $createdOrder->neighborhood }}, Bamako</strong>.
                @endif
            </p>

            <!-- Accès Fichiers Numériques si applicable -->
            @if($createdOrder->hasDigitalItems())
                <div class="mb-6 text-left p-4 rounded-xl border {{ $createdOrder->isPaid() ? 'bg-emerald-50/50 border-emerald-200' : 'bg-amber-50/50 border-amber-200' }}">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 {{ $createdOrder->isPaid() ? 'text-[#16A34A]' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <h3 class="text-xs font-bold {{ $createdOrder->isPaid() ? 'text-emerald-900' : 'text-amber-900' }}">
                            {{ $createdOrder->isPaid() ? 'Vos téléchargements sont prêts !' : 'Vos produits numériques (en attente de confirmation)' }}
                        </h3>
                    </div>

                    @if($createdOrder->isPaid())
                        <p class="text-xs text-emerald-800 mb-3">
                            Vous pouvez télécharger vos fichiers dès maintenant ou les retrouver à tout moment dans votre espace client.
                        </p>
                        <div class="space-y-2">
                            @foreach($createdOrder->items as $orderItem)
                                @if($orderItem->isDigital() && $orderItem->product)
                                    @foreach($orderItem->product->files as $file)
                                        <div class="flex items-center justify-between p-2.5 bg-white rounded-lg border border-emerald-100 text-xs">
                                            <div>
                                                <span class="font-semibold text-[#1C1C1C]">{{ $file->name }}</span>
                                                <span class="text-[11px] text-[#6B7280]">({{ $file->formatted_file_size }})</span>
                                            </div>
                                            <a 
                                                href="{{ route('digital.download', ['orderNumber' => $createdOrder->order_number, 'fileId' => $file->id]) }}"
                                                class="px-3 py-1 bg-[#E31E24] hover:bg-[#C9171D] text-white text-xs font-semibold rounded smooth-transition"
                                            >
                                                Télécharger
                                            </a>
                                        </div>
                                    @endforeach
                                    @if($orderItem->product->access_type === 'external_link' && $orderItem->product->external_access_url)
                                        <div class="p-2.5 bg-white rounded-lg border border-emerald-100 text-xs flex items-center justify-between">
                                            <span class="font-semibold text-[#1C1C1C]">Lien de formation / Ressource externe</span>
                                            <a href="{{ $orderItem->product->external_access_url }}" target="_blank" class="px-3 py-1 bg-[#111111] hover:bg-neutral-800 text-white text-xs font-semibold rounded">
                                                Accéder au cours
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                        @if($createdOrder->isPaid() && $createdOrder->customer_email)
                            <div class="mt-3 pt-3 border-t border-emerald-200/60 flex items-center gap-2 text-[11px] text-emerald-800">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>Les liens de téléchargement ont également été envoyés à votre email : <strong>{{ $createdOrder->customer_email }}</strong>.</span>
                            </div>
                        @endif
                    @else
                        <p class="text-xs text-amber-800">
                            Vos liens de téléchargement seront activés automatiquement dès confirmation de votre règlement Orange Money ou par le livreur.
                        </p>
                    @endif
                </div>
            @endif

            <!-- Récapitulatif Rapide -->
            <div class="bg-[#F8F8F8] rounded-lg p-4 border border-[#ECECEC] text-left text-xs space-y-2 mb-6">
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Numéro de commande</span>
                    <span class="font-semibold text-[#1C1C1C]">{{ $createdOrder->order_number }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Destinataire</span>
                    <span class="text-[#1C1C1C]">{{ $createdOrder->customer_full_name }} ({{ $createdOrder->customer_phone }})</span>
                </div>
                @if($createdOrder->customer_email)
                    <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                        <span class="text-[#6B7280]">Email de confirmation</span>
                        <span class="text-[#1C1C1C]">{{ $createdOrder->customer_email }}</span>
                    </div>
                @endif
                @if($createdOrder->hasPhysicalItems())
                    <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                        <span class="text-[#6B7280]">Adresse de livraison</span>
                        <span class="text-[#1C1C1C]">{{ $createdOrder->address }}, {{ $createdOrder->neighborhood }}</span>
                    </div>
                @else
                    <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                        <span class="text-[#6B7280]">Type de livraison</span>
                        <span class="text-emerald-700 font-semibold">Téléchargement numérique direct</span>
                    </div>
                @endif
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Moyen de règlement</span>
                    <span class="font-medium text-[#1C1C1C]">
                        {{ $createdOrder->payment_method === 'orange_money' ? 'Orange Money Mali' : 'Espèces à la livraison' }}
                    </span>
                </div>
                <div class="flex justify-between pt-1 text-sm font-bold text-[#1C1C1C]">
                    <span>Total</span>
                    <span class="text-[#E31E24]">{{ $createdOrder->formatted_total }}</span>
                </div>
            </div>

            <!-- Notice Orange Money si sélectionné -->
            @if($createdOrder->payment_method === 'orange_money' && !$createdOrder->isPaid())
                <div class="bg-amber-50 rounded-lg p-4 border border-amber-200 text-left text-xs mb-6 text-amber-900">
                    <p class="font-semibold text-[#111111] mb-1">Validation Orange Money :</p>
                    <p class="mb-3">Un message a été envoyé au numéro <strong>{{ $createdOrder->orange_money_number }}</strong>. Tapez <strong>#144#</strong> pour autoriser le paiement, ou cliquez ci-dessous pour payer directement en ligne :</p>
                    <a 
                        href="{{ route('checkout.orange.retry', ['orderNumber' => $createdOrder->order_number]) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-xs rounded-lg transition-colors shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Payer maintenant avec Orange Money
                    </a>
                </div>
            @endif

            <div class="flex items-center justify-center gap-3">
                <a 
                    href="{{ route('home') }}"
                    class="h-10 px-5 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-xs rounded-lg flex items-center justify-center smooth-transition"
                >
                    Retour à l'accueil
                </a>
                @auth
                    <a 
                        href="{{ route('account.downloads') }}"
                        class="h-10 px-4 bg-white hover:bg-neutral-50 text-[#1C1C1C] border border-[#ECECEC] font-medium text-xs rounded-lg flex items-center justify-center smooth-transition"
                    >
                        Mes achats
                    </a>
                @endauth
            </div>
        </div>

    @elseif(empty($items))
        <!-- Panier Vide -->
        <div class="max-w-md mx-auto bg-white rounded-xl border border-[#ECECEC] p-8 text-center my-8">
            <p class="text-sm font-semibold text-[#1C1C1C] mb-1">Votre panier est vide</p>
            <p class="text-xs text-[#6B7280] mb-5">Veuillez sélectionner des articles avant de passer commande.</p>
            <x-button variant="primary" size="sm" href="{{ route('catalog') }}">
                Voir le catalogue
            </x-button>
        </div>

    @else
        <!-- Formulaire One-Page Checkout -->
        <form wire:submit.prevent="submitOrder">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Colonne Formulaire (7 cols) -->
                <div class="lg:col-span-7 space-y-5">
                    
                    <!-- 1. Coordonnées -->
                    <div class="bg-white rounded-xl border border-[#ECECEC] p-4 sm:p-5">
                        <h2 class="text-sm font-bold text-[#1C1C1C] mb-3 pb-2 border-b border-[#ECECEC]">
                            1. Coordonnées
                        </h2>

                        @if($loadedFromCustomerProfile && $profileLoadedMessage)
                            <div class="mb-3.5 p-2.5 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center justify-between text-xs text-emerald-800">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#16A34A] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="font-medium">{{ $profileLoadedMessage }}</span>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Fiche client</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <x-input 
                                    label="Prénom" 
                                    wire:model="firstName" 
                                    placeholder="Ex: Oumar" 
                                    :error="$errors->first('firstName')"
                                    required
                                />
                            </div>
                            <div>
                                <x-input 
                                    label="Nom" 
                                    wire:model="lastName" 
                                    placeholder="Ex: Traoré" 
                                    :error="$errors->first('lastName')"
                                    required
                                />
                            </div>
                            <div>
                                <x-input 
                                    label="Téléphone (+223)" 
                                    wire:model.live.debounce.400ms="phone" 
                                    placeholder="+223 76 00 00 00" 
                                    :error="$errors->first('phone')"
                                    required
                                />
                            </div>
                            <div>
                                <x-input 
                                    label="{{ $hasDigital ? 'Email (obligatoire pour les fichiers)' : 'Email (facultatif)' }}" 
                                    type="email"
                                    wire:model="email" 
                                    placeholder="oumar@exemple.ml" 
                                    :error="$errors->first('email')"
                                    :required="$hasDigital"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- 2. Livraison -->
                    @if($isPurelyDigital)
                        <!-- Si commande 100% numérique : pas de livraison physique -->
                        <div class="bg-white rounded-xl border border-[#ECECEC] p-4 sm:p-5">
                            <h2 class="text-sm font-bold text-[#1C1C1C] mb-3 pb-2 border-b border-[#ECECEC] flex items-center justify-between">
                                <span>2. Mode de réception</span>
                                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                    Téléchargement immédiat
                                </span>
                            </h2>

                            <div class="flex items-start gap-3 p-3 bg-[#F8F8F8] rounded-lg border border-[#ECECEC] text-xs text-[#6B7280]">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </div>
                                <div class="space-y-1">
                                    <p class="font-medium text-[#1C1C1C]">Commande 100 % dématérialisée</p>
                                    <p>Cette commande ne nécessite aucune livraison physique. Vos liens de téléchargement seront accessibles immédiatement après confirmation du paiement sur votre écran et envoyés par email.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Si commande physique ou mixte : Adresse Bamako requise -->
                        <div class="bg-white rounded-xl border border-[#ECECEC] p-4 sm:p-5">
                            <h2 class="text-sm font-bold text-[#1C1C1C] mb-3 pb-2 border-b border-[#ECECEC]">
                                2. Adresse de livraison à Bamako
                                @if($hasDigital)
                                    <span class="text-xs font-normal text-amber-700 ml-2">(Pour vos articles physiques)</span>
                                @endif
                            </h2>

                            <div class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <x-select label="Ville" wire:model="city" required>
                                            <option value="Bamako">District de Bamako</option>
                                            <option value="Kati">Kati</option>
                                            <option value="Kalaban-Coro">Kalaban-Coro</option>
                                        </x-select>
                                    </div>
                                    <div>
                                        <x-select label="Quartier" wire:model="neighborhood" :error="$errors->first('neighborhood')" required>
                                            @foreach($neighborhoods as $n)
                                                <option value="{{ $n }}">{{ $n }}</option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                </div>

                                <div>
                                    <x-input 
                                        label="Adresse / Repère précis" 
                                        wire:model="address" 
                                        placeholder="Ex: Rue 214, près de la Pharmacie ACI, Porte 12" 
                                        :error="$errors->first('address')"
                                        required
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-[#1C1C1C] mb-1">
                                        Instructions pour le livreur (facultatif)
                                    </label>
                                    <textarea 
                                        wire:model="deliveryNotes"
                                        rows="2"
                                        placeholder="Ex: Appeler à l'arrivée au portail."
                                        class="w-full px-3 py-2 text-sm bg-white text-[#1C1C1C] placeholder:text-[#9CA3AF] border border-[#ECECEC] hover:border-neutral-300 focus:border-[#E31E24] rounded-lg outline-none smooth-transition"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 3. Moyen de Paiement -->
                    <div class="bg-white rounded-xl border border-[#ECECEC] p-4 sm:p-5">
                        <h2 class="text-sm font-bold text-[#1C1C1C] mb-3 pb-2 border-b border-[#ECECEC]">
                            3. Mode de règlement
                        </h2>

                        <div class="space-y-2.5">
                            <!-- Orange Money -->
                            <label 
                                wire:click="setPaymentMethod('orange_money')"
                                class="flex items-start gap-3 p-3.5 rounded-lg border cursor-pointer smooth-transition {{ $paymentMethod === 'orange_money' ? 'border-[#E31E24] bg-red-50/20' : 'border-[#ECECEC] hover:border-neutral-300' }}"
                            >
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="orange_money" 
                                    wire:model.live="paymentMethod" 
                                    class="mt-0.5 accent-[#E31E24]"
                                >
                                <div class="flex-1 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-[#1C1C1C]">Orange Money Mali</span>
                                        <span class="text-[10px] bg-[#F7B500] text-[#111111] font-bold px-1.5 py-0.5 rounded">Recommandé</span>
                                    </div>
                                    <p class="text-[#6B7280] mt-0.5">Paiement instantané via votre numéro Orange Money (+223).</p>

                                    @if($paymentMethod === 'orange_money')
                                        <div class="mt-3 pt-3 border-t border-[#ECECEC] space-y-2.5" wire:click.stop>
                                            <!-- Affichage du numéro direct du client -->
                                            <div class="p-2.5 bg-[#F9FAFB] rounded-lg border border-[#E5E7EB] flex items-center justify-between gap-2">
                                                <div>
                                                    <span class="text-[11px] text-[#6B7280] block">Numéro débité Orange Money :</span>
                                                    <span class="font-bold text-[#1C1C1C] text-sm font-mono">
                                                        {{ $useDifferentPaymentNumber ? ($orangeMoneyNumber ?: 'Non renseigné') : ($phone && $phone !== '+223 ' ? $phone : 'Même numéro que vos coordonnées') }}
                                                    </span>
                                                </div>
                                                @if(!$useDifferentPaymentNumber && $phone && $phone !== '+223 ')
                                                    <span class="inline-flex items-center gap-1 text-[11px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 shrink-0">
                                                        <svg class="w-3 h-3 text-[#16A34A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Numéro client direct
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Option pour payer avec un autre numéro -->
                                            <label class="flex items-center gap-2 cursor-pointer text-xs text-[#4B5563] select-none pt-0.5">
                                                <input 
                                                    type="checkbox" 
                                                    wire:model.live="useDifferentPaymentNumber" 
                                                    class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24] accent-[#E31E24]"
                                                >
                                                <span>Payer avec un autre numéro Orange Money</span>
                                            </label>

                                            @if($useDifferentPaymentNumber)
                                                <div class="pt-1">
                                                    <x-input 
                                                        label="Autre numéro Orange Money (+223)" 
                                                        wire:model="orangeMoneyNumber" 
                                                        placeholder="+223 76 00 00 00" 
                                                        :error="$errors->first('orangeMoneyNumber')"
                                                        required
                                                    />
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </label>

                            <!-- Espèces (uniquement si le panier contient des articles physiques) -->
                            @if(!$isPurelyDigital)
                                <label 
                                    wire:click="setPaymentMethod('cash_on_delivery')"
                                    class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer smooth-transition {{ $paymentMethod === 'cash_on_delivery' ? 'border-[#111111] bg-neutral-50' : 'border-[#ECECEC] hover:border-neutral-300' }}"
                                >
                                    <input 
                                        type="radio" 
                                        name="payment_method" 
                                        value="cash_on_delivery" 
                                        wire:model.live="paymentMethod" 
                                        class="mt-0.5 accent-[#111111]"
                                    >
                                    <div class="flex-1 text-xs">
                                        <span class="font-semibold text-[#1C1C1C]">Paiement à la livraison (Espèces)</span>
                                        <p class="text-[#6B7280] mt-0.5">Règlement au coursier lors de la remise de votre colis physique.</p>
                                        @if($hasDigital)
                                            <p class="text-amber-700 font-medium text-[11px] mt-1 bg-amber-50 p-1.5 rounded">
                                                Note : Vos produits numériques seront débloqués dès que le paiement en espèces sera validé lors de la livraison.
                                            </p>
                                        @endif
                                    </div>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Colonne Récapitulatif (5 cols) -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-xl border border-[#ECECEC] p-4 sm:p-5 sticky top-20 space-y-4">
                        <h3 class="text-sm font-bold text-[#1C1C1C] pb-2 border-b border-[#ECECEC] flex items-center justify-between">
                            <span>Récapitulatif</span>
                            <span class="text-xs font-normal text-[#6B7280]">{{ count($items) }} article{{ count($items) > 1 ? 's' : '' }}</span>
                        </h3>

                        <!-- Liste Articles -->
                        <div class="divide-y divide-[#ECECEC] max-h-56 overflow-y-auto">
                            @foreach($items as $item)
                                @php $isItemDigital = ($item['product_type'] ?? 'physical') === 'digital'; @endphp
                                <div class="py-2 flex items-center gap-2.5 first:pt-0 last:pb-0 text-xs">
                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-10 h-10 rounded object-cover border border-[#ECECEC] shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-medium text-[#1C1C1C] truncate">{{ $item['name'] }}</p>
                                            @if($isItemDigital)
                                                <span class="px-1.5 py-0.2 text-[9px] font-semibold bg-amber-100 text-amber-800 rounded shrink-0">
                                                    Numérique
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-[#6B7280]">Qté : {{ $item['quantity'] }}</p>
                                    </div>
                                    <div class="font-semibold text-[#1C1C1C] shrink-0">
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Calculs -->
                        <div class="space-y-1.5 pt-3 border-t border-[#ECECEC] text-xs">
                            <div class="flex justify-between text-[#6B7280]">
                                <span>Sous-total</span>
                                <span class="font-medium text-[#1C1C1C]">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between text-[#6B7280]">
                                <span>Livraison</span>
                                @if($deliveryFee === 0)
                                    <span class="font-medium text-emerald-700">Offerte</span>
                                @else
                                    <span class="font-medium text-[#1C1C1C]">{{ number_format($deliveryFee, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>
                            <div class="flex justify-between text-sm font-bold text-[#1C1C1C] pt-2 border-t border-[#ECECEC]">
                                <span>Total</span>
                                <span class="text-[#E31E24]">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>

                        <!-- Bouton Validation -->
                        <div class="pt-1">
                            <button 
                                type="submit" 
                                class="w-full h-11 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-sm rounded-lg flex items-center justify-center smooth-transition cursor-pointer"
                            >
                                @if($paymentMethod === 'orange_money')
                                    <span>Payer avec Orange Money ({{ number_format($total, 0, ',', ' ') }} FCFA)</span>
                                @else
                                    <span>Confirmer la commande ({{ number_format($total, 0, ',', ' ') }} FCFA)</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    @endif
</div>
