<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters and search.
     */
    public function index(Request $request): View
    {
        $search     = trim((string) $request->input('q'));
        $categoryId = $request->input('category_id');
        $status     = $request->input('status');

        $query = Product::with(['category', 'media'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('meta_description', 'like', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($status)) {
            if ($status === 'published') {
                $query->where('status', 'published')->where('is_active', true);
            } elseif ($status === 'draft') {
                $query->where(function ($q) {
                    $q->where('status', 'draft')->orWhere('is_active', false);
                });
            }
        }

        $products = $query->paginate(20)->withQueryString();

        // Data for category filter dropdown
        $categories = Category::orderBy('name')->get();

        // Statistics
        $totalProducts     = Product::count();
        $publishedProducts = Product::where('status', 'published')->where('is_active', true)->count();
        $draftProducts     = $totalProducts - $publishedProducts;

        return view('admin.products.index', compact(
            'products',
            'search',
            'categoryId',
            'status',
            'categories',
            'totalProducts',
            'publishedProducts',
            'draftProducts'
        ));
    }

    /**
     * Show form to add a new product.
     */
    public function create(): View
    {
        $categories  = Category::orderBy('name')->get();
        $mediaAssets = Media::latest()->take(60)->get();

        return view('admin.products.create', compact('categories', 'mediaAssets'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'slug' => $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title')),
        ]);

        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['required', 'string', 'max:255', 'unique:products,slug'],
            'category_id'      => ['required', 'exists:categories,id'],
            'media_id'         => ['nullable', 'exists:media,id'],
            'price'            => ['required', 'numeric', 'min:0'],
            'original_price'   => ['nullable', 'numeric', 'min:0'],
            'description'      => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'badge'            => ['nullable', 'string', 'max:50'],
            'rating'           => ['nullable', 'numeric', 'min:1', 'max:5'],
            'reviews_count'    => ['nullable', 'integer', 'min:0'],
            'status'           => ['required', 'in:published,draft'],
            'is_active'        => ['nullable', 'boolean'],
        ], [
            'title.required'       => 'Product title is required.',
            'slug.unique'          => 'A product with this URL slug already exists. Please choose a unique title or slug.',
            'category_id.required' => 'Please select a category for this product.',
            'category_id.exists'   => 'The selected category does not exist.',
            'price.required'       => 'Product seva price is required.',
        ]);

        $category = Category::find($validated['category_id']);
        if (! $category) {
            return back()->withInput()->withErrors(['category_id' => 'Invalid category selected.']);
        }

        $product = Product::create([
            'title'            => trim($validated['title']),
            'slug'             => $validated['slug'],
            'category_id'      => $category->id,
            'media_id'         => $validated['media_id'] ?? null,
            'price'            => $validated['price'],
            'original_price'   => $validated['original_price'] ?? null,
            'description'      => $validated['description'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'badge'            => $validated['badge'] ?? null,
            'rating'           => $validated['rating'] ?? 4.8,
            'reviews_count'    => $validated['reviews_count'] ?? 124,
            'status'           => $validated['status'],
            'is_active'        => $request->boolean('is_active', true) && ($validated['status'] === 'published'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', "Product '{$product->title}' has been created successfully.");
    }

    /**
     * Show form to edit an existing product.
     */
    public function edit(Product $product): View
    {
        $categories  = Category::orderBy('name')->get();
        $mediaAssets = Media::latest()->take(60)->get();

        return view('admin.products.edit', compact('product', 'categories', 'mediaAssets'));
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->merge([
            'slug' => $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title')),
        ]);

        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'category_id'      => ['required', 'exists:categories,id'],
            'media_id'         => ['nullable', 'exists:media,id'],
            'price'            => ['required', 'numeric', 'min:0'],
            'original_price'   => ['nullable', 'numeric', 'min:0'],
            'description'      => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'badge'            => ['nullable', 'string', 'max:50'],
            'rating'           => ['nullable', 'numeric', 'min:1', 'max:5'],
            'reviews_count'    => ['nullable', 'integer', 'min:0'],
            'status'           => ['required', 'in:published,draft'],
            'is_active'        => ['nullable', 'boolean'],
        ]);

        $category = Category::find($validated['category_id']);
        if (! $category) {
            return back()->withInput()->withErrors(['category_id' => 'Invalid category selected.']);
        }

        $product->update([
            'title'            => trim($validated['title']),
            'slug'             => $validated['slug'],
            'category_id'      => $category->id,
            'media_id'         => $validated['media_id'] ?? null,
            'price'            => $validated['price'],
            'original_price'   => $validated['original_price'] ?? null,
            'description'      => $validated['description'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'badge'            => $validated['badge'] ?? null,
            'rating'           => $validated['rating'] ?? ($product->rating ?? 4.8),
            'reviews_count'    => $validated['reviews_count'] ?? ($product->reviews_count ?? 124),
            'status'           => $validated['status'],
            'is_active'        => $request->boolean('is_active', true) && ($validated['status'] === 'published'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', "Product '{$product->title}' has been updated successfully.");
    }

    /**
     * Safely delete a product (never deletes shared media).
     */
    public function destroy(Product $product): RedirectResponse
    {
        $title = $product->title;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Product '{$title}' has been deleted successfully.");
    }

    /**
     * Toggle published / active status.
     */
    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->is_active = ! $product->is_active;
        if (! $product->is_active) {
            $product->status = 'draft';
        } else {
            $product->status = 'published';
        }
        $product->save();

        $label = $product->is_active ? 'published' : 'moved to drafts';

        return back()->with('success', "Product '{$product->title}' has been {$label}.");
    }
}
