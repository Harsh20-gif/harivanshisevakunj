<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'media_id',
        'price',
        'original_price',
        'description',
        'meta_description',
        'badge',
        'rating',
        'reviews_count',
        'status',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'category_id'    => 'integer',
        'media_id'       => 'integer',
        'price'          => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating'         => 'float',
        'reviews_count'  => 'integer',
        'is_active'      => 'boolean',
    ];

    /**
     * Alias 'name' to 'title' for storefront component compatibility.
     */
    public function getNameAttribute(): string
    {
        return $this->title;
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['title'] = $value;
    }

    /**
     * Alias 'seo_description' to 'meta_description'.
     */
    public function getSeoDescriptionAttribute(): ?string
    {
        return $this->meta_description;
    }

    public function setSeoDescriptionAttribute(?string $value): void
    {
        $this->attributes['meta_description'] = $value;
    }

    /**
     * Get the product image URL from the associated shared Media asset.
     */
    public function getImageUrlAttribute(): ?string
    {
        return ($this->media && $this->media->existsOnDisk()) ? $this->media->url : null;
    }

    /**
     * Get the absolute public image URL for social previews and WhatsApp enquiries.
     */
    public function getAbsoluteImageUrlAttribute(): ?string
    {
        return \App\Support\WhatsApp::resolveAbsoluteImageUrl($this->image_url);
    }

    /**
     * Get the WhatsApp click-to-chat enquiry URL for this product.
     */
    public function getWhatsappEnquiryUrlAttribute(): ?string
    {
        return \App\Support\WhatsApp::generateProductEnquiryUrl($this);
    }

    /**
     * The category this product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Backward-compatible alias for category in flat hierarchy.
     */
    public function getParentCategoryAttribute(): ?Category
    {
        return $this->category;
    }

    /**
     * The associated shared Media asset from the central Media Library.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Scope for published and active products for storefront visibility.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('status', 'published');
    }

    /**
     * Scope for active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Boot model logic: auto-slug generation if missing.
     */
    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });
    }
}
