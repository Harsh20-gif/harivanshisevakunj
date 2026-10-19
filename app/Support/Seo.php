<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Str;

class Seo
{
    /**
     * Get the configured canonical base URL (no trailing slash).
     * Falls back to APP_URL in local dev, and respects CANONICAL_URL in production.
     */
    public static function canonicalBase(): string
    {
        $base = config('seo.canonical_url');
        if (empty($base)) {
            $base = config('app.url');
        }
        if (empty($base)) {
            $base = 'http://127.0.0.1:8000';
        }
        return rtrim((string) $base, '/');
    }

    /**
     * Build an absolute canonical URL using the canonical base domain.
     */
    public static function url(string $path = '', array $query = []): string
    {
        $base = self::canonicalBase();
        $cleanPath = '/' . ltrim($path, '/');

        // Normalise root path
        if ($cleanPath === '/') {
            $url = $base . '/';
        } else {
            $url = $base . $cleanPath;
        }

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    /**
     * Resolve an absolute canonical image URL for Open Graph, Twitter cards, and Schema.org.
     * Ensures production canonical domain is used, re-basing local paths/hosts onto canonicalBase.
     */
    public static function imageUrl(?string $path = null): string
    {
        if (empty($path)) {
            $path = config('seo.default_image', 'images/categories/logo.png');
        }

        // If path is a full URL, parse path to re-base it onto the canonical domain
        if (Str::startsWith($path, ['http://', 'https://'])) {
            $parts = parse_url($path);
            $cleanPath = $parts['path'] ?? '/';
            return self::url($cleanPath);
        }

        return self::url($path);
    }

    /**
     * Determine the canonical URL for the current request or an explicit path/URL.
     */
    public static function canonicalUrl(?string $explicit = null): string
    {
        if ($explicit !== null && $explicit !== '') {
            if (Str::startsWith($explicit, ['http://', 'https://'])) {
                // If it already matches canonicalBase scheme/host or needs path extraction
                $parts = parse_url($explicit);
                $path = $parts['path'] ?? '/';
                $query = [];
                if (!empty($parts['query'])) {
                    parse_str($parts['query'], $query);
                }
                return self::url($path, $query);
            }
            return self::url($explicit);
        }

        $request = request();

        if ($request->routeIs('home') || $request->path() === '/') {
            return self::canonicalBase() . '/';
        }

        if ($request->routeIs('about')) {
            return self::url('about');
        }

        if ($request->routeIs('cart.*')) {
            return self::url('cart');
        }

        if ($request->routeIs('products.show')) {
            $slug = $request->route('slug') ?? ($request->route('product')?->slug ?? '');
            return self::url('products/' . $slug);
        }

        if ($request->routeIs('products.index')) {
            // If category filter is present and no search term, keep category in canonical URL
            if ($request->filled('category') && !$request->filled('q')) {
                $categorySlug = $request->query('category');
                $query = ['category' => $categorySlug];

                // If page > 1, preserve page
                if ($request->filled('page') && (int) $request->query('page') > 1) {
                    $query['page'] = (int) $request->query('page');
                }

                return self::url('products', $query);
            }

            // Internal search results or standard shop index should canonicalize to the main catalogue
            $query = [];
            if (!$request->filled('q') && $request->filled('page') && (int) $request->query('page') > 1) {
                $query['page'] = (int) $request->query('page');
            }

            return self::url('products', $query);
        }

        // Generic fallback for any other public route: strip tracking query parameters
        return self::url($request->path());
    }

    /**
     * Determine robots directive (index/noindex, follow/nofollow).
     */
    public static function metaRobots(?string $explicit = null): string
    {
        if ($explicit !== null && $explicit !== '') {
            return $explicit;
        }

        $request = request();

        // Admin section and login: never index
        if ($request->is('admin*') || $request->routeIs('admin.*')) {
            return 'noindex, nofollow';
        }

        // Cart and checkout: noindex, follow
        if ($request->routeIs('cart.*') || $request->is('cart*')) {
            return 'noindex, follow';
        }

        // Internal search results: noindex, follow (prevents crawl waste and search result indexation)
        if ($request->filled('q')) {
            return 'noindex, follow';
        }

        return 'index, follow';
    }

    /**
     * Generate Schema.org Organization structured data.
     */
    public static function organizationSchema(): array
    {
        $base = self::canonicalBase();

        return [
            '@context'     => 'https://schema.org',
            '@type'        => 'Organization',
            '@id'          => $base . '/#organization',
            'name'         => config('seo.site_name', 'Harivanshi Seva Kunj'),
            'founder'      => [
                '@type' => 'Person',
                'name'  => config('seo.business.proprietor', 'Uma Harivanshi'),
            ],
            'url'          => $base,
            'logo'         => [
                '@type' => 'ImageObject',
                'url'   => self::url('images/categories/logo.png'),
            ],
            'contactPoint' => [
                '@type'             => 'ContactPoint',
                'telephone'         => config('seo.business.telephone', '+91 92860 70355'),
                'contactType'       => 'customer service',
                'availableLanguage' => ['Hindi', 'English'],
            ],
            'sameAs'       => array_values(array_filter([
                config('seo.social.instagram'),
                config('seo.social.whatsapp'),
            ])),
        ];
    }

    /**
     * Generate Schema.org WebSite structured data with Sitelinks Searchbox.
     */
    public static function websiteSchema(): array
    {
        $base = self::canonicalBase();

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            '@id'             => $base . '/#website',
            'name'            => config('seo.site_name', 'Harivanshi Seva Kunj'),
            'url'             => $base,
            'publisher'       => [
                '@id' => $base . '/#organization',
            ],
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => $base . '/products?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * Generate Schema.org BreadcrumbList structured data.
     *
     * @param array<int, array{name: string, url: string|null}> $items
     */
    public static function breadcrumbSchema(array $items): array
    {
        $elements = [];
        $position = 1;

        foreach ($items as $item) {
            $entry = [
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $item['name'],
            ];

            if (!empty($item['url'])) {
                $entry['item'] = self::canonicalUrl($item['url']);
            }

            $elements[] = $entry;
        }

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * Generate Schema.org Product structured data from real database record.
     * Never fabricates reviews or ratings.
     */
    public static function productSchema(mixed $product): ?array
    {
        if (!($product instanceof Product)) {
            return null;
        }
        $base = self::canonicalBase();
        $productUrl = self::url('products/' . $product->slug);

        $image = self::imageUrl($product->image_url);

        $description = $product->meta_description ?: strip_tags((string) $product->description);
        if (empty($description)) {
            $description = $product->title . ' from Harivanshi Seva Kunj in Shri Dham Vrindavan.';
        }

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            '@id'         => $productUrl . '#product',
            'name'        => $product->title,
            'description' => trim($description),
            'url'         => $productUrl,
            'image'       => [$image],
            'brand'       => [
                '@type' => 'Brand',
                'name'  => config('seo.site_name', 'Harivanshi Seva Kunj'),
            ],
            'offers'      => [
                '@type'         => 'Offer',
                'priceCurrency' => 'INR',
                'price'         => number_format((float) $product->price, 2, '.', ''),
                'availability'  => $product->is_active ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url'           => $productUrl,
                'seller'        => [
                    '@id' => $base . '/#organization',
                ],
            ],
        ];

        if ($product->category) {
            $schema['category'] = $product->category->name;
        }

        return $schema;
    }

    /**
     * Generate Schema.org LocalBusiness structured data ONLY if genuine physical shop address is configured.
     * Returns null if street address has not yet been provided by the business owner.
     */
    public static function localBusinessSchema(): ?array
    {
        $streetAddress = config('seo.business.street_address');

        // Strict guard: Do not output LocalBusiness schema with missing/fake street address.
        if (empty($streetAddress)) {
            return null;
        }

        $base = self::canonicalBase();
        $business = config('seo.business');

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => ['Store', 'LocalBusiness'],
            '@id'         => $base . '/#localbusiness',
            'name'        => $business['name'] ?? 'Harivanshi Seva Kunj',
            'founder'     => [
                '@type' => 'Person',
                'name'  => $business['proprietor'] ?? 'Uma Harivanshi',
            ],
            'url'         => $base,
            'telephone'   => $business['telephone'] ?? null,
            'email'       => $business['email'] ?? null,
            'priceRange'  => $business['price_range'] ?? '₹₹',
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $streetAddress,
                'addressLocality' => $business['address_locality'] ?? 'Vrindavan',
                'addressRegion'   => $business['address_region'] ?? 'Uttar Pradesh',
                'postalCode'      => $business['postal_code'] ?? '281121',
                'addressCountry'  => $business['address_country'] ?? 'IN',
            ],
        ];

        if (!empty($business['geo']['latitude']) && !empty($business['geo']['longitude'])) {
            $schema['geo'] = [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $business['geo']['latitude'],
                'longitude' => $business['geo']['longitude'],
            ];
        }

        if (!empty($business['opening_hours'])) {
            $schema['openingHours'] = $business['opening_hours'];
        }

        return $schema;
    }
}
