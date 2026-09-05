<x-admin.layout title="Catégories de Produits" :breadcrumb="['Catégories' => route('admin.categories.index')]">
    <!-- Header -->
    <x-admin.page-header title="Catégories" description="Organisation et gestion des rayons du catalogue BKO SU (produits physiques et numériques)">
        <x-slot:actions>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md transition-colors shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Ajouter une catégorie</span>
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="mb-4 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-sm">&times;</button>
        </div>
    @endif

    <!-- Cartes KPI résumé -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <div class="text-[11px] font-semibold text-[#6B7280] uppercase tracking-wider">Total Catégories</div>
            <div class="text-2xl font-bold text-[#111111] mt-1">{{ $totalCategories }}</div>
            <div class="text-[11px] text-[#6B7280] mt-0.5">Rayons actifs sur la plateforme</div>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <div class="text-[11px] font-semibold text-[#6B7280] uppercase tracking-wider">En Vedette</div>
            <div class="text-2xl font-bold text-[#E31E24] mt-1">{{ $featuredCount }}</div>
            <div class="text-[11px] text-[#6B7280] mt-0.5">Affichées sur la page d'accueil</div>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-lg p-4">
            <div class="text-[11px] font-semibold text-[#6B7280] uppercase tracking-wider">Produits Associés</div>
            <div class="text-2xl font-bold text-emerald-600 mt-1">{{ \App\Models\Product::count() }}</div>
            <div class="text-[11px] text-[#6B7280] mt-0.5">Articles physiques & numériques</div>
        </div>
    </div>

    <!-- Barre de Recherche & Filtres -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg p-3 mb-4">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, slug ou badge..." class="w-full h-8 pl-8 pr-3 text-xs bg-[#F9FAFB] border border-[#E5E7EB] rounded-md focus:bg-white focus:border-[#E31E24] focus:outline-none">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="featured" class="h-8 px-2.5 text-xs bg-[#F9FAFB] border border-[#E5E7EB] rounded-md focus:bg-white focus:border-[#E31E24] focus:outline-none">
                    <option value="">Toutes les visibilités</option>
                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>En vedette (Accueil)</option>
                    <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Standard</option>
                </select>

                <button type="submit" class="h-8 px-3 text-xs font-medium text-white bg-[#111111] hover:bg-black rounded-md transition-colors shrink-0">
                    Filtrer
                </button>

                @if(request()->hasAny(['search', 'featured']))
                    <a href="{{ route('admin.categories.index') }}" class="h-8 px-2 text-xs font-medium text-[#6B7280] hover:text-[#111111] flex items-center justify-center shrink-0">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau des catégories -->
    <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F9FAFB] border-b border-[#E5E7EB] text-[#4B5563] font-semibold">
                    <tr>
                        <th class="px-4 py-3">Catégorie</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3 text-center">Badge</th>
                        <th class="px-4 py-3 text-center">Produits</th>
                        <th class="px-4 py-3 text-center">Ordre</th>
                        <th class="px-4 py-3 text-center">En vedette</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($categories as $category)
                        <tr class="hover:bg-[#F9FAFB]/60 transition-colors">
                            <!-- Nom & Icône / Image -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-md bg-neutral-100 flex items-center justify-center shrink-0 border border-neutral-200 overflow-hidden text-neutral-700">
                                        @if($category->image_url)
                                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs font-mono font-bold uppercase">{{ substr($category->icon ?: 'CA', 0, 2) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-[#111111] hover:text-[#E31E24] transition-colors">
                                            <a href="{{ route('admin.categories.edit', $category) }}">
                                                {{ $category->name }}
                                            </a>
                                        </div>
                                        <div class="text-[11px] text-[#6B7280] flex items-center gap-1.5">
                                            <span>Icône : <code>{{ $category->icon ?: 'shopping-cart' }}</code></span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Slug -->
                            <td class="px-4 py-3 font-mono text-[11px] text-[#6B7280]">
                                {{ $category->slug }}
                            </td>

                            <!-- Badge commercial -->
                            <td class="px-4 py-3 text-center">
                                @if($category->badge)
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-red-50 text-[#E31E24] border border-red-200 rounded-full">
                                        {{ $category->badge }}
                                    </span>
                                @else
                                    <span class="text-[#9CA3AF]">—</span>
                                @endif
                            </td>

                            <!-- Nombre de produits -->
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.products.index', ['category' => $category->slug]) }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $category->products_count > 0 ? 'bg-neutral-100 text-neutral-800 hover:bg-neutral-200' : 'bg-gray-50 text-gray-400' }}">
                                    <span>{{ $category->products_count }}</span>
                                    <span class="text-[10px] font-normal">prod.</span>
                                </a>
                            </td>

                            <!-- Ordre d'affichage -->
                            <td class="px-4 py-3 text-center font-mono text-[11px] text-[#4B5563]">
                                {{ $category->display_order }}
                            </td>

                            <!-- En vedette -->
                            <td class="px-4 py-3 text-center">
                                @if($category->is_featured)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Accueil
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                        Non
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="p-1.5 text-[#4B5563] hover:text-[#111111] hover:bg-[#F3F4F6] rounded-md transition-colors" title="Modifier la catégorie">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la catégorie « {{ $category->name }} » ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors" title="Supprimer la catégorie">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-[#6B7280]">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-neutral-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <rect width="7" height="7" x="3" y="3" rx="1"></rect>
                                        <rect width="7" height="7" x="14" y="3" rx="1"></rect>
                                        <rect width="7" height="7" x="14" y="14" rx="1"></rect>
                                        <rect width="7" height="7" x="3" y="14" rx="1"></rect>
                                    </svg>
                                    <p class="font-medium text-[#111111]">Aucune catégorie trouvée</p>
                                    <p class="text-xs text-[#9CA3AF] mt-0.5">Commencez par créer votre première catégorie de produits.</p>
                                    <a href="{{ route('admin.categories.create') }}" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md transition-colors">
                                        + Ajouter une catégorie
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-3 border-t border-[#E5E7EB]">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-admin.layout>
