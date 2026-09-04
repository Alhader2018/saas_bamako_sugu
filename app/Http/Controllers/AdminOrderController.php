<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $paymentStatus = $request->get('payment_status');
        $search = trim((string) $request->get('search'));
        $neighborhood = $request->get('neighborhood');

        $query = Order::with('items')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($neighborhood) {
            $query->where('neighborhood', $neighborhood);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_first_name', 'like', "%{$search}%")
                  ->orWhere('customer_last_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('neighborhood', 'like', "%{$search}%")
                  ->orWhere('orange_money_transaction_id', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        // Compteurs par statut (WooCommerce tabs)
        $counts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'in_delivery' => Order::where('status', 'in_delivery')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $neighborhoods = Order::select('neighborhood')
            ->whereNotNull('neighborhood')
            ->distinct()
            ->pluck('neighborhood')
            ->filter();

        return view('admin.orders.index', compact('orders', 'counts', 'status', 'paymentStatus', 'search', 'neighborhood', 'neighborhoods'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_delivery,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,cancelled',
        ]);

        $updates = ['status' => $request->status];
        if ($request->filled('payment_status')) {
            $updates['payment_status'] = $request->payment_status;
        }

        $order->update($updates);

        return redirect()->back()->with('success', "Statut de la commande {$order->order_number} mis à jour avec succès.");
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $selectedIds = $request->input('selected_orders', []);

        if (empty($selectedIds) || !is_array($selectedIds)) {
            return redirect()->back()->with('error', 'Veuillez sélectionner au moins une commande.');
        }

        switch ($action) {
            case 'confirm':
                Order::whereIn('id', $selectedIds)->update(['status' => 'confirmed']);
                $msg = count($selectedIds) . ' commande(s) confirmée(s).';
                break;
            case 'in_delivery':
                Order::whereIn('id', $selectedIds)->update(['status' => 'in_delivery']);
                $msg = count($selectedIds) . ' commande(s) passée(s) en livraison.';
                break;
            case 'delivered':
                Order::whereIn('id', $selectedIds)->update(['status' => 'delivered', 'payment_status' => 'paid']);
                $msg = count($selectedIds) . ' commande(s) marquée(s) livrée(s).';
                break;
            case 'cancel':
                Order::whereIn('id', $selectedIds)->update(['status' => 'cancelled']);
                $msg = count($selectedIds) . ' commande(s) annulée(s).';
                break;
            default:
                return redirect()->back()->with('error', 'Action non valide.');
        }

        return redirect()->back()->with('success', $msg);
    }

    public function print(Order $order)
    {
        $order->load('items');
        return view('admin.orders.print', compact('order'));
    }
}
