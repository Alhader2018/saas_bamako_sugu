@props(['status' => 'pending', 'type' => 'order'])

@php
    $text = $status;
    $classes = 'bg-neutral-100 text-neutral-700 border-neutral-200';

    if ($type === 'order') {
        switch ($status) {
            case 'pending':
                $text = 'En attente';
                $classes = 'bg-amber-50 text-amber-700 border-amber-200';
                break;
            case 'confirmed':
                $text = 'Confirmée';
                $classes = 'bg-blue-50 text-blue-700 border-blue-200';
                break;
            case 'in_delivery':
                $text = 'En livraison';
                $classes = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                break;
            case 'delivered':
                $text = 'Livrée';
                $classes = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                break;
            case 'cancelled':
                $text = 'Annulée';
                $classes = 'bg-neutral-100 text-neutral-600 border-neutral-200';
                break;
            default:
                $text = ucfirst($status);
                $classes = 'bg-neutral-50 text-neutral-700 border-neutral-200';
        }
    } elseif ($type === 'payment') {
        switch ($status) {
            case 'paid':
                $text = 'Payé';
                $classes = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                break;
            case 'pending':
                $text = 'En attente';
                $classes = 'bg-amber-50 text-amber-700 border-amber-200';
                break;
            case 'failed':
                $text = 'Échoué';
                $classes = 'bg-red-50 text-red-700 border-red-200';
                break;
            case 'cancelled':
                $text = 'Annulé';
                $classes = 'bg-neutral-100 text-neutral-600 border-neutral-200';
                break;
            default:
                $text = ucfirst($status);
                $classes = 'bg-neutral-50 text-neutral-700 border-neutral-200';
        }
    } elseif ($type === 'stock') {
        if ($status === 'out') {
            $text = 'Rupture';
            $classes = 'bg-red-50 text-red-700 border-red-200';
        } elseif ($status === 'low') {
            $text = 'Stock faible';
            $classes = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $text = 'En stock';
            $classes = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        }
    }
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium border {$classes}"]) }}>
    {{ $text }}
</span>
