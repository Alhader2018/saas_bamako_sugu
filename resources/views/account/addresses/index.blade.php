<x-customer.layout title="Mes adresses">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111111]">
                Mes adresses de livraison
            </h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                Gérez vos adresses à Bamako pour faciliter vos livraisons express.
            </p>
        </div>

        <button type="button" 
                onclick="document.getElementById('new-address-form').classList.toggle('hidden'); document.getElementById('new-address-form').scrollIntoView({ behavior: 'smooth' })" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold bg-[#E31E24] text-white hover:bg-[#C9171D] transition-colors shrink-0 shadow-2xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Ajouter une adresse</span>
        </button>
    </div>

    <!-- Formulaire d'ajout d'adresse (Caché par défaut ou ouvert si erreurs) -->
    <div id="new-address-form" class="{{ $errors->any() ? '' : 'hidden' }} bg-white border border-[#E5E7EB] rounded-xl p-5 sm:p-6 mb-6 shadow-xs">
        <h2 class="text-sm font-bold text-[#111111] mb-4 flex items-center justify-between">
            <span>Ajouter une nouvelle adresse à Bamako</span>
            <button type="button" onclick="document.getElementById('new-address-form').classList.add('hidden')" class="text-xs text-[#6B7280] hover:text-[#111111]">
                Fermer ✕
            </button>
        </h2>

        <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Libellé -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">
                        Nom de l'adresse <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="label" 
                           value="{{ old('label', 'Domicile') }}" 
                           required 
                           placeholder="Ex: Domicile, Bureau, Famille..." 
                           class="w-full h-9 px-3 bg-[#F9FAFB] border @error('label') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                    @error('label') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nom du destinataire -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">
                        Nom du destinataire <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="recipient_name" 
                           value="{{ old('recipient_name', $user->name) }}" 
                           required 
                           placeholder="Nom et prénom de la personne à livrer" 
                           class="w-full h-9 px-3 bg-[#F9FAFB] border @error('recipient_name') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                    @error('recipient_name') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Téléphone -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">
                        Téléphone joignable <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           name="phone" 
                           value="{{ old('phone', $user->phone ?: '+223 ') }}" 
                           required 
                           placeholder="+223 70 00 11 22" 
                           class="w-full h-9 px-3 bg-[#F9FAFB] border @error('phone') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                    @error('phone') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Quartier -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">
                        Quartier à Bamako <span class="text-red-500">*</span>
                    </label>
                    <select name="neighborhood" required class="w-full h-9 px-3 bg-[#F9FAFB] border @error('neighborhood') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none text-xs">
                        <option value="">Choisir le quartier...</option>
                        @foreach($neighborhoods as $nh)
                            <option value="{{ $nh }}" @selected(old('neighborhood') === $nh)>{{ $nh }}</option>
                        @endforeach
                    </select>
                    @error('neighborhood') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Commune (Optionnel) -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">Commune</label>
                    <input type="text" 
                           name="commune" 
                           value="{{ old('commune') }}" 
                           placeholder="Ex: Commune IV, Commune V..." 
                           class="w-full h-9 px-3 bg-[#F9FAFB] border border-[#D1D5DB] rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                </div>
            </div>

            <!-- Adresse et repère -->
            <div>
                <label class="block font-medium text-[#374151] mb-1">
                    Adresse exacte et point de repère <span class="text-red-500">*</span>
                </label>
                <textarea name="address" 
                          rows="2" 
                          required 
                          placeholder="Rue, numéro de porte, à proximité de la pharmacie, du monument, etc." 
                          class="w-full p-3 text-xs bg-[#F9FAFB] border @error('address') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">{{ old('address') }}</textarea>
                @error('address') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Instructions -->
            <div>
                <label class="block font-medium text-[#374151] mb-1">Instructions pour le livreur (Optionnel)</label>
                <input type="text" 
                       name="delivery_notes" 
                       value="{{ old('delivery_notes') }}" 
                       placeholder="Ex: Appeler à l'arrivée au portail gris, sonner deux fois..." 
                       class="w-full h-9 px-3 bg-[#F9FAFB] border border-[#D1D5DB] rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-[#4B5563]">
                    <input type="checkbox" name="is_default" value="1" class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-0">
                    <span>Définir comme adresse principale de livraison</span>
                </label>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="document.getElementById('new-address-form').classList.add('hidden')" class="px-3.5 py-2 text-xs text-[#6B7280] hover:text-[#111111]">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-xs rounded-lg shadow-2xs transition-colors">
                        Enregistrer l'adresse
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- LISTE DES ADRESSES EXISTANTES -->
    @if($addresses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($addresses as $addr)
                <div class="bg-white border {{ $addr->is_default ? 'border-[#E31E24] ring-1 ring-[#E31E24]' : 'border-[#E5E7EB]' }} rounded-xl p-5 shadow-2xs flex flex-col justify-between">
                    <div>
                        <!-- En-tête carte adresse -->
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="text-xs font-bold text-[#111111]">
                                    {{ $addr->label }}
                                </h3>
                                @if($addr->is_default)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-[#E31E24]">
                                        Adresse principale
                                    </span>
                                @endif
                            </div>

                            <span class="text-[11px] text-[#6B7280]">Bamako</span>
                        </div>

                        <!-- Détails -->
                        <div class="text-xs text-[#4B5563] space-y-1 mt-2">
                            <p class="font-bold text-[#111111]">{{ $addr->recipient_name }}</p>
                            <p class="font-medium text-[#111111]">{{ $addr->phone }}</p>
                            <p class="text-[#111111] font-semibold">{{ $addr->neighborhood }} @if($addr->commune)({{ $addr->commune }})@endif</p>
                            <p class="text-[11px] leading-relaxed text-[#6B7280]">{{ $addr->address }}</p>

                            @if($addr->delivery_notes)
                                <div class="mt-2 p-2 rounded bg-[#F9FAFB] border border-[#ECECEC] text-[11px] text-[#6B7280]">
                                    <strong>Note :</strong> {{ $addr->delivery_notes }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions Bas -->
                    <div class="pt-4 border-t border-[#F3F4F6] mt-4 flex items-center justify-between text-xs">
                        <div>
                            @if(!$addr->is_default)
                                <form action="{{ route('account.addresses.set-default', $addr) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#E31E24] hover:underline font-medium">
                                        Définir comme principale
                                    </button>
                                </form>
                            @else
                                <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                                    <span>✓</span> Par défaut
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('account.addresses.destroy', $addr) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette adresse ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[11px] text-[#9CA3AF] hover:text-red-600 transition-colors">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Aucune adresse encore enregistrée -->
        <div class="bg-white border border-[#E5E7EB] rounded-xl p-10 text-center shadow-2xs">
            <div class="w-12 h-12 mx-auto rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-[#111111]">Aucune adresse enregistrée</h3>
            <p class="text-xs text-[#6B7280] mt-1 max-w-sm mx-auto">
                Enregistrez vos adresses à Bamako pour passer vos commandes plus rapidement sans avoir à saisir votre quartier à chaque fois.
            </p>
            <div class="mt-4">
                <button type="button" 
                        onclick="document.getElementById('new-address-form').classList.remove('hidden'); document.getElementById('new-address-form').scrollIntoView({ behavior: 'smooth' })" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-lg text-xs font-semibold shadow-xs transition-colors">
                    <span>Ajouter ma première adresse</span>
                </button>
            </div>
        </div>
    @endif

</x-customer.layout>
