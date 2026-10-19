<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WhatsAppProductEnquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_card_on_catalogue_renders_whatsapp_enquiry_with_id_and_image(): void
    {
        Storage::fake('public');

        $filePath = 'media/2026/10/yamuna-jal.jpg';
        Storage::disk('public')->put($filePath, 'fake image data');

        $media = Media::create([
            'title'     => 'Yamuna Jal Holy Water',
            'file_path' => $filePath,
            'mime_type' => 'image/jpeg',
            'file_size' => 2048,
        ]);

        $category = Category::create([
            'name'      => 'Holy Water',
            'slug'      => 'holy-water',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Sacred Yamuna Jal',
            'slug'        => 'sacred-yamuna-jal',
            'media_id'    => $media->id,
            'price'       => 151,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Sacred Yamuna Jal');
        $response->assertSee('product-card__btn--whatsapp');

        // Extract the WhatsApp URL from product card
        $content = $response->getContent();
        preg_match('#<a\s+href="([^"]+)"[^>]*class="[^"]*product-card__btn--whatsapp#', $content, $matches);
        if (empty($matches)) {
            preg_match('#class="[^"]*product-card__btn--whatsapp[^"]*"[^>]*href="([^"]+)"#', $content, $matches);
        }

        $this->assertNotEmpty($matches, 'WhatsApp click-to-chat URL not found on product card');
        $whatsappUrl = html_entity_decode($matches[1]);

        // Decode the URL query text
        $textParam = substr($whatsappUrl, strpos($whatsappUrl, '?text=') + 6);
        $decodedMessage = rawurldecode($textParam);

        // Verify Acceptance Criteria:
        // 1. Correct product details
        $this->assertStringContainsString('Product: Sacred Yamuna Jal', $decodedMessage);
        // 2. Correct database product ID
        $this->assertStringContainsString('Product ID: ' . $product->id, $decodedMessage);
        // 3. Absolute image URL points to uploaded product image
        $this->assertStringContainsString('Product Image: ' . $product->absolute_image_url, $decodedMessage);
        // 4. Product page URL
        $this->assertStringContainsString('Product Page: ' . route('products.show', $product->slug), $decodedMessage);
        // 5. Devotional greeting and polite enquiry
        $this->assertStringContainsString('Radhe Radhe 🙏', $decodedMessage);
        $this->assertStringContainsString('Please share the price, availability and delivery details.', $decodedMessage);
        $this->assertStringContainsString('Harivanshi Seva Kunj', $decodedMessage);
    }

    public function test_product_card_gracefully_handles_missing_product_image(): void
    {
        $category = Category::create([
            'name'      => 'Dhoop & Agarbatti',
            'slug'      => 'dhoop-agarbatti',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Gau May Dhoop Stick',
            'slug'        => 'gau-may-dhoop-stick',
            'media_id'    => null, // No image
            'price'       => 80,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Gau May Dhoop Stick');

        $content = $response->getContent();
        preg_match('#<a\s+href="([^"]+)"[^>]*class="[^"]*product-card__btn--whatsapp#', $content, $matches);
        if (empty($matches)) {
            preg_match('#class="[^"]*product-card__btn--whatsapp[^"]*"[^>]*href="([^"]+)"#', $content, $matches);
        }

        $this->assertNotEmpty($matches, 'WhatsApp button still renders when image is missing');
        $whatsappUrl = html_entity_decode($matches[1]);

        $textParam = substr($whatsappUrl, strpos($whatsappUrl, '?text=') + 6);
        $decodedMessage = rawurldecode($textParam);

        // Product ID and details are present
        $this->assertStringContainsString('Product: Gau May Dhoop Stick', $decodedMessage);
        $this->assertStringContainsString('Product ID: ' . $product->id, $decodedMessage);
        $this->assertStringContainsString('Product Page: ' . route('products.show', $product->slug), $decodedMessage);

        // Missing image is gracefully handled without broken links
        $this->assertStringNotContainsString('Product Image:', $decodedMessage);
    }

    public function test_related_product_card_on_detail_page_renders_whatsapp_enquiry_with_id_and_image(): void
    {
        Storage::fake('public');

        $filePath = 'media/2026/10/tulsi-kanthi.jpg';
        Storage::disk('public')->put($filePath, 'kanthi fake image');

        $media = Media::create([
            'title'     => 'Tulsi Kanthi 3 Round',
            'file_path' => $filePath,
            'mime_type' => 'image/jpeg',
            'file_size' => 3072,
        ]);

        $category = Category::create([
            'name'      => 'Kanthi Mala',
            'slug'      => 'kanthi-mala',
            'is_active' => true,
        ]);

        $mainProduct = Product::create([
            'category_id' => $category->id,
            'title'       => 'Main Product',
            'slug'        => 'main-product',
            'price'       => 100,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $relatedProduct = Product::create([
            'category_id' => $category->id,
            'title'       => '3 Round Radha Kanthi Mala',
            'slug'        => '3-round-radha-kanthi-mala',
            'media_id'    => $media->id,
            'price'       => 350,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->get(route('products.show', $mainProduct->slug));

        $response->assertStatus(200);
        $response->assertSee('3 Round Radha Kanthi Mala');
        $response->assertSee('product-card__btn--whatsapp');

        $content = $response->getContent();
        preg_match('#<a\s+href="([^"]+)"[^>]*class="[^"]*product-card__btn--whatsapp#', $content, $matches);
        if (empty($matches)) {
            preg_match('#class="[^"]*product-card__btn--whatsapp[^"]*"[^>]*href="([^"]+)"#', $content, $matches);
        }

        $this->assertNotEmpty($matches, 'WhatsApp button not found on related product card');
        $whatsappUrl = html_entity_decode($matches[1]);

        $textParam = substr($whatsappUrl, strpos($whatsappUrl, '?text=') + 6);
        $decodedMessage = rawurldecode($textParam);

        $this->assertStringContainsString('Product: 3 Round Radha Kanthi Mala', $decodedMessage);
        $this->assertStringContainsString('Product ID: ' . $relatedProduct->id, $decodedMessage);
        $this->assertStringContainsString('Product Image: ' . $relatedProduct->absolute_image_url, $decodedMessage);
        $this->assertStringContainsString('Product Page: ' . route('products.show', $relatedProduct->slug), $decodedMessage);
    }

    public function test_product_model_accessors(): void
    {
        Storage::fake('public');

        $filePath = 'media/2026/10/radha-chhavi.jpg';
        Storage::disk('public')->put($filePath, 'chhavi image');

        $media = Media::create([
            'title'     => 'Shri Radha Chhavi',
            'file_path' => $filePath,
            'mime_type' => 'image/jpeg',
            'file_size' => 1024,
        ]);

        $category = Category::create([
            'name'      => 'Chhavi',
            'slug'      => 'chhavi',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Pujya Guruji Photo Frame',
            'slug'        => 'pujya-guruji-photo-frame',
            'media_id'    => $media->id,
            'price'       => 500,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        // Model attributes
        $this->assertNotNull($product->absolute_image_url);
        $this->assertStringStartsWith('http', $product->absolute_image_url);

        $enquiryUrl = $product->whatsapp_enquiry_url;
        $this->assertNotNull($enquiryUrl);
        $this->assertStringStartsWith('https://wa.me/', $enquiryUrl);

        $textParam = substr($enquiryUrl, strpos($enquiryUrl, '?text=') + 6);
        $decoded = rawurldecode($textParam);
        $this->assertStringContainsString('Product ID: ' . $product->id, $decoded);
        $this->assertStringContainsString('Product: ' . $product->title, $decoded);
    }

    public function test_whatsapp_button_shows_disabled_state_when_invalid_number_configured(): void
    {
        config(['services.whatsapp.number' => 'invalid-num']);
        config(['seo.business.whatsapp' => 'invalid-num']);

        $category = Category::create([
            'name'      => 'Braj Raj',
            'slug'      => 'braj-raj',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'title'       => 'Vrindavan Raj Tilak',
            'slug'        => 'vrindavan-raj-tilak',
            'price'       => 51,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('product-card__btn--disabled');
        $response->assertSee('WhatsApp Unavailable');
    }

    public function test_homepage_featured_products_render_whatsapp_enquiry_links(): void
    {
        $category = Category::create([
            'name'      => 'Featured Category',
            'slug'      => 'featured-category',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Featured Brass Puja Bell',
            'slug'        => 'featured-brass-puja-bell',
            'price'       => 299,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Featured Brass Puja Bell');

        $content = $response->getContent();
        $this->assertStringContainsString('https://wa.me/', $content);
        $this->assertStringContainsString(rawurlencode('Product ID: ' . $product->id), $content);
    }
}
