<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_card_displays_both_purchase_actions_on_catalogue(): void
    {
        $category = Category::create(['name' => 'Puja Samagri', 'slug' => 'puja-samagri', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Pure Brass Diya',
            'slug'        => 'pure-brass-diya',
            'price'       => 350,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Pure Brass Diya');
        $response->assertSee('Add to Cart');
        $response->assertSee('Buy on WhatsApp');
        $response->assertSee('product-card__btn--cart');
        $response->assertSee('product-card__btn--whatsapp');

        // Check WhatsApp click-to-chat URL details
        $businessNumber = config('services.whatsapp.number');
        $response->assertSee('https://wa.me/' . $businessNumber, false);
        $response->assertSee(rawurlencode('Harivanshi Seva Kunj'), false);
        $response->assertSee(rawurlencode('Pure Brass Diya'), false);
    }

    public function test_can_add_published_product_to_cart_via_standard_post(): void
    {
        $category = Category::create(['name' => 'Puja Samagri', 'slug' => 'puja-samagri', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Vrindavan Chandan',
            'slug'        => 'vrindavan-chandan',
            'price'       => 150,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        $response->assertSessionHas('success');
        $response->assertSessionHas('cart', function ($cart) use ($product) {
            return isset($cart[$product->id])
                && $cart[$product->id]['quantity'] === 2
                && $cart[$product->id]['price'] == 150;
        });
    }

    public function test_can_add_product_to_cart_via_ajax_json_request(): void
    {
        $category = Category::create(['name' => 'Puja Samagri', 'slug' => 'puja-samagri', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Tulsi Kanthi Mala',
            'slug'        => 'tulsi-kanthi-mala',
            'price'       => 200,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'    => true,
            'cart_count' => 1,
            'cart_total' => 200,
        ]);
        $response->assertJsonPath('message', '“Tulsi Kanthi Mala” added to your cart!');
    }

    public function test_cannot_add_unpublished_product_to_cart(): void
    {
        $category = Category::create(['name' => 'Puja Samagri', 'slug' => 'puja-samagri', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Secret Unpublished Idol',
            'slug'        => 'secret-unpublished-idol',
            'price'       => 5000,
            'status'      => 'draft',
            'is_active'   => false,
        ]);

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response->assertStatus(404);
        $this->assertEmpty(session('cart', []));
    }

    public function test_cannot_add_invalid_product_id_to_cart(): void
    {
        $response = $this->post(route('cart.store'), [
            'product_id' => 999999,
            'quantity'   => 1,
        ]);

        $response->assertSessionHasErrors('product_id');
        $this->assertEmpty(session('cart', []));
    }

    public function test_can_update_cart_quantity(): void
    {
        $cart = [
            10 => [
                'id'       => 10,
                'title'    => 'Radha Krishna Poshak',
                'price'    => 400,
                'quantity' => 1,
                'image'    => null,
                'slug'     => 'radha-krishna-poshak',
            ],
        ];

        session(['cart' => $cart]);

        $response = $this->patch(route('cart.update', 10), [
            'quantity' => 3,
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertEquals(3, session('cart')[10]['quantity']);
    }

    public function test_can_remove_item_from_cart(): void
    {
        $cart = [
            5 => [
                'id'       => 5,
                'title'    => 'Yamuna Jal',
                'price'    => 100,
                'quantity' => 2,
                'image'    => null,
                'slug'     => 'yamuna-jal',
            ],
        ];

        session(['cart' => $cart]);

        $response = $this->delete(route('cart.destroy', 5));

        $response->assertRedirect(route('cart.index'));
        $this->assertArrayNotHasKey(5, session('cart', []));
    }

    public function test_cart_page_shows_empty_state_when_cart_is_empty(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Your Seva Cart is empty');
        $response->assertSee('Explore Sacred Items');
    }

    public function test_cart_page_shows_items_and_whatsapp_order_link(): void
    {
        $cart = [
            1 => [
                'id'       => 1,
                'title'    => 'Sacred Tulsi Mala',
                'price'    => 250,
                'quantity' => 2,
                'image'    => null,
                'slug'     => 'sacred-tulsi-mala',
            ],
        ];

        session(['cart' => $cart]);

        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Sacred Tulsi Mala');
        $response->assertSee('Order via WhatsApp');
        $response->assertSee('https://wa.me/' . config('services.whatsapp.number'), false);
    }

    public function test_cart_page_shows_proper_calculation_including_100_rupee_delivery_charge_and_invoice(): void
    {
        $cart = [
            1 => [
                'id'       => 1,
                'title'    => 'Sacred Tulsi Mala',
                'price'    => 250,
                'quantity' => 2,
                'image'    => null,
                'slug'     => 'sacred-tulsi-mala',
            ],
            2 => [
                'id'       => 2,
                'title'    => 'Vrindavan Chandan',
                'price'    => 150,
                'quantity' => 1,
                'image'    => null,
                'slug'     => 'vrindavan-chandan',
            ],
        ];

        session(['cart' => $cart]);

        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Seva Order Invoice');
        $response->assertSee('Items Subtotal');
        $response->assertSee('₹650');
        $response->assertSee('Delivery Charge');
        $response->assertSee('+₹100');
        $response->assertSee('Total Payable Amount');
        $response->assertSee('₹750');
        $response->assertSee('Print / Save Invoice');
    }
}
