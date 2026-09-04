<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));
        $categoryId = $request->get('category_id');
        $stockFilter = $request->get('stock_filter');

        $query = Product::with('category')->latest();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($stockFilter === 'out') {
            $query->where('stock', '<=', 0);
        } elseif ($stockFilter === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', 5);
        } elseif ($stockFilter === 'in_stock') {
            $query->where('stock', '>', 5);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        $counts = [
            'all' => Product::count(),
            'low' => Product::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out' => Product::where('stock', '<=', 0)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'counts', 'search', 'categoryId', 'stockFilter'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'vendor_name' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:50',
            'badge' => 'nullable|string|max:50',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'is_flash_deal' => 'boolean',
            'is_popular' => 'boolean',
            'is_new' => 'boolean',
            'is_recommended' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['reference'] = $validated['reference'] ?: 'REF-' . strtoupper(Str::random(6));
        $validated['is_flash_deal'] = $request->boolean('is_flash_deal');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_recommended'] = $request->boolean('is_recommended');

        if (!empty($validated['original_price']) && $validated['original_price'] > $validated['price']) {
            $validated['discount_percent'] = (int) round((($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100);
        } else {
            $validated['discount_percent'] = 0;
        }

        $product = Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', "Le produit \"{$product->name}\" a été créé avec succès.");
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'vendor_name' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:50',
            'badge' => 'nullable|string|max:50',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'is_flash_deal' => 'boolean',
            'is_popular' => 'boolean',
            'is_new' => 'boolean',
            'is_recommended' => 'boolean',
        ]);

        $validated['is_flash_deal'] = $request->boolean('is_flash_deal');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_recommended'] = $request->boolean('is_recommended');

        if (!empty($validated['original_price']) && $validated['original_price'] > $validated['price']) {
            $validated['discount_percent'] = (int) round((($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100);
        } else {
            $validated['discount_percent'] = 0;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', "Le produit \"{$product->name}\" a été mis à jour.");
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Le produit \"{$productName}\" a été supprimé.");
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $request->stock]);

        return redirect()->back()->with('success', "Stock mis à jour pour {$product->name} ({$request->stock} unités).");
    }
}
