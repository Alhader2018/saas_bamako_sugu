<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#F6F7F8]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expirée (419) — BKO SU</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bko-su.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }</style>
</head>
<body class="h-full antialiased text-[#1F2937] flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center bg-white border border-[#E5E7EB] rounded-lg p-8 shadow-xs">
        <span class="text-4xl font-bold text-amber-500">419</span>
        <h1 class="text-lg font-bold text-[#111111] mt-2">Votre session a expiré</h1>
        <p class="text-xs text-[#6B7280] mt-1.5">
            Pour des raisons de sécurité, veuillez rafraîchir la page et réessayer votre opération.
        </p>
        <div class="mt-6">
            <button onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-[#111111] hover:bg-black rounded-md transition-colors">
                Rafraîchir la page
            </button>
        </div>
    </div>
</body>
</html>
