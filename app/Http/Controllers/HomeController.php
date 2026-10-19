<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     *
     * Passes:
     *   $slides       – hero slider configuration
     *   $categories   – active categories from DB with fallback
     *   $demoProducts – published products from DB with demo fallback
     */
    public function index()
    {
        $slides = $this->buildSlides();
        $categories   = $this->buildCategories();
        $demoProducts = $this->buildDemoProducts();

        return view('home', compact('slides', 'categories', 'demoProducts'));
    }

    // ── Private helpers ────────────────────────────────────────────────────

    /**
     * Returns slide config for the hero slider.
     * Slides whose image file does not exist are silently excluded,
     * so the slider degrades gracefully to the text fallback.
     */
    private function buildSlides(): array
    {
        $all = [
            [
                'image'          => file_exists(public_path('images/devotional/lalju.webp')) ? asset('images/devotional/lalju.webp') : asset('images/devotional/lalju.png'),
                'mobile_image'   => file_exists(public_path('images/devotional/lalju-mobile.webp')) ? asset('images/devotional/lalju-mobile.webp') : null,
                'png_fallback'   => asset('images/devotional/lalju.png'),
                'alt'            => 'Shri Radha Vallabh Lal Ju darshan in Pitambar',
                'badge'          => '✤ || श्री राधे || ✤',
                'heading'        => 'Braj Dham ki Seva,<br><span class="hero-slider__heading--accent">Aapke Ghar Tak</span>',
                'subheading'     => 'Tulsi Mala, Kanthi Mala, Braj Raj, Yamuna Jal, Thakur Ji Vastra aur anya pavitra seva samagri.',
                'primaryLabel'   => 'Shop Now',
                'primaryUrl'     => route('products.index'),
                'secondaryLabel' => 'Our Story',
                'secondaryUrl'   => route('about'),
                'bgPosition'     => 'right center',
                '_file'          => file_exists(public_path('images/devotional/lalju.webp')) ? public_path('images/devotional/lalju.webp') : public_path('images/devotional/lalju.png'),
            ],
            [
                'image'          => file_exists(public_path('images/devotional/gurudev.webp')) ? asset('images/devotional/gurudev.webp') : asset('images/devotional/gurudev.png'),
                'mobile_image'   => file_exists(public_path('images/devotional/gurudev-mobile.webp')) ? asset('images/devotional/gurudev-mobile.webp') : null,
                'png_fallback'   => asset('images/devotional/gurudev.png'),
                'alt'            => 'Pujya Gurudev Premanand Govind Sharan Ji Maharaj Darshan',
                'badge'          => '✤ || श्री हरिवंश || ✤',
                'heading'        => 'Pujya Gurudev Kripa,<br><span class="hero-slider__heading--accent">Sanatan Seva Parampara</span>',
                'subheading'     => 'Chhavi, Kanthi Mala, Vrindavan Raj evam samast pavitra sadhan.',
                'primaryLabel'   => 'Shop Now',
                'primaryUrl'     => route('products.index', ['category' => 'chhavi-guruji']),
                'secondaryLabel' => 'About Us',
                'secondaryUrl'   => route('about'),
                'bgPosition'     => '70% 22%',
                '_file'          => file_exists(public_path('images/devotional/gurudev.webp')) ? public_path('images/devotional/gurudev.webp') : public_path('images/devotional/gurudev.png'),
            ],
            [
                'image'          => file_exists(public_path('images/devotional/barsana.webp')) ? asset('images/devotional/barsana.webp') : asset('images/devotional/barsana.png'),
                'mobile_image'   => file_exists(public_path('images/devotional/barsana-mobile.webp')) ? asset('images/devotional/barsana-mobile.webp') : null,
                'png_fallback'   => asset('images/devotional/barsana.png'),
                'alt'            => 'Shri Radha Rani Darshan — Barsana Dham',
                'badge'          => '✤ || श्री राधे || ✤',
                'heading'        => 'Shri Radha Rani Kripa,<br><span class="hero-slider__heading--accent">Barsana Dham Seva</span>',
                'subheading'     => 'Pavitra Tulsi, Kanthi Mala, evam Barsana seva samagri sidhe aapke ghar tak.',
                'primaryLabel'   => 'Shop Now',
                'primaryUrl'     => route('products.index'),
                'secondaryLabel' => 'Our Story',
                'secondaryUrl'   => route('about'),
                'bgPosition'     => 'center center',
                '_file'          => file_exists(public_path('images/devotional/barsana.webp')) ? public_path('images/devotional/barsana.webp') : public_path('images/devotional/barsana.png'),
            ],
            [
                'image'          => file_exists(public_path('images/devotional/mahaprabhu.webp')) ? asset('images/devotional/mahaprabhu.webp') : asset('images/devotional/mahaprabhu.png'),
                'mobile_image'   => file_exists(public_path('images/devotional/mahaprabhu-mobile.webp')) ? asset('images/devotional/mahaprabhu-mobile.webp') : null,
                'png_fallback'   => asset('images/devotional/mahaprabhu.png'),
                'alt'            => 'Shri Hit Harivansh Mahaprabhu — Harivanshi Seva Parampara',
                'badge'          => '✤ || श्री हरिवंश || ✤',
                'heading'        => 'Shri Hit Harivansh Mahaprabhu,<br><span class="hero-slider__heading--accent">Vrindavan Ras Upasana</span>',
                'subheading'     => 'Harivanshi parampara anusar shuddh evam pramanik seva samagri.',
                'primaryLabel'   => 'Shop Now',
                'primaryUrl'     => route('products.index'),
                'secondaryLabel' => 'Contact Us',
                'secondaryUrl'   => route('contact'),
                'bgPosition'     => 'center center',
                '_file'          => file_exists(public_path('images/devotional/mahaprabhu.webp')) ? public_path('images/devotional/mahaprabhu.webp') : public_path('images/devotional/mahaprabhu.png'),
            ],
        ];

        // Filter to only slides whose image file actually exists on disk.
        return array_values(
            array_filter($all, fn($s) => file_exists($s['_file']))
        );
    }

    /**
     * Categories data — fetches active parents from database, falls back to demo array.
     */
    private function buildCategories(): array
    {
        if (Schema::hasTable('categories')) {
            $dbCategories = Category::active()->with('media')->orderBy('sort_order')->orderBy('name')->get();
            if ($dbCategories->isNotEmpty()) {
                return $dbCategories->map(fn($c) => [
                    'name'  => $c->name,
                    'slug'  => $c->slug,
                    'icon'  => '📿',
                    'image' => $c->image_url,
                ])->toArray();
            }
        }

        return [
            ['name' => 'Tulsi Mala',        'slug' => 'tulsi-mala',        'icon' => '📿', 'image' => null],
            ['name' => 'Braj Raj',          'slug' => 'braj-raj',          'icon' => '🏺', 'image' => null],
            ['name' => 'Yamuna Jal',        'slug' => 'yamuna-jal',        'icon' => '💧', 'image' => null],
            ['name' => 'Kanthi Mala',       'slug' => 'kanthi-mala',       'icon' => '📿', 'image' => null],
            ['name' => 'Thakur Ji Vastra',  'slug' => 'thakur-ji-vastra',  'icon' => '👑', 'image' => null],
            ['name' => 'Chhavi / Guruji',   'slug' => 'chhavi-guruji',     'icon' => '🖼️', 'image' => null],
            ['name' => 'Pooja Samagri',     'slug' => 'pooja-samagri',     'icon' => '🪔', 'image' => null],
            ['name' => 'Other Seva',        'slug' => 'other-seva',        'icon' => '🎁', 'image' => null],
        ];
    }

    /**
     * Products data — fetches published products from database, falls back to demo placeholders.
     */
    private function buildDemoProducts(): array
    {
        if (Schema::hasTable('products')) {
            $dbProducts = Product::published()->with('media')->latest()->take(4)->get();
            if ($dbProducts->isNotEmpty()) {
                return $dbProducts->map(fn($p) => [
                    'id'             => $p->id,
                    'name'           => $p->title,
                    'slug'           => $p->slug,
                    'price'          => (float) $p->price,
                    'original_price' => $p->original_price ? (float) $p->original_price : null,
                    'badge'          => $p->badge,
                    'rating'         => $p->rating,
                    'reviews_count'  => $p->reviews_count,
                    'image'          => $p->image_url,
                    'isDemo'         => false,
                ])->toArray();
            }
        }

        return [
            ['name' => 'Brass Puja Thali Set',        'price' => 499,  'original_price' => 699,  'badge' => 'Bestseller', 'image' => null, 'isDemo' => true],
            ['name' => 'Radha Krishna Poshak (Small)', 'price' => 299,  'original_price' => 399,  'badge' => 'New',        'image' => null, 'isDemo' => true],
            ['name' => 'Tulsi Mala — 108 Beads',      'price' => 199,  'original_price' => null, 'badge' => null,         'image' => null, 'isDemo' => true],
            ['name' => 'Vrindavan Dhoop Batti',        'price' => 129,  'original_price' => 150,  'badge' => null,         'image' => null, 'isDemo' => true],
        ];
    }
}
