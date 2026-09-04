<div>
    <!-- Backdrop -->
    <div 
        x-data="{ show: @entangle('isOpen') }" 
        x-show="show" 
        x-cloak 
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/40 z-50"
        @click="$wire.close()"
    ></div>

    <!-- Drawer Panel -->
    <div 
        x-data="{ show: @entangle('isOpen') }" 
        x-show="show" 
        x-cloak
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 max-w-full w-full sm:w-104 bg-white z-50 shadow-lg flex flex-col justify-between"
    >
        <!-- Header -->
        <div class="px-5 py-4 border-b border-[#ECECEC] flex items-center justify-between bg-white">
            <div>
                <h2 class="text-sm font-bold text-[#1C1C1C]">Votre panier ({{ $count }})</h2>
            </div>

            <button 
                type="button" 
                wire:click="close" 
                class="w-7 h-7 rounded text-neutral-400 hover:text-[#1C1C1C] flex items-center justify-center cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Info Livraison Bamako -->
        <div class="bg-[#F8F8F8] px-5 py-2.5 border-b border-[#ECECEC] text-xs text-[#6B7280]">
            @if($subtotal >= 50000)
                <span class="font-medium text-emerald-700">Livraison offerte sur cette commande à Bamako.</span>
            @else
                <span>Plus que <strong>{{ number_format(50000 - $subtotal, 0, ',', ' ') }} FCFA</strong> pour la livraison offerte.</span>
            @endif
        </div>

        <!-- Items List -->
        <div class="flex-1 overflow-y-auto px-5 py-3 divide-y divide-[#ECECEC]">
            @forelse($items as $item)
                <div class="py-3 flex gap-3 items-center first:pt-0 last:pb-0" wire:key="cart-item-{{ $item['id'] }}">
                    <img 
                        src="{{ $item['image_url'] }}" 
                        alt="{{ $item['name'] }}" 
                        class="w-16 h-16 rounded object-cover border border-[#ECECEC] shrink-0"
                    >

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h4 class="text-xs font-semibold text-[#1C1C1C] line-clamp-1">
                                {{ $item['name'] }}
                            </h4>
                            <button 
                                type="button" 
                                wire:click="removeItem({{ $item['id'] }})"
                                class="text-neutral-400 hover:text-[#DC2626] p-0.5 cursor-pointer"
                                title="Supprimer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <p class="text-[11px] text-[#6B7280] mb-2">{{ $item['vendor_name'] }}</p>

                        <div class="flex items-center justify-between">
                            <!-- Stepper Quantité -->
                            <div class="inline-flex items-center border border-[#ECECEC] rounded bg-neutral-50 h-6 text-xs">
                                <button 
                                    type="button" 
                                    wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                    class="w-5 h-full flex items-center justify-center hover:bg-white text-[#1C1C1C] cursor-pointer"
                                >-</button>
                                <span class="w-6 text-center font-medium text-[#1C1C1C]">{{ $item['quantity'] }}</span>
                                <button 
                                    type="button" 
                                    wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                    class="w-5 h-full flex items-center justify-center hover:bg-white text-[#1C1C1C] cursor-pointer"
                                >+</button>
                            </div>

                            <!-- Prix -->
                            <span class="text-xs font-semibold text-[#1C1C1C]">
                                {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-center py-12 px-4">
                    <p class="text-sm font-semibold text-[#1C1C1C] mb-1">Votre panier est vide</p>
                    <p class="text-xs text-[#6B7280] mb-5">Ajoutez des articles pour débuter votre commande.</p>
                    <x-button variant="primary" size="sm" wire:click="close">
                        Continuer les achats
                    </x-button>
                </div>
            @endforelse
        </div>

        <!-- Footer Panier -->
        @if(count($items) > 0)
            <div class="p-4 border-t border-[#ECECEC] bg-white space-y-3">
                <div class="space-y-1 text-xs text-[#6B7280]">
                    <div class="flex justify-between">
                        <span>Sous-total</span>
                        <span class="font-medium text-[#1C1C1C]">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Livraison Bamako</span>
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

                <a 
                    href="{{ route('checkout') }}"
                    class="w-full h-11 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-sm rounded-lg flex items-center justify-center smooth-transition cursor-pointer"
                >
                    Passer la commande
                </a>
            </div>
        @endif
    </div>
</div>
