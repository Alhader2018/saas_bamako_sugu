<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $query = Order::select(
            'customer_phone',
            DB::raw('MAX(customer_first_name) as first_name'),
            DB::raw('MAX(customer_last_name) as last_name'),
            DB::raw('MAX(customer_email) as email'),
            DB::raw('MAX(neighborhood) as neighborhood'),
            DB::raw('COUNT(id) as orders_count'),
            DB::raw('SUM(total) as total_spent'),
            DB::raw('MAX(created_at) as last_order_date')
        )
        ->whereNotNull('customer_phone')
        ->groupBy('customer_phone')
        ->orderByDesc('last_order_date');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_first_name', 'like', "%{$search}%")
                  ->orWhere('customer_last_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('neighborhood', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(20)->withQueryString();

        $totalCustomers = Order::distinct('customer_phone')->count('customer_phone');
        $totalCustomerRevenue = Order::where('status', '!=', 'cancelled')->sum('total');

        return view('admin.customers.index', compact('customers', 'search', 'totalCustomers', 'totalCustomerRevenue'));
    }

    public function show(string $phone)
    {
        $orders = Order::with('items')
            ->where('customer_phone', $phone)
            ->latest()
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->route('admin.customers.index')->with('error', 'Client non trouvé.');
        }

        $firstOrder = $orders->first();
        $customer = (object) [
            'name' => $firstOrder->customer_full_name,
            'phone' => $phone,
            'email' => $firstOrder->customer_email,
            'neighborhood' => $firstOrder->neighborhood,
            'address' => $firstOrder->address,
            'orders_count' => $orders->count(),
            'total_spent' => $orders->where('status', '!=', 'cancelled')->sum('total'),
            'orders' => $orders,
        ];

        return view('admin.customers.show', compact('customer'));
    }
}
