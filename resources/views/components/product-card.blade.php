{{--
    Product Card Component
    ─────────────────────────────────────────────────────────────
    Displays sacred storefront product card matching the design:
      1. Ornate golden filigree corner accents & junction flourishes
      2. Bestseller crown pill badge (top-left)
      3. White circular wishlist heart button (top-right)
      4. Scalloped curved transition between image and white body
      5. Price with strike-through MRP and pastel discount pill
      6. Star rating and reviews count
      7. Dual action buttons: Outline Quick Cart + Filled Gold Add to Cart
    ─────────────────────────────────────────────────────────────
    Props:
      $id            – int, product ID (required for cart actions)
      $name          – string, product title
      $price         – numeric/string, product price
      $originalPrice – numeric/string, crossed-out MRP (optional)
      $image         – string, asset/storage path (optional)
      $badge         – string, e.g. "Bestseller", "Sacred" (optional)
      $rating        – numeric, e.g. 4.8 (optional)
      $reviewsCount  – int, e.g. 124 (optional)
      $slug          – string, product slug (optional)
      $isDemo        – bool, demo mode indicator (default false)
      $product       – \App\Models\Product (optional)
    ─────────────────────────────────────────────────────────────
--}}
@props([
    'id'            => null,
    'name'          => 'Product Name',
    'price'         => '0',
    'originalPrice' => null,
    'image'         => null,
    'badge'         => null,
    'rating'        => null,
    'reviewsCount'  => null,
    'slug'          => null,
    'isDemo'        => false,
    'product'       => null,
])

@php
    if ($product instanceof \App\Models\Product) {
        $id            = $id ?? $product->id;
        $name          = ($name !== 'Product Name') ? $name : $product->title;
        $price         = ($price !== '0') ? $price : $product->price;
        $originalPrice = $originalPrice ?? $product->original_price;
        $image         = $image ?? $product->image_url;
        $badge         = $badge ?? $product->badge;
        $rating        = $rating ?? $product->rating ?? 4.8;
        $reviewsCount  = $reviewsCount ?? $product->reviews_count ?? (100 + (($product->id * 17) % 85));
        $slug          = $slug ?? $product->slug;
        $isDemo        = false;
    }

    $hasDetailLink = !empty($slug) && !$isDemo;
    $productDetailUrl = $hasDetailLink ? route('products.show', $slug) : null;
    $productUrl = $productDetailUrl ?: url('/products?q=' . urlencode($name));

    $isRemoteOrStorage = $image && (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '/storage') || str_starts_with($image, 'storage/'));
    $imageSrc = $isRemoteOrStorage ? $image : ($image ? asset($image) : null);
    $hasImage = $isRemoteOrStorage || ($image && file_exists(public_path($image)));

    // Calculate discount percentage
    $discountPercent = 0;
    if (!empty($originalPrice) && (float)$originalPrice > (float)$price && (float)$originalPrice > 0) {
        $discountPercent = (int) round((((float)$originalPrice - (float)$price) / (float)$originalPrice) * 100);
    }

    $badgeText = $badge ?: 'Bestseller';
    $ratingScore = number_format((float)($rating ?? 4.8), 1);
    $reviewCount = (int)($reviewsCount ?? ($id ? (100 + (($id * 17) % 85)) : 124));

    $whatsappUrl = null;
    if ($product instanceof \App\Models\Product) {
        $whatsappUrl = $product->whatsapp_enquiry_url;
    } elseif (!empty($id)) {
        $foundProduct = \App\Models\Product::find($id);
        if ($foundProduct) {
            $whatsappUrl = $foundProduct->whatsapp_enquiry_url;
        }
    }
@endphp

<article class="product-card {{ $isDemo ? 'product-card--demo' : '' }}" aria-label="{{ $name }}" data-product-id="{{ $id }}">

    {{-- 4 Outer Filigree Accents: Top-Left, Top-Right, Bottom-Left, Bottom-Right --}}
    <svg class="product-card__filigree product-card__filigree--tl" width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M2.5 24C2.5 12.1 12.1 2.5 24 2.5" stroke="#D4A843" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M5 20C5 11.7 11.7 5 20 5" stroke="#E6C265" stroke-width="1.2" stroke-linecap="round"/>
        <path d="M22 22L7 7M7 7C11 8 13 12 13 16M7 7C8 11 12 13 16 13" stroke="#D4A843" stroke-width="1.6" stroke-linecap="round"/>
        <path d="M12 12C14 10 17 10 19 12C21 14 21 17 19 19C17 21 14 21 12 19C10 17 10 14 12 12Z" fill="#D4A843" fill-opacity="0.25" stroke="#D4A843" stroke-width="1.2"/>
        <circle cx="4.5" cy="4.5" r="2.2" fill="#D4A843"/>
        <circle cx="14" cy="3.5" r="1.6" fill="#D4A843"/>
        <circle cx="3.5" cy="14" r="1.6" fill="#D4A843"/>
    </svg>

    <svg class="product-card__filigree product-card__filigree--tr" width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <g transform="scale(-1, 1) translate(-36, 0)">
            <path d="M2.5 24C2.5 12.1 12.1 2.5 24 2.5" stroke="#D4A843" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M5 20C5 11.7 11.7 5 20 5" stroke="#E6C265" stroke-width="1.2" stroke-linecap="round"/>
            <path d="M22 22L7 7M7 7C11 8 13 12 13 16M7 7C8 11 12 13 16 13" stroke="#D4A843" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M12 12C14 10 17 10 19 12C21 14 21 17 19 19C17 21 14 21 12 19C10 17 10 14 12 12Z" fill="#D4A843" fill-opacity="0.25" stroke="#D4A843" stroke-width="1.2"/>
            <circle cx="4.5" cy="4.5" r="2.2" fill="#D4A843"/>
            <circle cx="14" cy="3.5" r="1.6" fill="#D4A843"/>
            <circle cx="3.5" cy="14" r="1.6" fill="#D4A843"/>
        </g>
    </svg>

    <svg class="product-card__filigree product-card__filigree--bl" width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M3 27C3 16 11 8 22 8M3 27C14 27 22 19 22 8" stroke="#D4A843" stroke-width="1.6" stroke-linecap="round"/>
        <circle cx="5" cy="25" r="2" fill="#D4A843"/>
        <circle cx="3.5" cy="14" r="1.5" fill="#D4A843"/>
        <circle cx="14" cy="26.5" r="1.5" fill="#D4A843"/>
    </svg>

    <svg class="product-card__filigree product-card__filigree--br" width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <g transform="scale(-1, 1) translate(-30, 0)">
            <path d="M3 27C3 16 11 8 22 8M3 27C14 27 22 19 22 8" stroke="#D4A843" stroke-width="1.6" stroke-linecap="round"/>
            <circle cx="5" cy="25" r="2" fill="#D4A843"/>
            <circle cx="3.5" cy="14" r="1.5" fill="#D4A843"/>
            <circle cx="14" cy="26.5" r="1.5" fill="#D4A843"/>
        </g>
    </svg>

    {{-- Demo indicator if applicable --}}
    @if($isDemo)
        <div class="product-card__demo-banner" aria-label="This is a demo placeholder">
            Demo placeholder
        </div>
    @endif

    {{-- Product Visual Area --}}
    <div class="product-card__image-wrap">
        @if($hasDetailLink)
            <a href="{{ $productUrl }}" class="product-card__image-link" aria-label="View {{ $name }}">
        @endif

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
                         class="product-card__image"
                         width="400"
                         height="320"
                         loading="lazy"
                         onerror="this.closest('picture').style.display='none'; if(this.closest('.product-card__image-wrap').querySelector('.product-card__image-placeholder')) this.closest('.product-card__image-wrap').querySelector('.product-card__image-placeholder').style.display='flex';">
                </picture>
            @else
                <img src="{{ $imageSrc }}"
                     alt="{{ $name }}"
                     class="product-card__image"
                     width="400"
                     height="320"
                     loading="lazy"
                     onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
            @endif
            <div class="product-card__image-placeholder" style="display: none;" aria-hidden="true">
                <span>🛍️</span>
            </div>
        @else
            <div class="product-card__image-placeholder" aria-hidden="true">
                <span>🛍️</span>
            </div>
        @endif

        @if($hasDetailLink)
            </a>
        @endif

        {{-- Golden Crown Highlight Badge (Top Left) --}}
        <span class="product-card__badge">
            <svg class="product-card__badge-crown" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#3D1A06" aria-hidden="true">
                <path d="M2.5 18h19v2.5h-19V18zm1.5-3l2.5-8.5 5 4.5 4.5-6.5 4.5 6.5 5-4.5 2.5 8.5H4z"/>
            </svg>
            <span>{{ $badgeText }}</span>
        </span>

        {{-- Wishlist Heart Button (Top Right) --}}
        <button type="button" class="product-card__wishlist" aria-label="Add {{ $name }} to wishlist" data-product-id="{{ $id }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
        </button>
    </div>

    {{-- Product Body with Scalloped Curved Overlap --}}
    <div class="product-card__body">
        {{-- Middle Junction Filigrees anchored to upper corners of white body --}}
        <svg class="product-card__filigree product-card__filigree--ml" width="30" height="34" viewBox="0 0 30 34" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M1 17C7 17 14 12 14 4M1 17C7 17 14 22 14 30M1 17H12" stroke="#D4A843" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M6 11C10 13 10 21 6 23" stroke="#E6C265" stroke-width="1.2" stroke-linecap="round"/>
            <path d="M10 17L17 12L24 17L17 22Z" fill="#D4A843" fill-opacity="0.3" stroke="#D4A843" stroke-width="1.2"/>
            <circle cx="2" cy="17" r="2" fill="#D4A843"/>
            <circle cx="14" cy="4" r="1.6" fill="#D4A843"/>
            <circle cx="14" cy="30" r="1.6" fill="#D4A843"/>
            <circle cx="24" cy="17" r="1.4" fill="#D4A843"/>
        </svg>

        <svg class="product-card__filigree product-card__filigree--mr" width="30" height="34" viewBox="0 0 30 34" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <g transform="scale(-1, 1) translate(-30, 0)">
                <path d="M1 17C7 17 14 12 14 4M1 17C7 17 14 22 14 30M1 17H12" stroke="#D4A843" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M6 11C10 13 10 21 6 23" stroke="#E6C265" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M10 17L17 12L24 17L17 22Z" fill="#D4A843" fill-opacity="0.3" stroke="#D4A843" stroke-width="1.2"/>
                <circle cx="2" cy="17" r="2" fill="#D4A843"/>
                <circle cx="14" cy="4" r="1.6" fill="#D4A843"/>
                <circle cx="14" cy="30" r="1.6" fill="#D4A843"/>
                <circle cx="24" cy="17" r="1.4" fill="#D4A843"/>
            </g>
        </svg>

        {{-- Title --}}
        <h3 class="product-card__name">
            @if($hasDetailLink)
                <a href="{{ $productUrl }}" class="product-card__title-link">{{ $name }}</a>
            @else
                {{ $name }}
            @endif
        </h3>

        {{-- Pricing Row with Discount Pill on the right --}}
        <div class="product-card__pricing-row">
            <div class="product-card__pricing">
                <span class="product-card__price">₹{{ number_format((float)$price) }}</span>
                @if($originalPrice && (float)$originalPrice > (float)$price)
                    <span class="product-card__original-price">₹{{ number_format((float)$originalPrice) }}</span>
                @endif
            </div>

            @if($discountPercent > 0)
                <span class="product-card__discount-pill">{{ $discountPercent }}% OFF</span>
            @endif
        </div>

        {{-- Star Rating & Reviews Count --}}
        <div class="product-card__rating">
            <svg class="product-card__star-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#F5BA13" aria-hidden="true">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
            <strong class="product-card__rating-score">{{ $ratingScore }}</strong>
            <span class="product-card__rating-count">({{ $reviewCount }} reviews)</span>
        </div>

        {{-- Purchase Actions Row: Left side = Add to Cart, Right side = WhatsApp --}}
        <div class="product-card__actions">
            {{-- Left Side: Add to Cart --}}
            <form action="{{ route('cart.store') }}" method="POST" class="product-card__cart-form" data-product-id="{{ $id }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $id }}">
                <input type="hidden" name="quantity" value="1">

                <button type="submit"
                        class="btn product-card__btn product-card__btn--cart"
                        aria-label="Add {{ $name }} to cart"
                        title="Add to Cart"
                        @if(!$id || $isDemo) disabled title="Product demo mode me hai" @endif>
                    <svg class="product-card__btn-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="8" cy="21" r="1.5" fill="currentColor"></circle>
                        <circle cx="19" cy="21" r="1.5" fill="currentColor"></circle>
                        <path d="M2.5 2.5h3l2.4 12a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.6-7.5H6.2"></path>
                    </svg>
                    <span class="product-card__btn-text">
                        <span class="product-card__btn-text--full">Add to Cart</span>
                        <span class="product-card__btn-text--mobile">Add</span>
                    </span>
                </button>
            </form>

            {{-- Right Side: WhatsApp Button --}}
            @if(\App\Support\WhatsApp::isValidNumber() && $whatsappUrl)
                <a href="{{ $whatsappUrl }}"
                   class="btn product-card__btn product-card__btn--whatsapp"
                   target="_blank"
                   rel="noopener noreferrer"
                   aria-label="Buy on WhatsApp for {{ $name }}"
                   title="Buy on WhatsApp">
                    <svg class="product-card__btn-icon product-card__btn-icon--whatsapp" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2zm.01 1.67c2.2 0 4.26.86 5.82 2.42a8.225 8.225 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.196 8.196 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24zm4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.24-.74-.66-1.24-1.48-1.39-1.73-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.43.13-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.78 2.71 4.3 3.8 2.53 1.09 2.53.73 2.99.69.45-.05 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.17-.48-.29z"/>
                    </svg>
                    <span class="product-card__btn-text">
                        <span class="product-card__btn-text--full">WhatsApp</span>
                        <span class="product-card__btn-text--mobile">Chat</span>
                    </span>
                    <span class="sr-only">Buy on WhatsApp</span>
                </a>
            @else
                <button type="button"
                        class="btn product-card__btn product-card__btn--whatsapp product-card__btn--disabled"
                        disabled
                        title="WhatsApp Unavailable"
                        aria-label="WhatsApp Unavailable">
                    <svg class="product-card__btn-icon product-card__btn-icon--whatsapp" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2zm.01 1.67c2.2 0 4.26.86 5.82 2.42a8.225 8.225 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.196 8.196 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24zm4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.24-.74-.66-1.24-1.48-1.39-1.73-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.43.13-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.78 2.71 4.3 3.8 2.53 1.09 2.53.73 2.99.69.45-.05 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.17-.48-.29z"/>
                    </svg>
                    <span class="product-card__btn-text">
                        <span class="product-card__btn-text--full">WhatsApp</span>
                        <span class="product-card__btn-text--mobile">Chat</span>
                    </span>
                </button>
            @endif
        </div>
    </div>

</article>
