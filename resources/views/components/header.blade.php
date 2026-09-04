<header class="sticky top-0 z-40 bg-white border-b border-[#ECECEC]">
    <!-- Top Announcement Bar (Bamako Info & Contact) -->
    <div class="bg-[#111111] text-white text-[11px] py-1 px-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <p class="text-neutral-300">
                Livraison rapide partout à Bamako • Paiement Orange Money & Espèces
            </p>
            <div class="hidden sm:flex items-center gap-4 text-neutral-400">
                <a href="tel:+22370000000" class="hover:text-white">Assistance : +223 70 00 00 00</a>
                <span>•</span>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white">Administration</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4 h-16">
            
            <!-- Logo BKO SU (Source de vérité) -->
            <a href="{{ route('home') }}" class="shrink-0">
                <x-logo class="h-8 w-auto" />
            </a>

            <!-- Menu Déroulant Rayons (Desktop) -->
            <div class="hidden lg:block relative shrink-0" x-data="{ open: false }" @click.outside="open = false">
                <button 
                    @click="open = !open" 
                    type="button"
                    class="flex items-center gap-1.5 h-9 px-3 rounded-md text-[#1C1C1C] hover:bg-neutral-50 text-xs font-medium border border-[#ECECEC] smooth-transition cursor-pointer"
                >
                    <span>Rayons</span>
                    <svg class="w-3.5 h-3.5 text-[#6B7280]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Menu Déroulant -->
                <div 
                    x-show="open" 
                    x-cloak
                    class="absolute top-full left-0 mt-1 w-56 bg-white rounded-lg border border-[#ECECEC] shadow-sm py-1.5 z-50 text-xs"
                >
                    @php
                        $categories = \App\Models\Category::orderBy('display_order')->get();
                    @endphp
                    @foreach($categories as $cat)
                        <a 
                            href="{{ route('catalog', ['category' => $cat->slug]) }}" 
                            class="block px-3.5 py-2 text-[#1C1C1C] hover:bg-neutral-50 hover:text-[#E31E24] smooth-transition"
                        >
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Grande Barre de Recherche (Desktop & Tablet) -->
            <div class="hidden sm:flex flex-1 max-w-xl mx-2">
                <livewire:search-bar />
            </div>

            <!-- Actions Droite -->
            <div class="flex items-center gap-2">
                <!-- Lien Favoris -->
                <a 
                    href="{{ auth()->check() ? route('account.favorites.index') : route('login') }}"
                    class="hidden md:flex items-center justify-center w-9 h-9 rounded-md text-[#1C1C1C] hover:bg-neutral-50 border border-transparent hover:border-[#ECECEC] smooth-transition cursor-pointer"
                    aria-label="Mes favoris"
                    title="Mes favoris"
                >
                    <svg class="w-4 h-4 text-neutral-600 hover:text-[#E31E24]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>

                <!-- Mon Compte -->
                <div class="relative" x-data="{ accountOpen: false }" @click.outside="accountOpen = false">
                    <button 
                        @click="accountOpen = !accountOpen" 
                        type="button"
                        class="hidden sm:flex items-center gap-1.5 h-9 px-2.5 rounded-md text-[#1C1C1C] hover:bg-neutral-50 border border-transparent hover:border-[#ECECEC] smooth-transition cursor-pointer text-xs font-medium"
                    >
                        <svg class="w-4 h-4 text-neutral-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Compte</span>
                    </button>

                    <div 
                        x-show="accountOpen" 
                        x-cloak
                        class="absolute right-0 mt-1 w-52 bg-white rounded-lg border border-[#ECECEC] shadow-md py-1.5 z-50 text-xs divide-y divide-[#F3F4F6]"
                    >
                        @auth
                            <div class="px-3.5 py-2 text-[11px] text-[#6B7280]">
                                Connecté en tant que :<br>
                                <strong class="text-[#111111] font-semibold truncate block">{{ auth()->user()->name }}</strong>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('account.dashboard') }}" class="block px-3.5 py-1.5 hover:bg-neutral-50 text-[#E31E24] font-semibold">Tableau de bord</a>
                                <a href="{{ route('account.orders.index') }}" class="block px-3.5 py-1.5 hover:bg-neutral-50 text-[#1C1C1C]">Mes commandes</a>
                                <a href="{{ route('account.favorites.index') }}" class="block px-3.5 py-1.5 hover:bg-neutral-50 text-[#1C1C1C]">Mes favoris</a>
                                <a href="{{ route('account.addresses.index') }}" class="block px-3.5 py-1.5 hover:bg-neutral-50 text-[#1C1C1C]">Mes adresses</a>
                                <a href="{{ route('account.profile.index') }}" class="block px-3.5 py-1.5 hover:bg-neutral-50 text-[#1C1C1C]">Mon profil</a>
                                @if(auth()->user()->isStaff())
                                    <div class="pt-1 mt-1 border-t border-[#F3F4F6]">
                                        <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-1.5 hover:bg-neutral-50 text-[#E31E24] font-medium">Administration BKO SU →</a>
                                    </div>
                                @endif
                            </div>
                            <div class="py-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3.5 py-1.5 hover:bg-neutral-50 text-red-600 font-medium">
                                        Se déconnecter
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="py-1">
                                <a href="{{ route('login') }}" class="block px-3.5 py-2 hover:bg-neutral-50 text-[#1C1C1C] font-semibold">Se connecter</a>
                                <a href="{{ route('register') }}" class="block px-3.5 py-2 hover:bg-neutral-50 text-[#4B5563]">Créer un compte</a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Panier Réactif Livewire -->
                <livewire:cart-badge />
            </div>

        </div>

        <!-- Recherche Mobile (compacte) -->
        <div class="sm:hidden pb-2.5">
            <livewire:search-bar />
        </div>
    </div>
</header>
