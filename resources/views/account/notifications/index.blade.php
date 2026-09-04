<x-customer.layout title="Mes notifications">

    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-[#111111]">
            Centre de notifications
        </h1>
        <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">
            Suivez les alertes de préparation et d'expédition de vos commandes.
        </p>
    </div>

    @if($notifications->count() > 0)
        <div class="bg-white border border-[#E5E7EB] rounded-xl overflow-hidden shadow-2xs divide-y divide-[#F3F4F6] mb-5">
            @foreach($notifications as $notif)
                <div class="p-4 sm:p-5 flex items-start gap-3.5 hover:bg-[#F9FAFB] transition-colors">
                    <!-- Icone selon type -->
                    <div class="w-9 h-9 rounded-full shrink-0 flex items-center justify-center 
                        @if($notif->type === 'delivered') bg-emerald-50 text-emerald-600
                        @elseif($notif->type === 'cancelled') bg-neutral-100 text-[#6B7280]
                        @elseif($notif->type === 'in_delivery') bg-blue-50 text-blue-600
                        @else bg-red-50 text-[#E31E24] @endif">
                        @if($notif->type === 'delivered')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        @elseif($notif->type === 'in_delivery')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25V3.75m0 3.75h-9a1.125 1.125 0 00-1.125 1.125v6.75" />
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        @endif
                    </div>

                    <!-- Contenu -->
                    <div class="min-w-0 flex-1 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <h4 class="font-bold text-[#111111] text-xs">{{ $notif->title }}</h4>
                            <span class="text-[10px] text-[#9CA3AF] shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-[#4B5563] mt-1 leading-relaxed">{{ $notif->message }}</p>

                        @if($notif->order_id)
                            <div class="mt-2">
                                <a href="{{ route('account.orders.show', $notif->order_id) }}" class="text-xs font-semibold text-[#E31E24] hover:underline inline-flex items-center gap-1">
                                    <span>Voir la commande</span>
                                    <span>→</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $notifications->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white border border-[#E5E7EB] rounded-xl p-10 text-center shadow-2xs">
            <div class="w-12 h-12 mx-auto rounded-full bg-neutral-100 text-[#6B7280] flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-[#111111]">Aucune notification</h3>
            <p class="text-xs text-[#6B7280] mt-1 max-w-sm mx-auto">
                Vous recevrez des notifications ici dès que vos commandes changent de statut.
            </p>
        </div>
    @endif

</x-customer.layout>
