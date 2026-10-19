<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'file_path',
        'alt_text',
        'mime_type',
        'file_size',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'url',
        'formatted_size',
    ];

    /**
     * Get the publicly accessible URL for the media asset.
     */
    public function getUrlAttribute(): ?string
    {
        if (empty($this->file_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Get human-readable file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int) $this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }

    /**
     * Check whether the file currently exists on the public storage disk.
     */
    public function existsOnDisk(): bool
    {
        return !empty($this->file_path) && Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Safely check if this media asset is referenced by other models/tables.
     */
    public function isReferenced(): bool
    {
        // Check future products table
        if (Schema::hasTable('products')) {
            if (Schema::hasColumn('products', 'media_id') && DB::table('products')->where('media_id', $this->id)->exists()) {
                return true;
            }
            if (Schema::hasColumn('products', 'image') && DB::table('products')->where('image', $this->file_path)->exists()) {
                return true;
            }
        }

        // Check future categories table
        if (Schema::hasTable('categories')) {
            if (Schema::hasColumn('categories', 'media_id') && DB::table('categories')->where('media_id', $this->id)->exists()) {
                return true;
            }
            if (Schema::hasColumn('categories', 'image') && DB::table('categories')->where('image', $this->file_path)->exists()) {
                return true;
            }
        }

        // Check future product gallery images table
        if (Schema::hasTable('product_images')) {
            if (Schema::hasColumn('product_images', 'media_id') && DB::table('product_images')->where('media_id', $this->id)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Safely delete the physical file from disk if not referenced elsewhere.
     */
    public function deleteFile(): bool
    {
        if ($this->isReferenced()) {
            return false;
        }

        if ($this->existsOnDisk()) {
            return Storage::disk('public')->delete($this->file_path);
        }

        return true;
    }
}
