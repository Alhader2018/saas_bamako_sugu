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
            
            <!-- Inscription avec Google (Gmail) -->
            <a href="{{ route('auth.google') }}" 
               class="w-full h-10 px-4 mb-4 flex items-center justify-center gap-2.5 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#D1D5DB] rounded-md font-medium text-xs shadow-2xs transition-colors">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>S'inscrire avec Google</span>
            </a>

            <!-- Séparateur -->
            <div class="relative flex py-1 items-center mb-4">
                <div class="flex-grow border-t border-[#E5E7EB]"></div>
                <span class="flex-shrink mx-3 text-[11px] text-[#9CA3AF] uppercase tracking-wider font-medium">ou avec votre email</span>
                <div class="flex-grow border-t border-[#E5E7EB]"></div>
            </div>

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
