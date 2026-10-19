<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Support\Seo;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap for indexable canonical pages.
     */
    public function index(): Response
    {
        $urls = [];

        // 1. Homepage (highest priority, daily)
        $latestProduct = Product::published()->latest('updated_at')->first();
        $urls[] = [
            'loc'        => Seo::canonicalBase() . '/',
            'lastmod'    => ($latestProduct?->updated_at ?? now())->tz('UTC')->toAtomString(),
            'changefreq' => 'daily',
            'priority'   => '1.0',
        ];

        // 2. Catalogue Index (daily)
        $urls[] = [
            'loc'        => Seo::url('products'),
            'lastmod'    => ($latestProduct?->updated_at ?? now())->tz('UTC')->toAtomString(),
            'changefreq' => 'daily',
            'priority'   => '0.9',
        ];

        // 3. Active Categories with Published Products (weekly)
        $categories = Category::active()
            ->whereHas('products', fn($p) => $p->published())
            ->orderBy('sort_order')
            ->get();

        foreach ($categories as $category) {
            $urls[] = [
                'loc'        => Seo::url('products', ['category' => $category->slug]),
                'lastmod'    => $category->updated_at?->tz('UTC')->toAtomString() ?? now()->tz('UTC')->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.8',
            ];
        }

        // 4. Published Individual Products (weekly)
        // Keep draft, deleted, private, and unpublished products out of the sitemap
        $products = Product::published()
            ->select(['id', 'slug', 'updated_at'])
            ->latest('updated_at')
            ->get();

        foreach ($products as $product) {
            $urls[] = [
                'loc'        => Seo::url('products/' . $product->slug),
                'lastmod'    => $product->updated_at?->tz('UTC')->toAtomString() ?? now()->tz('UTC')->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.8',
            ];
        }

        // 5. Informational Public Pages
        $urls[] = [
            'loc'        => Seo::url('about'),
            'lastmod'    => now()->startOfMonth()->tz('UTC')->toAtomString(),
            'changefreq' => 'monthly',
            'priority'   => '0.6',
        ];
        $urls[] = [
            'loc'        => Seo::url('contact'),
            'lastmod'    => now()->startOfMonth()->tz('UTC')->toAtomString(),
            'changefreq' => 'monthly',
            'priority'   => '0.6',
        ];
        $urls[] = [
            'loc'        => Seo::url('blog'),
            'lastmod'    => now()->startOfMonth()->tz('UTC')->toAtomString(),
            'changefreq' => 'weekly',
            'priority'   => '0.7',
        ];

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
