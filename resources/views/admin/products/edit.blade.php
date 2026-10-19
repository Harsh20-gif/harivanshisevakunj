@extends('admin.layouts.app')

@section('title', 'Edit Product — ' . $product->title)
@section('page_title', 'Edit Product')

@section('breadcrumbs')
    @include('admin.components.breadcrumbs', [
        'crumbs' => [
            ['label' => 'Products', 'url' => route('admin.products.index')],
        ],
        'active' => 'Edit ' . $product->title
    ])
@endsection

@section('content')

    <div class="admin-product-editor-layout">
        {{-- Left Column: Form --}}
        <div class="admin-product-editor-form">
            <div class="admin-card">
                <div class="admin-card__header">
                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 1.25rem;">✏️</span>
                            <div>
                                <h2 style="font-family: var(--font-heading); font-size: 1.15rem; color: var(--color-heading); margin: 0;">
                                    Edit Product: {{ $product->title }}
                                </h2>
                                <p style="font-size: var(--text-xs); color: var(--color-text-muted); margin: 2px 0 0 0;">
                                    Current Category: <strong>{{ $product->category?->name }}</strong>
                                </p>
                            </div>
                        </div>

                        <span class="admin-badge {{ ($product->status === 'published' && $product->is_active) ? 'admin-badge--published' : 'admin-badge--draft' }}">
                            {{ ($product->status === 'published' && $product->is_active) ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                </div>

            <form method="POST" action="{{ route('admin.products.update', $product) }}" class="admin-form" id="product-edit-form">
                @csrf
                @method('PUT')

                {{-- Section 1: Category Placement --}}
                <div class="admin-form-section">
                    <h3 class="admin-form-section__title">1. Category Placement</h3>
                    
                    <div class="admin-form-group">
                        <label for="category_id" class="admin-form-label">
                            Category <span class="admin-required">*</span>
                        </label>
                        <select name="category_id"
                                id="category_id"
                                class="admin-form-select @error('category_id') admin-form-select--error @enderror"
                                required>
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                    📁 {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="admin-form-help">Select the category this product belongs to.</p>
                        @error('category_id')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Section 2: Shared Media Asset --}}
                <div class="admin-form-section">
                    <h3 class="admin-form-section__title">2. Product Image</h3>

                    @include('admin.components.media-picker-modal', [
                        'selectedMedia' => $product->media,
                        'fieldName'     => 'media_id',
                        'label'         => 'Select or Upload Product Image (Shared Media Library)',
                        'helpText'      => 'Reuses sacred imagery from the central Media Library without creating duplicate files.',
                        'mediaAssets'   => $mediaAssets
                    ])
                </div>

                {{-- Section 3: Product Basic Information --}}
                <div class="admin-form-section">
                    <h3 class="admin-form-section__title">3. Product Information</h3>

                    <div class="admin-form-group">
                        <label for="title" class="admin-form-label">
                            Product Title <span class="admin-required">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               id="title"
                               value="{{ old('title', $product->title) }}"
                               class="admin-form-input @error('title') admin-form-input--error @enderror"
                               required
                               oninput="autoGenerateSlug(this.value)">
                        @error('title')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- URL Slug --}}
                    <div class="admin-form-group">
                        <label for="slug" class="admin-form-label">
                            URL Slug <span class="admin-required">*</span>
                        </label>
                        <div class="admin-input-prefix-group">
                            <span class="admin-input-prefix">/product/</span>
                            <input type="text"
                                   name="slug"
                                   id="slug"
                                   value="{{ old('slug', $product->slug) }}"
                                   class="admin-form-input admin-input-prefixed @error('slug') admin-form-input--error @enderror"
                                   required>
                        </div>
                        <p class="admin-form-help">Unique URL identifier for this product.</p>
                        @error('slug')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pricing and Badge Row --}}
                    <div class="admin-form-row">
                        <div class="admin-form-group" style="flex: 1;">
                            <label for="price" class="admin-form-label">
                                Seva / Offer Price (₹) <span class="admin-required">*</span>
                            </label>
                            <div class="admin-input-prefix-group">
                                <span class="admin-input-prefix">₹</span>
                                <input type="number"
                                       name="price"
                                       id="price"
                                       value="{{ old('price', $product->price) }}"
                                       step="0.01"
                                       min="0"
                                       class="admin-form-input admin-input-prefixed @error('price') admin-form-input--error @enderror"
                                       required>
                            </div>
                            @error('price')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="admin-form-group" style="flex: 1;">
                            <label for="original_price" class="admin-form-label">
                                Original MRP (₹) <span style="font-weight: normal; color: var(--color-text-muted);">(Optional)</span>
                            </label>
                            <div class="admin-input-prefix-group">
                                <span class="admin-input-prefix">₹</span>
                                <input type="number"
                                       name="original_price"
                                       id="original_price"
                                       value="{{ old('original_price', $product->original_price) }}"
                                       step="0.01"
                                       min="0"
                                       class="admin-form-input admin-input-prefixed @error('original_price') admin-form-input--error @enderror">
                            </div>
                            @error('original_price')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="admin-form-group" style="flex: 1;">
                            <label for="badge" class="admin-form-label">
                                Highlight Badge <span style="font-weight: normal; color: var(--color-text-muted);">(Optional)</span>
                            </label>
                            <input type="text"
                                   name="badge"
                                   id="badge"
                                   value="{{ old('badge', $product->badge) }}"
                                   maxlength="50"
                                   class="admin-form-input @error('badge') admin-form-input--error @enderror"
                                   placeholder="e.g. Bestseller, Sacred, New">
                            <div class="admin-badge-presets" style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px;">
                                <button type="button" class="admin-badge-preset-btn" onclick="setBadgeVal('Bestseller')" style="font-size: 11px; padding: 2px 8px; border-radius: 999px; background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; cursor: pointer;">👑 Bestseller</button>
                                <button type="button" class="admin-badge-preset-btn" onclick="setBadgeVal('Sacred')" style="font-size: 11px; padding: 2px 8px; border-radius: 999px; background: #F3E8FF; color: #6B21A8; border: 1px solid #D8B4FE; cursor: pointer;">🌸 Sacred</button>
                                <button type="button" class="admin-badge-preset-btn" onclick="setBadgeVal('Trending')" style="font-size: 11px; padding: 2px 8px; border-radius: 999px; background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; cursor: pointer;">🔥 Trending</button>
                                <button type="button" class="admin-badge-preset-btn" onclick="setBadgeVal('New')" style="font-size: 11px; padding: 2px 8px; border-radius: 999px; background: #E0E7FF; color: #3730A3; border: 1px solid #A5B4FC; cursor: pointer;">✨ New</button>
                                <button type="button" class="admin-badge-preset-btn" onclick="setBadgeVal('Pavitra')" style="font-size: 11px; padding: 2px 8px; border-radius: 999px; background: #DCFCE7; color: #166534; border: 1px solid #86EFAC; cursor: pointer;">🕉️ Pavitra</button>
                            </div>
                            @error('badge')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Rating & Social Proof Row --}}
                    <div class="admin-form-row">
                        <div class="admin-form-group" style="flex: 1;">
                            <label for="rating" class="admin-form-label">
                                Star Rating (1.0 — 5.0) <span style="font-weight: normal; color: var(--color-text-muted);">(Optional)</span>
                            </label>
                            <div class="admin-input-prefix-group">
                                <span class="admin-input-prefix">⭐</span>
                                <input type="number"
                                       name="rating"
                                       id="rating"
                                       value="{{ old('rating', $product->rating ?? '4.8') }}"
                                       step="0.1"
                                       min="1.0"
                                       max="5.0"
                                       class="admin-form-input admin-input-prefixed @error('rating') admin-form-input--error @enderror"
                                       placeholder="4.8">
                            </div>
                            <p class="admin-form-help">Appears next to golden star on product card.</p>
                            @error('rating')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="admin-form-group" style="flex: 1;">
                            <label for="reviews_count" class="admin-form-label">
                                Reviews Count <span style="font-weight: normal; color: var(--color-text-muted);">(Optional)</span>
                            </label>
                            <input type="number"
                                   name="reviews_count"
                                   id="reviews_count"
                                   value="{{ old('reviews_count', $product->reviews_count ?? '124') }}"
                                   min="0"
                                   class="admin-form-input @error('reviews_count') admin-form-input--error @enderror"
                                   placeholder="124">
                            <p class="admin-form-help">Shown in brackets on card, e.g. (124 reviews).</p>
                            @error('reviews_count')
                                <p class="admin-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="admin-form-group">
                        <label for="description" class="admin-form-label">
                            Product Description / Mahatmyam
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="admin-form-textarea @error('description') admin-form-textarea--error @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Section 4: SEO and Publication Status --}}
                <div class="admin-form-section">
                    <h3 class="admin-form-section__title">4. Search Engine Optimization (SEO) &amp; Publication</h3>

                    <div class="admin-form-group">
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <label for="meta_description" class="admin-form-label">
                                Meta / SEO Description
                            </label>
                            <span id="seo-counter" style="font-size: var(--text-xs); color: var(--color-text-muted);">
                                {{ strlen($product->meta_description ?? '') }} / 160 recommended
                            </span>
                        </div>
                        <textarea name="meta_description"
                                  id="meta_description"
                                  rows="2"
                                  maxlength="500"
                                  class="admin-form-textarea @error('meta_description') admin-form-textarea--error @enderror"
                                  oninput="updateSeoCounter(this.value)">{{ old('meta_description', $product->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-form-row">
                        <div class="admin-form-group" style="flex: 1;">
                            <label for="status" class="admin-form-label">
                                Publication Status <span class="admin-required">*</span>
                            </label>
                            <select name="status" id="status" class="admin-form-select">
                                <option value="published" {{ old('status', $product->status) === 'published' ? 'selected' : '' }}>
                                    🟢 Published (Visible to Public)
                                </option>
                                <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>
                                    📝 Save as Draft (Hidden from Storefront)
                                </option>
                            </select>
                        </div>

                        <div class="admin-form-group" style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                            <label class="admin-checkbox-label" style="margin-top: 1.5rem;">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       class="admin-checkbox"
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <span class="admin-checkbox-custom"></span>
                                <div>
                                    <span style="font-weight: var(--font-semibold); color: var(--color-heading);">
                                        Active in Storefront
                                    </span>
                                    <div style="font-size: var(--text-xs); color: var(--color-text-muted);">
                                        Unchecking immediately hides product from public storefront.
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Form Submit Buttons --}}
                <div class="admin-form-actions">
                    <button type="submit" class="admin-btn admin-btn--primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Update Product</span>
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn--secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Right Column: Live Storefront Card Preview --}}
    <aside class="admin-product-editor-preview">
        @include('admin.components.live-card-preview', ['product' => $product])
    </aside>
</div>

@endsection

@push('scripts')
<script>
    let slugManualOverride = true;
    document.getElementById('slug')?.addEventListener('input', function() {
        slugManualOverride = true;
    });

    function autoGenerateSlug(text) {
        if (slugManualOverride) return;
        const slugInput = document.getElementById('slug');
        if (!slugInput) return;

        const slug = text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/&/g, '-and-')
            .replace(/[\s\W-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        slugInput.value = slug;
    }

    function updateSeoCounter(text) {
        const counter = document.getElementById('seo-counter');
        if (!counter) return;
        const len = (text || '').length;
        counter.textContent = len + ' / 160 recommended (max 500)';
        if (len > 160) {
            counter.style.color = '#B45309';
        } else {
            counter.style.color = 'var(--color-text-muted)';
        }
    }

    function setBadgeVal(val) {
        const badgeInput = document.getElementById('badge');
        if (badgeInput) {
            badgeInput.value = val;
            badgeInput.dispatchEvent(new Event('input'));
        }
    }
</script>
@endpush
