<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
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

    public function test_unauthenticated_user_cannot_access_categories(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_user_cannot_access_categories(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.categories.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_categories_index(): void
    {
        Category::create([
            'name'       => 'Raj',
            'slug'       => 'raj',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name'       => 'Tulsi Mala',
            'slug'       => 'tulsi-mala',
            'is_active'  => true,
            'sort_order' => 2,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Categories');
        $response->assertSee('Raj');
        $response->assertSee('Tulsi Mala');
    }

    public function test_admin_can_view_create_category_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertSee('New Category Information');
        $response->assertDontSee('Hierarchy Level');
        $response->assertDontSee('Subcategory');
    }

    public function test_admin_can_create_category(): void
    {
        $media = Media::create([
            'title'     => 'Raj Icon',
            'file_path' => 'media/2026/10/raj.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1024,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name'              => 'Yamuna Jal',
            'slug'              => 'yamuna-jal',
            'short_description' => 'Sacred holy waters from Keshi Ghat, Vrindavan.',
            'media_id'          => $media->id,
            'sort_order'        => 1,
            'is_active'         => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name'              => 'Yamuna Jal',
            'slug'              => 'yamuna-jal',
            'short_description' => 'Sacred holy waters from Keshi Ghat, Vrindavan.',
            'media_id'          => $media->id,
            'sort_order'        => 1,
            'is_active'         => true,
        ]);
    }

    public function test_category_slug_must_be_unique(): void
    {
        Category::create(['name' => 'Raj', 'slug' => 'raj']);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Another Raj',
            'slug' => 'raj',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    public function test_admin_can_view_edit_category_page(): void
    {
        $category = Category::create([
            'name'       => 'Pooja Samagri',
            'slug'       => 'pooja-samagri',
            'sort_order' => 5,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.edit', $category));

        $response->assertStatus(200);
        $response->assertSee('Edit Category: Pooja Samagri');
        $response->assertDontSee('Hierarchy Level');
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::create([
            'name'       => 'Old Name',
            'slug'       => 'old-name',
            'sort_order' => 5,
            'is_active'  => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name'       => 'Updated Name',
            'slug'       => 'updated-name',
            'sort_order' => 10,
            'is_active'  => '0',
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id'         => $category->id,
            'name'       => 'Updated Name',
            'slug'       => 'updated-name',
            'sort_order' => 10,
            'is_active'  => false,
        ]);
    }

    public function test_admin_can_toggle_category_status(): void
    {
        $category = Category::create(['name' => 'Raj', 'slug' => 'raj', 'is_active' => true]);

        $response = $this->actingAs($this->admin)->patch(route('admin.categories.toggle', $category));

        $response->assertSessionHas('success');
        $this->assertFalse($category->fresh()->is_active);

        $this->actingAs($this->admin)->patch(route('admin.categories.toggle', $category));
        $this->assertTrue($category->fresh()->is_active);
    }

    public function test_admin_can_delete_empty_category(): void
    {
        $category = Category::create(['name' => 'Empty Cat', 'slug' => 'empty-cat']);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = Category::create(['name' => 'Raj', 'slug' => 'raj']);

        // Create product directly under category
        $product = Product::create([
            'category_id' => $category->id,
            'title'       => 'Sacred Black Raj 100g',
            'slug'        => 'sacred-black-raj-100g',
            'price'       => 150,
            'status'      => 'published',
            'is_active'   => true,
        ]);

        // Attempt to delete category that has products -> MUST BE BLOCKED
        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }
}
