<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminMediaTest extends TestCase
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

    public function test_unauthenticated_user_cannot_access_media_library_and_is_redirected(): void
    {
        $response = $this->get(route('admin.media.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_user_cannot_access_media_library_and_receives_403(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.media.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_media_library_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.media.index'));

        $response->assertStatus(200);
        $response->assertSee('Central Media Library');
        $response->assertSee('Upload Image');
    }

    public function test_admin_can_upload_valid_image_and_stores_relative_path_on_public_disk(): void
    {
        Storage::fake('public');

        $pngContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $file = UploadedFile::fake()->createWithContent('pitambar-vastra.png', $pngContent);

        $response = $this->actingAs($this->admin)->post(route('admin.media.store'), [
            'image'    => $file,
            'title'    => 'Shri Radha Vallabh Pitambar Vastra',
            'alt_text' => 'Devotional yellow silk vastra for Thakur Ji',
        ]);

        $response->assertRedirect(route('admin.media.index'));
        $response->assertSessionHas('success');

        // Verify database record
        $this->assertDatabaseHas('media', [
            'title'    => 'Shri Radha Vallabh Pitambar Vastra',
            'alt_text' => 'Devotional yellow silk vastra for Thakur Ji',
        ]);

        $media = Media::first();
        $this->assertNotNull($media);

        // Verify stored path is strictly relative and under media/
        $this->assertStringStartsWith('media/', $media->file_path);
        $this->assertStringNotContainsString(':', $media->file_path); // No Windows drive letters
        $this->assertStringNotContainsString('\\', $media->file_path); // Normalized slashes

        // Verify file exists on public disk
        Storage::disk('public')->assertExists($media->file_path);

        // Verify accessor URL
        $this->assertEquals(Storage::disk('public')->url($media->file_path), $media->url);
    }

    public function test_upload_validation_fails_for_invalid_file_type(): void
    {
        Storage::fake('public');

        $invalidFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.media.store'), [
            'image' => $invalidFile,
            'title' => 'Invalid PDF Upload',
        ]);

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseCount('media', 0);
    }

    public function test_upload_validation_fails_for_oversized_file(): void
    {
        Storage::fake('public');

        // 12MB file exceeds 10MB (10240 KB) limit
        $largeFile = UploadedFile::fake()->create('huge-banner.jpg', 12500, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admin.media.store'), [
            'image' => $largeFile,
            'title' => 'Oversized Image',
        ]);

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseCount('media', 0);
    }

    public function test_admin_can_search_media_by_title(): void
    {
        Media::create([
            'title'     => 'Tulsi Kanthi Mala 3 Round',
            'file_path' => 'media/2026/10/mala.jpg',
            'alt_text'  => 'Sacred Tulsi Kanthi Mala',
            'mime_type' => 'image/jpeg',
            'file_size' => 120400,
        ]);

        Media::create([
            'title'     => 'Pavitra Yamuna Maharani Jal Kalash',
            'file_path' => 'media/2026/10/yamuna.jpg',
            'alt_text'  => 'Holy Yamuna Jal sealed bottle',
            'mime_type' => 'image/jpeg',
            'file_size' => 98400,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.media.index', ['q' => 'Tulsi Kanthi']));

        $response->assertStatus(200);
        $response->assertSee('Tulsi Kanthi Mala 3 Round');
        $response->assertDontSee('Pavitra Yamuna Maharani Jal Kalash');
    }

    public function test_admin_can_update_media_title_and_alt_text(): void
    {
        $media = Media::create([
            'title'     => 'Original Mala Title',
            'file_path' => 'media/2026/10/test-mala.jpg',
            'alt_text'  => 'Original Alt',
            'mime_type' => 'image/jpeg',
            'file_size' => 64000,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.media.update', $media), [
            'title'    => 'Updated Sacred Tulsi Mala Title',
            'alt_text' => 'Updated sacred alt description',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('media', [
            'id'       => $media->id,
            'title'    => 'Updated Sacred Tulsi Mala Title',
            'alt_text' => 'Updated sacred alt description',
        ]);
    }

    public function test_admin_can_safely_delete_media_and_removes_file_from_storage(): void
    {
        Storage::fake('public');

        $filePath = 'media/2026/10/darshan.jpg';
        Storage::disk('public')->put($filePath, 'fake image content');

        $media = Media::create([
            'title'     => 'Shri Radha Darshan',
            'file_path' => $filePath,
            'alt_text'  => 'Radha Darshan Chhavi',
            'mime_type' => 'image/jpeg',
            'file_size' => 54000,
        ]);

        Storage::disk('public')->assertExists($filePath);

        $response = $this->actingAs($this->admin)->delete(route('admin.media.destroy', $media));

        $response->assertRedirect(route('admin.media.index'));
        $response->assertSessionHas('success');

        // Record removed from database
        $this->assertDatabaseMissing('media', ['id' => $media->id]);

        // File deleted from storage
        Storage::disk('public')->assertMissing($filePath);
    }

    public function test_deletion_gracefully_handles_already_missing_file_on_disk(): void
    {
        Storage::fake('public');

        $missingPath = 'media/2026/10/already-gone.jpg';

        $media = Media::create([
            'title'     => 'Missing File Media',
            'file_path' => $missingPath,
            'mime_type' => 'image/jpeg',
            'file_size' => 12000,
        ]);

        // File is NOT on disk
        Storage::disk('public')->assertMissing($missingPath);

        $response = $this->actingAs($this->admin)->delete(route('admin.media.destroy', $media));

        $response->assertRedirect(route('admin.media.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }

    public function test_media_url_and_disk_existence_handling(): void
    {
        Storage::fake('public');

        $filePath = 'media/2026/10/tulsi.jpg';
        Storage::disk('public')->put($filePath, 'image bytes');

        $media = Media::create([
            'title'     => 'Tulsi Image',
            'file_path' => $filePath,
            'mime_type' => 'image/jpeg',
            'file_size' => 1024,
        ]);

        $this->assertTrue($media->existsOnDisk());
        $this->assertEquals(Storage::disk('public')->url($filePath), $media->url);
        $this->assertStringNotContainsString('\\', $media->url);

        // When file is deleted, existsOnDisk becomes false
        Storage::disk('public')->delete($filePath);
        $this->assertFalse($media->existsOnDisk());
    }

    public function test_admin_media_index_renders_storage_url_for_existing_file(): void
    {
        Storage::fake('public');

        $filePath = 'media/2026/10/darshan-chhavi.jpg';
        Storage::disk('public')->put($filePath, 'darshan content');

        $media = Media::create([
            'title'     => 'Darshan Chhavi',
            'file_path' => $filePath,
            'mime_type' => 'image/jpeg',
            'file_size' => 2048,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.media.index'));

        $response->assertStatus(200);
        $response->assertSee(Storage::disk('public')->url($filePath));
        $response->assertDontSee('File Missing');
    }
}

