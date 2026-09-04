<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#F6F7F8]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte — BKO SU</title>
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

    <div class="w-full max-w-sm">
        <!-- Logo & Titre -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block">
                <x-logo class="h-8 w-auto mx-auto" />
            </a>
            <h1 class="text-lg font-bold text-[#111111] mt-3">Créer un compte client</h1>
            <p class="text-xs text-[#6B7280] mt-0.5">Pour suivre vos commandes et livraisons à Bamako</p>
        </div>

        <!-- Card Inscription -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-6 shadow-xs">
            
            <form action="{{ route('register') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf

                <div>
                    <label class="block font-medium text-[#374151] mb-1">Nom complet <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus
                           placeholder="Ex: Mamadou Diallo" 
                           class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                    @error('name')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-[#374151] mb-1">Adresse email <span class="text-red-500">*</span></label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="nom@exemple.com" 
                           class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                    @error('email')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-[#374151] mb-1">Numéro de téléphone Mali <span class="text-red-500">*</span></label>
                    <input type="tel" 
                           name="phone" 
                           value="{{ old('phone', '+223 ') }}" 
                           required 
                           placeholder="+223 XX XX XX XX" 
                           class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none font-medium">
                    @error('phone')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-[#374151] mb-1">Mot de passe (8 caractères min) <span class="text-red-500">*</span></label>
                    <input type="password" 
                           name="password" 
                           required 
                           autocomplete="new-password"
                           placeholder="••••••••" 
                           class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                    @error('password')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-[#374151] mb-1">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                    <input type="password" 
                           name="password_confirmation" 
                           required 
                           autocomplete="new-password"
                           placeholder="••••••••" 
                           class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-md font-semibold transition-colors shadow-xs mt-2">
                    Créer mon compte
                </button>
            </form>

            <div class="mt-4 pt-4 border-t border-[#E5E7EB] text-center text-xs text-[#6B7280]">
                <span>Déjà inscrit ?</span>
                <a href="{{ route('login') }}" class="text-[#E31E24] font-medium hover:underline ml-1">
                    Se connecter
                </a>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-xs text-[#6B7280] hover:text-[#111111]">
                ← Retourner sur la boutique
            </a>
        </div>
    </div>

</body>
</html>
