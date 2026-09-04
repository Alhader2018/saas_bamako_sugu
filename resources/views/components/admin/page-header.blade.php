@props(['title', 'description' => null])
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-4 border-b border-[#E5E7EB] mb-6">
    <div>
        <h1 class="text-xl font-bold text-[#111111] tracking-tight">{{ $title }}</h1>
        @if($description)
            <p class="text-xs text-[#6B7280] mt-0.5">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex items-center gap-2.5 shrink-0">
            {{ $actions }}
        </div>
    @endif
</div>
