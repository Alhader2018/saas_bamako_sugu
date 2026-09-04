<footer class="bg-white border-t border-[#ECECEC] mt-12 text-xs">
    <!-- Engagements Service BKO SU (Sobre, sans boîtes de cartes gonflées) -->
    <div class="border-b border-[#ECECEC] bg-[#F8F8F8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- 1 -->
                <div>
                    <h4 class="font-bold text-[#1C1C1C] mb-1">Livraison express Bamako</h4>
                    <p class="text-[#6B7280]">Livraison le jour même dans tous les quartiers de Bamako.</p>
                </div>

                <!-- 2 -->
                <div>
                    <h4 class="font-bold text-[#1C1C1C] mb-1">Paiement Orange Money</h4>
                    <p class="text-[#6B7280]">Paiement sécurisé par code #144# ou en espèces à la livraison.</p>
                </div>

                <!-- 3 -->
                <div>
                    <h4 class="font-bold text-[#1C1C1C] mb-1">Origine certifiée</h4>
                    <p class="text-[#6B7280]">Produits issus des commerçants et coopératives agréés du Mali.</p>
                </div>

                <!-- 4 -->
                <div>
                    <h4 class="font-bold text-[#1C1C1C] mb-1">Service client local</h4>
                    <p class="text-[#6B7280]">Équipe basée à Bamako joignable au +223 70 00 00 00.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liens & Navigation Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            
            <!-- Col 1: Brand & Contact -->
            <div class="lg:col-span-2 space-y-3">
                <x-logo class="h-8 w-auto" />
                <p class="text-[#6B7280] leading-relaxed max-w-sm">
                    <strong>BKO SU</strong> (Bamako Supermarché) — Marketplace connectée pour les courses et le commerce local à Bamako.
                </p>
                <p class="text-[#6B7280]">
                    District de Bamako, Mali • Contact : contact@bamakosugu.com
                </p>
            </div>

            <!-- Col 2: Rayons -->
            <div>
                <h5 class="font-bold text-[#1C1C1C] mb-2.5">Rayons</h5>
                <ul class="space-y-1.5 text-[#6B7280]">
                    <li><a href="{{ route('catalog', ['category' => 'supermarche']) }}" class="hover:text-[#E31E24]">Supermarché</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'mode']) }}" class="hover:text-[#E31E24]">Bazin & Mode</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'high-tech']) }}" class="hover:text-[#E31E24]">Téléphonie</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'fruits-legumes']) }}" class="hover:text-[#E31E24]">Fruits & Légumes</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'beaute']) }}" class="hover:text-[#E31E24]">Karité & Beauté</a></li>
                </ul>
            </div>

            <!-- Col 3: Quartiers -->
            <div>
                <h5 class="font-bold text-[#1C1C1C] mb-2.5">Zones desservies</h5>
                <ul class="space-y-1.5 text-[#6B7280]">
                    <li><span>ACI 2000 & Hamdallaye</span></li>
                    <li><span>Badalabougou & Fleuve</span></li>
                    <li><span>Hippodrome & Quinzambougou</span></li>
                    <li><span>Faladié & Banankabougou</span></li>
                    <li><span>Baco-Djicoroni & Kalaban</span></li>
                </ul>
            </div>

            <!-- Col 4: Newsletter -->
            <div>
                <h5 class="font-bold text-[#1C1C1C] mb-2.5">Promotions par SMS/Email</h5>
                <p class="text-[#6B7280] mb-2.5">Recevez les meilleures opportunités de Bamako.</p>
                
                <form onsubmit="event.preventDefault(); window.dispatchEvent(new CustomEvent('toast', {detail: {message: 'Inscription enregistrée.'}}))" class="space-y-2">
                    <input 
                        type="text" 
                        placeholder="Numéro ou email..." 
                        class="w-full h-9 px-3 text-xs bg-[#F8F8F8] border border-[#ECECEC] rounded-md outline-none focus:border-[#E31E24]"
                        required
                    >
                    <button 
                        type="submit" 
                        class="w-full h-8 bg-[#111111] hover:bg-[#E31E24] text-white font-medium text-xs rounded-md smooth-transition cursor-pointer"
                    >
                        S'inscrire
                    </button>
                </form>
            </div>

        </div>

        <!-- Mentions Finales -->
        <div class="mt-8 pt-5 border-t border-[#ECECEC] flex flex-col sm:flex-row items-center justify-between gap-3 text-neutral-500 text-[11px]">
            <p>© {{ date('Y') }} <strong>BKO SU</strong> (Bamako Supermarché) — Tous droits réservés.</p>
            <div class="flex items-center gap-3">
                <span>Devise : <strong>FCFA (XOF)</strong></span>
                <span>•</span>
                <span>Orange Money Mali / Espèces</span>
            </div>
        </div>
    </div>
</footer>
