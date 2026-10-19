<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds the initial flat catalogue: Category -> Product.
     */
    public function run(): void
    {
        $categoriesData = [
            [
                'name'              => 'Tulsi Mala',
                'slug'              => 'tulsi-mala',
                'short_description' => 'Handcrafted pure Vrindavan Tulsi wood malas for chanting and daily sadhana.',
                'sort_order'        => 1,
                'products'          => [
                    [
                        'title'            => 'Original Vrindavan Tulsi Japa Mala (108 Beads)',
                        'slug'             => 'original-vrindavan-tulsi-japa-mala-108-beads',
                        'price'            => 199.00,
                        'original_price'   => 299.00,
                        'badge'            => 'Bestseller',
                        'description'      => 'Hand-carved sacred Tulsi beads strung with natural thread and auspicious knots for daily Naam Japa.',
                        'meta_description' => 'Original 108 beads Tulsi Japa Mala from Shri Dham Vrindavan for chanting and sadhana.',
                    ],
                    [
                        'title'            => 'Shri Radha Naam Tulsi Mala',
                        'slug'             => 'shri-radha-naam-tulsi-mala',
                        'price'            => 249.00,
                        'original_price'   => 349.00,
                        'badge'            => 'Sacred',
                        'description'      => 'Auspicious Tulsi Mala engraved with divine Radha Naam. Handcrafted by Vrindavan artisans.',
                        'meta_description' => 'Pure Radha Naam Tulsi Mala sanctified in Shri Dham Vrindavan.',
                    ],
                ],
            ],
            [
                'name'              => 'Braj Raj',
                'slug'              => 'braj-raj',
                'short_description' => 'Sacred dust of Braj Dham and holy leela sthalis.',
                'sort_order'        => 2,
                'products'          => [
                    [
                        'title'            => 'Pavitra Raman Reti Black Raj (100g)',
                        'slug'             => 'pavitra-raman-reti-black-raj-100g',
                        'price'            => 149.00,
                        'original_price'   => 199.00,
                        'badge'            => 'Sacred',
                        'description'      => 'Authentic Raman Reti Krishna leela dust collected with reverence. Ideal for daily tilak and home mandir seva.',
                        'meta_description' => 'Pure Raman Reti Black Raj for devotion and daily tilak from Shri Dham Vrindavan.',
                    ],
                    [
                        'title'            => 'Shri Dham Vrindavan White Raj (100g)',
                        'slug'             => 'shri-dham-vrindavan-white-raj-100g',
                        'price'            => 149.00,
                        'original_price'   => 199.00,
                        'badge'            => 'Bestseller',
                        'description'      => 'Holy white Braj Raj sanctified by generations of rasik saints in Vrindavan.',
                        'meta_description' => 'Original White Braj Raj from sacred leela spots of Vrindavan.',
                    ],
                ],
            ],
            [
                'name'              => 'Yamuna Jal',
                'slug'              => 'yamuna-jal',
                'short_description' => 'Pavitra holy waters from Shri Yamuna Maharani and Braj Kunds.',
                'sort_order'        => 3,
                'products'          => [
                    [
                        'title'            => 'Shri Yamuna Maharani Pavitra Jal (250ml)',
                        'slug'             => 'shri-yamuna-maharani-pavitra-jal-250ml',
                        'price'            => 99.00,
                        'original_price'   => 149.00,
                        'badge'            => 'Pavitra',
                        'description'      => 'Holy Yamuna Jal collected from Keshi Ghat, Vrindavan. Perfect for Thakurji Abhishek and holy sprinkling.',
                        'meta_description' => 'Authentic Yamuna Maharani Jal directly from Vrindavan Dham.',
                    ],
                    [
                        'title'            => 'Shri Radha Kund Pavitra Jal (100ml)',
                        'slug'             => 'shri-radha-kund-pavitra-jal-100ml',
                        'price'            => 120.00,
                        'original_price'   => 180.00,
                        'badge'            => 'Sacred',
                        'description'      => 'Divinely sanctified sacred water from Shri Radha Kund, Govardhan Braj Dham.',
                        'meta_description' => 'Original Shri Radha Kund Jal for puja and spiritual blessings.',
                    ],
                ],
            ],
            [
                'name'              => 'Kanthi Mala',
                'slug'              => 'kanthi-mala',
                'short_description' => 'Devotional neck malas in pure Tulsi, Neem, and sandalwood.',
                'sort_order'        => 4,
                'products'          => [
                    [
                        'title'            => 'Original 2-Round Vrindavan Tulsi Kanthi',
                        'slug'             => 'original-2-round-vrindavan-tulsi-kanthi',
                        'price'            => 150.00,
                        'original_price'   => 250.00,
                        'badge'            => 'Popular',
                        'description'      => 'Pure two-round Tulsi kanthi mala for wearing around the neck. Brings peace and spiritual protection.',
                        'meta_description' => 'Original 2-round pure Tulsi Kanthi Mala from Vrindavan.',
                    ],
                    [
                        'title'            => 'Shri Radha Locket Tulsi Kanthi',
                        'slug'             => 'shri-radha-locket-tulsi-kanthi',
                        'price'            => 199.00,
                        'original_price'   => 299.00,
                        'badge'            => 'Sacred',
                        'description'      => 'Tulsi Kanthi featuring a blessed Shri Radha silver-finish devotional pendant.',
                        'meta_description' => 'Pure Tulsi Kanthi with Radha locket from Vrindavan Dham.',
                    ],
                ],
            ],
            [
                'name'              => 'Thakur Ji Vastra',
                'slug'              => 'thakur-ji-vastra',
                'short_description' => 'Divine dresses, poshaks, and mukut shringar for Thakur Ji.',
                'sort_order'        => 5,
                'products'          => [
                    [
                        'title'            => 'Radha Krishna Designer Poshak (Small)',
                        'slug'             => 'radha-krishna-designer-poshak-small',
                        'price'            => 299.00,
                        'original_price'   => 399.00,
                        'badge'            => 'New',
                        'description'      => 'Hand-embroidered silk poshak designed with zari work for Laddu Gopal / Radha Krishna.',
                        'meta_description' => 'Designer handcrafted Thakur Ji poshak from Vrindavan.',
                    ],
                    [
                        'title'            => 'Thakur Ji Pitambar Silk Vastra Set',
                        'slug'             => 'thakur-ji-pitambar-silk-vastra-set',
                        'price'            => 499.00,
                        'original_price'   => 699.00,
                        'badge'            => 'Bestseller',
                        'description'      => 'Bright golden-yellow Pitambar silk vastra set embellished with delicate gota patti.',
                        'meta_description' => 'Authentic Pitambar vastra for Thakurji seva from Vrindavan.',
                    ],
                ],
            ],
            [
                'name'              => 'Chhavi / Guruji',
                'slug'              => 'chhavi-guruji',
                'short_description' => 'Braj chhavis, framed darshan, and Pujya Gurudev portraits.',
                'sort_order'        => 6,
                'products'          => [
                    [
                        'title'            => 'Pujya Gurudev Premanand Ji Maharaj Framed Chhavi',
                        'slug'             => 'pujya-gurudev-premanand-ji-maharaj-framed-chhavi',
                        'price'            => 299.00,
                        'original_price'   => 450.00,
                        'badge'            => 'Popular',
                        'description'      => 'Divine darshan framed picture of Pujya Gurudev Premanand Govind Sharan Ji Maharaj.',
                        'meta_description' => 'Blessed framed picture of Pujya Maharaj Ji for your home altar.',
                    ],
                    [
                        'title'            => 'Shri Hit Harivansh Mahaprabhu Darshan Frame',
                        'slug'             => 'shri-hit-harivansh-mahaprabhu-darshan-frame',
                        'price'            => 299.00,
                        'original_price'   => 450.00,
                        'badge'            => 'Sacred',
                        'description'      => 'Inspiring framed darshan of Shri Hit Harivansh Mahaprabhu, pioneer of Ras-upasana in Vrindavan.',
                        'meta_description' => 'Shri Hit Harivansh Mahaprabhu framed darshan photo.',
                    ],
                ],
            ],
            [
                'name'              => 'Pooja Samagri',
                'slug'              => 'pooja-samagri',
                'short_description' => 'Incense, pure chandan, dhoop batti, and daily altar articles.',
                'sort_order'        => 7,
                'products'          => [
                    [
                        'title'            => 'Brass Puja Thali Set with Aarti Diya',
                        'slug'             => 'brass-puja-thali-set-with-aarti-diya',
                        'price'            => 499.00,
                        'original_price'   => 699.00,
                        'badge'            => 'Bestseller',
                        'description'      => 'Complete solid brass Puja Thali set including agarbatti stand, diya, and kumkum holder.',
                        'meta_description' => 'Premium Brass Puja Thali set for home mandir seva.',
                    ],
                    [
                        'title'            => 'Vrindavan Sugandhit Dhoop Batti & Chandan',
                        'slug'             => 'vrindavan-sugandhit-dhoop-batti-and-chandan',
                        'price'            => 129.00,
                        'original_price'   => 150.00,
                        'badge'            => 'Pure',
                        'description'      => 'Charcoal-free aromatic dhoop batti made with natural herbs, cow ghee and pure Vrindavan chandan.',
                        'meta_description' => 'Pure herbal dhoop batti and chandan from Vrindavan.',
                    ],
                ],
            ],
            [
                'name'              => 'Other Seva',
                'slug'              => 'other-seva',
                'short_description' => 'Braj spiritual literature, chunri, and devotional gift items.',
                'sort_order'        => 8,
                'products'          => [
                    [
                        'title'            => 'Radha Naam Seva Chunri (Vrindavan Blessings)',
                        'slug'             => 'radha-naam-seva-chunri-vrindavan-blessings',
                        'price'            => 199.00,
                        'original_price'   => 299.00,
                        'badge'            => 'Pavitra',
                        'description'      => 'Auspicious Radha-Naam printed devotional yellow chunri for temples, parikrama, or deity offerings.',
                        'meta_description' => 'Sacred Radha Naam printed chunri from Vrindavan Dham.',
                    ],
                    [
                        'title'            => 'Shri Hit Chaurasi & Braj Katha Gutka Book',
                        'slug'             => 'shri-hit-chaurasi-and-braj-katha-gutka-book',
                        'price'            => 150.00,
                        'original_price'   => 200.00,
                        'badge'            => 'Popular',
                        'description'      => 'Authentic pocket-size devotional granth containing Shri Hit Chaurasi pads and Braj lila commentary.',
                        'meta_description' => 'Shri Hit Chaurasi devotional granth with Hindi meaning.',
                    ],
                ],
            ],
        ];

        foreach ($categoriesData as $catData) {
            $products = $catData['products'] ?? [];
            unset($catData['products']);

            $category = Category::firstOrCreate(
                ['slug' => $catData['slug']],
                array_merge($catData, ['is_active' => true])
            );

            foreach ($products as $prodData) {
                Product::firstOrCreate(
                    ['slug' => $prodData['slug']],
                    array_merge($prodData, [
                        'category_id' => $category->id,
                        'status'      => 'published',
                        'is_active'   => true,
                    ])
                );
            }
        }
    }
}
