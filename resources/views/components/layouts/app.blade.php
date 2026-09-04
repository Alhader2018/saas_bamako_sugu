<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E31E24">
    <title>{{ $title ?? 'BKO SU — Bamako Supermarché | Tout Bamako dans un seul panier' }}</title>
    <meta name="description" content="Marketplace e-commerce de Bamako : supermarché, bazin, téléphonie, produits frais avec livraison rapide et paiement sécurisé Orange Money.">

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bko-su.svg') }}">
    
    <!-- Police Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F8F8F8] text-[#1C1C1C] min-h-screen flex flex-col antialiased selection:bg-[#E31E24] selection:text-white" x-data="{ toastMessage: '', toastVisible: false }" @toast.window="toastMessage = $event.detail.message; toastVisible = true; setTimeout(() => toastVisible = false, 3000)">

    <!-- Header Sticky -->
    <x-header />

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Livewire Cart Drawer -->
    <livewire:cart-drawer />

    <!-- Notification Toast Discrète (8px radius, pas de pill flottant gonflé) -->
    <div 
        x-show="toastVisible" 
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-16 sm:bottom-5 right-4 sm:right-5 z-50 bg-[#111111] text-white px-3.5 py-2.5 rounded-lg shadow border border-neutral-700 text-xs flex items-center gap-2"
    >
        <span class="w-2 h-2 rounded-full bg-[#F7B500] shrink-0"></span>
        <span x-text="toastMessage"></span>
    </div>

    <!-- Barre Navigation Mobile (Simple et fonctionnelle) -->
    <div class="sm:hidden fixed bottom-0 inset-x-0 bg-white border-t border-[#ECECEC] px-4 py-2 z-40 flex items-center justify-around text-[10px] font-medium text-[#6B7280]">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('home') ? 'text-[#E31E24]' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Accueil</span>
        </a>

        <a href="{{ route('catalog') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('catalog') ? 'text-[#E31E24]' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span>Rayons</span>
        </a>

        <button 
            type="button" 
            onclick="Livewire.dispatch('open-cart')"
            class="flex flex-col items-center gap-0.5 text-[#E31E24]"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <circle cx="8" cy="21" r="1"></circle>
                <circle cx="19" cy="21" r="1"></circle>
                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
            </svg>
            <span>Panier</span>
        </button>

        <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('account.*') ? 'text-[#E31E24]' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span>Compte</span>
        </a>
    </div>

    @livewireScripts
</body>
</html>
