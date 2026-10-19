<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_mobile_drawer_and_backdrop(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Backdrop & Drawer dialog
        $response->assertSee('id="mobile-nav-backdrop"', false);
        $response->assertSee('id="mobile-drawer"', false);
        $response->assertSee('role="dialog"', false);
        $response->assertSee('aria-modal="true"', false);
        $response->assertSee('aria-label="Navigation Menu"', false);

        // Header elements in drawer
        $response->assertSee('class="mobile-drawer__header"', false);
        $response->assertSee('id="mobile-drawer-close"', false);
        $response->assertSee('images/categories/logo.png', false);
        $response->assertSee('हरिवंशी सेवा कुंज', false);

        // Navigation links
        $response->assertSee(route('home'), false);
        $response->assertSee(route('products.index'), false);
        $response->assertSee(route('about'), false);
        $response->assertSee(route('blog'), false);
        $response->assertSee(route('contact'), false);

        // Active link indicator on home page
        $response->assertSee('mobile-drawer__link--active', false);

        // Devotional footer
        $response->assertSee('राधावल्लभ श्री हरिवंश', false);

        // WhatsApp link configured
        $response->assertSee('https://wa.me/', false);
        $response->assertSee('+91 92860 70355', false);
    }

    public function test_shop_page_highlights_shop_nav_item(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('mobile-drawer__link--active', false);
        $response->assertSee(route('products.index'), false);
    }

    public function test_about_page_highlights_about_nav_item(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('mobile-drawer__link--active', false);
        $response->assertSee(route('about'), false);
    }

    public function test_desktop_navigation_is_preserved(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('id="primary-nav"', false);
        $response->assertSee('class="site-nav"', false);
        $response->assertSee('class="site-header__menu-toggle"', false);
    }

    public function test_contact_page_is_publicly_accessible(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertSee('Humse Sampark Karein');
        $response->assertSee('Sunrakh Banger, Sobhri Kund Road');
        $response->assertSee('+91 92860 70355');
    }

    public function test_blog_page_is_publicly_accessible(): void
    {
        $response = $this->get(route('blog'));

        $response->assertStatus(200);
        $response->assertSee('Braj Katha');
        $response->assertSee('Shri Radha Vallabh Lal Ju Ka Prakatya');
        $response->assertSee('Tulsi Mala Dharan Karne Ke Niyam');
    }
}
