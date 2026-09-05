<x-admin.layout title="Modifier la Catégorie : {{ $category->name }}" :breadcrumb="['Catégories' => route('admin.categories.index'), $category->name => '']">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-lg font-bold text-[#111111]">Modifier la Catégorie</h1>
                <p class="text-xs text-[#6B7280]">Édition du rayon « {{ $category->name }} » ({{ $category->products_count }} produit(s) associé(s))</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded-md hover:bg-neutral-50 transition-colors">
                    &larr; Retour à la liste
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-xs">
                <p class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informations principales -->
            <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                <h2 class="text-sm font-semibold text-[#111111] mb-4">Informations Générales</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="sm:col-span-2">
                        <label class="block font-medium text-[#374151] mb-1">Nom de la catégorie <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required placeholder="Ex: Livres & E-books" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Slug URL</label>
                        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="Ex: livres-et-ebooks" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-mono">
                        <span class="text-[11px] text-[#6B7280] mt-1 block">Identifiant unique utilisé dans les liens URL du catalogue.</span>
                    </div>

                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Badge commercial (Optionnel)</label>
                        <input type="text" name="badge" value="{{ old('badge', $category->badge) }}" placeholder="Ex: Populaire, Nouveau, Promo" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-medium text-[#374151] mb-1">Description (Optionnel)</label>
                        <textarea name="description" rows="3" placeholder="Brève description..." class="w-full p-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Visuel & Icône -->
            <div class="bg-white border border-[#E5E7EB] rounded-lg p-5" x-data="{ selectedIcon: '{{ old('icon', $category->icon ?: 'shopping-cart') }}' }">
                <h2 class="text-sm font-semibold text-[#111111] mb-4">Icône & Image de Couverture</h2>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-medium text-[#374151] mb-2">Choisir une icône représentative</label>
                        
                        <!-- Suggestions d'icônes rapides -->
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mb-3">
                            @php
                                $presetIcons = [
                                    'shopping-cart' => 'Panier',
                                    'book-open' => 'Livres / Ebook',
                                    'monitor' => 'Informatique',
                                    'smartphone' => 'Téléphones',
                                    'download' => 'Numérique',
                                    'shirt' => 'Mode',
                                    'apple' => 'Alimentation',
                                    'sparkles' => 'Beauté',
                                    'home' => 'Maison',
                                    'baby' => 'Bébé & Enfant',
                                    'car' => 'Auto & Moto',
                                    'folder' => 'Dossier / Pack',
                                ];
                            @endphp

                            @foreach($presetIcons as $iconKey => $iconLabel)
                                <button type="button" 
                                        @click="selectedIcon = '{{ $iconKey }}'"
                                        class="flex flex-col items-center justify-center p-2.5 rounded-lg border text-center transition-all cursor-pointer"
                                        :class="selectedIcon === '{{ $iconKey }}' ? 'border-[#E31E24] bg-red-50/30 text-[#E31E24] font-semibold ring-1 ring-[#E31E24]' : 'border-[#E5E7EB] text-[#4B5563] hover:border-neutral-300 hover:bg-neutral-50'">
                                    <span class="text-xs font-mono mb-1">{{ $iconKey }}</span>
                                    <span class="text-[10px] text-[#6B7280] leading-tight">{{ $iconLabel }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-[#6B7280]">Ou saisissez un nom d'icône personnalisé :</span>
                            <input type="text" name="icon" x-model="selectedIcon" placeholder="ex: book-open" class="w-48 h-8 px-2.5 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-mono">
                        </div>
                    </div>

                    <!-- Image / Bannière de la catégorie -->
                    <div class="pt-4 border-t border-[#ECECEC]">
                        <label class="block font-medium text-[#374151] mb-2">Image de couverture actuelle</label>
                        
                        @if($category->image_url)
                            <div class="mb-3 flex items-center gap-3 p-2 bg-neutral-50 rounded-lg border border-[#E5E7EB] w-max">
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-12 h-12 rounded object-cover border border-neutral-200">
                                <div class="text-[11px] text-[#6B7280]">
                                    <span class="font-medium text-[#111111] block">Image active</span>
                                    <span class="truncate max-w-xs block font-mono">{{ $category->image_url }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] text-[#6B7280] mb-1">Remplacer par un fichier local :</label>
                                <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-[#4B5563] file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-[#111111] file:text-white hover:file:bg-black file:cursor-pointer border border-[#D1D5DB] rounded-md p-1 bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] text-[#6B7280] mb-1">Ou remplacer par une URL :</label>
                                <input type="url" name="image_url" value="{{ old('image_url', $category->image_url) }}" placeholder="https://..." class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Positionnement & Visibilité -->
            <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                <h2 class="text-sm font-semibold text-[#111111] mb-4">Affichage & Priorité</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Ordre d'affichage</label>
                        <input type="number" name="display_order" value="{{ old('display_order', $category->display_order) }}" min="0" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        <span class="text-[11px] text-[#6B7280] mt-1 block">Les catégories ayant le chiffre le plus bas (ex: 0, 1, 2) apparaîtront en premier.</span>
                    </div>

                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24]">
                            <div>
                                <span class="font-medium text-[#111111] block">Mettre en vedette sur la page d'accueil</span>
                                <span class="text-[11px] text-[#6B7280]">La catégorie sera visible parmi les raccourcis de la boutique.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between pt-2">
                <div>
                    @if($category->products_count === 0)
                        <button type="button" 
                                onclick="if(confirm('Supprimer définitivement la catégorie « {{ $category->name }} » ?')) { document.getElementById('delete-cat-form').submit(); }"
                                class="px-3 py-2 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors">
                            Supprimer cette catégorie
                        </button>
                    @else
                        <span class="text-[11px] text-[#9CA3AF]" title="Impossible de supprimer une catégorie liée à des produits">
                            🔒 Non supprimable ({{ $category->products_count }} produit(s) associé(s))
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded-md hover:bg-neutral-50 transition-colors">
                        Annuler
                    </a>

                    <button type="submit" class="px-5 py-2 text-xs font-medium text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md transition-colors shadow-xs cursor-pointer">
                        Mettre à jour la catégorie
                    </button>
                </div>
            </div>
        </form>

        @if($category->products_count === 0)
            <form id="delete-cat-form" action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</x-admin.layout>
