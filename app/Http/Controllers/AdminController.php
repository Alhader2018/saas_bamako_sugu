<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $statusFilter = $request->get('status');

        $ordersQuery = Order::with('items')->latest();
        if ($statusFilter) {
            $ordersQuery->where('status', $statusFilter);
        }
        $recentOrders = $ordersQuery->take(10)->get();

        // Statistiques globales
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $totalOrdersCount = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $deliveredOrdersCount = Order::where('status', 'delivered')->count();
        $avgBasket = $totalOrdersCount > 0 ? (int) ($totalRevenue / $totalOrdersCount) : 0;

        $totalProductsCount = Product::count();
        $lowStockCount = Product::where('stock', '<=', 5)->count();
        $products = Product::with('category')->latest()->take(8)->get();
        $categories = Category::withCount('products')->get();

        return view('admin.dashboard', compact(
            'recentOrders',
            'totalRevenue',
            'totalOrdersCount',
            'pendingOrdersCount',
            'deliveredOrdersCount',
            'avgBasket',
            'totalProductsCount',
            'lowStockCount',
            'products',
            'categories',
            'statusFilter'
        ));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_delivery,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', "Statut de la commande {$order->order_number} mis à jour avec succès.");
    }

    public function updateProductStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'stock' => $request->stock,
        ]);

        return redirect()->back()->with('success', "Stock du produit {$product->name} mis à jour ({$request->stock} unités).");
    }
}
