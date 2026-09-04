<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#F8F8F8]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Mon Espace' }} — BKO SU</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bko-su.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased text-[#1C1C1C] bg-[#F8F8F8]">

    <!-- Top Navigation Bar Client (Desktop & Mobile) -->
    <header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
            <!-- Brand Logo -->
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2" title="Retourner à la boutique">
                    <x-logo class="h-7 w-auto" />
                </a>
                <span class="hidden sm:inline-block text-[#D1D5DB]">|</span>
                <span class="hidden sm:inline-block text-xs font-semibold text-[#4B5563] uppercase tracking-wider">Espace Client</span>
            </div>

            <!-- Actions Rapides Droite -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Continuer mes achats -->
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium text-[#374151] hover:text-[#E31E24] hover:bg-neutral-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span class="hidden sm:inline">Boutique</span>
                </a>

                <!-- Notifications -->
                @php
                    $unreadCount = auth()->user()->unreadNotificationsCount();
                @endphp
                <a href="{{ route('account.notifications.index') }}" class="relative p-2 text-[#4B5563] hover:text-[#111111] hover:bg-neutral-50 rounded-md transition-colors" title="Mes notifications">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    @if($unreadCount > 0)
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[#E31E24] ring-2 ring-white"></span>
                    @endif
                </a>

                <!-- Profil Avatar -->
                <a href="{{ route('account.profile.index') }}" class="flex items-center gap-2 pl-2 border-l border-[#E5E7EB]">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-7 h-7 rounded-full object-cover ring-1 ring-[#D1D5DB]">
                    @else
                        <div class="w-7 h-7 rounded-full bg-[#111111] text-white flex items-center justify-center font-bold text-[11px]">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="hidden md:inline text-xs font-semibold text-[#111111] truncate max-w-[120px]">
                        {{ auth()->user()->name }}
                    </span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container avec Sidebar -->
    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">
            
            <!-- Sidebar Navigation Desktop -->
            <aside class="w-full lg:w-64 shrink-0">
                <!-- User Summary Box -->
                <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 mb-4 shadow-2xs">
                    <div class="flex items-center gap-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-[#D1D5DB]">
                        @else
                            <div class="w-10 h-10 rounded-full bg-[#111111] text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <h3 class="text-xs font-bold text-[#111111] truncate">{{ auth()->user()->name }}</h3>
                            <p class="text-[11px] text-[#6B7280] truncate">{{ auth()->user()->email ?: auth()->user()->phone }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-medium bg-neutral-100 text-[#4B5563]">
                                Client BKO SU
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="bg-white border border-[#E5E7EB] rounded-xl p-2 shadow-2xs divide-y divide-[#F3F4F6]">
                    <div class="space-y-0.5 pb-2">
                        <!-- Dashboard -->
                        <a href="{{ route('account.dashboard') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('account.dashboard') ? 'bg-[#E31E24] text-white font-semibold shadow-xs' : 'text-[#374151] hover:bg-[#F9FAFB] hover:text-[#111111]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span>Tableau de bord</span>
                        </a>

                        <!-- Mes commandes -->
                        <a href="{{ route('account.orders.index') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('account.orders.*') ? 'bg-[#E31E24] text-white font-semibold shadow-xs' : 'text-[#374151] hover:bg-[#F9FAFB] hover:text-[#111111]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <span>Mes commandes</span>
                        </a>

                        <!-- Mes favoris -->
                        <a href="{{ route('account.favorites.index') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('account.favorites.*') ? 'bg-[#E31E24] text-white font-semibold shadow-xs' : 'text-[#374151] hover:bg-[#F9FAFB] hover:text-[#111111]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <span>Mes favoris</span>
                        </a>

                        <!-- Mes adresses -->
                        <a href="{{ route('account.addresses.index') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('account.addresses.*') ? 'bg-[#E31E24] text-white font-semibold shadow-xs' : 'text-[#374151] hover:bg-[#F9FAFB] hover:text-[#111111]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span>Mes adresses</span>
                        </a>

                        <!-- Paiements -->
                        <a href="{{ route('account.payments.index') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('account.payments.*') ? 'bg-[#E31E24] text-white font-semibold shadow-xs' : 'text-[#374151] hover:bg-[#F9FAFB] hover:text-[#111111]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 002.25 19.5z" />
                            </svg>
                            <span>Paiements</span>
                        </a>

                        <!-- Notifications -->
                        <a href="{{ route('account.notifications.index') }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('account.notifications.*') ? 'bg-[#E31E24] text-white font-semibold shadow-xs' : 'text-[#374151] hover:bg-[#F9FAFB] hover:text-[#111111]' }}">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                <span>Notifications</span>
                            </div>
                            @if($unreadCount > 0)
                                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded-full {{ request()->routeIs('account.notifications.*') ? 'bg-white text-[#E31E24]' : 'bg-[#E31E24] text-white' }}">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Mon profil -->
                        <a href="{{ route('account.profile.index') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('account.profile.*') ? 'bg-[#E31E24] text-white font-semibold shadow-xs' : 'text-[#374151] hover:bg-[#F9FAFB] hover:text-[#111111]' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span>Mon profil</span>
                        </a>
                    </div>

                    <!-- Déconnexion & Retour Boutique -->
                    <div class="pt-2 space-y-1">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-1.5 text-xs text-[#6B7280] hover:text-[#111111] hover:bg-neutral-50 rounded-md transition-colors">
                            <span>← Retourner à la boutique</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 rounded-md transition-colors font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                <span>Se déconnecter</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            <!-- Main Content Right -->
            <main class="flex-1 w-full min-w-0">
                
                <!-- Messages d'alertes globaux -->
                @if(session('success'))
                    <div class="mb-5 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-2xs">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-800 text-xs flex items-center gap-2.5 shadow-2xs">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-5 p-3.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-xs flex items-center gap-2.5 shadow-2xs">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                <!-- Slot de la page -->
                {{ $slot }}

            </main>
        </div>
    </div>

    <!-- Navigation Mobile Basse Fixe -->
    <div class="lg:hidden fixed bottom-0 inset-x-0 bg-white border-t border-[#E5E7EB] px-3 py-2 z-40 flex items-center justify-around text-[10px] font-medium text-[#6B7280]">
        <a href="{{ route('account.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('account.dashboard') ? 'text-[#E31E24] font-semibold' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span>Tableau de bord</span>
        </a>

        <a href="{{ route('account.orders.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('account.orders.*') ? 'text-[#E31E24] font-semibold' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span>Commandes</span>
        </a>

        <a href="{{ route('account.favorites.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('account.favorites.*') ? 'text-[#E31E24] font-semibold' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            <span>Favoris</span>
        </a>

        <a href="{{ route('account.profile.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('account.profile.*') ? 'text-[#E31E24] font-semibold' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span>Mon Compte</span>
        </a>
    </div>

</body>
</html>
