<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_clean_canonical_tag_and_global_structured_data(): void
    {
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Exactly one canonical link tag pointing to production domain
        $response->assertSee('<link rel="canonical" href="https://harivanshisevakunj.com/">', false);

        // Primary meta robots directive
        $response->assertSee('<meta name="robots" content="index, follow">', false);

        // Open Graph meta
        $response->assertSee('<meta property="og:url" content="https://harivanshisevakunj.com/">', false);
        $response->assertSee('<meta property="og:site_name" content="Harivanshi Seva Kunj">', false);

        // Organization and WebSite JSON-LD
        $response->assertSee('"@type": "Organization"', false);
        $response->assertSee('"@type": "WebSite"', false);
        $response->assertSee('"urlTemplate": "https://harivanshisevakunj.com/products?q={search_term_string}"', false);

        // LocalBusiness schema should NOT be emitted when physical street address is null
        $response->assertDontSee('"streetAddress"', false);
    }

    public function test_catalogue_with_search_query_sets_noindex_and_canonicalizes_to_catalogue(): void
    {
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        $response = $this->get(route('products.index', ['q' => 'tulsi']));

        $response->assertStatus(200);

        // Internal search should have noindex, follow to prevent crawl waste and index bloat
        $response->assertSee('<meta name="robots" content="noindex, follow">', false);

        // Search canonical points back to clean catalogue
        $response->assertSee('<link rel="canonical" href="https://harivanshisevakunj.com/products">', false);
    }

    public function test_cart_page_is_marked_noindex_follow(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_published_product_detail_page_is_accessible_with_schema_and_breadcrumbs(): void
    {
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        $category = Category::create([
            'name'       => 'Tulsi Mala Seva',
            'slug'       => 'tulsi-mala-seva',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $media = \App\Models\Media::create([
            'title'     => 'Tulsi Kanthi Photo',
            'file_path' => 'media/2026/10/tulsi-kanthi.webp',
            'mime_type' => 'image/webp',
            'file_size' => 45000,
        ]);
        \Illuminate\Support\Facades\Storage::disk('public')->put('media/2026/10/tulsi-kanthi.webp', 'fake image content');

        $product = Product::create([
            'category_id'      => $category->id,
            'media_id'         => $media->id,
            'title'            => 'Vrindavan Tulsi Kanthi Mala',
            'slug'             => 'vrindavan-tulsi-kanthi-mala',
            'price'            => 250.00,
            'original_price'   => 350.00,
            'description'      => 'Authentic two-round Tulsi kanthi mala sanctified in Shri Dham Vrindavan.',
            'meta_description' => 'Pure Tulsi Kanthi Mala from Vrindavan Dham.',
            'badge'            => 'Sacred',
            'status'           => 'published',
            'is_active'        => true,
        ]);

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);

        // Heading & content
        $response->assertSee('Vrindavan Tulsi Kanthi Mala');
        $response->assertSee('₹250');
        $response->assertSee('₹350');
        $response->assertSee('Tulsi Mala Seva');

        // Breadcrumbs rendered on page
        $response->assertSee('Home');
        $response->assertSee('Shop');

        // Canonical URL
        $response->assertSee('<link rel="canonical" href="https://harivanshisevakunj.com/products/vrindavan-tulsi-kanthi-mala">', false);

        // Open graph type product & product extensions
        $response->assertSee('<meta property="og:type" content="product">', false);
        $response->assertSee('<meta property="product:price:amount" content="250.00">', false);
        $response->assertSee('<meta property="product:price:currency" content="INR">', false);
        $response->assertSee('<meta property="product:availability" content="instock">', false);

        // og:image uses actual production product image from public storage, NOT logo
        $response->assertSee('<meta property="og:image" content="https://harivanshisevakunj.com/storage/media/2026/10/tulsi-kanthi.webp">', false);
        $response->assertSee('<meta property="og:image:type" content="image/webp">', false);
        $response->assertDontSee('127.0.0.1/storage/media');
        $response->assertDontSee('localhost:8000/storage/media');

        // WhatsApp message has no raw link line but has clean friendly enquiry
        $response->assertSee(rawurlencode("Product: Vrindavan Tulsi Kanthi Mala"), false);
        $response->assertSee(rawurlencode("Price: ₹250"), false);
        $response->assertDontSee(rawurlencode("Product Link:"), false);
        $response->assertDontSee(rawurlencode("Link: https://"), false);

        // JSON-LD Product & BreadcrumbList schemas
        $response->assertSee('"@type": "Product"', false);
        $response->assertSee('"price": "250.00"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
    }

    public function test_unpublished_product_detail_page_returns_404(): void
    {
        $category = Category::create([
            'name'       => 'Draft Category',
            'slug'       => 'draft-category',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $draftProduct = Product::create([
            'category_id' => $category->id,
            'title'       => 'Draft Puja Item',
            'slug'        => 'draft-puja-item',
            'price'       => 100.00,
            'status'      => 'draft',
            'is_active'   => false,
        ]);

        $response = $this->get(route('products.show', $draftProduct->slug));

        $response->assertStatus(404);
    }

    public function test_product_card_links_to_individual_product_page(): void
    {
        $category = Category::create([
            'name'       => 'Braj Seva',
            'slug'       => 'braj-seva',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Sacred Braj Raj',
            'slug'        => 'sacred-braj-raj',
            'price'       => 150.00,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);

        // Standard HTML <a href> link to product detail page
        $response->assertSee(route('products.show', 'sacred-braj-raj'), false);
    }

    public function test_sitemap_xml_renders_canonical_urls_and_excludes_drafts(): void
    {
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        $category = Category::create([
            'name'       => 'Pooja Samagri',
            'slug'       => 'pooja-samagri',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $published = Product::create([
            'category_id' => $category->id,
            'title'       => 'Pure Yamuna Jal',
            'slug'        => 'pure-yamuna-jal',
            'price'       => 120.00,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $draft = Product::create([
            'category_id' => $category->id,
            'title'       => 'Unpublished Incense',
            'slug'        => 'unpublished-incense',
            'price'       => 50.00,
            'status'      => 'draft',
            'is_active'   => false,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');

        // Check canonical domain is used
        $response->assertSee('https://harivanshisevakunj.com/</loc>', false);
        $response->assertSee('https://harivanshisevakunj.com/products</loc>', false);
        $response->assertSee('https://harivanshisevakunj.com/products?category=pooja-samagri</loc>', false);
        $response->assertSee('https://harivanshisevakunj.com/products/pure-yamuna-jal</loc>', false);
        $response->assertSee('https://harivanshisevakunj.com/about</loc>', false);
        $response->assertSee('https://harivanshisevakunj.com/contact</loc>', false);
        $response->assertSee('https://harivanshisevakunj.com/blog</loc>', false);

        // Excludes drafts
        $response->assertDontSee('unpublished-incense');

        // Excludes localhost when production canonical URL is configured
        $response->assertDontSee('http://localhost:8000');
        $response->assertDontSee('http://127.0.0.1:8000');
    }

    public function test_robots_txt_disallows_admin_and_cart_and_references_sitemap(): void
    {
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');

        $content = $response->getContent();

        $this->assertStringContainsString('Disallow: /admin/', $content);
        $this->assertStringContainsString('Disallow: /cart', $content);
        $this->assertStringContainsString('Allow: /', $content);
        $this->assertStringContainsString('Sitemap: https://harivanshisevakunj.com/sitemap.xml', $content);
    }

    public function test_local_business_schema_rendered_when_physical_street_address_configured(): void
    {
        config([
            'seo.canonical_url' => 'https://harivanshisevakunj.com',
            'seo.business.street_address' => 'Shop 12, Raman Reti Road, Near Bankey Bihari Temple',
            'seo.business.geo.latitude' => '27.5800',
            'seo.business.geo.longitude' => '77.7000',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('"LocalBusiness"', false);
        $response->assertSee('"Store"', false);
        $response->assertSee('"streetAddress": "Shop 12, Raman Reti Road, Near Bankey Bihari Temple"', false);
        $response->assertSee('"latitude": "27.5800"', false);
    }

    public function test_confirmed_vrindavan_location_rendered_on_storefront(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Sunrakh Banger, Sobhri Kund Road, Radha Vatika Ke Paas, Vrindavan, Mathura - 281121');
        $response->assertSee('Sunrakh Banger, Vrindavan');
    }
}
