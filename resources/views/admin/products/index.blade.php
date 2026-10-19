@extends('admin.layouts.app')

@section('title', 'Product Catalogue')
@section('page_title', 'Products Management')

@section('breadcrumbs')
    @include('admin.components.breadcrumbs', [
        'crumbs' => [],
        'active' => 'Products'
    ])
@endsection

@section('content')

    {{-- Filter Toolbar --}}
    <div class="admin-toolbar">
        <div class="admin-toolbar__left" style="flex: 1; flex-wrap: wrap;">
            <form method="GET" action="{{ route('admin.products.index') }}" class="admin-search-form" role="search" style="display: flex; gap: var(--space-2); flex-wrap: wrap; align-items: center;">
                {{-- Search Box --}}
                <div class="media-search-wrap">
                    <span class="media-search-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="search"
                           name="q"
                           value="{{ $search }}"
                           class="media-search-input"
                           placeholder="Search products by title, slug..."
                           aria-label="Search products">
                </div>

                {{-- Category Filter --}}
                <select name="category_id" class="admin-form-select admin-form-select--compact" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Status Filter --}}
                <select name="status" class="admin-form-select admin-form-select--compact" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published Only</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                </select>

                @if($search || $categoryId || $status)
                    <a href="{{ route('admin.products.index') }}" class="admin-link-clear">
                        Clear Filters
                    </a>
                @endif
            </form>
        </div>

        <div class="admin-toolbar__right">
            <div class="media-stat-pill" title="Total Products in Catalogue">
                <span aria-hidden="true">📦</span>
                <span>{{ $totalProducts }} {{ Str::plural('Product', $totalProducts) }}</span>
            </div>
            <div class="media-stat-pill" title="Published & Live on Storefront">
                <span aria-hidden="true">🟢</span>
                <span>{{ $publishedProducts }} Published</span>
            </div>
            <div class="media-stat-pill" title="Draft Products">
                <span aria-hidden="true">📝</span>
                <span>{{ $draftProducts }} Drafts</span>
            </div>

            <div class="admin-view-toggle" role="group" aria-label="Switch product view mode">
                <button type="button" class="admin-view-toggle__btn is-active" id="btn-view-cards" onclick="setProductViewMode('cards')" title="Cards View (Storefront Style)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Cards</span>
                </button>
                <button type="button" class="admin-view-toggle__btn" id="btn-view-table" onclick="setProductViewMode('table')" title="Table View (Data Table)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                    <span>Table</span>
                </button>
            </div>

            <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    @if($products->isEmpty())
        {{-- Empty State --}}
        <div class="admin-card admin-empty-state">
            <div class="admin-empty-state__icon" aria-hidden="true">🛍️</div>
            <h3 class="admin-empty-state__title">
                {{ ($search || $categoryId || $status) ? 'No matching products found' : 'No Products Added Yet' }}
            </h3>
            <p class="admin-empty-state__desc">
                {{ ($search || $categoryId || $status)
                    ? 'Try clearing the search query or category filter to view all products.'
                    : 'Click below to add your first sacred product (e.g. Tulsi Mala, Yamuna Jal, Black Raj) and associate it with a category and shared media.' }}
            </p>
            <div style="margin-top: var(--space-4);">
                @if($search || $categoryId || $status)
                    <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn--secondary">Reset All Filters</a>
                @else
                    <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn--primary">Add First Product</a>
                @endif
            </div>
        </div>
    @else
        {{-- VIEW 1: Sacred Storefront Cards View (Matching image card design) --}}
        <div id="products-cards-view" class="admin-products-view admin-products-view--cards">
            <div class="admin-products-card-grid">
                @foreach($products as $product)
                    @php
                        $cardDiscount = 0;
                        if ($product->original_price && (float)$product->original_price > (float)$product->price && (float)$product->original_price > 0) {
                            $cardDiscount = (int) round((((float)$product->original_price - (float)$product->price) / (float)$product->original_price) * 100);
                        }
                        $cardBadge = $product->badge ?: 'Bestseller';
                        $cardReviewCount = 100 + (($product->id * 17) % 85);
                    @endphp

                    <div class="admin-product-card-wrap">
                        {{-- Admin Header Bar: Category + Status + Active Switch --}}
                        <div class="admin-product-card-admin-bar">
                            <div class="admin-product-card-admin-bar__left">
                                @if($product->category)
                                    <span class="admin-badge admin-badge--parent" title="Category: {{ $product->category->name }}">
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge--inactive">Unassigned</span>
                                @endif

                                @if($product->status === 'published' && $product->is_active)
                                    <span class="admin-badge admin-badge--published" title="Live on public storefront">
                                        <span class="admin-badge__dot"></span> Live
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge--draft" title="Draft / Unlisted">
                                        Draft
                                    </span>
                                @endif
                            </div>

                            <div class="admin-product-card-admin-bar__right">
                                <form method="POST" action="{{ route('admin.products.toggle', $product) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="admin-badge-toggle {{ $product->is_active ? 'admin-badge-toggle--active' : 'admin-badge-toggle--inactive' }}"
                                            title="Click to toggle active status">
                                        <span class="admin-badge-toggle__dot"></span>
                                        <span>{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Sacred Storefront Card Replica --}}
                        <article class="product-card" aria-label="{{ $product->title }}">

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

                            {{-- Product Image Wrap --}}
                            <div class="product-card__image-wrap">
                                @if($product->media && $product->media->existsOnDisk())
                                    <img src="{{ $product->media->url }}" alt="{{ $product->title }}" class="product-card__image" loading="lazy">
                                @else
                                    <div class="product-card__image-placeholder" aria-hidden="true">
                                        <span>🛍️</span>
                                    </div>
                                @endif

                                {{-- Golden Crown Highlight Badge (Top Left) --}}
                                <span class="product-card__badge">
                                    <svg class="product-card__badge-crown" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#3D1A06" aria-hidden="true">
                                        <path d="M2.5 18h19v2.5h-19V18zm1.5-3l2.5-8.5 5 4.5 4.5-6.5 4.5 6.5 5-4.5 2.5 8.5H4z"/>
                                    </svg>
                                    <span>{{ $cardBadge }}</span>
                                </span>

                                {{-- Floating Wishlist Button / Storefront Link (Top Right) --}}
                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" rel="noopener noreferrer" class="product-card__wishlist" title="Preview on Storefront (Opens New Tab)" aria-label="Preview in Storefront">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                </a>
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

                                <h3 class="product-card__name">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="product-card__title-link" title="Edit {{ $product->title }}">
                                        {{ $product->title }}
                                    </a>
                                </h3>

                                {{-- Pricing Row with Discount Pill on the right --}}
                                <div class="product-card__pricing-row">
                                    <div class="product-card__pricing">
                                        <span class="product-card__price">₹{{ number_format($product->price) }}</span>
                                        @if($product->original_price && $product->original_price > $product->price)
                                            <span class="product-card__original-price">₹{{ number_format($product->original_price) }}</span>
                                        @endif
                                    </div>

                                    @if($cardDiscount > 0)
                                        <span class="product-card__discount-pill">{{ $cardDiscount }}% OFF</span>
                                    @endif
                                </div>

                                {{-- Star Rating Row --}}
                                <div class="product-card__rating">
                                    <svg class="product-card__star-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#F5BA13" aria-hidden="true">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    <strong class="product-card__rating-score">{{ number_format((float)($product->rating ?? 4.8), 1) }}</strong>
                                    <span class="product-card__rating-count">({{ (int)($product->reviews_count ?? $cardReviewCount) }} reviews)</span>
                                </div>

                                {{-- Purchase Actions: Left Outline Storefront link + Right Gold Edit Button --}}
                                <div class="product-card__actions">
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn product-card__btn product-card__btn--quick"
                                       title="View Public Storefront Page"
                                       aria-label="View {{ $product->title }} in Storefront">
                                        <svg class="product-card__btn-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="2" y1="12" x2="22" y2="12"></line>
                                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn product-card__btn product-card__btn--cart"
                                       title="Edit Product Details">
                                        <svg class="product-card__btn-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <span class="product-card__btn-text">Edit Product</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Admin Footer Toolbar: Slug + Delete Button --}}
                            <div class="admin-product-card-footer">
                                <span class="admin-product-card-footer__slug" title="/product/{{ $product->slug }}">
                                    /{{ Str::limit($product->slug, 22) }}
                                </span>

                                <form method="POST"
                                      action="{{ route('admin.products.destroy', $product) }}"
                                      onsubmit="return confirm('Are you sure you want to delete product \'{{ addslashes($product->title) }}\'? The shared media asset will not be deleted.');"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="admin-btn admin-btn--xs admin-btn--outline-danger"
                                            title="Safely delete product">
                                        Delete
                                    </button>
                                </form>
                            </div>

                        </article>
                    </div>
                @endforeach
            </div>

            {{-- Cards Pagination --}}
            <div style="padding: var(--space-6) 0; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        </div>

        {{-- VIEW 2: Compact Admin Table View --}}
        <div id="products-table-view" class="admin-products-view admin-products-view--table" style="display: none;">
            <div class="admin-card admin-card--table">
                <div class="admin-table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 70px;">Image</th>
                                <th>Product Title</th>
                                <th>Category</th>
                                <th style="width: 130px;">Seva Price</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 100px;">Visibility</th>
                                <th style="width: 140px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    {{-- Thumbnail --}}
                                    <td>
                                        <div class="admin-table-thumb">
                                            @if($product->media && $product->media->existsOnDisk())
                                                <img src="{{ $product->media->url }}" alt="{{ $product->title }}" loading="lazy">
                                            @else
                                                <span class="admin-table-thumb__placeholder">🛍️</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Title & Slug --}}
                                    <td>
                                        <div class="admin-table-product-title">
                                            <strong>{{ $product->title }}</strong>
                                            @if($product->badge)
                                                <span class="admin-badge admin-badge--pill">{{ $product->badge }}</span>
                                            @endif
                                        </div>
                                        <div class="admin-table-slug">
                                            /product/{{ $product->slug }}
                                        </div>
                                        @if($product->meta_description)
                                            <div class="admin-table-meta-snippet" title="{{ $product->meta_description }}">
                                                SEO: {{ Str::limit($product->meta_description, 60) }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Category --}}
                                    <td>
                                        @if($product->category)
                                            <span class="admin-badge admin-badge--parent">{{ $product->category->name }}</span>
                                        @else
                                            <span class="admin-badge admin-badge--inactive">Unassigned</span>
                                        @endif
                                    </td>

                                    {{-- Pricing --}}
                                    <td>
                                        <div class="admin-price-cell">
                                            <span class="admin-price-current">₹{{ number_format($product->price) }}</span>
                                            @if($product->original_price && $product->original_price > $product->price)
                                                <span class="admin-price-mrp">₹{{ number_format($product->original_price) }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Status (Published / Draft) --}}
                                    <td>
                                        @if($product->status === 'published' && $product->is_active)
                                            <span class="admin-badge admin-badge--published">
                                                <span class="admin-badge__dot"></span> Published
                                            </span>
                                        @else
                                            <span class="admin-badge admin-badge--draft">
                                                Draft
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Active Toggle --}}
                                    <td>
                                        <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="admin-badge-toggle {{ $product->is_active ? 'admin-badge-toggle--active' : 'admin-badge-toggle--inactive' }}"
                                                    title="Click to toggle publication status">
                                                <span class="admin-badge-toggle__dot"></span>
                                                <span>{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Action Buttons --}}
                                    <td style="text-align: right;">
                                        <div class="admin-table-actions">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="admin-btn admin-btn--xs admin-btn--secondary"
                                               title="Edit product">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.products.destroy', $product) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete product \'{{ addslashes($product->title) }}\'? The shared media asset will not be deleted.');"
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="admin-btn admin-btn--xs admin-btn--outline-danger"
                                                        title="Safely delete product">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Table Pagination --}}
                <div style="padding: var(--space-4) var(--space-6); border-top: 1px solid var(--color-border); display: flex; justify-content: center;">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    function setProductViewMode(mode) {
        const cardsView = document.getElementById('products-cards-view');
        const tableView = document.getElementById('products-table-view');
        const btnCards = document.getElementById('btn-view-cards');
        const btnTable = document.getElementById('btn-view-table');

        if (mode === 'table') {
            if (cardsView) cardsView.style.display = 'none';
            if (tableView) tableView.style.display = 'block';
            if (btnCards) btnCards.classList.remove('is-active');
            if (btnTable) btnTable.classList.add('is-active');
        } else {
            if (cardsView) cardsView.style.display = 'block';
            if (tableView) tableView.style.display = 'none';
            if (btnCards) btnCards.classList.add('is-active');
            if (btnTable) btnTable.classList.remove('is-active');
        }

        try {
            localStorage.setItem('admin_product_view_mode', mode);
        } catch(e) {}
    }

    document.addEventListener('DOMContentLoaded', function() {
        try {
            const saved = localStorage.getItem('admin_product_view_mode');
            if (saved === 'table') {
                setProductViewMode('table');
            } else {
                setProductViewMode('cards');
            }
        } catch(e) {}
    });
</script>
@endpush
