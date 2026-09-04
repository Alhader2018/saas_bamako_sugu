<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#ECECEC]">
    <!-- Top Announcement Bar (Bamako Info & Promo) -->
    <div class="bg-[#111111] text-white text-[11px] sm:text-xs py-1.5 px-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-[#16A34A] animate-ping"></span>
                <span>🇲🇱 <strong>Bamako Express</strong> : Livraison dans tous les quartiers aujourd'hui</span>
            </div>
            <div class="hidden sm:flex items-center gap-4 text-neutral-300">
                <span>Paiement <strong>Orange Money</strong> & <strong>Cash</strong></span>
                <span class="text-neutral-500">•</span>
                <a href="tel:+22370000000" class="hover:text-white flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-[#F7B500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Service Client : +223 70 00 00 00
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4 h-18">
            
            <!-- Logo BKO SU -->
            <a href="{{ route('home') }}" class="shrink-0 group">
                <x-logo class="h-9 sm:h-10 w-auto" />
            </a>

            <!-- Catégories Dropdown (Desktop) -->
            <div class="hidden lg:block relative shrink-0" x-data="{ open: false }" @click.outside="open = false">
                <button 
                    @click="open = !open" 
                    type="button"
                    class="flex items-center gap-2 h-11 px-4 rounded-xl bg-neutral-100 hover:bg-neutral-200 text-[#1C1C1C] text-xs font-bold smooth-transition cursor-pointer"
                >
                    <svg class="w-4 h-4 text-[#E31E24]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span>Rayons</span>
                    <svg class="w-3.5 h-3.5 text-[#6B7280] smooth-transition" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div 
                    x-show="open" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl border border-[#ECECEC] shadow-xl py-2 z-50"
                >
                    @php
                        $categories = \App\Models\Category::orderBy('display_order')->get();
                    @endphp
                    @foreach($categories as $cat)
                        <a 
                            href="{{ route('catalog', ['category' => $cat->slug]) }}" 
                            class="flex items-center justify-between px-4 py-2.5 text-xs font-semibold text-[#1C1C1C] hover:bg-[#F8F8F8] hover:text-[#E31E24] smooth-transition"
                        >
                            <span class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-[#E31E24]"></span>
                                {{ $cat->name }}
                            </span>
                            @if($cat->badge)
                                <span class="text-[10px] font-bold text-[#F7B500] bg-amber-50 px-1.5 py-0.2 rounded">
                                    {{ $cat->badge }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Grande Barre de Recherche (Desktop & Tablet) -->
            <div class="hidden sm:flex flex-1 max-w-2xl mx-2">
                <livewire:search-bar />
            </div>

            <!-- Actions Utilisateur -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Lien Admin / Back-office -->
                <a 
                    href="{{ route('admin.dashboard') }}"
                    class="hidden xl:flex items-center gap-1.5 h-11 px-3 text-xs font-semibold text-[#6B7280] hover:text-[#1C1C1C] hover:bg-neutral-50 rounded-xl smooth-transition"
                    title="Accès Espace Administration"
                >
                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Admin</span>
                </a>

                <!-- Favoris -->
                <button 
                    type="button" 
                    onclick="window.dispatchEvent(new CustomEvent('toast', {detail: {message: 'Vos articles favoris sont synchronisés !'}}))"
                    class="hidden md:flex items-center justify-center w-11 h-11 rounded-xl text-[#1C1C1C] hover:bg-neutral-100 border border-transparent hover:border-[#ECECEC] smooth-transition cursor-pointer"
                    aria-label="Mes favoris"
                >
                    <svg class="w-5 h-5 text-neutral-600 hover:text-[#E31E24]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>

                <!-- Mon Compte -->
                <div class="relative" x-data="{ accountOpen: false }" @click.outside="accountOpen = false">
                    <button 
                        @click="accountOpen = !accountOpen" 
                        type="button"
                        class="hidden sm:flex items-center gap-2 h-11 px-3.5 rounded-xl text-[#1C1C1C] hover:bg-neutral-100 border border-transparent hover:border-[#ECECEC] smooth-transition cursor-pointer"
                    >
                        <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-xs font-semibold text-[#1C1C1C]">Compte</span>
                    </button>

                    <div 
                        x-show="accountOpen" 
                        x-cloak
                        class="absolute right-0 mt-2 w-52 bg-white rounded-2xl border border-[#ECECEC] shadow-xl py-2 z-50 text-xs"
                    >
                        <div class="px-4 py-2 border-b border-[#ECECEC]">
                            <p class="font-bold text-[#1C1C1C]">Client Bamako</p>
                            <p class="text-[#6B7280] text-[11px]">+223 • Mali</p>
                        </div>
                        <a href="{{ route('checkout') }}" class="block px-4 py-2 hover:bg-neutral-50 text-[#1C1C1C]">Mes commandes en cours</a>
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-neutral-50 text-[#E31E24] font-semibold">Tableau de bord Admin</a>
                    </div>
                </div>

                <!-- Panier Réactif Livewire avec Badge -->
                <livewire:cart-badge />
            </div>

        </div>

        <!-- Recherche Mobile (visible sur petits écrans sous le logo) -->
        <div class="sm:hidden pb-3">
            <livewire:search-bar />
        </div>
    </div>
</header>
