<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorefrontCatalogueTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_catalogue_page_is_publicly_accessible(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('shop-category-bar');
        $response->assertSee('shop-category-item');
        $response->assertDontSee('Browse Seva Categories');
        $response->assertDontSee('catalogue-hero');
    }

    public function test_only_published_products_are_visible_on_storefront(): void
    {
        $category = Category::create(['name' => 'Tulsi Mala', 'slug' => 'tulsi-mala', 'is_active' => true]);

        // Published product
        Product::create([
            'category_id' => $category->id,
            'title'       => 'Pavitra Tulsi Kanthi 108',
            'slug'        => 'pavitra-tulsi-kanthi-108',
            'price'       => 249,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        // Draft product
        Product::create([
            'category_id' => $category->id,
            'title'       => 'Unpublished Rare Mala',
            'slug'        => 'unpublished-rare-mala',
            'price'       => 999,
            'status'      => 'draft',
            'is_active'   => false,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Pavitra Tulsi Kanthi 108');
        $response->assertDontSee('Unpublished Rare Mala');
    }

    public function test_only_active_categories_are_shown_in_catalogue_navigation(): void
    {
        Category::create(['name' => 'Braj Raj', 'slug' => 'braj-raj', 'is_active' => true]);
        Category::create(['name' => 'Hidden Secret Seva', 'slug' => 'hidden-secret-seva', 'is_active' => false]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Braj Raj');
        $response->assertDontSee('Hidden Secret Seva');
    }

    public function test_filter_by_category_shows_assigned_products(): void
    {
        $rajCategory = Category::create(['name' => 'Raj', 'slug' => 'raj', 'is_active' => true]);
        $jalCategory = Category::create(['name' => 'Jal', 'slug' => 'jal', 'is_active' => true]);

        Product::create([
            'category_id' => $rajCategory->id,
            'title'       => 'Sacred Black Raj Churna',
            'slug'        => 'sacred-black-raj-churna',
            'price'       => 110,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        Product::create([
            'category_id' => $jalCategory->id,
            'title'       => 'Pavitra Yamuna Jal Bottle',
            'slug'        => 'pavitra-yamuna-jal-bottle',
            'price'       => 51,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        // Filter by category 'raj'
        $response = $this->get(route('products.index', ['category' => 'raj']));

        $response->assertStatus(200);
        $response->assertSee('Sacred Black Raj Churna');
        $response->assertDontSee('Pavitra Yamuna Jal Bottle');
    }

    public function test_search_filter_returns_matching_published_products(): void
    {
        $category = Category::create(['name' => 'Puja', 'slug' => 'puja', 'is_active' => true]);

        Product::create([
            'category_id' => $category->id,
            'title'       => 'Gau Seva Chandan Dhoop',
            'slug'        => 'gau-seva-chandan-dhoop',
            'price'       => 150,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'title'       => 'Pure Brass Bell Ghanti',
            'slug'        => 'pure-brass-bell-ghanti',
            'price'       => 350,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->get(route('products.index', ['q' => 'Chandan']));

        $response->assertStatus(200);
        $response->assertSee('Gau Seva Chandan Dhoop');
        $response->assertDontSee('Pure Brass Bell Ghanti');
    }

    public function test_homepage_displays_active_categories_and_published_products(): void
    {
        $media = Media::create([
            'title'     => 'Tulsi Banner',
            'file_path' => 'media/2026/10/banner.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1024,
        ]);

        $category = Category::create([
            'name'       => 'Braj Raj Dham',
            'slug'       => 'braj-raj-dham',
            'media_id'   => $media->id,
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'category_id' => $category->id,
            'title'       => 'Shri Radha Vallabh Charan Raj',
            'slug'        => 'shri-radha-vallabh-charan-raj',
            'price'       => 210,
            'badge'       => 'Sacred',
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Braj Raj Dham');
        $response->assertSee('Shri Radha Vallabh Charan Raj');
        $response->assertSee('shop-category-scroll');
        $response->assertSee('shop-category-list');
        $response->assertSee('shop-category-circle');
        $response->assertSee(route('products.index', ['category' => 'braj-raj-dham']));
        $response->assertSee(route('products.index'));

        // Slider original images only — no text, buttons, badges or overlays
        $response->assertSee('hero-slider__img');
        $response->assertSee('lalju.png');
        $response->assertSee('gurudev.png');
        $response->assertDontSee('hero-slider__overlay');
        $response->assertDontSee('hero-slider__badge');
        $response->assertDontSee('hero-slider__heading');
        $response->assertDontSee('hero-slider__subheading');
        $response->assertDontSee('hero-slider__cta');
    }

    public function test_homepage_category_links_filter_products_on_shop_page(): void
    {
        $category = Category::create([
            'name'       => 'Pooja Thali Seva',
            'slug'       => 'pooja-thali-seva',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'category_id' => $category->id,
            'title'       => 'Pure Brass Aarti Thali',
            'slug'        => 'pure-brass-aarti-thali',
            'price'       => 350,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        // Home renders link
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee(route('products.index', ['category' => 'pooja-thali-seva']));

        // Following link to shop page filters products correctly
        $shopResponse = $this->get(route('products.index', ['category' => 'pooja-thali-seva']));
        $shopResponse->assertStatus(200);
        $shopResponse->assertSee('Pure Brass Aarti Thali');
    }

    public function test_storefront_displays_product_image_when_media_exists(): void
    {
        Storage::fake('public');

        $filePath = 'media/2026/10/jal-kalash.jpg';
        Storage::disk('public')->put($filePath, 'kalash image');

        $media = Media::create([
            'title'     => 'Yamuna Jal Kalash',
            'file_path' => $filePath,
            'mime_type' => 'image/jpeg',
            'file_size' => 4096,
        ]);

        $category = Category::create(['name' => 'Holy Water', 'slug' => 'holy-water', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Pavitra Kalash',
            'slug'        => 'pavitra-kalash',
            'media_id'    => $media->id,
            'price'       => 199,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $this->assertNotNull($product->image_url);
        $this->assertEquals(Storage::disk('public')->url($filePath), $product->image_url);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee($product->image_url);
    }

    public function test_storefront_falls_back_gracefully_when_image_is_missing(): void
    {
        $category = Category::create(['name' => 'Puja Samagri', 'slug' => 'puja-samagri', 'is_active' => true]);

        $productWithoutMedia = Product::create([
            'category_id' => $category->id,
            'title'       => 'Incense Sticks Without Image',
            'slug'        => 'incense-sticks-no-image',
            'media_id'    => null,
            'price'       => 50,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $this->assertNull($productWithoutMedia->image_url);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Incense Sticks Without Image');
        $response->assertSee('product-card__image-placeholder');
    }

    public function test_circular_navigation_highlights_active_category(): void
    {
        Category::create(['name' => 'Tulsi Mala', 'slug' => 'tulsi-mala', 'is_active' => true]);

        $response = $this->get(route('products.index', ['category' => 'tulsi-mala']));

        $response->assertStatus(200);
        $response->assertSee('shop-category-item--active');
        $response->assertSee('Category:');
        $response->assertSee('Tulsi Mala');
    }

    public function test_selecting_all_category_resets_filter(): void
    {
        Category::create(['name' => 'Tulsi Mala', 'slug' => 'tulsi-mala', 'is_active' => true]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('shop-category-circle--all');
        $response->assertSee('shop-category-item--active');
    }
}


