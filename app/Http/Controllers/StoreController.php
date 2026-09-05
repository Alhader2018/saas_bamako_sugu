<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_featured', true)->orderBy('display_order')->get();
        $flashDeals = Product::flashDeals()->with('category')->take(4)->get();
        $popularProducts = Product::popular()->with('category')->take(8)->get();
        $newArrivals = Product::newArrivals()->with('category')->take(4)->get();
        $recommendedProducts = Product::recommended()->with('category')->take(4)->get();

        $partnerShops = [
            [
                'name' => 'Grand Moulin du Mali',
                'category' => 'Supermarché & Épicerie',
                'location' => 'Zone Industrielle, Bamako',
                'badge' => 'Certifié BKO',
                'image' => 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'BKO Tech Store',
                'category' => 'High-Tech & Smartphones',
                'location' => 'ACI 2000, Bamako',
                'badge' => 'Garantie 1 An',
                'image' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'Atelier Modibo Bazin',
                'category' => 'Mode & Tissus Bazin',
                'location' => 'Badalabougou, Bamako',
                'badge' => 'Artisanat Local',
                'image' => 'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'Vergers & Maraîchers du Mali',
                'category' => 'Fruits & Légumes Frais',
                'location' => 'Baguineda / Médine, Bamako',
                'badge' => 'Frais du Jour',
                'image' => 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=400&q=80',
            ],
        ];

        return view('store.index', compact(
            'categories',
            'flashDeals',
            'popularProducts',
            'newArrivals',
            'recommendedProducts',
            'partnerShops'
        ));
    }

    public function catalog(Request $request)
    {
        $categories = Category::orderBy('display_order')->get();
        $query = Product::query()->with('category');

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = Category::where('slug', $request->category)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->get('sort', 'popular');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('is_popular', 'desc')->orderBy('rating', 'desc'),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('store.catalog', compact('products', 'categories', 'activeCategory', 'sort'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->with('category')->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('store.product', compact('product', 'relatedProducts'));
    }

    public function checkout()
    {
        return view('store.checkout');
    }

    public function buyNow(Request $request, Product $product)
    {
        $quantity = $product->isDigital() ? 1 : max(1, (int) $request->input('quantity', 1));
        \App\Services\CartService::add($product->id, $quantity);

        return redirect()->route('checkout');
    }
}

