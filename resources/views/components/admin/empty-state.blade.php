@props(['title' => 'Aucun résultat trouvé', 'message' => 'Essayez de modifier vos filtres ou effectuez une nouvelle recherche.', 'actionUrl' => null, 'actionText' => null])
<div class="py-12 px-4 text-center">
    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#F3F4F6] text-[#9CA3AF] mb-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </div>
    <h3 class="text-sm font-semibold text-[#111111]">{{ $title }}</h3>
    <p class="text-xs text-[#6B7280] max-w-sm mx-auto mt-1">{{ $message }}</p>
    @if($actionUrl && $actionText)
        <div class="mt-4">
            <a href="{{ $actionUrl }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-[#E31E24] hover:bg-[#C9171D] transition-colors">
                {{ $actionText }}
            </a>
        </div>
    @endif
</div>
