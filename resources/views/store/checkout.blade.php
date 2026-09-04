<x-layouts.app title="Validation de Commande — BKO SU (Bamako Supermarché)">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-[#6B7280] mb-6">
            <a href="{{ route('home') }}" class="hover:text-[#E31E24]">Accueil</a>
            <span>/</span>
            <span class="text-[#1C1C1C] font-semibold">Finaliser ma commande</span>
        </nav>

        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-black text-[#1C1C1C] tracking-tight">
                Finaliser votre commande
            </h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-1">
                Livraison rapide partout à Bamako. Choisissez votre moyen de paiement sécurisé.
            </p>
        </div>

        <!-- Livewire One-Page Checkout Form -->
        <livewire:checkout-form />

    </div>

</x-layouts.app>
