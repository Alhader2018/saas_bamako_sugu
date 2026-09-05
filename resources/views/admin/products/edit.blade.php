<x-admin.layout title="Modifier {{ $product->name }}" :breadcrumb="['Produits' => route('admin.products.index'), $product->name => null]">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" x-data="{ productType: '{{ old('product_type', $product->product_type ?? 'physical') }}', accessType: '{{ old('access_type', $product->access_type ?? 'file_download') }}' }">
        @csrf
        @method('PUT')

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-4 border-b border-[#E5E7EB] mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" class="p-1.5 text-[#6B7280] hover:text-[#111111] hover:bg-white rounded border border-[#E5E7EB]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-[#111111]">Modifier : {{ $product->name }}</h1>
                    <p class="text-xs text-[#6B7280]">
                        Réf: {{ $product->reference }} • Rayon: {{ $product->category?->name }} • 
                        <span class="font-semibold {{ $product->isDigital() ? 'text-amber-600' : 'text-neutral-600' }}">
                            {{ $product->isDigital() ? 'Produit Numérique' : 'Produit Physique' }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="px-3 py-1.5 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F9FAFB]">
                    Voir sur la boutique ↗
                </a>
                <button type="submit" class="px-4 py-1.5 text-xs font-medium text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md shadow-xs cursor-pointer">
                    Mettre à jour
                </button>
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

        <!-- Layout 2 colonnes -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- COLONNE GAUCHE : Détails Produit -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Nature du Produit -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                    <h2 class="text-sm font-semibold text-[#111111] mb-3">Nature du Produit</h2>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <label 
                            class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer smooth-transition"
                            :class="productType === 'physical' ? 'border-[#E31E24] bg-red-50/20 font-semibold text-[#111111]' : 'border-[#E5E7EB] text-[#6B7280] hover:border-neutral-300'"
                        >
                            <input type="radio" name="product_type" value="physical" x-model="productType" class="accent-[#E31E24]">
                            <div>
                                <span class="block">📦 Produit physique</span>
                                <span class="text-[11px] font-normal text-[#6B7280]">Livraison physique & gestion de stock</span>
                            </div>
                        </label>

                        <label 
                            class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer smooth-transition"
                            :class="productType === 'digital' ? 'border-[#E31E24] bg-red-50/20 font-semibold text-[#111111]' : 'border-[#E5E7EB] text-[#6B7280] hover:border-neutral-300'"
                        >
                            <input type="radio" name="product_type" value="digital" x-model="productType" class="accent-[#E31E24]">
                            <div>
                                <span class="block">⚡ Produit numérique</span>
                                <span class="text-[11px] font-normal text-[#6B7280]">E-book, PDF, formation, ZIP</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 1. Informations générales -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                    <h2 class="text-sm font-semibold text-[#111111] mb-4">Informations Générales</h2>
                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Nom du produit <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Description détaillée</label>
                            <textarea name="description" rows="4" class="w-full p-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section Spécifique Numérique (Conditionnelle) -->
                <div x-show="productType === 'digital'" x-cloak class="bg-white border-2 border-amber-200 rounded-lg p-5 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-[#ECECEC]">
                        <h2 class="text-sm font-semibold text-[#111111] flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#F7B500]"></span>
                            Paramètres du Produit Numérique
                        </h2>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-900 bg-amber-100 px-2 py-0.5 rounded">Dématérialisé</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Format numérique</label>
                            <select name="digital_type" class="w-full h-9 px-2.5 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                                <option value="ebook" {{ old('digital_type', $product->digital_type) === 'ebook' ? 'selected' : '' }}>E-book (PDF / ePub)</option>
                                <option value="pdf" {{ old('digital_type', $product->digital_type) === 'pdf' ? 'selected' : '' }}>Document / Fiche PDF</option>
                                <option value="course" {{ old('digital_type', $product->digital_type) === 'course' ? 'selected' : '' }}>Formation en ligne / Cours</option>
                                <option value="video" {{ old('digital_type', $product->digital_type) === 'video' ? 'selected' : '' }}>Vidéo / Masterclass</option>
                                <option value="audio" {{ old('digital_type', $product->digital_type) === 'audio' ? 'selected' : '' }}>Fichier Audio / Podcast</option>
                                <option value="software" {{ old('digital_type', $product->digital_type) === 'software' ? 'selected' : '' }}>Logiciel / Template / Script</option>
                                <option value="zip" {{ old('digital_type', $product->digital_type) === 'zip' ? 'selected' : '' }}>Pack de ressources (Archive ZIP)</option>
                                <option value="other" {{ old('digital_type', $product->digital_type) === 'other' ? 'selected' : '' }}>Autre ressource numérique</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Mode d'accès au contenu</label>
                            <select name="access_type" x-model="accessType" class="w-full h-9 px-2.5 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                                <option value="file_download">Téléchargement direct de fichiers</option>
                                <option value="external_link">Lien vers plateforme externe (Notion, Drive, LMS)</option>
                                <option value="video_player">Accès espace vidéo / Streaming</option>
                            </select>
                        </div>
                    </div>

                    <!-- Lien Externe si choisi -->
                    <div x-show="accessType === 'external_link'" class="text-xs">
                        <label class="block font-medium text-[#374151] mb-1">URL de la ressource ou formation en ligne</label>
                        <input type="url" name="external_access_url" value="{{ old('external_access_url', $product->external_access_url) }}" placeholder="https://notion.so/... ou https://classroom..." class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        <span class="text-[11px] text-[#6B7280] mt-1 block">Ce lien privé sera transmis à l'acheteur dès la confirmation du paiement.</span>
                    </div>

                    <!-- Restrictions Téléchargement -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2 border-t border-[#ECECEC]">
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Limite de téléchargements</label>
                            <input type="number" name="download_limit" value="{{ old('download_limit', $product->download_limit) }}" min="1" placeholder="Ex: 5 (vide = illimité)" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Durée de validité en jours</label>
                            <input type="number" name="download_expiry_days" value="{{ old('download_expiry_days', $product->download_expiry_days) }}" min="1" placeholder="Ex: 365 (vide = permanent)" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>
                    </div>

                    <!-- Fichiers existants attachés -->
                    <div class="pt-2 border-t border-[#ECECEC]">
                        <h4 class="text-xs font-bold text-[#111111] mb-2">Fichiers actuellement attachés ({{ $product->files->count() }})</h4>
                        @if($product->files->isEmpty())
                            <p class="text-[11px] text-[#6B7280] italic mb-3">Aucun fichier n'est encore associé à ce produit numérique.</p>
                        @else
                            <div class="space-y-1.5 mb-3">
                                @foreach($product->files as $file)
                                    <div class="flex items-center justify-between p-2.5 bg-[#F8F8F8] rounded border border-[#ECECEC] text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-[#111111]">{{ $file->name }}</span>
                                            <span class="text-[11px] text-[#6B7280]">({{ $file->formatted_file_size }})</span>
                                        </div>
                                        <button 
                                            type="button" 
                                            onclick="if(confirm('Supprimer définitivement ce fichier ?')) { document.getElementById('delete-file-{{ $file->id }}').submit(); }"
                                            class="text-xs text-red-600 hover:text-red-800 font-medium cursor-pointer"
                                        >
                                            Supprimer
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Ajouter de nouveaux fichiers -->
                        <label class="block font-medium text-[#374151] mb-1 text-xs">Ajouter de nouveaux fichiers</label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 p-2 bg-[#F8F8F8] rounded border border-[#ECECEC]">
                                <input type="text" name="file_names[]" placeholder="Titre du fichier" class="w-1/2 h-8 px-2 text-xs bg-white border border-[#D1D5DB] rounded">
                                <input type="file" name="files[]" class="w-1/2 text-xs text-[#6B7280] file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-[#E31E24] file:text-white hover:file:bg-[#C9171D]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Médias & Image -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                    <h2 class="text-sm font-semibold text-[#111111] mb-4">Vignette / Couverture du Produit</h2>
                    <div class="space-y-3 text-xs">
                        @if($product->image_url)
                            <div class="mb-3">
                                <span class="text-[11px] text-[#6B7280] block mb-1">Aperçu actuel :</span>
                                <img src="{{ $product->image_url }}" class="w-24 h-24 object-cover rounded-md border border-[#E5E7EB]" alt="{{ $product->name }}">
                            </div>
                        @endif
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">URL de l'image / couverture</label>
                            <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- 3. Prix & Inventaire -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                    <h2 class="text-sm font-semibold text-[#111111] mb-4">Tarification & Stock</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Prix de vente FCFA <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-semibold">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Prix régulier barré (Optionnel)</label>
                            <input type="number" name="original_price" value="{{ old('original_price', $product->original_price) }}" min="0" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div x-show="productType === 'physical'">
                            <label class="block font-medium text-[#374151] mb-1">Stock en magasin <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-bold">
                        </div>

                        <div x-show="productType === 'digital'" class="flex items-center">
                            <div class="p-2.5 bg-emerald-50 text-emerald-800 rounded-md border border-emerald-200 text-xs w-full">
                                <strong>Stock illimité :</strong> Géré virtuellement sans rupture physique.
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Référence SKU</label>
                            <input type="text" name="reference" value="{{ old('reference', $product->reference) }}" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-mono">
                        </div>
                    </div>
                </div>

            </div>

            <!-- COLONNE DROITE : Organisation & Visibilité -->
            <div class="space-y-6">
                
                <!-- Catégorie & Rayon -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5 text-xs">
                    <h3 class="text-sm font-semibold text-[#111111] mb-3">Organisation</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Catégorie / Rayon <span class="text-red-500">*</span></label>
                            <select name="category_id" required class="w-full h-9 px-2.5 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Vendeur / Formateur / Auteur</label>
                            <input type="text" name="vendor_name" value="{{ old('vendor_name', $product->vendor_name) }}" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Badge commercial</label>
                            <input type="text" name="badge" value="{{ old('badge', $product->badge) }}" placeholder="Ex: Populaire, -30%" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Visibilité -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5 text-xs">
                    <h3 class="text-sm font-semibold text-[#111111] mb-3">Mise en avant</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $product->is_popular) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24]">
                            <span class="text-[#374151]">Produit Populaire</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24]">
                            <span class="text-[#374151]">Nouveauté</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_recommended" value="1" {{ old('is_recommended', $product->is_recommended) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24]">
                            <span class="text-[#374151]">Recommandé par BKO SU</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_flash_deal" value="1" {{ old('is_flash_deal', $product->is_flash_deal) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24]">
                            <span class="text-[#374151]">Offre Flash</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <!-- Formulaire de suppression de fichier séparé pour éviter les imbrications HTML -->
    @foreach($product->files as $file)
        <form id="delete-file-{{ $file->id }}" action="{{ route('admin.products.files.destroy', $file) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</x-admin.layout>
