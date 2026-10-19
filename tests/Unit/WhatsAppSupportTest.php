<?php

namespace Tests\Unit;

use App\Support\WhatsApp;
use Tests\TestCase;

class WhatsAppSupportTest extends TestCase
{
    public function test_formats_and_validates_phone_numbers(): void
    {
        // Standard clean 12-digit Indian number with country code
        $this->assertEquals('919286070355', WhatsApp::formatPhoneNumber('919286070355'));

        // Formatted with spaces, plus signs, dashes
        $this->assertEquals('919286070355', WhatsApp::formatPhoneNumber('+91 92860-70355'));
        $this->assertEquals('919286070355', WhatsApp::formatPhoneNumber('+91 (92860) 70355'));

        // 10-digit number without country code gets 91 prepended
        $this->assertEquals('919286070355', WhatsApp::formatPhoneNumber('9286070355'));

        // Invalid numbers
        $this->assertNull(WhatsApp::formatPhoneNumber(''));
        $this->assertNull(WhatsApp::formatPhoneNumber(null));
        $this->assertNull(WhatsApp::formatPhoneNumber('12345')); // Too short
        $this->assertNull(WhatsApp::formatPhoneNumber('abcdefghijk')); // Letters only
        $this->assertNull(WhatsApp::formatPhoneNumber('12345678901234567')); // Too long (>15 digits)
    }

    public function test_resolves_business_number_from_config(): void
    {
        config(['services.whatsapp.number' => '919876543210']);
        $this->assertEquals('919876543210', WhatsApp::getBusinessNumber());
        $this->assertTrue(WhatsApp::isValidNumber());

        config(['services.whatsapp.number' => '']);
        config(['seo.business.whatsapp' => '919111222333']);
        $this->assertEquals('919111222333', WhatsApp::getBusinessNumber());

        config(['services.whatsapp.number' => null]);
        config(['seo.business.whatsapp' => null]);
        putenv('WHATSAPP_NUMBER=');
        // Falls back safely
        $this->assertNotNull(WhatsApp::getBusinessNumber());
    }

    public function test_resolves_absolute_image_url_correctly(): void
    {
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        // Relative storage path
        $url1 = WhatsApp::resolveAbsoluteImageUrl('/storage/products/yamuna-jal.jpg');
        $this->assertEquals('https://harivanshisevakunj.com/storage/products/yamuna-jal.jpg', $url1);

        // Path without leading slash
        $url2 = WhatsApp::resolveAbsoluteImageUrl('storage/products/yamuna-jal.jpg');
        $this->assertEquals('https://harivanshisevakunj.com/storage/products/yamuna-jal.jpg', $url2);

        // Dev localhost URL in production canonical context gets re-based
        $url3 = WhatsApp::resolveAbsoluteImageUrl('http://localhost:8000/storage/products/yamuna-jal.jpg');
        $this->assertEquals('https://harivanshisevakunj.com/storage/products/yamuna-jal.jpg', $url3);

        // Remote public CDN URL is preserved
        $url4 = WhatsApp::resolveAbsoluteImageUrl('https://cdn.example.com/products/sacred.jpg');
        $this->assertEquals('https://cdn.example.com/products/sacred.jpg', $url4);

        // Missing image returns null
        $this->assertNull(WhatsApp::resolveAbsoluteImageUrl(null));
        $this->assertNull(WhatsApp::resolveAbsoluteImageUrl(''));
        $this->assertNull(WhatsApp::resolveAbsoluteImageUrl('   '));
    }

    public function test_resolves_absolute_product_url(): void
    {
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        $url = WhatsApp::resolveAbsoluteProductUrl('yamuna-jal');
        $this->assertEquals('https://harivanshisevakunj.com/products/yamuna-jal', $url);

        $this->assertNull(WhatsApp::resolveAbsoluteProductUrl(null));
        $this->assertNull(WhatsApp::resolveAbsoluteProductUrl(''));
    }

    public function test_builds_product_enquiry_message_with_id_and_image(): void
    {
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        $message = WhatsApp::buildProductEnquiryMessage([
            'id'    => 125,
            'name'  => 'Yamuna Jal',
            'image' => '/storage/products/yamuna-jal.jpg',
            'slug'  => 'yamuna-jal',
        ]);

        $this->assertStringContainsString('Radhe Radhe 🙏', $message);
        $this->assertStringContainsString('I would like to enquire about this product.', $message);
        $this->assertStringContainsString('Product: Yamuna Jal', $message);
        $this->assertStringContainsString('Product ID: 125', $message);
        $this->assertStringContainsString('Product Image: https://harivanshisevakunj.com/storage/products/yamuna-jal.jpg', $message);
        $this->assertStringContainsString('Product Page: https://harivanshisevakunj.com/products/yamuna-jal', $message);
        $this->assertStringContainsString('Please share the price, availability and delivery details.', $message);
        $this->assertStringContainsString('Thank you.', $message);
        $this->assertStringContainsString('Harivanshi Seva Kunj', $message);
    }

    public function test_graceful_fallback_when_image_is_missing(): void
    {
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        $message = WhatsApp::buildProductEnquiryMessage([
            'id'    => 126,
            'name'  => 'Incense Sticks Without Image',
            'image' => null,
            'slug'  => 'incense-sticks-no-image',
        ]);

        $this->assertStringContainsString('Radhe Radhe 🙏', $message);
        $this->assertStringContainsString('Product: Incense Sticks Without Image', $message);
        $this->assertStringContainsString('Product ID: 126', $message);
        $this->assertStringContainsString('Product Page: https://harivanshisevakunj.com/products/incense-sticks-no-image', $message);
        // Does not output broken or empty Product Image line
        $this->assertStringNotContainsString('Product Image:', $message);
    }

    public function test_generates_safe_rfc3986_rawurlencode_wa_me_url(): void
    {
        config(['services.whatsapp.number' => '919286070355']);
        config(['seo.canonical_url' => 'https://harivanshisevakunj.com']);

        $url = WhatsApp::generateProductEnquiryUrl([
            'id'    => 125,
            'name'  => 'Yamuna Jal',
            'image' => '/storage/products/yamuna-jal.jpg',
            'slug'  => 'yamuna-jal',
        ]);

        $this->assertNotNull($url);
        $this->assertStringStartsWith('https://wa.me/919286070355?text=', $url);

        // Verify URL contains percent-encoded components
        $queryText = substr($url, strpos($url, '?text=') + 6);
        $decoded = rawurldecode($queryText);

        $this->assertStringContainsString('Product: Yamuna Jal', $decoded);
        $this->assertStringContainsString('Product ID: 125', $decoded);
        $this->assertStringContainsString('Product Image: https://harivanshisevakunj.com/storage/products/yamuna-jal.jpg', $decoded);
    }
}
