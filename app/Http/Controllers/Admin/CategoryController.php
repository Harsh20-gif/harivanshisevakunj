<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a flat listing of categories with search and filters.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q'));

        $query = Category::with('media')
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $categories = $query->paginate(20)->withQueryString();

        // Statistics
        $totalCategories  = Category::count();
        $activeCategories = Category::where('is_active', true)->count();

        return view('admin.categories.index', compact(
            'categories',
            'search',
            'totalCategories',
            'activeCategories'
        ));
    }

    /**
     * Show form to create a new flat category.
     */
    public function create(): View
    {
        $mediaAssets = Media::latest()->take(60)->get();

        return view('admin.categories.create', compact('mediaAssets'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'slug' => $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('name')),
        ]);

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'slug'              => ['required', 'string', 'max:255', 'unique:categories,slug'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'media_id'          => ['nullable', 'exists:media,id'],
            'is_active'         => ['nullable', 'boolean'],
            'sort_order'        => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Category name is required.',
            'slug.unique'   => 'A category with this URL slug already exists. Please choose a unique slug.',
        ]);

        $category = Category::create([
            'name'              => trim($validated['name']),
            'slug'              => $validated['slug'],
            'short_description' => $validated['short_description'] ?? null,
            'media_id'          => $validated['media_id'] ?? null,
            'is_active'         => $request->boolean('is_active', true),
            'sort_order'        => (int) ($validated['sort_order'] ?? 0),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$category->name}' has been created successfully.");
    }

    /**
     * Show form to edit an existing category.
     */
    public function edit(Category $category): View
    {
        $mediaAssets = Media::latest()->take(60)->get();

        return view('admin.categories.edit', compact('category', 'mediaAssets'));
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->merge([
            'slug' => $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('name')),
        ]);

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'slug'              => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'media_id'          => ['nullable', 'exists:media,id'],
            'is_active'         => ['nullable', 'boolean'],
            'sort_order'        => ['nullable', 'integer', 'min:0'],
        ]);

        $category->update([
            'name'              => trim($validated['name']),
            'slug'              => $validated['slug'],
            'short_description' => $validated['short_description'] ?? null,
            'media_id'          => $validated['media_id'] ?? null,
            'is_active'         => $request->boolean('is_active', true),
            'sort_order'        => (int) ($validated['sort_order'] ?? 0),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$category->name}' updated successfully.");
    }

    /**
     * Safely delete a category if not assigned to any products.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->hasAssociatedProducts()) {
            $count = $category->allProductsCount();
            return back()->with('error', "Cannot delete category '{$category->name}' because it has {$count} associated product(s). Please reassign or delete the products first.");
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$categoryName}' has been safely deleted.");
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(Category $category): RedirectResponse
    {
        $category->is_active = ! $category->is_active;
        $category->save();

        $statusLabel = $category->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Category '{$category->name}' has been {$statusLabel}.");
    }
}
