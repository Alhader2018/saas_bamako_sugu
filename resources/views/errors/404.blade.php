<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#F6F7F8]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Introuvable (404) — BKO SU</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bko-su.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }</style>
</head>
<body class="h-full antialiased text-[#1F2937] flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center bg-white border border-[#E5E7EB] rounded-lg p-8 shadow-xs">
        <span class="text-4xl font-bold text-[#E31E24]">404</span>
        <h1 class="text-lg font-bold text-[#111111] mt-2">Page Introuvable</h1>
        <p class="text-xs text-[#6B7280] mt-1.5">
            La page que vous recherchez n'existe pas ou a été déplacée.
        </p>
        <div class="mt-6">
            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-[#E31E24] hover:bg-[#C9171D] rounded-md transition-colors">
                Retourner sur la boutique
            </a>
        </div>
    </div>
</body>
</html>
