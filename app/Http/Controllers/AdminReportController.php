<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30days');

        $startDate = match ($period) {
            'today' => Carbon::today(),
            '7days' => Carbon::now()->subDays(7),
            '30days' => Carbon::now()->subDays(30),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->subDays(30),
        };

        $ordersQuery = Order::where('created_at', '>=', $startDate);

        $totalRevenue = (clone $ordersQuery)->where('status', '!=', 'cancelled')->sum('total');
        $totalOrders = (clone $ordersQuery)->count();
        $successfulOrders = (clone $ordersQuery)->where('payment_status', 'paid')->count();
        $avgBasket = $totalOrders > 0 ? (int) ($totalRevenue / $totalOrders) : 0;

        // Top 5 produits vendus sur la période
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_amount'))
            ->whereHas('order', function ($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate)->where('status', '!=', 'cancelled');
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Répartition par mode de paiement
        $paymentBreakdown = (clone $ordersQuery)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.index', compact('totalRevenue', 'totalOrders', 'successfulOrders', 'avgBasket', 'topProducts', 'paymentBreakdown', 'period'));
    }
}
