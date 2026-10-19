<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_is_publicly_accessible_and_renders_required_content(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);

        // Devotional greeting, headings and supporting tagline
        $response->assertSee('राधा वल्लभ श्री हरिवंश');
        $response->assertSee('About Harivanshi Seva Kunj');
        $response->assertSee('Aapke Ghar Tak Vrindavan Dham Ki Seva');

        // Purpose section
        $response->assertSee('Our Purpose');

        // Navigation links
        $response->assertSee(route('products.index'));
    }

    public function test_about_page_displays_active_categories_with_shop_filter_links(): void
    {
        $activeCat = Category::create([
            'name'       => 'Braj Raj Seva',
            'slug'       => 'braj-raj-seva',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $inactiveCat = Category::create([
            'name'       => 'Hidden Category',
            'slug'       => 'hidden-category',
            'is_active'  => false,
            'sort_order' => 2,
        ]);

        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('Braj Raj Seva');
        $response->assertSee(route('products.index', ['category' => 'braj-raj-seva']));
        $response->assertDontSee('Hidden Category');
    }

    public function test_about_page_displays_instagram_community_profile_and_follower_count(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);

        // Instagram community details
        $response->assertSee('Connect With Our Devotional Community');
        $response->assertSee('@uma_harivanshi75');
        $response->assertSee('33.3K');
        $response->assertSee('https://www.instagram.com/uma_harivanshi75/');
    }
}
