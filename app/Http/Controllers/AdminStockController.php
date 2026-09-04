<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminStockController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = trim((string) $request->get('search'));

        $query = Product::with('category')->orderBy('stock', 'asc');

        if ($filter === 'out') {
            $query->where('stock', '<=', 0);
        } elseif ($filter === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', 5);
        } elseif ($filter === 'available') {
            $query->where('stock', '>', 5);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(25)->withQueryString();

        $counts = [
            'all' => Product::count(),
            'low' => Product::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out' => Product::where('stock', '<=', 0)->count(),
            'available' => Product::where('stock', '>', 5)->count(),
        ];

        $totalStockUnits = Product::sum('stock');
        $totalStockValue = Product::selectRaw('SUM(stock * price) as val')->value('val') ?? 0;

        return view('admin.stock.index', compact('products', 'counts', 'filter', 'search', 'totalStockUnits', 'totalStockValue'));
    }

    public function quickUpdate(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $request->stock]);

        return redirect()->back()->with('success', "Stock de \"{$product->name}\" ajusté à {$request->stock} unités.");
    }
}
