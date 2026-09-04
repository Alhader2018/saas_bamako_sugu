<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $method = $request->get('method');
        $paymentStatus = $request->get('payment_status');
        $search = trim((string) $request->get('search'));

        $query = Order::latest();

        if ($method) {
            $query->where('payment_method', $method);
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('orange_money_transaction_id', 'like', "%{$search}%")
                  ->orWhere('orange_money_number', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_first_name', 'like', "%{$search}%")
                  ->orWhere('customer_last_name', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(20)->withQueryString();

        $stats = [
            'total_paid' => Order::where('payment_status', 'paid')->sum('total'),
            'orange_money_count' => Order::where('payment_method', 'orange_money')->count(),
            'cash_count' => Order::where('payment_method', 'cash_on_delivery')->count(),
            'pending_count' => Order::where('payment_status', 'pending')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats', 'method', 'paymentStatus', 'search'));
    }
}
