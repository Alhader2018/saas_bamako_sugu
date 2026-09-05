<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    /**
     * Liste des catégories avec recherche et pagination.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('badge', 'like', "%{$search}%");
            });
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === '1');
        }

        $categories = $query->orderBy('display_order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        $totalCategories = Category::count();
        $featuredCount = Category::where('is_featured', true)->count();

        return view('admin.categories.index', compact('categories', 'totalCategories', 'featuredCount'));
    }

    /**
     * Formulaire de création d'une catégorie.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'icon' => 'nullable|string|max:50',
            'badge' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|max:10240',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        // Génération propre du slug si vide ou existant
        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_featured'] = $request->boolean('is_featured', true);
        $validated['display_order'] = (int) ($validated['display_order'] ?? 0);
        $validated['icon'] = $validated['icon'] ?: 'shopping-cart';

        // Gestion de l'image
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path = $request->file('image_file')->store('categories', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $category = Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', "La catégorie « {$category->name} » a été créée avec succès.");
    }

    /**
     * Formulaire d'édition d'une catégorie.
     */
    public function edit(Category $category)
    {
        $category->loadCount('products');
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie existante.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'icon' => 'nullable|string|max:50',
            'badge' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|max:10240',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['display_order'] = (int) ($validated['display_order'] ?? 0);
        $validated['icon'] = $validated['icon'] ?: 'shopping-cart';

        // Gestion de l'image
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path = $request->file('image_file')->store('categories', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', "La catégorie « {$category->name} » a été mise à jour.");
    }

    /**
     * Supprime une catégorie si elle ne contient aucun produit.
     */
    public function destroy(Category $category)
    {
        $productCount = $category->products()->count();

        if ($productCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', "Impossible de supprimer la catégorie « {$category->name} » car elle contient {$productCount} produit(s). Veuillez d'abord réassigner ou supprimer ces produits.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "La catégorie « {$name} » a été supprimée avec succès.");
    }
}
