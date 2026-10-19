<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'media_id',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'media_id'   => 'integer',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Alias 'title' to 'name' for flexible API and template usage.
     */
    public function getTitleAttribute(): string
    {
        return $this->name;
    }

    public function setTitleAttribute(string $value): void
    {
        $this->attributes['name'] = $value;
    }

    /**
     * Alias 'image_id' to 'media_id'.
     */
    public function getImageIdAttribute(): ?int
    {
        return $this->media_id;
    }

    public function setImageIdAttribute(?int $value): void
    {
        $this->attributes['media_id'] = $value;
    }

    /**
     * Get the category image URL from the associated shared Media asset.
     */
    public function getImageUrlAttribute(): ?string
    {
        return ($this->media && $this->media->existsOnDisk()) ? $this->media->url : null;
    }

    /**
     * Devotional emoji icon based on category slug.
     */
    public function getIconAttribute(): string
    {
        return match ($this->slug) {
            'braj-raj', 'raj'                => '🏺',
            'yamuna-jal', 'jal'              => '💧',
            'tulsi-mala'                     => '📿',
            'kanthi-mala'                    => '📿',
            'thakur-ji-vastra', 'deity-wear' => '👑',
            'chhavi-guruji'                  => '🖼️',
            'pooja-samagri', 'puja-items'    => '🪔',
            'other-seva', 'books'            => '🎁',
            default                          => '📿',
        };
    }

    /**
     * Associated shared Media asset.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Products assigned directly to this category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Scope for active categories only.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Count products assigned directly to this category.
     */
    public function allProductsCount(): int
    {
        return $this->products()->count();
    }

    /**
     * Determine if category can be safely deleted.
     */
    public function hasAssociatedProducts(): bool
    {
        return $this->products()->exists();
    }

    /**
     * Boot model logic: auto-slug generation if missing.
     */
    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
