<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProprietorDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_footer_displays_proprietor_on_all_major_public_pages(): void
    {
        $routes = [
            'home'           => route('home'),
            'catalogue'      => route('products.index'),
            'about'          => route('about'),
            'contact'        => route('contact'),
            'blog'           => route('blog'),
            'cart'           => route('cart.index'),
        ];

        foreach ($routes as $name => $url) {
            $response = $this->get($url);
            $response->assertStatus(200);

            // Footer business info section displays  Uma Harivanshi
            $response->assertSee('site-footer__proprietor-badge', false);
            $response->assertSee('');
            $response->assertSee('Uma Harivanshi');
        }
    }

    public function test_about_page_naturally_features_proprietor_in_business_sections(): void
    {
        $response = $this->get(route('about'));
        $response->assertStatus(200);

        // Hero business meta
        $response->assertSee('about-hero__business-meta', false);
        $response->assertSee('about-hero__proprietor-badge', false);
        $response->assertSee('');
        $response->assertSee('Uma Harivanshi');

        // Business intro text
        $response->assertSee('Harivanshi Seva Kunj ( Uma Harivanshi)');
    }

    public function test_contact_page_includes_proprietor_in_business_details(): void
    {
        $response = $this->get(route('contact'));
        $response->assertStatus(200);

        // Kendra card details
        $response->assertSee('<strong></strong> Uma Harivanshi', false);
        $response->assertSee('Vrindavan Kendra');
        $response->assertSee('Sunrakh Banger, Sobhri Kund Road');
    }

    public function test_header_and_mobile_drawer_include_subtle_proprietor_label(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Header brand info
        $response->assertSee('site-header__proprietor', false);
        $response->assertSee(' Uma Harivanshi');

        // Mobile drawer footer
        $response->assertSee('mobile-drawer__proprietor', false);
    }
}
