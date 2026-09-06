<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));
        $categoryId = $request->get('category_id');
        $stockFilter = $request->get('stock_filter');
        $typeFilter = $request->get('type_filter'); // 'physical', 'digital', or null

        $query = Product::with('category')->latest();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($typeFilter === 'digital') {
            $query->where('product_type', 'digital');
        } elseif ($typeFilter === 'physical') {
            $query->where(function ($q) {
                $q->where('product_type', 'physical')->orWhereNull('product_type');
            });
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
            'digital' => Product::where('product_type', 'digital')->count(),
            'physical' => Product::where('product_type', '!=', 'digital')->orWhereNull('product_type')->count(),
            'low' => Product::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out' => Product::where('stock', '<=', 0)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'counts', 'search', 'categoryId', 'stockFilter', 'typeFilter'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $isDigital = $request->input('product_type') === 'digital';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:physical,digital',
            'digital_type' => 'nullable|string|max:50',
            'access_type' => 'nullable|in:file_download,external_link,video_player',
            'external_access_url' => 'nullable|url',
            'download_limit' => 'nullable|integer|min:1',
            'download_expiry_days' => 'nullable|integer|min:1',
            'price' => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'stock' => $isDigital ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'vendor_name' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:50',
            'badge' => 'nullable|string|max:50',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'is_flash_deal' => 'boolean',
            'is_popular' => 'boolean',
            'is_new' => 'boolean',
            'is_recommended' => 'boolean',
            'files.*' => 'nullable|file|max:512000', // max 500Mo par fichier
            'file_names.*' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['reference'] = !empty($validated['reference']) ? $validated['reference'] : 'REF-' . strtoupper(Str::random(6));
        $validated['stock'] = $isDigital ? 9999 : (int) ($validated['stock'] ?? 0);
        $validated['access_type'] = $validated['access_type'] ?: 'file_download';
        $validated['is_flash_deal'] = $request->boolean('is_flash_deal');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_recommended'] = $request->boolean('is_recommended');
        $validated['description'] = $validated['description'] ?? '';

        // Support d'image par défaut ou upload si l'URL n'est pas renseignée
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image_url'] = '/storage/' . $path;
        } elseif (empty($validated['image_url'])) {
            $validated['image_url'] = $isDigital 
                ? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80'
                : 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80';
        }

        if (!empty($validated['original_price']) && $validated['original_price'] > $validated['price']) {
            $validated['discount_percent'] = (int) round((($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100);
        } else {
            $validated['discount_percent'] = 0;
        }

        $product = Product::create($validated);

        // Traitement des fichiers attachés
        if ($isDigital && $request->hasFile('files')) {
            foreach ($request->file('files') as $idx => $uploadedFile) {
                if ($uploadedFile && $uploadedFile->isValid()) {
                    $path = $uploadedFile->store('digital_products', 'local');
                    $customName = $request->input("file_names.{$idx}") ?: pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    ProductFile::create([
                        'product_id' => $product->id,
                        'name' => $customName,
                        'file_path' => $path,
                        'file_name' => $uploadedFile->getClientOriginalName(),
                        'file_size' => $uploadedFile->getSize(),
                        'mime_type' => $uploadedFile->getClientMimeType(),
                        'sort_order' => (int) $idx,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', "Le produit \"{$product->name}\" a été créé avec succès.");
    }

    public function edit(Product $product)
    {
        $product->load('files');
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $isDigital = $request->input('product_type') === 'digital';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:physical,digital',
            'digital_type' => 'nullable|string|max:50',
            'access_type' => 'nullable|in:file_download,external_link,video_player',
            'external_access_url' => 'nullable|url',
            'download_limit' => 'nullable|integer|min:1',
            'download_expiry_days' => 'nullable|integer|min:1',
            'price' => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'stock' => $isDigital ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'vendor_name' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:50',
            'badge' => 'nullable|string|max:50',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'is_flash_deal' => 'boolean',
            'is_popular' => 'boolean',
            'is_new' => 'boolean',
            'is_recommended' => 'boolean',
            'files.*' => 'nullable|file|max:512000',
            'file_names.*' => 'nullable|string|max:255',
        ]);

        $validated['stock'] = $isDigital ? 9999 : (int) ($validated['stock'] ?? 0);
        $validated['access_type'] = $validated['access_type'] ?: 'file_download';
        $validated['is_flash_deal'] = $request->boolean('is_flash_deal');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_recommended'] = $request->boolean('is_recommended');
        $validated['description'] = $validated['description'] ?? '';

        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image_url'] = '/storage/' . $path;
        } elseif (empty($validated['image_url'])) {
            $validated['image_url'] = $product->image_url ?: ($isDigital 
                ? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80'
                : 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80');
        }

        if (!empty($validated['original_price']) && $validated['original_price'] > $validated['price']) {
            $validated['discount_percent'] = (int) round((($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100);
        } else {
            $validated['discount_percent'] = 0;
        }

        $product->update($validated);

        // Ajout de nouveaux fichiers
        if ($isDigital && $request->hasFile('files')) {
            $currentMaxSort = $product->files()->max('sort_order') ?? 0;
            foreach ($request->file('files') as $idx => $uploadedFile) {
                if ($uploadedFile && $uploadedFile->isValid()) {
                    $path = $uploadedFile->store('digital_products', 'local');
                    $customName = $request->input("file_names.{$idx}") ?: pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    ProductFile::create([
                        'product_id' => $product->id,
                        'name' => $customName,
                        'file_path' => $path,
                        'file_name' => $uploadedFile->getClientOriginalName(),
                        'file_size' => $uploadedFile->getSize(),
                        'mime_type' => $uploadedFile->getClientMimeType(),
                        'sort_order' => (int) ($currentMaxSort + $idx + 1),
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', "Le produit \"{$product->name}\" a été mis à jour.");
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;

        // Supprimer physiquement les fichiers privés associés
        foreach ($product->files as $file) {
            Storage::disk('local')->delete($file->file_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Le produit \"{$productName}\" a été supprimé.");
    }

    public function destroyFile(ProductFile $file)
    {
        $product = $file->product;
        Storage::disk('local')->delete($file->file_path);
        $file->delete();

        return redirect()->back()->with('success', "Le fichier \"{$file->name}\" a été retiré du produit.");
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $request->stock]);

        return redirect()->back()->with('success', "Stock mis à jour pour {$product->name} ({$request->stock} unités).");
    }

    public function destroyReview(\App\Models\ProductReview $review)
    {
        $reviewName = $review->customer_name;
        $review->delete();

        return redirect()->back()->with('success', "L'avis de \"{$reviewName}\" a été supprimé.");
    }
}
