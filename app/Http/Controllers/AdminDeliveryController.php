<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDeliveryController extends Controller
{
    public function index(Request $request)
    {
        $selectedNeighborhood = $request->get('neighborhood');
        $deliveryStatus = $request->get('delivery_status');

        $query = Order::with('items')->latest();

        if ($selectedNeighborhood) {
            $query->where('neighborhood', $selectedNeighborhood);
        }

        if ($deliveryStatus) {
            $query->where('status', $deliveryStatus);
        } else {
            // Par défaut, afficher les commandes nécessitant une attention logistique
            $query->whereIn('status', ['confirmed', 'in_delivery', 'pending']);
        }

        $orders = $query->paginate(20)->withQueryString();

        // Répartition par quartier de Bamako
        $neighborhoodStats = Order::select('neighborhood', DB::raw('COUNT(*) as total_orders'))
            ->whereNotNull('neighborhood')
            ->whereIn('status', ['confirmed', 'in_delivery', 'pending'])
            ->groupBy('neighborhood')
            ->orderByDesc('total_orders')
            ->get();

        $deliveryCounts = [
            'to_prepare' => Order::where('status', 'confirmed')->count(),
            'in_delivery' => Order::where('status', 'in_delivery')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        return view('admin.deliveries.index', compact('orders', 'neighborhoodStats', 'deliveryCounts', 'selectedNeighborhood', 'deliveryStatus'));
    }
}
