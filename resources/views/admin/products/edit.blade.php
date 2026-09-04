<x-admin.layout title="Modifier {{ $product->name }}" :breadcrumb="['Produits' => route('admin.products.index'), $product->name => null]">
    <form action="{{ route('admin.products.update', $product) }}" method="POST">
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
                    <p class="text-xs text-[#6B7280]">Réf: {{ $product->reference }} • Rayon: {{ $product->category?->name }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="px-3 py-1.5 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F9FAFB]">
                    Voir sur la boutique ↗
                </a>
                <button type="submit" class="px-4 py-1.5 text-xs font-medium text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md shadow-xs">
                    Mettre à jour
                </button>
            </div>
        </div>

        <!-- Layout 2 colonnes (WooCommerce) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- COLONNE GAUCHE : Détails Produit -->
            <div class="lg:col-span-2 space-y-6">
                
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

                <!-- 2. Médias & Image -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                    <h2 class="text-sm font-semibold text-[#111111] mb-4">Image du Produit</h2>
                    <div class="space-y-3 text-xs">
                        @if($product->image_url)
                            <div class="mb-3">
                                <span class="text-[11px] text-[#6B7280] block mb-1">Aperçu actuel :</span>
                                <img src="{{ $product->image_url }}" class="w-24 h-24 object-cover rounded-md border border-[#E5E7EB]" alt="{{ $product->name }}">
                            </div>
                        @endif
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">URL de l'image</label>
                            <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- 3. Prix & Inventaire -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                    <h2 class="text-sm font-semibold text-[#111111] mb-4">Prix & Inventaire</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Prix de vente FCFA <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-semibold">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Prix régulier barré (Optionnel)</label>
                            <input type="number" name="original_price" value="{{ old('original_price', $product->original_price) }}" min="0" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Stock en magasin <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-bold">
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
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Marque / Vendeur local</label>
                            <input type="text" name="vendor_name" value="{{ old('vendor_name', $product->vendor_name) }}" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Badge commercial</label>
                            <input type="text" name="badge" value="{{ old('badge', $product->badge) }}" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Visibilité & Mises en avant -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5 text-xs">
                    <h3 class="text-sm font-semibold text-[#111111] mb-3">Mises en avant</h3>
                    
                    <div class="space-y-2.5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_flash_deal" value="1" {{ old('is_flash_deal', $product->is_flash_deal) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-0">
                            <span class="text-[#374151]">Deal Flash (Vente flash du jour)</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $product->is_popular) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-0">
                            <span class="text-[#374151]">Populaire à Bamako</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-0">
                            <span class="text-[#374151]">Nouveau au supermarché</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_recommended" value="1" {{ old('is_recommended', $product->is_recommended) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-0">
                            <span class="text-[#374151]">Recommandé par BKO SU</span>
                        </label>
                    </div>
                </div>

                <!-- Enregistrer -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-4 space-y-2">
                    <button type="submit" class="w-full py-2.5 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-md text-xs font-semibold shadow-xs transition-colors">
                        Mettre à jour le produit
                    </button>
                </div>

            </div>
        </div>
    </form>
</x-admin.layout>
