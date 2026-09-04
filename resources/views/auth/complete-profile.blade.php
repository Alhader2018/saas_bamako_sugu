<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#F6F7F8]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adresse de livraison — BKO SU</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bko-su.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-[#1F2937] flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo & En-tête -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block">
                <x-logo class="h-8 w-auto mx-auto" />
            </a>
            
            <div class="inline-flex items-center gap-2 px-3 py-1 mt-4 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-4 h-4 rounded-full object-cover">
                @else
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                @endif
                <span>Connecté avec Google : {{ $user->email }}</span>
            </div>

            <h1 class="text-xl font-bold text-[#111111] mt-3">Précisez votre adresse de livraison</h1>
            <p class="text-xs text-[#6B7280] mt-1 max-w-sm mx-auto">
                Indispensable pour que nos livreurs vous acheminent vos commandes rapidement partout à Bamako.
            </p>
        </div>

        <!-- Formulaire Carte -->
        <div class="bg-white border border-[#E5E7EB] rounded-xl p-6 sm:p-7 shadow-xs">
            
            @if(session('info'))
                <div class="mb-5 p-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-xs flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form action="{{ route('profile.complete.update') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <!-- Ville -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">Ville</label>
                    <div class="relative">
                        <input type="text" 
                               name="city" 
                               value="Bamako" 
                               readonly
                               class="w-full h-9 px-3 bg-[#F9FAFB] border border-[#E5E7EB] rounded-md text-[#6B7280] cursor-not-allowed font-medium">
                        <span class="absolute right-3 top-2.5 text-[10px] text-[#9CA3AF] uppercase font-semibold">Mali</span>
                    </div>
                </div>

                <!-- Téléphone Malien -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">
                        Numéro de téléphone malien <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="tel" 
                               name="phone" 
                               value="{{ old('phone', $user->phone ?: '+223 ') }}" 
                               required 
                               placeholder="+223 70 00 11 22" 
                               class="w-full h-9 px-3 bg-white border @error('phone') border-red-500 @else border-[#D1D5DB] @enderror rounded-md focus:border-[#E31E24] focus:outline-none">
                    </div>
                    @error('phone')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @else
                        <p class="text-[11px] text-[#9CA3AF] mt-1">Numéro joignable pour le livreur (Orange, Moov ou Telecel).</p>
                    @enderror
                </div>

                <!-- Quartier de Bamako -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">
                        Quartier à Bamako <span class="text-red-500">*</span>
                    </label>
                    <select name="neighborhood" 
                            required 
                            class="w-full h-9 px-3 bg-white border @error('neighborhood') border-red-500 @else border-[#D1D5DB] @enderror rounded-md focus:border-[#E31E24] focus:outline-none text-xs">
                        <option value="">Sélectionnez votre quartier...</option>
                        @foreach($neighborhoods as $nh)
                            <option value="{{ $nh }}" @selected(old('neighborhood', $user->neighborhood) === $nh)>{{ $nh }}</option>
                        @endforeach
                    </select>
                    @error('neighborhood')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Adresse / Repère -->
                <div>
                    <label class="block font-medium text-[#374151] mb-1">
                        Adresse précise ou repère connu <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" 
                              rows="3" 
                              required 
                              placeholder="Ex: Rue 24, Porte 102, non loin de la Pharmacie du Progrès ou de l'école..." 
                              class="w-full p-3 bg-white border @error('address') border-red-500 @else border-[#D1D5DB] @enderror rounded-md focus:border-[#E31E24] focus:outline-none resize-none leading-relaxed">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @else
                        <p class="text-[11px] text-[#9CA3AF] mt-1">Donnez un repère pour faciliter l'arrivée du livreur.</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-md font-semibold transition-colors shadow-xs flex items-center justify-center gap-2">
                        <span>Enregistrer et continuer</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-4 text-center">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-xs text-[#6B7280] hover:text-[#111111]">
                    Se déconnecter de ce compte
                </button>
            </form>
        </div>
    </div>

</body>
</html>
