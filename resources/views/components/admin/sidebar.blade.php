<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-60 bg-white border-r border-[#E5E7EB] flex flex-col justify-between shrink-0 transform -translate-x-full md:translate-x-0 md:static md:inset-auto transition-transform duration-200 ease-in-out">
    <div class="flex flex-col flex-1 overflow-y-auto">
        <!-- Logo & Header -->
        <div class="h-14 px-4 border-b border-[#E5E7EB] flex items-center justify-between shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <x-logo class="h-6 w-auto" />
            </a>
            <span class="text-[10px] font-semibold bg-[#111111] text-white px-1.5 py-0.5 rounded tracking-wide">
                ADMIN
            </span>
        </div>

        <!-- Navigation Sections -->
        <nav class="p-3 space-y-5 text-[13px]">
            
            <!-- SECTION VUE D'ENSEMBLE -->
            <div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                        <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                        <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                        <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                    </svg>
                    <span>Vue d'ensemble</span>
                </a>
            </div>

            <!-- SECTION COMMANDES -->
            <div>
                <p class="px-3 text-[10px] font-semibold tracking-wider text-[#9CA3AF] uppercase mb-1">
                    Commandes
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-md font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                            </svg>
                            <span>Commandes</span>
                        </span>
                        @php $pendingBadge = \App\Models\Order::where('status', 'pending')->count(); @endphp
                        @if($pendingBadge > 0)
                            <span class="bg-[#FEF2F2] text-[#E31E24] text-[10px] px-1.5 py-0.2 rounded font-semibold border border-red-200">
                                {{ $pendingBadge }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.payments.index') }}" 
                       class="flex items-center gap-2.5 px-3 py-1.5 rounded-md font-medium transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.payments.*') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                            <line x1="2" x2="22" y1="10" y2="10"></line>
                        </svg>
                        <span>Paiements</span>
                    </a>

                    <a href="{{ route('admin.deliveries.index') }}" 
                       class="flex items-center gap-2.5 px-3 py-1.5 rounded-md font-medium transition-colors {{ request()->routeIs('admin.deliveries.*') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.deliveries.*') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                            <path d="M15 18H9"></path>
                            <path d="M19 18h2a1 1 0 0 0 1-1v-5.5a1 1 0 0 0-.293-.707l-3.5-3.5A1 1 0 0 0 17.5 7H14v11Z"></path>
                            <circle cx="7" cy="18" r="2"></circle>
                            <circle cx="17" cy="18" r="2"></circle>
                        </svg>
                        <span>Livraisons Bamako</span>
                    </a>
                </div>
            </div>

            <!-- SECTION CATALOGUE -->
            <div>
                <p class="px-3 text-[10px] font-semibold tracking-wider text-[#9CA3AF] uppercase mb-1">
                    Catalogue
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center gap-2.5 px-3 py-1.5 rounded-md font-medium transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.products.*') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path d="m7.5 4.27 9 5.15"></path>
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                            <path d="m3.3 7 8.7 5 8.7-5"></path>
                            <path d="M12 22V12"></path>
                        </svg>
                        <span>Produits</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-md font-medium transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <rect width="7" height="7" x="3" y="3" rx="1"></rect>
                                <rect width="7" height="7" x="14" y="3" rx="1"></rect>
                                <rect width="7" height="7" x="14" y="14" rx="1"></rect>
                                <rect width="7" height="7" x="3" y="14" rx="1"></rect>
                            </svg>
                            <span>Catégories</span>
                        </span>
                        @php $catCount = \App\Models\Category::count(); @endphp
                        <span class="text-[10px] px-1.5 py-0.2 rounded font-semibold {{ request()->routeIs('admin.categories.*') ? 'bg-red-100 text-[#E31E24]' : 'bg-gray-100 text-[#6B7280]' }}">
                            {{ $catCount }}
                        </span>
                    </a>

                    <a href="{{ route('admin.stock.index') }}" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-md font-medium transition-colors {{ request()->routeIs('admin.stock.*') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.stock.*') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <line x1="18" x2="18" y1="20" y2="10"></line>
                                <line x1="12" x2="12" y1="20" y2="4"></line>
                                <line x1="6" x2="6" y1="20" y2="14"></line>
                            </svg>
                            <span>Inventaire & Stock</span>
                        </span>
                        @php $lowStockBadge = \App\Models\Product::where('stock', '<=', 5)->count(); @endphp
                        @if($lowStockBadge > 0)
                            <span class="bg-[#FFFBEB] text-[#D97706] text-[10px] px-1.5 py-0.2 rounded font-semibold border border-amber-200">
                                {{ $lowStockBadge }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- SECTION CLIENTS -->
            <div>
                <p class="px-3 text-[10px] font-semibold tracking-wider text-[#9CA3AF] uppercase mb-1">
                    Clients
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.customers.index') }}" 
                       class="flex items-center gap-2.5 px-3 py-1.5 rounded-md font-medium transition-colors {{ request()->routeIs('admin.customers.*') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.customers.*') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Répertoire Clients</span>
                    </a>
                </div>
            </div>

            <!-- SECTION ANALYSE & RAPPORTS -->
            <div>
                <p class="px-3 text-[10px] font-semibold tracking-wider text-[#9CA3AF] uppercase mb-1">
                    Analyse
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-center gap-2.5 px-3 py-1.5 rounded-md font-medium transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path d="M3 3v18h18"></path>
                            <path d="m19 9-5 5-4-4-3 3"></path>
                        </svg>
                        <span>Rapports & Ventes</span>
                    </a>
                </div>
            </div>

            <!-- SECTION SYSTÈME -->
            <div>
                <p class="px-3 text-[10px] font-semibold tracking-wider text-[#9CA3AF] uppercase mb-1">
                    Système
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center gap-2.5 px-3 py-1.5 rounded-md font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#FEF2F2] text-[#E31E24] font-semibold border-l-2 border-[#E31E24]' : 'text-[#4B5563] hover:text-[#111111] hover:bg-[#F9FAFB]' }}">
                        <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-[#E31E24]' : 'text-[#6B7280]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span>Paramètres & Orange Money</span>
                    </a>
                </div>
            </div>

        </nav>
    </div>

    <!-- Footer Sidebar -->
    <div class="p-3 border-t border-[#E5E7EB] bg-[#FAFAFA] shrink-0">
        <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-2.5 py-1.5 text-xs text-[#4B5563] hover:text-[#111111] hover:bg-white rounded transition-colors border border-transparent hover:border-[#E5E7EB]">
            <span class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-[#6B7280]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Voir la boutique</span>
            </span>
            <span class="text-[10px] text-[#9CA3AF]">↗</span>
        </a>
        <div class="mt-2 px-2.5 pt-2 border-t border-[#E5E7EB] flex items-center justify-between text-[11px] text-[#6B7280]">
            <div class="truncate">
                <span class="truncate font-semibold text-[#111111] block">{{ auth()->user()?->name ?: 'Admin BKO SU' }}</span>
                <span class="text-[10px] text-[#6B7280]">{{ ucfirst(auth()->user()?->role ?: 'staff') }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" title="Se déconnecter" class="p-1 text-[#9CA3AF] hover:text-[#E31E24] hover:bg-white rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
