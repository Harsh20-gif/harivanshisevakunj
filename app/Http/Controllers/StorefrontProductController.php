<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontProductController extends Controller
{
    /**
     * Display the storefront public product catalogue with flat category filtering.
     */
    public function index(Request $request): View
    {
        $search       = trim((string) $request->input('q'));
        $categorySlug = trim((string) $request->input('category'));

        // Query published products only
        $query = Product::published()->with(['category', 'media'])->latest();

        $selectedCategory = null;

        if ($categorySlug) {
            $selectedCategory = Category::active()->where('slug', $categorySlug)->first();

            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('meta_description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(16)->withQueryString();

        // Active flat categories with published product count for navigation
        $categories = Category::active()
            ->with('media')
            ->withCount(['products' => fn($p) => $p->published()])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Shop', 'url' => route('products.index')],
        ];

        if ($selectedCategory) {
            $breadcrumbs[] = [
                'name' => $selectedCategory->name,
                'url'  => route('products.index', ['category' => $selectedCategory->slug]),
            ];
        }

        return view('products.index', compact(
            'products',
            'categories',
            'selectedCategory',
            'search',
            'categorySlug',
            'breadcrumbs'
        ));
    }

    /**
     * Display a published individual product detail page.
     * Throws 404 for drafts, inactive, or non-existent items to prevent crawl waste.
     */
    public function show(string $slug): View
    {
        $product = Product::published()
            ->with(['category', 'media'])
            ->where('slug', $slug)
            ->firstOrFail();

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Shop', 'url' => route('products.index')],
        ];

        if ($product->category) {
            $breadcrumbs[] = [
                'name' => $product->category->name,
                'url'  => route('products.index', ['category' => $product->category->slug]),
            ];
        }

        $breadcrumbs[] = [
            'name' => $product->title,
            'url'  => route('products.show', $product->slug),
        ];

        $relatedProducts = Product::published()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->with('media')
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'breadcrumbs'));
    }
}

