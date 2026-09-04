<div>
    <!-- Backdrop -->
    <div 
        x-data="{ show: @entangle('isOpen') }" 
        x-show="show" 
        x-cloak 
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50"
        @click="$wire.close()"
    ></div>

    <!-- Drawer Panel -->
    <div 
        x-data="{ show: @entangle('isOpen') }" 
        x-show="show" 
        x-cloak
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 max-w-full w-full sm:w-110 bg-white z-50 shadow-2xl flex flex-col justify-between"
    >
        <!-- Header -->
        <div class="px-5 py-4 border-b border-[#ECECEC] flex items-center justify-between bg-white">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-[#E31E24]/10 text-[#E31E24] flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="8" cy="21" r="1"></circle>
                        <circle cx="19" cy="21" r="1"></circle>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#1C1C1C]">Mon Panier BKO SU</h2>
                    <p class="text-xs text-[#6B7280]">{{ $count }} article{{ $count > 1 ? 's' : '' }}</p>
                </div>
            </div>

            <button 
                type="button" 
                wire:click="close" 
                class="w-8 h-8 rounded-lg text-neutral-400 hover:text-[#1C1C1C] hover:bg-neutral-100 flex items-center justify-center smooth-transition cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Notification Bar Livraison Bamako -->
        <div class="bg-amber-50 px-5 py-2.5 border-b border-amber-100/70 text-xs flex items-center gap-2 text-amber-900">
            <svg class="w-4 h-4 text-[#F7B500] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @if($subtotal >= 50000)
                <span class="font-semibold text-emerald-800">🎉 Félicitations ! Livraison offerte partout à Bamako !</span>
            @else
                <span>Plus que <strong>{{ number_format(50000 - $subtotal, 0, ',', ' ') }} FCFA</strong> pour la <strong>livraison gratuite</strong> à Bamako !</span>
            @endif
        </div>

        <!-- Items List -->
        <div class="flex-1 overflow-y-auto px-5 py-4 divide-y divide-[#ECECEC]/70">
            @forelse($items as $item)
                <div class="py-3.5 flex gap-3.5 items-center first:pt-0 last:pb-0" wire:key="cart-item-{{ $item['id'] }}">
                    <img 
                        src="{{ $item['image_url'] }}" 
                        alt="{{ $item['name'] }}" 
                        class="w-18 h-18 rounded-xl object-cover border border-[#ECECEC] shrink-0"
                    >

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1">
                            <h4 class="text-xs font-semibold text-[#1C1C1C] line-clamp-1 leading-snug">
                                {{ $item['name'] }}
                            </h4>
                            <button 
                                type="button" 
                                wire:click="removeItem({{ $item['id'] }})"
                                class="text-neutral-400 hover:text-[#DC2626] smooth-transition p-1 cursor-pointer"
                                title="Supprimer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>

                        <p class="text-[11px] text-[#6B7280] mb-2">{{ $item['vendor_name'] }}</p>

                        <div class="flex items-center justify-between">
                            <!-- Stepper Quantité -->
                            <div class="inline-flex items-center border border-[#ECECEC] rounded-lg bg-neutral-50 h-7 text-xs">
                                <button 
                                    type="button" 
                                    wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                    class="w-6 h-full flex items-center justify-center hover:bg-white text-[#1C1C1C] smooth-transition cursor-pointer"
                                >-</button>
                                <span class="w-7 text-center font-bold text-[#1C1C1C]">{{ $item['quantity'] }}</span>
                                <button 
                                    type="button" 
                                    wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                    class="w-6 h-full flex items-center justify-center hover:bg-white text-[#1C1C1C] smooth-transition cursor-pointer"
                                >+</button>
                            </div>

                            <!-- Prix FCFA -->
                            <span class="text-xs font-bold text-[#1C1C1C]">
                                {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-center py-16 px-4">
                    <div class="w-16 h-16 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-[#1C1C1C] mb-1">Votre panier est vide</h3>
                    <p class="text-xs text-[#6B7280] max-w-xs mb-6">Explorez les offres de Bamako Supermarché et commencez vos achats dès maintenant.</p>
                    <x-button variant="primary" size="sm" wire:click="close">
                        Découvrir les produits
                    </x-button>
                </div>
            @endforelse
        </div>

        <!-- Footer / Total / Checkout -->
        @if(count($items) > 0)
            <div class="p-5 border-t border-[#ECECEC] bg-[#F8F8F8] space-y-3">
                <div class="space-y-1.5 text-xs text-[#6B7280]">
                    <div class="flex justify-between">
                        <span>Sous-total</span>
                        <span class="font-semibold text-[#1C1C1C]">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Livraison Bamako</span>
                        @if($deliveryFee === 0)
                            <span class="font-bold text-[#16A34A]">Gratuite</span>
                        @else
                            <span class="font-semibold text-[#1C1C1C]">{{ number_format($deliveryFee, 0, ',', ' ') }} FCFA</span>
                        @endif
                    </div>
                    <div class="flex justify-between text-base font-bold text-[#1C1C1C] pt-2 border-t border-[#ECECEC]">
                        <span>Total estimé</span>
                        <span class="text-[#E31E24]">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>

                <div class="pt-1">
                    <a 
                        href="{{ route('checkout') }}"
                        class="w-full h-12 bg-[#E31E24] hover:bg-[#C9171D] text-white font-bold rounded-xl flex items-center justify-center gap-2 shadow-sm shadow-red-500/20 smooth-transition cursor-pointer"
                    >
                        <span>Commander maintenant</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <div class="flex items-center justify-center gap-4 text-[11px] text-[#6B7280] pt-1">
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span> Paiement Orange Money
                    </span>
                    <span>•</span>
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-[#F7B500]"></span> Cash à la livraison
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>
