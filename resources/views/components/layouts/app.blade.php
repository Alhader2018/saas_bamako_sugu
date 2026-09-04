<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#E31E24">
    <title>{{ $title ?? 'BKO SU — Bamako Supermarché | Tout Bamako dans un seul panier' }}</title>
    <meta name="description" content="Marketplace premium de Bamako : Supermarché, Bazin & Mode, Téléphonie, Beauté et Fruits & Légumes avec livraison express et paiement sécurisé Orange Money.">

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bko-su.svg') }}">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F8F8F8] text-[#1C1C1C] min-h-screen flex flex-col antialiased selection:bg-[#E31E24] selection:text-white" x-data="{ toastMessage: '', toastVisible: false }" @toast.window="toastMessage = $event.detail.message; toastVisible = true; setTimeout(() => toastVisible = false, 3500)">

    <!-- Header Sticky -->
    <x-header />

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Livewire Cart Drawer (Slide-Over) -->
    <livewire:cart-drawer />

    <!-- Toast Notification Flottante -->
    <div 
        x-show="toastVisible" 
        x-cloak
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-4 opacity-0 scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
        x-transition:leave-end="translate-y-4 opacity-0 scale-95"
        class="fixed bottom-20 sm:bottom-6 right-4 sm:right-6 z-50 max-w-sm bg-[#111111] text-white px-4 py-3 rounded-2xl shadow-xl border border-neutral-700 flex items-center gap-3"
    >
        <span class="w-2.5 h-2.5 rounded-full bg-[#F7B500] shrink-0"></span>
        <p class="text-xs font-semibold" x-text="toastMessage"></p>
    </div>

    <!-- Mobile Bottom Navigation Bar (Section 18: Mobile First) -->
    <div class="sm:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-[#ECECEC] px-4 py-2 z-40 flex items-center justify-around">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-[10px] font-semibold {{ request()->routeIs('home') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Accueil</span>
        </a>

        <a href="{{ route('catalog') }}" class="flex flex-col items-center gap-1 text-[10px] font-semibold {{ request()->routeIs('catalog') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span>Rayons</span>
        </a>

        <button 
            type="button" 
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            class="flex flex-col items-center gap-1 text-[10px] font-semibold text-[#6B7280]"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.3-4.3"></path>
            </svg>
            <span>Recherche</span>
        </button>

        <button 
            type="button" 
            onclick="Livewire.dispatch('open-cart')"
            class="relative flex flex-col items-center gap-1 text-[10px] font-semibold text-[#E31E24]"
        >
            <div class="relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
            </div>
            <span>Panier</span>
        </button>
    </div>

    @livewireScripts
</body>
</html>
