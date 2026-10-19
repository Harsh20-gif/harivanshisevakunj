{{--
    Live Storefront Card Preview Component
    ─────────────────────────────────────────────────────────────
    Renders real-time interactive preview of how the sacred product
    card appears on the customer storefront.
    Props:
      $product - ?App\Models\Product (optional)
    ─────────────────────────────────────────────────────────────
--}}
@props([
    'product' => null,
])

@php
    $previewTitle = $product?->title ?: 'Sacred Vrindavan Item';
    $previewPrice = $product?->price ? number_format((float)$product->price) : '299';
    $previewRawPrice = (float)($product?->price ?? 299);
    $previewOriginalPrice = $product?->original_price ? number_format((float)$product->original_price) : null;
    $previewRawOriginalPrice = $product?->original_price ? (float)$product->original_price : 0;
    $previewBadge = $product?->badge ?: 'Bestseller';
    $previewRating = number_format((float)($product?->rating ?? 4.8), 1);
    $previewReviews = (int)($product?->reviews_count ?? 124);
    $previewMediaUrl = ($product?->media && $product->media->existsOnDisk()) ? $product->media->url : null;
    $previewDiscount = 0;
    if ($previewRawOriginalPrice > $previewRawPrice && $previewRawOriginalPrice > 0) {
        $previewDiscount = (int) round((($previewRawOriginalPrice - $previewRawPrice) / $previewRawOriginalPrice) * 100);
    }
    $previewIsPublished = ($product?->status ?? 'published') === 'published';
    $previewIsActive = (bool)($product?->is_active ?? true);
@endphp

<div class="admin-preview-sticky">
    <div class="admin-preview-header">
        <div class="admin-preview-header__title">
            <span style="font-size: 1.15rem;" aria-hidden="true">🎴</span>
            <strong style="color: var(--color-heading); font-size: 0.95rem;">Live Storefront Card Preview</strong>
        </div>
        <span class="admin-preview-status-pill {{ ($previewIsPublished && $previewIsActive) ? 'admin-preview-status-pill--live' : 'admin-preview-status-pill--draft' }}" id="live-preview-status-badge">
            <span class="admin-preview-status-pill__dot"></span>
            <span id="live-preview-status-text">{{ ($previewIsPublished && $previewIsActive) ? 'Live on Store' : 'Draft / Unlisted' }}</span>
        </span>
    </div>

    <p class="admin-preview-desc">
        Real-time replica matching the exact storefront layout with ornate gold filigree, crown badge, price, discount pill &amp; dual cart buttons.
    </p>

    <div class="admin-preview-card-frame">
        <article class="product-card" id="admin-live-product-card" aria-label="Live storefront preview">

            {{-- 4 Outer Sacred Filigree Corner Accents --}}
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

            {{-- Product Visual Area --}}
            <div class="product-card__image-wrap">
                <img id="live-card-img"
                     src="{{ $previewMediaUrl ?: '' }}"
                     alt="Live Product Preview"
                     class="product-card__image"
                     style="{{ $previewMediaUrl ? 'display: block;' : 'display: none;' }}">

                <div id="live-card-placeholder"
                     class="product-card__image-placeholder"
                     style="{{ $previewMediaUrl ? 'display: none;' : 'display: flex;' }}"
                     aria-hidden="true">
                    <span>🛍️</span>
                </div>

                {{-- Golden Crown Highlight Badge (Top Left) --}}
                <span class="product-card__badge" id="live-card-badge-wrap">
                    <svg class="product-card__badge-crown" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#3D1A06" aria-hidden="true">
                        <path d="M2.5 18h19v2.5h-19V18zm1.5-3l2.5-8.5 5 4.5 4.5-6.5 4.5 6.5 5-4.5 2.5 8.5H4z"/>
                    </svg>
                    <span id="live-card-badge-text">{{ $previewBadge }}</span>
                </span>

                {{-- Floating Circular Wishlist Heart Button (Top Right) --}}
                <button type="button" class="product-card__wishlist" tabindex="-1" aria-label="Preview Wishlist button">
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
                <h3 class="product-card__name" id="live-card-title">
                    {{ $previewTitle }}
                </h3>

                {{-- Pricing Row with Discount Pill on the right --}}
                <div class="product-card__pricing-row">
                    <div class="product-card__pricing">
                        <span class="product-card__price" id="live-card-price">₹{{ $previewPrice }}</span>
                        <span class="product-card__original-price" id="live-card-mrp" style="{{ ($previewRawOriginalPrice > $previewRawPrice) ? 'display: inline;' : 'display: none;' }}">
                            ₹{{ $previewOriginalPrice }}
                        </span>
                    </div>

                    <span class="product-card__discount-pill" id="live-card-discount" style="{{ $previewDiscount > 0 ? 'display: inline-block;' : 'display: none;' }}">
                        {{ $previewDiscount }}% OFF
                    </span>
                </div>

                {{-- Star Rating & Reviews Count --}}
                <div class="product-card__rating">
                    <svg class="product-card__star-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#F5BA13" aria-hidden="true">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    <strong class="product-card__rating-score" id="live-card-rating">{{ $previewRating }}</strong>
                    <span class="product-card__rating-count" id="live-card-reviews">({{ $previewReviews }} reviews)</span>
                </div>

                {{-- Purchase Actions: Left Outline Quick Cart + Right Filled Gold Add to Cart --}}
                <div class="product-card__actions">
                    <button type="button" class="btn product-card__btn product-card__btn--quick" tabindex="-1" title="Quick Add" aria-label="Quick Add">
                        <svg class="product-card__btn-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="8" cy="21" r="1.5" fill="currentColor"></circle>
                            <circle cx="19" cy="21" r="1.5" fill="currentColor"></circle>
                            <path d="M2.5 2.5h3l2.4 12a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.6-7.5H6.2"></path>
                        </svg>
                    </button>

                    <button type="button" class="btn product-card__btn product-card__btn--cart" tabindex="-1" title="Add to Cart" aria-label="Add to Cart">
                        <svg class="product-card__btn-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="8" cy="21" r="1.5" fill="currentColor"></circle>
                            <circle cx="19" cy="21" r="1.5" fill="currentColor"></circle>
                            <path d="M2.5 2.5h3l2.4 12a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.6-7.5H6.2"></path>
                        </svg>
                        <span class="product-card__btn-text">Add to Cart</span>
                    </button>
                </div>
            </div>

        </article>
    </div>
</div>

<script>
    (function initAdminCardLivePreview() {
        const titleInput = document.getElementById('title');
        const priceInput = document.getElementById('price');
        const mrpInput = document.getElementById('original_price');
        const badgeInput = document.getElementById('badge');
        const ratingInput = document.getElementById('rating');
        const reviewsInput = document.getElementById('reviews_count');
        const statusSelect = document.getElementById('status');
        const activeCheckbox = document.querySelector('input[name="is_active"]');

        const liveTitle = document.getElementById('live-card-title');
        const livePrice = document.getElementById('live-card-price');
        const liveMrp = document.getElementById('live-card-mrp');
        const liveDiscount = document.getElementById('live-card-discount');
        const liveBadgeWrap = document.getElementById('live-card-badge-wrap');
        const liveBadgeText = document.getElementById('live-card-badge-text');
        const liveRating = document.getElementById('live-card-rating');
        const liveReviews = document.getElementById('live-card-reviews');
        const liveImg = document.getElementById('live-card-img');
        const livePlaceholder = document.getElementById('live-card-placeholder');
        const liveStatusBadge = document.getElementById('live-preview-status-badge');
        const liveStatusText = document.getElementById('live-preview-status-text');

        // Update Title
        function updateTitle() {
            if (!liveTitle) return;
            const val = (titleInput && titleInput.value.trim()) ? titleInput.value.trim() : 'Sacred Vrindavan Item';
            liveTitle.textContent = val;
        }

        // Update Pricing and Discount
        function updatePricing() {
            const priceVal = priceInput ? parseFloat(priceInput.value) : NaN;
            const mrpVal = mrpInput ? parseFloat(mrpInput.value) : NaN;

            if (livePrice) {
                if (!isNaN(priceVal) && priceVal >= 0) {
                    livePrice.textContent = '₹' + Math.round(priceVal).toLocaleString('en-IN');
                } else {
                    livePrice.textContent = '₹0';
                }
            }

            if (!isNaN(mrpVal) && !isNaN(priceVal) && mrpVal > priceVal && mrpVal > 0) {
                if (liveMrp) {
                    liveMrp.textContent = '₹' + Math.round(mrpVal).toLocaleString('en-IN');
                    liveMrp.style.display = 'inline';
                }
                const discount = Math.round(((mrpVal - priceVal) / mrpVal) * 100);
                if (liveDiscount) {
                    liveDiscount.textContent = discount + '% OFF';
                    liveDiscount.style.display = 'inline-block';
                }
            } else {
                if (liveMrp) liveMrp.style.display = 'none';
                if (liveDiscount) liveDiscount.style.display = 'none';
            }
        }

        // Update Badge
        function updateBadge() {
            if (!liveBadgeText) return;
            const badgeVal = (badgeInput && badgeInput.value.trim()) ? badgeInput.value.trim() : 'Bestseller';
            liveBadgeText.textContent = badgeVal;
        }

        // Update Rating & Reviews Count
        function updateRatingReviews() {
            if (liveRating && ratingInput) {
                const rVal = parseFloat(ratingInput.value);
                liveRating.textContent = (!isNaN(rVal) && rVal > 0) ? rVal.toFixed(1) : '4.8';
            }
            if (liveReviews && reviewsInput) {
                const cVal = parseInt(reviewsInput.value, 10);
                liveReviews.textContent = '(' + ((!isNaN(cVal) && cVal >= 0) ? cVal : 124) + ' reviews)';
            }
        }

        // Update Publication Status Pill
        function updateStatus() {
            if (!liveStatusBadge || !liveStatusText) return;
            const isPublished = (!statusSelect || statusSelect.value === 'published');
            const isActive = (!activeCheckbox || activeCheckbox.checked);

            if (isPublished && isActive) {
                liveStatusBadge.className = 'admin-preview-status-pill admin-preview-status-pill--live';
                liveStatusText.textContent = 'Live on Store';
            } else {
                liveStatusBadge.className = 'admin-preview-status-pill admin-preview-status-pill--draft';
                liveStatusText.textContent = 'Draft / Unlisted';
            }
        }

        // Listeners for Form Inputs
        if (titleInput) titleInput.addEventListener('input', updateTitle);
        if (priceInput) priceInput.addEventListener('input', updatePricing);
        if (mrpInput) mrpInput.addEventListener('input', updatePricing);
        if (badgeInput) badgeInput.addEventListener('input', updateBadge);
        if (ratingInput) ratingInput.addEventListener('input', updateRatingReviews);
        if (reviewsInput) reviewsInput.addEventListener('input', updateRatingReviews);
        if (statusSelect) statusSelect.addEventListener('change', updateStatus);
        if (activeCheckbox) activeCheckbox.addEventListener('change', updateStatus);

        // Listen for Media Picker Custom Events
        window.addEventListener('media-picker:selected', function(e) {
            if (e.detail && e.detail.url) {
                if (liveImg) {
                    liveImg.src = e.detail.url;
                    liveImg.style.display = 'block';
                }
                if (livePlaceholder) {
                    livePlaceholder.style.display = 'none';
                }
            }
        });

        window.addEventListener('media-picker:cleared', function() {
            if (liveImg) {
                liveImg.src = '';
                liveImg.style.display = 'none';
            }
            if (livePlaceholder) {
                livePlaceholder.style.display = 'flex';
            }
        });

        // Initialize state
        updateTitle();
        updatePricing();
        updateBadge();
        updateRatingReviews();
        updateStatus();
    })();
</script>
