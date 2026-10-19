<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email'    => 'admin@harivanshisevakunj.com',
            'is_admin' => true,
        ]);

        $this->regularUser = User::factory()->create([
            'email'    => 'devotee@example.com',
            'is_admin' => false,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_products(): void
    {
        $response = $this->get(route('admin.products.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_user_cannot_access_products(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.products.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_products_index(): void
    {
        $category = Category::create(['name' => 'Tulsi Mala', 'slug' => 'tulsi-mala']);

        Product::create([
            'category_id' => $category->id,
            'title'       => 'Original Shyam Tulsi Kanthi',
            'slug'        => 'original-shyam-tulsi-kanthi',
            'price'       => 299,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertSee('Products Management');
        $response->assertSee('Original Shyam Tulsi Kanthi');
        $response->assertSee('Tulsi Mala');
        $response->assertSee('₹299');
    }

    public function test_admin_can_view_create_product_page(): void
    {
        Category::create(['name' => 'Tulsi Mala', 'slug' => 'tulsi-mala']);

        $response = $this->actingAs($this->admin)->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertSee('Add New Product');
        $response->assertSee('Tulsi Mala');
        $response->assertDontSee('Step 1: Select Parent Category');
        $response->assertDontSee('Step 2: Select Subcategory');
    }

    public function test_admin_can_create_product_with_category_and_shared_media(): void
    {
        $category = Category::create(['name' => 'Raj', 'slug' => 'raj']);

        $media = Media::create([
            'title'     => 'Black Raj Jar Photo',
            'file_path' => 'media/2026/10/black-raj.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 2048,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id'      => $category->id,
            'media_id'         => $media->id,
            'title'            => 'Pavitra Black Raj 50g',
            'slug'             => 'pavitra-black-raj-50g',
            'price'            => 150.00,
            'original_price'   => 199.00,
            'badge'            => 'Sacred',
            'description'      => 'Authentic sacred dust from Raman Reti, Vrindavan.',
            'meta_description' => 'Original Raman Reti Black Raj for tilak and daily puja.',
            'status'           => 'published',
            'is_active'        => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'category_id'      => $category->id,
            'media_id'         => $media->id,
            'title'            => 'Pavitra Black Raj 50g',
            'slug'             => 'pavitra-black-raj-50g',
            'price'            => 150.00,
            'original_price'   => 199.00,
            'status'           => 'published',
            'is_active'        => true,
        ]);
    }

    public function test_product_slug_must_be_unique(): void
    {
        $category = Category::create(['name' => 'Raj', 'slug' => 'raj']);

        Product::create([
            'category_id' => $category->id,
            'title'       => 'Black Raj',
            'slug'        => 'black-raj',
            'price'       => 100,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'title'       => 'Another Black Raj',
            'slug'        => 'black-raj',
            'price'       => 120,
            'status'      => 'published',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    public function test_admin_can_view_edit_product_page(): void
    {
        $category = Category::create(['name' => 'Raj', 'slug' => 'raj']);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Pavitra Black Raj 50g',
            'slug'        => 'pavitra-black-raj-50g',
            'price'       => 150.00,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertSee('Edit Product: Pavitra Black Raj 50g');
        $response->assertDontSee('Step 1: Select Parent Category');
        $response->assertDontSee('Step 2: Select Subcategory');
    }

    public function test_admin_can_update_product(): void
    {
        $category = Category::create(['name' => 'Raj', 'slug' => 'raj']);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Old Product Title',
            'slug'        => 'old-product-title',
            'price'       => 100,
            'status'      => 'draft',
            'is_active'   => false,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'category_id'      => $category->id,
            'title'            => 'Updated Product Title',
            'slug'             => 'updated-product-title',
            'price'            => 250,
            'original_price'   => 350,
            'badge'            => 'Bestseller',
            'rating'           => 4.9,
            'reviews_count'    => 185,
            'status'           => 'published',
            'is_active'        => '1',
            'meta_description' => 'Updated SEO Description',
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id'               => $product->id,
            'title'            => 'Updated Product Title',
            'slug'             => 'updated-product-title',
            'price'            => 250,
            'original_price'   => 350,
            'badge'            => 'Bestseller',
            'rating'           => 4.9,
            'reviews_count'    => 185,
            'status'           => 'published',
            'is_active'        => true,
            'meta_description' => 'Updated SEO Description',
        ]);
    }

    public function test_admin_can_toggle_product_status(): void
    {
        $category = Category::create(['name' => 'Raj', 'slug' => 'raj']);

        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Black Raj',
            'slug'        => 'black-raj',
            'price'       => 100,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        // Toggle to inactive (draft)
        $response = $this->actingAs($this->admin)->patch(route('admin.products.toggle', $product));
        $response->assertSessionHas('success');
        $this->assertFalse($product->fresh()->is_active);
        $this->assertEquals('draft', $product->fresh()->status);

        // Toggle back to active (published)
        $this->actingAs($this->admin)->patch(route('admin.products.toggle', $product));
        $this->assertTrue($product->fresh()->is_active);
        $this->assertEquals('published', $product->fresh()->status);
    }

    public function test_admin_can_delete_product_without_deleting_media_or_category(): void
    {
        $category = Category::create(['name' => 'Raj', 'slug' => 'raj']);

        $media = Media::create([
            'title'     => 'Shared Asset',
            'file_path' => 'media/2026/10/shared.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1024,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'media_id'    => $media->id,
            'title'       => 'Product to Delete',
            'slug'        => 'product-to-delete',
            'price'       => 100,
            'status'      => 'draft',
            'is_active'   => false,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        // Verify media and category remain completely untouched
        $this->assertDatabaseHas('media', ['id' => $media->id]);
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_can_filter_products_by_category(): void
    {
        $catRaj = Category::create(['name' => 'Raj', 'slug' => 'raj']);
        $catJal = Category::create(['name' => 'Jal', 'slug' => 'jal']);

        $p1 = Product::create([
            'category_id' => $catRaj->id,
            'title'       => 'Raman Reti Black Raj',
            'slug'        => 'raman-reti-black-raj',
            'price'       => 100,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        $p2 = Product::create([
            'category_id' => $catJal->id,
            'title'       => 'Shri Yamuna Maharani Jal',
            'slug'        => 'shri-yamuna-maharani-jal',
            'price'       => 50,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        // Filter by Raj category
        $response = $this->actingAs($this->admin)->get(route('admin.products.index', ['category_id' => $catRaj->id]));

        $response->assertStatus(200);
        $response->assertSee('Raman Reti Black Raj');
        $response->assertDontSee('Shri Yamuna Maharani Jal');
    }

    public function test_product_rating_and_reviews_count_validation(): void
    {
        $category = Category::create(['name' => 'Raj', 'slug' => 'raj']);

        // Rating > 5 fails
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id'   => $category->id,
            'title'         => 'Test Invalid Rating',
            'slug'          => 'test-invalid-rating',
            'price'         => 100,
            'rating'        => 6.5,
            'status'        => 'published',
        ]);
        $response->assertSessionHasErrors('rating');

        // Negative reviews count fails
        $response2 = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id'   => $category->id,
            'title'         => 'Test Invalid Reviews',
            'slug'          => 'test-invalid-reviews',
            'price'         => 100,
            'reviews_count' => -5,
            'status'        => 'published',
        ]);
        $response2->assertSessionHasErrors('reviews_count');
    }
}
