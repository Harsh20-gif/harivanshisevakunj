@props([
    'title'       => null,
    'description' => null,
    'canonical'   => null,
    'robots'      => null,
    'ogType'      => 'website',
    'ogTitle'     => null,
    'ogDescription' => null,
    'ogImage'     => null,
    'breadcrumbs' => null,
    'product'     => null,
])

@php
    use App\Support\Seo;

    $resolvedTitle = $title ?: (View::hasSection('title') ? View::getSection('title') : config('seo.default_title'));
    $resolvedDescription = $description ?: (View::hasSection('meta_description') ? View::getSection('meta_description') : config('seo.default_description'));
    $resolvedCanonical = $canonical ?: (View::hasSection('canonical') ? View::getSection('canonical') : Seo::canonicalUrl());
    $resolvedRobots = $robots ?: (View::hasSection('robots') ? View::getSection('robots') : Seo::metaRobots());

    $resolvedOgTitle = $ogTitle ?: (View::hasSection('og_title') ? View::getSection('og_title') : $resolvedTitle);
    $resolvedOgDesc  = $ogDescription ?: (View::hasSection('og_description') ? View::getSection('og_description') : $resolvedDescription);
    // Resolve actual product image when on product detail page
    $productImage = null;
    if (!empty($product) && $product instanceof \App\Models\Product && !empty($product->image_url)) {
        $productImage = Seo::imageUrl($product->image_url);
    }

    $resolvedOgImage = $ogImage
        ?: (View::hasSection('og_image')
            ? Seo::imageUrl(View::getSection('og_image'))
            : ($productImage ?: Seo::imageUrl(config('seo.default_image', 'images/categories/logo.png'))));
    $resolvedOgType  = View::hasSection('og_type') ? View::getSection('og_type') : $ogType;

    // Detect image mime type for og:image:type
    $ogImageExt = strtolower(pathinfo(parse_url($resolvedOgImage, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
    $mimeMap = [
        'webp' => 'image/webp',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
    ];
    $resolvedOgImageMime = $mimeMap[$ogImageExt] ?? null;

    // Structured data
    $orgSchema = Seo::organizationSchema();
    $webSchema = Seo::websiteSchema();
    $localSchema = Seo::localBusinessSchema();
    $breadSchema = !empty($breadcrumbs) && is_array($breadcrumbs) ? Seo::breadcrumbSchema($breadcrumbs) : null;
    $productSchema = (!empty($product) && $product instanceof \App\Models\Product) ? Seo::productSchema($product) : null;
@endphp

{{-- Standard Primary SEO Meta --}}
<title>{{ $resolvedTitle }}</title>
<meta name="description" content="{{ $resolvedDescription }}">
<meta name="robots" content="{{ $resolvedRobots }}">
<link rel="canonical" href="{{ $resolvedCanonical }}">

{{-- Open Graph Meta --}}
<meta property="og:site_name" content="{{ config('seo.site_name', 'Harivanshi Seva Kunj') }}">
<meta property="og:type" content="{{ $resolvedOgType }}">
<meta property="og:title" content="{{ $resolvedOgTitle }}">
<meta property="og:description" content="{{ $resolvedOgDesc }}">
<meta property="og:url" content="{{ $resolvedCanonical }}">
<meta property="og:image" content="{{ $resolvedOgImage }}">
<meta property="og:image:secure_url" content="{{ $resolvedOgImage }}">
@if($resolvedOgImageMime)
<meta property="og:image:type" content="{{ $resolvedOgImageMime }}">
@endif
<meta property="og:image:alt" content="{{ $resolvedOgTitle }}">
<meta property="og:locale" content="en_IN">

{{-- Open Graph Product Specific Tags --}}
@if($resolvedOgType === 'product' && !empty($product) && $product instanceof \App\Models\Product)
<meta property="product:price:amount" content="{{ number_format((float) $product->price, 2, '.', '') }}">
<meta property="product:price:currency" content="INR">
<meta property="product:availability" content="{{ $product->is_active ? 'instock' : 'oos' }}">
@if($product->category)
<meta property="product:category" content="{{ $product->category->name }}">
@endif
@endif

{{-- Twitter Card Meta --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $resolvedOgTitle }}">
<meta name="twitter:description" content="{{ $resolvedOgDesc }}">
<meta name="twitter:image" content="{{ $resolvedOgImage }}">

{{-- Organization Schema (Global) --}}
<script type="application/ld+json">
{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>

{{-- WebSite Schema with Sitelinks Searchbox (Global) --}}
<script type="application/ld+json">
{!! json_encode($webSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>

{{-- Optional LocalBusiness Schema (Rendered ONLY when genuine physical address is configured) --}}
@if($localSchema)
<script type="application/ld+json">
{!! json_encode($localSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

{{-- Breadcrumbs Schema --}}
@if($breadSchema)
<script type="application/ld+json">
{!! json_encode($breadSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

{{-- Product Schema --}}
@if($productSchema)
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
