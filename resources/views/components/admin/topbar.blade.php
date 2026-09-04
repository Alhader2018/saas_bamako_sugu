@props(['breadcrumb' => []])
<header class="h-14 bg-white border-b border-[#E5E7EB] px-4 sm:px-6 flex items-center justify-between gap-4 sticky top-0 z-30">
    <!-- Left: Mobile Toggle & Breadcrumbs -->
    <div class="flex items-center gap-3 min-w-0">
        <button type="button" onclick="toggleMobileSidebar()" class="md:hidden p-1.5 text-[#6B7280] hover:text-[#111111] hover:bg-[#F3F4F6] rounded-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Breadcrumbs -->
        <nav class="hidden sm:flex items-center gap-1.5 text-xs text-[#6B7280] min-w-0 truncate">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-[#111111] transition-colors">Admin</a>
            @foreach($breadcrumb as $label => $url)
                <span class="text-[#D1D5DB]">/</span>
                @if($url && !$loop->last)
                    <a href="{{ $url }}" class="hover:text-[#111111] transition-colors truncate">{{ $label }}</a>
                @else
                    <span class="font-medium text-[#111111] truncate">{{ $label }}</span>
                @endif
            @endforeach
        </nav>
    </div>

    <!-- Center/Right: Global Search & Shortcuts -->
    <div class="flex items-center gap-3">
        <!-- Recherche globale -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="relative hidden sm:block">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Rechercher commande, tél (+223), client..." 
                   class="w-64 lg:w-80 h-8 pl-8 pr-3 text-xs bg-[#F9FAFB] border border-[#D1D5DB] rounded-md focus:bg-white focus:border-[#E31E24] focus:ring-1 focus:ring-[#E31E24] outline-none transition-all placeholder-[#9CA3AF]">
            <svg class="w-3.5 h-3.5 text-[#9CA3AF] absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </form>

        <!-- Notifications alertes -->
        @php
            $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
            $lowStockItems = \App\Models\Product::where('stock', '<=', 5)->count();
            $hasAlerts = ($pendingOrders > 0 || $lowStockItems > 0);
        @endphp
        <div class="relative flex items-center">
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
               title="{{ $pendingOrders }} commande(s) en attente, {{ $lowStockItems }} produit(s) en stock critique"
               class="p-1.5 rounded-md text-[#6B7280] hover:text-[#111111] hover:bg-[#F3F4F6] relative transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                </svg>
                @if($hasAlerts)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-[#E31E24] rounded-full ring-2 ring-white"></span>
                @endif
            </a>
        </div>

        <div class="h-4 w-px bg-[#E5E7EB] hidden sm:block"></div>

        <!-- Bouton Storefront direct -->
        <a href="{{ route('home') }}" 
           target="_blank" 
           class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-[#4B5563] hover:text-[#111111] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F9FAFB] transition-colors">
            <span class="hidden sm:inline">Storefront</span>
            <svg class="w-3 h-3 text-[#6B7280]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>
</header>
