<footer class="bg-white border-t border-[#ECECEC] mt-16">
    <!-- Avantages BKO SU -->
    <div class="border-b border-[#ECECEC] bg-[#F8F8F8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Avantage 1 -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#E31E24]/10 text-[#E31E24] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <rect width="16" height="13" x="1" y="3" rx="2"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#1C1C1C]">Livraison Express Bamako</h4>
                        <p class="text-xs text-[#6B7280] mt-1">Vos courses livrées à votre porte dans tous les quartiers de Bamako en un temps record.</p>
                    </div>
                </div>

                <!-- Avantage 2 -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#F7B500]/20 text-[#111111] flex items-center justify-center shrink-0 font-black">
                        OM
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#1C1C1C]">Paiement Mobile Sécurisé</h4>
                        <p class="text-xs text-[#6B7280] mt-1">Réglez facilement avec Orange Money (#144#) ou en espèces directement à la livraison.</p>
                    </div>
                </div>

                <!-- Avantage 3 -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#16A34A]/10 text-[#16A34A] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#1C1C1C]">Produits 100% Authentiques</h4>
                        <p class="text-xs text-[#6B7280] mt-1">Tous nos produits proviennent directement des commerçants et coopératives agréés.</p>
                    </div>
                </div>

                <!-- Avantage 4 -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-neutral-100 text-[#1C1C1C] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#1C1C1C]">Support Local 7j/7</h4>
                        <p class="text-xs text-[#6B7280] mt-1">Une équipe dédiée basée à Bamako joignable au +223 70 00 00 00 pour vous assister.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer Links -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            
            <!-- Col 1: Brand & Bio -->
            <div class="lg:col-span-2 space-y-4">
                <x-logo class="h-10 w-auto" />
                <p class="text-xs text-[#6B7280] leading-relaxed max-w-sm">
                    <strong>BKO SU</strong> (Bamako Supermarché) est la première marketplace connectée dédiée au commerce moderne malien. Tout Bamako dans un seul panier avec livraison rapide à domicile et au bureau.
                </p>
                <div class="pt-2 text-xs text-[#1C1C1C]">
                    <p class="font-bold">District de Bamako, Mali</p>
                    <p class="text-[#6B7280] mt-0.5">ACI 2000, Rue 312, Bamako • Téléphone : +223 70 00 00 00</p>
                </div>
            </div>

            <!-- Col 2: Rayons -->
            <div>
                <h5 class="text-xs font-bold text-[#1C1C1C] uppercase tracking-wider mb-3">Rayons populaires</h5>
                <ul class="space-y-2 text-xs text-[#6B7280]">
                    <li><a href="{{ route('catalog', ['category' => 'supermarche']) }}" class="hover:text-[#E31E24] smooth-transition">Supermarché & Épicerie</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'mode']) }}" class="hover:text-[#E31E24] smooth-transition">Bazin & Mode Bamako</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'high-tech']) }}" class="hover:text-[#E31E24] smooth-transition">Téléphonie & High-Tech</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'fruits-legumes']) }}" class="hover:text-[#E31E24] smooth-transition">Fruits & Légumes Frais</a></li>
                    <li><a href="{{ route('catalog', ['category' => 'beaute']) }}" class="hover:text-[#E31E24] smooth-transition">Karité Bio & Beauté</a></li>
                </ul>
            </div>

            <!-- Col 3: Quartiers Bamako -->
            <div>
                <h5 class="text-xs font-bold text-[#1C1C1C] uppercase tracking-wider mb-3">Zones de livraison</h5>
                <ul class="space-y-2 text-xs text-[#6B7280]">
                    <li><span>ACI 2000 & Hamdallaye</span></li>
                    <li><span>Badalabougou & Fleuve</span></li>
                    <li><span>Hippodrome & Quinzambougou</span></li>
                    <li><span>Faladié & Banankabougou</span></li>
                    <li><span>Baco-Djicoroni & Kalaban</span></li>
                </ul>
            </div>

            <!-- Col 4: Newsletter -->
            <div>
                <h5 class="text-xs font-bold text-[#1C1C1C] uppercase tracking-wider mb-3">Newsletter BKO SU</h5>
                <p class="text-xs text-[#6B7280] mb-3">Recevez chaque semaine les meilleures promotions de Bamako par email ou SMS.</p>
                
                <form onsubmit="event.preventDefault(); window.dispatchEvent(new CustomEvent('toast', {detail: {message: 'Merci pour votre inscription à la newsletter BKO SU !'}}))" class="space-y-2">
                    <input 
                        type="text" 
                        placeholder="Votre numéro ou email..." 
                        class="w-full h-10 px-3 text-xs bg-[#F8F8F8] border border-[#ECECEC] rounded-xl outline-none focus:border-[#E31E24]"
                        required
                    >
                    <button 
                        type="submit" 
                        class="w-full h-10 bg-[#111111] hover:bg-[#E31E24] text-white font-bold text-xs rounded-xl smooth-transition cursor-pointer"
                    >
                        S'inscrire
                    </button>
                </form>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="mt-12 pt-6 border-t border-[#ECECEC] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#6B7280]">
            <p>© {{ date('Y') }} <strong>BKO SU</strong> (Bamako Supermarché) — Tous droits réservés.</p>
            <div class="flex items-center gap-4 text-[11px]">
                <span>Devise : <strong>FCFA (XOF)</strong></span>
                <span>•</span>
                <span>Moyens acceptés : <strong>Orange Money</strong> / <strong>Espèces</strong></span>
            </div>
        </div>
    </div>
</footer>
