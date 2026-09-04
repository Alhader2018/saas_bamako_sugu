<div>
    @if($orderCompleted && $createdOrder)
        <!-- Écran de Confirmation de Commande Immédiate -->
        <div class="max-w-2xl mx-auto bg-white rounded-3xl border border-[#ECECEC] p-6 sm:p-10 text-center shadow-lg my-8">
            <div class="w-20 h-20 bg-emerald-50 text-[#16A34A] rounded-full flex items-center justify-center mx-auto mb-5 border-2 border-emerald-100">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <span class="inline-block bg-[#F7B500]/20 text-[#111111] font-bold text-xs px-3 py-1 rounded-full mb-3">
                Commande validée avec succès
            </span>

            <h1 class="text-2xl sm:text-3xl font-black text-[#1C1C1C] mb-2 tracking-tight">
                Merci {{ $createdOrder->customer_first_name }} !
            </h1>
            <p class="text-sm text-[#6B7280] mb-6">
                Votre commande <strong class="text-[#1C1C1C]">{{ $createdOrder->order_number }}</strong> a bien été enregistrée et est en cours de préparation pour livraison à <strong>{{ $createdOrder->neighborhood }}, Bamako</strong>.
            </p>

            <!-- Récapitulatif Rapide -->
            <div class="bg-[#F8F8F8] rounded-2xl p-5 border border-[#ECECEC] text-left text-xs sm:text-sm space-y-2.5 mb-8">
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Numéro de commande</span>
                    <span class="font-bold text-[#1C1C1C]">{{ $createdOrder->order_number }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Destinataire</span>
                    <span class="font-semibold text-[#1C1C1C]">{{ $createdOrder->customer_full_name }} ({{ $createdOrder->customer_phone }})</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Lieu de livraison</span>
                    <span class="font-semibold text-[#1C1C1C]">{{ $createdOrder->address }}, {{ $createdOrder->neighborhood }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#ECECEC]">
                    <span class="text-[#6B7280]">Mode de règlement</span>
                    <span class="font-bold {{ $createdOrder->payment_method === 'orange_money' ? 'text-[#F7B500]' : 'text-[#1C1C1C]' }}">
                        {{ $createdOrder->payment_method === 'orange_money' ? 'Orange Money Mali' : 'Espèces à la livraison' }}
                    </span>
                </div>
                <div class="flex justify-between pt-2 text-base font-black text-[#1C1C1C]">
                    <span>Total réglé / dû</span>
                    <span class="text-[#E31E24]">{{ $createdOrder->formatted_total }}</span>
                </div>
            </div>

            <!-- Notice Orange Money si sélectionné -->
            @if($createdOrder->payment_method === 'orange_money')
                <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200 text-left text-xs mb-8 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#F7B500] text-black font-extrabold flex items-center justify-center shrink-0">
                        OM
                    </div>
                    <div>
                        <p class="font-bold text-[#111111]">Paiement Orange Money initié</p>
                        <p class="text-amber-900 mt-0.5">
                            Un message de validation a été envoyé sur votre numéro Orange Money <strong>{{ $createdOrder->orange_money_number }}</strong>. Tapez <strong>#144#</strong> pour confirmer votre code PIN et finaliser la transaction.
                        </p>
                    </div>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a 
                    href="{{ route('home') }}"
                    class="w-full sm:w-auto h-12 px-8 bg-[#E31E24] hover:bg-[#C9171D] text-white font-bold rounded-xl flex items-center justify-center gap-2 smooth-transition shadow-sm shadow-red-500/20"
                >
                    Retour à l'accueil BKO SU
                </a>
                <a 
                    href="{{ route('catalog') }}"
                    class="w-full sm:w-auto h-12 px-6 bg-white hover:bg-neutral-50 text-[#1C1C1C] border border-[#ECECEC] font-semibold rounded-xl flex items-center justify-center gap-2 smooth-transition"
                >
                    Continuer le shopping
                </a>
            </div>
        </div>

    @elseif(empty($items))
        <!-- Panier Vide -->
        <div class="max-w-xl mx-auto bg-white rounded-3xl border border-[#ECECEC] p-10 text-center shadow-sm my-12">
            <div class="w-16 h-16 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-400 mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-[#1C1C1C] mb-2">Votre panier est actuellement vide</h2>
            <p class="text-sm text-[#6B7280] mb-6">Ajoutez des articles de nos boutiques bamakoises avant de finaliser votre commande.</p>
            <x-button variant="primary" href="{{ route('catalog') }}">
                Explorer les rayons BKO SU
            </x-button>
        </div>

    @else
        <!-- Formulaire One-Page Checkout -->
        <form wire:submit.prevent="submitOrder">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Colonne Formulaire (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- 1. Coordonnées -->
                    <div class="bg-white rounded-2xl border border-[#ECECEC] p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-[#ECECEC]">
                            <span class="w-7 h-7 rounded-full bg-[#E31E24] text-white text-xs font-black flex items-center justify-center">1</span>
                            <h2 class="text-base font-bold text-[#1C1C1C]">Coordonnées client</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                    label="Téléphone Mali (+223)" 
                                    wire:model="phone" 
                                    placeholder="+223 76 00 00 00" 
                                    :error="$errors->first('phone')"
                                    hint="Pour confirmation de livraison par le livreur"
                                    required
                                />
                            </div>
                            <div>
                                <x-input 
                                    label="Email (facultatif)" 
                                    type="email"
                                    wire:model="email" 
                                    placeholder="oumar@exemple.ml" 
                                    :error="$errors->first('email')"
                                    hint="Pour recevoir le reçu numérique"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- 2. Livraison Bamako -->
                    <div class="bg-white rounded-2xl border border-[#ECECEC] p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-[#ECECEC]">
                            <span class="w-7 h-7 rounded-full bg-[#E31E24] text-white text-xs font-black flex items-center justify-center">2</span>
                            <h2 class="text-base font-bold text-[#1C1C1C]">Livraison à Bamako</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-select label="Ville" wire:model="city" required>
                                        <option value="Bamako">District de Bamako</option>
                                        <option value="Kati">Kati (Périphérie)</option>
                                        <option value="Kalaban-Coro">Kalaban-Coro</option>
                                    </x-select>
                                </div>
                                <div>
                                    <x-select label="Quartier de livraison" wire:model="neighborhood" :error="$errors->first('neighborhood')" required>
                                        @foreach($neighborhoods as $n)
                                            <option value="{{ $n }}">{{ $n }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>

                            <div>
                                <x-input 
                                    label="Adresse / Rue / Repère précis" 
                                    wire:model="address" 
                                    placeholder="Ex: Rue 214, à 50m de la Pharmacie ACI, Porte 12" 
                                    :error="$errors->first('address')"
                                    hint="Un repère géographique précis permet une livraison beaucoup plus rapide"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#1C1C1C] uppercase tracking-wider mb-1.5">
                                    Instructions spéciales pour le livreur (optionnel)
                                </label>
                                <textarea 
                                    wire:model="deliveryNotes"
                                    rows="2"
                                    placeholder="Ex: Klaxonner ou appeler une fois au portail, maison avec véranda blanche."
                                    class="w-full px-3.5 py-2.5 text-sm bg-white text-[#1C1C1C] placeholder:text-[#6B7280] border border-[#ECECEC] hover:border-neutral-300 focus:border-[#E31E24] focus:ring-2 focus:ring-red-500/10 rounded-xl outline-none smooth-transition"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Paiement -->
                    <div class="bg-white rounded-2xl border border-[#ECECEC] p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-[#ECECEC]">
                            <span class="w-7 h-7 rounded-full bg-[#E31E24] text-white text-xs font-black flex items-center justify-center">3</span>
                            <h2 class="text-base font-bold text-[#1C1C1C]">Mode de paiement</h2>
                        </div>

                        <div class="space-y-3">
                            <!-- Option Orange Money -->
                            <label 
                                wire:click="setPaymentMethod('orange_money')"
                                class="flex items-start gap-3.5 p-4 rounded-xl border-2 cursor-pointer smooth-transition {{ $paymentMethod === 'orange_money' ? 'border-[#F7B500] bg-amber-50/40 ring-1 ring-[#F7B500]' : 'border-[#ECECEC] hover:border-neutral-300 bg-white' }}"
                            >
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="orange_money" 
                                    wire:model.live="paymentMethod" 
                                    class="mt-1 accent-[#E31E24]"
                                >
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-[#F7B500] text-black font-extrabold text-[11px] flex items-center justify-center">OM</span>
                                            <span class="text-sm font-bold text-[#1C1C1C]">Orange Money Mali</span>
                                        </div>
                                        <span class="text-[11px] bg-[#F7B500] text-[#111111] font-bold px-2 py-0.5 rounded-full">Recommandé</span>
                                    </div>
                                    <p class="text-xs text-[#6B7280] mt-1">Paiement instantané et sécurisé via votre compte Orange Money (+223).</p>

                                    @if($paymentMethod === 'orange_money')
                                        <div class="mt-3 pt-3 border-t border-amber-200/60">
                                            <x-input 
                                                label="Numéro Orange Money" 
                                                wire:model="orangeMoneyNumber" 
                                                placeholder="+223 76 00 00 00" 
                                                :error="$errors->first('orangeMoneyNumber')"
                                                hint="Vous validerez l'ordre de débit avec votre code secret #144#"
                                                required
                                            />
                                        </div>
                                    @endif
                                </div>
                            </label>

                            <!-- Option Espèces à la livraison -->
                            <label 
                                wire:click="setPaymentMethod('cash_on_delivery')"
                                class="flex items-start gap-3.5 p-4 rounded-xl border-2 cursor-pointer smooth-transition {{ $paymentMethod === 'cash_on_delivery' ? 'border-[#111111] bg-neutral-50 ring-1 ring-[#111111]' : 'border-[#ECECEC] hover:border-neutral-300 bg-white' }}"
                            >
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="cash_on_delivery" 
                                    wire:model.live="paymentMethod" 
                                    class="mt-1 accent-[#111111]"
                                >
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-[#16A34A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <rect width="20" height="12" x="2" y="6" rx="2"></rect>
                                                <circle cx="12" cy="12" r="2"></circle>
                                                <path d="M6 12h.01M18 12h.01"></path>
                                            </svg>
                                            <span class="text-sm font-bold text-[#1C1C1C]">Paiement à la livraison (Espèces)</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-[#6B7280] mt-1">Réglez en mains propres au coursier lors de la réception de vos courses à Bamako.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Colonne Récapitulatif (5 cols) -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl border border-[#ECECEC] p-5 sm:p-6 shadow-sm sticky top-24 space-y-5">
                        <h3 class="text-base font-bold text-[#1C1C1C] pb-3 border-b border-[#ECECEC] flex items-center justify-between">
                            <span>Récapitulatif de commande</span>
                            <span class="text-xs font-normal text-[#6B7280]">{{ count($items) }} article{{ count($items) > 1 ? 's' : '' }}</span>
                        </h3>

                        <!-- Liste Articles Panier -->
                        <div class="divide-y divide-[#ECECEC]/70 max-h-64 overflow-y-auto pr-1">
                            @foreach($items as $item)
                                <div class="py-2.5 flex items-center gap-3 first:pt-0 last:pb-0">
                                    <div class="relative shrink-0">
                                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-13 h-13 rounded-lg object-cover border border-[#ECECEC]">
                                        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-[#111111] text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                            {{ $item['quantity'] }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-semibold text-[#1C1C1C] truncate">{{ $item['name'] }}</h4>
                                        <p class="text-[11px] text-[#6B7280]">{{ $item['vendor_name'] }}</p>
                                    </div>
                                    <div class="text-xs font-bold text-[#1C1C1C] shrink-0">
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Calculs -->
                        <div class="space-y-2 pt-3 border-t border-[#ECECEC] text-xs">
                            <div class="flex justify-between text-[#6B7280]">
                                <span>Sous-total articles</span>
                                <span class="font-semibold text-[#1C1C1C]">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between text-[#6B7280]">
                                <span>Livraison Bamako ({{ $neighborhood }})</span>
                                @if($deliveryFee === 0)
                                    <span class="font-bold text-[#16A34A]">Offerte</span>
                                @else
                                    <span class="font-semibold text-[#1C1C1C]">{{ number_format($deliveryFee, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>
                            <div class="flex justify-between text-base font-black text-[#1C1C1C] pt-3 border-t border-[#ECECEC]">
                                <span>Total net à payer</span>
                                <span class="text-[#E31E24]">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>

                        <!-- Bouton Validation / CTA Principal Rouge BKO -->
                        <div class="pt-2">
                            <button 
                                type="submit" 
                                class="w-full h-13 bg-[#E31E24] hover:bg-[#C9171D] text-white font-black text-sm sm:text-base rounded-xl flex items-center justify-center gap-2 shadow-md shadow-red-500/25 smooth-transition cursor-pointer"
                            >
                                @if($paymentMethod === 'orange_money')
                                    <span>Payer avec Orange Money ({{ number_format($total, 0, ',', ' ') }} FCFA)</span>
                                @else
                                    <span>Confirmer la commande ({{ number_format($total, 0, ',', ' ') }} FCFA)</span>
                                @endif
                            </button>
                        </div>

                        <!-- Éléments de Réassurance BKO SU -->
                        <div class="space-y-2 pt-2 text-[11px] text-[#6B7280]">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#16A34A] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span>Paiement sécurisé et contrôlé avant livraison</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#16A34A] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Livraison express à Bamako en moins de 3 heures</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    @endif
</div>
