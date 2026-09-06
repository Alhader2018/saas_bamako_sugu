<x-admin.layout title="Ajouter un produit" :breadcrumb="['Produits' => route('admin.products.index'), 'Nouveau' => null]">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" x-data="{ productType: '{{ old('product_type', 'physical') }}', accessType: '{{ old('access_type', 'file_download') }}' }">
        @csrf

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-4 border-b border-[#E5E7EB] mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" class="p-1.5 text-[#6B7280] hover:text-[#111111] hover:bg-white rounded border border-[#E5E7EB]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-[#111111]">Nouveau Produit</h1>
                    <p class="text-xs text-[#6B7280]">Ajouter une référence physique ou un produit numérique au catalogue BKO SU</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 text-xs font-medium text-[#4B5563] bg-white border border-[#D1D5DB] rounded-md hover:bg-[#F9FAFB]">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-1.5 text-xs font-medium text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md shadow-xs cursor-pointer">
                    Publier le produit
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
                
                <!-- 0. Type de produit (Physique vs Numérique) -->
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
                                <span class="text-[11px] font-normal text-[#6B7280]">Nécessite une livraison à Bamako & stock</span>
                            </div>
                        </label>

                        <label 
                            class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer smooth-transition"
                            :class="productType === 'digital' ? 'border-[#E31E24] bg-red-50/20 font-semibold text-[#111111]' : 'border-[#E5E7EB] text-[#6B7280] hover:border-neutral-300'"
                        >
                            <input type="radio" name="product_type" value="digital" x-model="productType" class="accent-[#E31E24]">
                            <div>
                                <span class="block">⚡ Produit numérique</span>
                                <span class="text-[11px] font-normal text-[#6B7280]">E-book, PDF, formation, ZIP (Livraison instantanée)</span>
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
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: E-book : Guide de l'entrepreneur à Bamako" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-medium text-[#374151]">Description détaillée (HTML WYSIWYG)</label>
                                <span class="text-[11px] text-[#6B7280]">Éditeur visuel riche avec mise en forme</span>
                            </div>
                            <div class="border border-[#D1D5DB] rounded-md overflow-hidden bg-white">
                                <div id="quill-editor" style="min-height: 180px;" class="text-sm">
                                    {!! old('description') !!}
                                </div>
                            </div>
                            <input type="hidden" name="description" id="hidden-description" value="{{ old('description') }}">
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
                                <option value="ebook" {{ old('digital_type') === 'ebook' ? 'selected' : '' }}>E-book (PDF / ePub)</option>
                                <option value="pdf" {{ old('digital_type') === 'pdf' ? 'selected' : '' }}>Document / Fiche PDF</option>
                                <option value="course" {{ old('digital_type') === 'course' ? 'selected' : '' }}>Formation en ligne / Cours</option>
                                <option value="video" {{ old('digital_type') === 'video' ? 'selected' : '' }}>Vidéo / Masterclass</option>
                                <option value="audio" {{ old('digital_type') === 'audio' ? 'selected' : '' }}>Fichier Audio / Podcast</option>
                                <option value="software" {{ old('digital_type') === 'software' ? 'selected' : '' }}>Logiciel / Template / Script</option>
                                <option value="zip" {{ old('digital_type') === 'zip' ? 'selected' : '' }}>Pack de ressources (Archive ZIP)</option>
                                <option value="other" {{ old('digital_type') === 'other' ? 'selected' : '' }}>Autre ressource numérique</option>
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
                        <input type="url" name="external_access_url" value="{{ old('external_access_url') }}" placeholder="https://notion.so/... ou https://classroom..." class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        <span class="text-[11px] text-[#6B7280] mt-1 block">Ce lien privé sera partagé avec le client dès la confirmation de sa commande.</span>
                    </div>

                    <!-- Restrictions Téléchargement -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2 border-t border-[#ECECEC]">
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Limite de téléchargements (Optionnel)</label>
                            <input type="number" name="download_limit" value="{{ old('download_limit') }}" min="1" placeholder="Ex: 5 (laisser vide = illimité)" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Durée de validité en jours (Optionnel)</label>
                            <input type="number" name="download_expiry_days" value="{{ old('download_expiry_days') }}" min="1" placeholder="Ex: 365 (laisser vide = permanent)" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>
                    </div>

                    <!-- Upload Fichiers Sécurisés -->
                    <div class="pt-2 border-t border-[#ECECEC]">
                        <label class="block font-medium text-[#374151] mb-1 text-xs">Fichiers téléchargeables (Stockage sécurisé non public)</label>
                        <p class="text-[11px] text-[#6B7280] mb-2">Les fichiers sont stockés dans un espace protégé et ne peuvent être téléchargés que par les acheteurs ayant payé.</p>
                        
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 p-2 bg-[#F8F8F8] rounded border border-[#ECECEC]">
                                <input type="text" name="file_names[]" placeholder="Titre du fichier (ex: E-book complet PDF)" class="w-1/2 h-8 px-2 text-xs bg-white border border-[#D1D5DB] rounded">
                                <input type="file" name="files[]" class="w-1/2 text-xs text-[#6B7280] file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-[#E31E24] file:text-white hover:file:bg-[#C9171D]">
                            </div>
                            <div class="flex items-center gap-2 p-2 bg-[#F8F8F8] rounded border border-[#ECECEC]">
                                <input type="text" name="file_names[]" placeholder="Fichier supplémentaire (ex: Fichiers d'exercices ZIP)" class="w-1/2 h-8 px-2 text-xs bg-white border border-[#D1D5DB] rounded">
                                <input type="file" name="files[]" class="w-1/2 text-xs text-[#6B7280] file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-[#E31E24] file:text-white hover:file:bg-[#C9171D]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Médias & Image -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                    <h2 class="text-sm font-semibold text-[#111111] mb-4">Vignette / Couverture du Produit</h2>
                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Téléverser une image depuis votre appareil</label>
                            <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-[#6B7280] file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-[#111111] file:text-white hover:file:bg-black cursor-pointer">
                        </div>

                        <div class="relative flex py-1 items-center">
                            <div class="flex-grow border-t border-[#ECECEC]"></div>
                            <span class="flex-shrink mx-2 text-[11px] text-[#9CA3AF]">OU par lien URL</span>
                            <div class="flex-grow border-t border-[#ECECEC]"></div>
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">URL de l'image (Optionnel)</label>
                            <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                            <span class="text-[11px] text-[#6B7280] mt-1 block">Si vous ne téléversez rien, une image de couverture par défaut adaptée sera automatiquement attribuée.</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Prix & Inventaire -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5">
                    <h2 class="text-sm font-semibold text-[#111111] mb-4">Tarification & Stock</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Prix de vente FCFA <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" placeholder="Ex: 5000" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-semibold">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Prix régulier barré (Optionnel)</label>
                            <input type="number" name="original_price" value="{{ old('original_price') }}" min="0" placeholder="Ex: 10000" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div x-show="productType === 'physical'">
                            <label class="block font-medium text-[#374151] mb-1">Stock disponible <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', 20) }}" min="0" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div x-show="productType === 'digital'" class="flex items-center">
                            <div class="p-2.5 bg-emerald-50 text-emerald-800 rounded-md border border-emerald-200 text-xs w-full">
                                <strong>Stock illimité :</strong> Les produits numériques n'ont pas de contrainte de stock physique.
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Référence SKU</label>
                            <input type="text" name="reference" value="{{ old('reference') }}" placeholder="Ex: BKO-DIG-001" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-mono">
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
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-medium text-[#374151]">Catégorie / Rayon <span class="text-red-500">*</span></label>
                                <a href="{{ route('admin.categories.create') }}" target="_blank" class="text-[11px] text-[#E31E24] hover:underline font-semibold flex items-center gap-1">
                                    + Nouvelle catégorie
                                </a>
                            </div>
                            <select name="category_id" required class="w-full h-9 px-2.5 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                                <option value="">Sélectionner une catégorie...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Vendeur / Formateur / Auteur</label>
                            <input type="text" name="vendor_name" value="{{ old('vendor_name', 'BKO Supermarché') }}" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>

                        <div>
                            <label class="block font-medium text-[#374151] mb-1">Badge commercial</label>
                            <input type="text" name="badge" value="{{ old('badge') }}" placeholder="Ex: Meilleure Vente, -50%" class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Mise en avant sur la boutique -->
                <div class="bg-white border border-[#E5E7EB] rounded-lg p-5 text-xs">
                    <h3 class="text-sm font-semibold text-[#111111] mb-3">Mise en avant</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24]">
                            <span class="text-[#374151]">Produit Populaire</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24]">
                            <span class="text-[#374151]">Nouveauté</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_recommended" value="1" {{ old('is_recommended') ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24]">
                            <span class="text-[#374151]">Recommandé par BKO SU</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_flash_deal" value="1" {{ old('is_flash_deal') ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-[#E31E24]">
                            <span class="text-[#374151]">Offre Flash</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </form>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <style>
            .ql-toolbar.ql-snow {
                border-top: none;
                border-left: none;
                border-right: none;
                border-bottom: 1px solid #E5E7EB;
                background-color: #F9FAFB;
                border-top-left-radius: 0.375rem;
                border-top-right-radius: 0.375rem;
            }
            .ql-container.ql-snow {
                border: none;
                font-family: inherit;
                font-size: 13px;
            }
            .ql-editor {
                min-height: 160px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Rédigez ici la description détaillée du produit (titres, caractéristiques, avantages, sommaire...)...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['blockquote', 'code-block'],
                            ['link', 'clean']
                        ]
                    }
                });

                const form = document.querySelector('form');
                const hiddenInput = document.getElementById('hidden-description');

                form.addEventListener('submit', function () {
                    const html = quill.root.innerHTML;
                    hiddenInput.value = (html === '<p><br></p>' || html.trim() === '') ? '' : html;
                });
            });
        </script>
    @endpush
</x-admin.layout>
