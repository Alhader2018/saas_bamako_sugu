<x-customer.layout title="Mes Téléchargements">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-[#ECECEC] pb-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-[#111111]">Mes Téléchargements</h1>
                <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
                    Retrouvez l'accès à tous vos livres, formations, guides et fichiers achetés sur BKO SU.
                </p>
            </div>
            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#E31E24] hover:underline">
                Explorer le catalogue →
            </a>
        </div>

        @php
            $hasAnyDigitalItems = $orders->isNotEmpty();
        @endphp

        @if(!$hasAnyDigitalItems)
            <!-- État vide -->
            <div class="bg-white rounded-xl border border-[#ECECEC] p-10 text-center">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <h2 class="text-base font-bold text-[#111111] mb-1">Vous n'avez aucun achat numérique</h2>
                <p class="text-xs text-[#6B7280] max-w-md mx-auto mb-6">
                    Découvrez nos e-books, cours et formations en ligne disponibles en accès immédiat après règlement.
                </p>
                <a href="{{ route('catalog') }}" class="inline-flex items-center justify-center h-10 px-5 bg-[#E31E24] hover:bg-[#C9171D] text-white font-semibold text-xs rounded-lg smooth-transition">
                    Parcourir les formations et livres
                </a>
            </div>
        @else
            <!-- Liste des commandes avec produits numériques -->
            <div class="space-y-6">
                @foreach($orders as $order)
                    @foreach($order->items as $orderItem)
                        @php
                            $product = $orderItem->product;
                        @endphp
                        <div class="bg-white rounded-xl border border-[#ECECEC] overflow-hidden shadow-2xs">
                            <!-- Header de l'article -->
                            <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-[#F3F4F6] bg-neutral-50/50">
                                <div class="flex items-center gap-3.5">
                                    <img 
                                        src="{{ $orderItem->product_image ?: asset('images/placeholder.jpg') }}" 
                                        alt="{{ $orderItem->product_name }}" 
                                        class="w-14 h-14 object-cover rounded-lg border border-[#ECECEC] shrink-0"
                                    >
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-sm font-bold text-[#111111]">{{ $orderItem->product_name }}</h3>
                                            @if($product && $product->digital_type_label)
                                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-900 rounded">
                                                    {{ $product->digital_type_label }}
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-900 rounded">
                                                    Numérique
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-[#6B7280] mt-0.5">
                                            Acheté le {{ $order->created_at->format('d/m/Y à H:i') }} — Commande <span class="font-medium text-[#111111]">{{ $order->order_number }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="text-left sm:text-right shrink-0">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Accès actif
                                    </span>
                                </div>
                            </div>

                            <!-- Fichiers associés & liens de téléchargement -->
                            <div class="p-4 sm:p-5 space-y-3">
                                @if($product && $product->files->isNotEmpty())
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#6B7280]">Fichiers téléchargeables</h4>
                                    <div class="divide-y divide-[#F3F4F6] border border-[#ECECEC] rounded-lg">
                                        @foreach($product->files as $file)
                                            @php
                                                $dlCount = $downloadsCount[$file->id] ?? 0;
                                                $remaining = $product->download_limit ? max(0, $product->download_limit - $dlCount) : null;
                                                $isExhausted = $remaining !== null && $remaining <= 0;
                                            @endphp
                                            <div class="p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white hover:bg-neutral-50/70 transition-colors">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-lg bg-red-50 text-[#E31E24] flex items-center justify-center shrink-0 border border-red-100">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-bold text-[#111111]">{{ $file->name }}</div>
                                                        <div class="text-[11px] text-[#6B7280] flex items-center gap-2 mt-0.5">
                                                            <span>{{ $file->formatted_file_size }}</span>
                                                            @if($file->file_extension)
                                                                <span>• Format {{ strtoupper($file->file_extension) }}</span>
                                                            @endif
                                                            @if($product->download_limit)
                                                                <span>• Restant : <strong>{{ $remaining }} / {{ $product->download_limit }}</strong></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    @if($isExhausted)
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-neutral-100 text-[#9CA3AF]">
                                                            Limite atteinte
                                                        </span>
                                                    @else
                                                        <a 
                                                            href="{{ route('digital.download', ['orderNumber' => $order->order_number, 'fileId' => $file->id]) }}"
                                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#E31E24] hover:bg-[#C9171D] text-white text-xs font-semibold rounded-lg smooth-transition cursor-pointer"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                            </svg>
                                                            <span>Télécharger</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Accès à une ressource externe si configurée -->
                                @if($product && $product->access_type === 'external_link' && $product->external_access_url)
                                    <div class="p-3.5 rounded-lg bg-neutral-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <div class="text-xs font-bold">Espace de formation en ligne / Vidéos</div>
                                            <div class="text-[11px] text-neutral-400 mt-0.5">Accédez à vos modules vidéo ou vos cours directement.</div>
                                        </div>
                                        <a 
                                            href="{{ $product->external_access_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center gap-1.5 px-4 py-1.5 bg-[#F7B500] hover:bg-[#E0A300] text-[#111111] text-xs font-bold rounded-lg smooth-transition"
                                        >
                                            <span>Ouvrir la formation ↗</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @endif
    </div>
</x-customer.layout>
