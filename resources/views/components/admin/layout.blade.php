@props(['title' => 'Administration BKO SU', 'breadcrumb' => []])
<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#F6F7F8]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — BKO SU Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-bko-su.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F1F1;
        }
        ::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }
    </style>
</head>
<body class="h-full antialiased text-[#1F2937] text-[13px] bg-[#F6F7F8] flex flex-col md:flex-row">

    <!-- Mobile Drawer Overlay -->
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar Admin -->
    <x-admin.sidebar />

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        <!-- Topbar -->
        <x-admin.topbar :breadcrumb="$breadcrumb" />

        <!-- Flash messages -->
        <div class="px-4 sm:px-6 pt-4 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-4 px-4 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-[13px] rounded-md flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4 py-2.5 bg-red-50 border border-red-200 text-red-800 text-[13px] rounded-md flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">✕</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 px-4 py-2.5 bg-red-50 border border-red-200 text-red-800 text-[13px] rounded-md shadow-xs">
                    <p class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 px-4 sm:px-6 pb-12 max-w-7xl w-full mx-auto">
            {{ $slot }}
        </main>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
