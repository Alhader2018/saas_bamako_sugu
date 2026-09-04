<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#F6F7F8]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — BKO SU</title>
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
            <h1 class="text-lg font-bold text-[#111111] mt-3">Espace Sécurisé BKO SU</h1>
            <p class="text-xs text-[#6B7280] mt-0.5">Connectez-vous pour accéder à votre compte</p>
        </div>

        <!-- Card Login -->
        <div class="bg-white border border-[#E5E7EB] rounded-lg p-6 shadow-xs">
            
            @if(session('error'))
                <div class="mb-4 p-3 rounded-md bg-red-50 border border-red-200 text-red-800 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 p-3 rounded-md bg-blue-50 border border-blue-200 text-blue-800 text-xs">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Connexion avec Google (Gmail) -->
            <a href="{{ route('auth.google') }}" 
               class="w-full h-10 px-4 mb-4 flex items-center justify-center gap-2.5 bg-white hover:bg-[#F9FAFB] text-[#374151] border border-[#D1D5DB] rounded-md font-medium text-xs shadow-2xs transition-colors">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Continuer avec Google</span>
            </a>

            <!-- Séparateur -->
            <div class="relative flex py-1 items-center mb-4">
                <div class="flex-grow border-t border-[#E5E7EB]"></div>
                <span class="flex-shrink mx-3 text-[11px] text-[#9CA3AF] uppercase tracking-wider font-medium">ou avec votre email</span>
                <div class="flex-grow border-t border-[#E5E7EB]"></div>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-medium text-[#374151] mb-1">Adresse email</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           autocomplete="email"
                           placeholder="nom@exemple.com" 
                           class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                    @error('email')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-[#374151] mb-1">Mot de passe</label>
                    <input type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           placeholder="••••••••" 
                           class="w-full h-9 px-3 bg-white border border-[#D1D5DB] rounded-md focus:border-[#E31E24] focus:outline-none">
                    @error('password')
                        <p class="text-red-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer text-[#4B5563]">
                        <input type="checkbox" name="remember" class="rounded border-[#D1D5DB] text-[#E31E24] focus:ring-0">
                        <span>Se souvenir de moi</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#E31E24] hover:bg-[#C9171D] text-white rounded-md font-semibold transition-colors shadow-xs">
                    Se connecter
                </button>
            </form>

            <div class="mt-4 pt-4 border-t border-[#E5E7EB] text-center text-xs text-[#6B7280]">
                <span>Nouveau client ?</span>
                <a href="{{ route('register') }}" class="text-[#E31E24] font-medium hover:underline ml-1">
                    Créer un compte
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
