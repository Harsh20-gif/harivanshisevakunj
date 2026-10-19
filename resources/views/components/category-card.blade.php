{{--
    Category Card Component (Horizontal Circular Navigation Style)
    ─────────────────────────────────────────────────────────────
    Props:
      $name     – string, category display name
      $slug     – string, used to build route URL
      $icon     – string, emoji or fallback devotional icon
      $image    – string, image path/URL from media library
      $isActive – bool, whether this category is currently active/selected
      $url      – string, optional custom target URL
    ─────────────────────────────────────────────────────────────
--}}
@props([
    'name'     => '',
    'slug'     => '',
    'icon'     => '📿',
    'image'    => null,
    'isActive' => false,
    'url'      => null,
])

@php
    $targetUrl = $url ?: route('products.index', ['category' => $slug]);
    $isRemoteOrStorage = $image && (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '/storage') || str_starts_with($image, 'storage/'));
    $imageSrc = $isRemoteOrStorage ? $image : ($image ? asset($image) : null);
    $hasImage = $isRemoteOrStorage || ($image && file_exists(public_path($image)));
@endphp

<a href="{{ $targetUrl }}"
   class="category-card shop-category-item {{ $isActive ? 'shop-category-item--active' : '' }}"
   aria-current="{{ $isActive ? 'page' : 'false' }}"
   title="{{ $name }}">

    <div class="shop-category-circle">
        @if($hasImage)
            @php
                $webpSrc = null;
                if (is_string($imageSrc)) {
                    $candidate = preg_replace('/\.(png|jpe?g)$/i', '.webp', $imageSrc);
                    if ($candidate !== $imageSrc) {
                        $rel = preg_replace('#^https?://[^/]+/#', '', $candidate);
                        $rel = ltrim($rel, '/');
                        if (file_exists(public_path($rel)) || file_exists(storage_path('app/public/' . preg_replace('#^storage/#', '', $rel)))) {
                            $webpSrc = $candidate;
                        }
                    }
                }
            @endphp
            @if($webpSrc)
                <picture>
                    <source srcset="{{ $webpSrc }}" type="image/webp">
                    <img src="{{ $imageSrc }}"
                         alt="{{ $name }}"
                         class="shop-category-img"
                         width="84"
                         height="84"
                         loading="lazy"
                         onerror="this.closest('picture').style.display='none'; if(this.closest('.shop-category-circle').querySelector('.shop-category-placeholder')) this.closest('.shop-category-circle').querySelector('.shop-category-placeholder').style.display='flex';">
                </picture>
            @else
                <img src="{{ $imageSrc }}"
                     alt="{{ $name }}"
                     class="shop-category-img"
                     width="84"
                     height="84"
                     loading="lazy"
                     onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
            @endif
            <div class="shop-category-placeholder" style="display: none;" aria-hidden="true">
                <span>{{ $icon ?: '📿' }}</span>
            </div>
        @else
            <div class="shop-category-placeholder" aria-hidden="true">
                <span>{{ $icon ?: '📿' }}</span>
            </div>
        @endif
    </div>

    <span class="shop-category-name">{{ $name }}</span>
</a>
