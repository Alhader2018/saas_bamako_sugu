<x-customer.layout title="Mon profil">

    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-[#111111]">
            Mon profil & sécurité
        </h1>
        <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
            Gérez vos informations personnelles et vos accès de connexion.
        </p>
    </div>

    <div class="space-y-6 max-w-2xl">
        
        <!-- Informations personnelles -->
        <div class="bg-white border border-[#E5E7EB] rounded-xl p-5 sm:p-6 shadow-2xs">
            <h2 class="text-sm font-bold text-[#111111] mb-4 flex items-center justify-between">
                <span>Informations personnelles</span>
                @if($user->google_id)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700">
                        <span>Compte lié Google</span>
                    </span>
                @endif
            </h2>

            <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <!-- Nom complet -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">Nom complet <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           class="w-full h-9 px-3 bg-[#F9FAFB] border @error('name') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                    @error('name') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Adresse email <span class="text-red-500">*</span></label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required 
                               class="w-full h-9 px-3 bg-[#F9FAFB] border @error('email') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                        @error('email') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Téléphone <span class="text-red-500">*</span></label>
                        <input type="tel" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               required 
                               placeholder="+223 70 00 11 22" 
                               class="w-full h-9 px-3 bg-[#F9FAFB] border @error('phone') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                        @error('phone') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Quartier & Adresse -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Quartier principal à Bamako</label>
                        <input type="text" 
                               name="neighborhood" 
                               value="{{ old('neighborhood', $user->neighborhood) }}" 
                               placeholder="Ex: ACI 2000, Badalabougou..." 
                               class="w-full h-9 px-3 bg-[#F9FAFB] border border-[#D1D5DB] rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Adresse / Repère</label>
                        <input type="text" 
                               name="address" 
                               value="{{ old('address', $user->address) }}" 
                               placeholder="Rue, porte, repère connu..." 
                               class="w-full h-9 px-3 bg-[#F9FAFB] border border-[#D1D5DB] rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                    </div>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="px-4 py-2 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-xs rounded-lg shadow-2xs transition-colors">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Sécurité : Mot de passe -->
        <div class="bg-white border border-[#E5E7EB] rounded-xl p-5 sm:p-6 shadow-2xs">
            <h2 class="text-sm font-bold text-[#111111] mb-4">
                Sécurité & Mot de passe
            </h2>

            <form action="{{ route('account.profile.password') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-medium text-[#374151] mb-1">Mot de passe actuel</label>
                    <input type="password" 
                           name="current_password" 
                           required 
                           placeholder="••••••••" 
                           class="w-full h-9 px-3 bg-[#F9FAFB] border @error('current_password') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                    @error('current_password') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Nouveau mot de passe</label>
                        <input type="password" 
                               name="password" 
                               required 
                               placeholder="Minimum 8 caractères" 
                               class="w-full h-9 px-3 bg-[#F9FAFB] border @error('password') border-red-500 @else border-[#D1D5DB] @enderror rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                        @error('password') <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-[#374151] mb-1">Confirmer le nouveau mot de passe</label>
                        <input type="password" 
                               name="password_confirmation" 
                               required 
                               placeholder="Répétez le nouveau mot de passe" 
                               class="w-full h-9 px-3 bg-[#F9FAFB] border border-[#D1D5DB] rounded-lg focus:bg-white focus:border-[#E31E24] focus:outline-none">
                    </div>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="px-4 py-2 bg-[#111111] hover:bg-black text-white font-semibold text-xs rounded-lg shadow-2xs transition-colors">
                        Mettre à jour le mot de passe
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-customer.layout>
