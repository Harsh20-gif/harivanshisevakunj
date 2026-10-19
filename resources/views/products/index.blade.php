@extends('layouts.app')

@section('title', $selectedCategory ? ($selectedCategory->name . ' — Harivanshi Seva Kunj') : 'Shop Sacred Vrindavan Seva Items — Harivanshi Seva Kunj')
@section('meta_description', $selectedCategory?->short_description ?: 'Explore holy Tulsi Malas, Vrindavan Raj, Yamuna Jal, Thakur Ji Vastra and authentic seva articles blessed in Shri Dham Vrindavan.')

@section('content')

    {{-- Breadcrumb Navigation --}}
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
        <div class="container">
            <ol class="breadcrumb-list">
                @foreach($breadcrumbs as $crumb)
                    <li class="breadcrumb-item {{ $loop->last ? 'breadcrumb-item--active' : '' }}" @if($loop->last) aria-current="page" @endif>
                        @if(!$loop->last && !empty($crumb['url']))
                            <a href="{{ $crumb['url'] }}" class="breadcrumb-link">{{ $crumb['name'] }}</a>
                            <span class="breadcrumb-sep" aria-hidden="true">&rsaquo;</span>
                        @else
                            <span class="breadcrumb-current">{{ $crumb['name'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </nav>

    {{-- Horizontal Circular Category Navigation --}}
    <nav class="shop-category-bar" aria-label="Product Categories">
        <div class="container">
            <div class="shop-category-scroll">
                <div class="shop-category-list">
                    {{-- "All" Circle --}}
                    <a href="{{ route('products.index', $search ? ['q' => $search] : []) }}"
                       class="shop-category-item {{ empty($categorySlug) ? 'shop-category-item--active' : '' }}"
                       aria-current="{{ empty($categorySlug) ? 'page' : 'false' }}">
                        <div class="shop-category-circle shop-category-circle--all">
                            <span class="shop-category-all-icon" aria-hidden="true">✦</span>
                        </div>
                        <span class="shop-category-name">Sabhi (All)</span>
                    </a>

                    {{-- Active Parent Categories in Configured Sort Order --}}
                    @foreach($categories as $category)
                        <x-category-card
                            :name="$category->name"
                            :slug="$category->slug"
                            :icon="$category->icon"
                            :image="$category->image_url"
                            :isActive="$categorySlug === $category->slug"
                            :url="route('products.index', array_filter(['category' => $category->slug, 'q' => $search]))"
                        />
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Product Listing Section --}}
    <section class="catalogue-main">
        <div class="container">

            {{-- Results Toolbar: Active Filter Badges & Search --}}
            <div class="catalogue-results-bar">
                <div class="catalogue-results-info">
                    @if($selectedCategory)
                        <div class="catalogue-active-badge">
                            <span class="catalogue-active-badge__label">Category:</span>
                            <span class="catalogue-filter-pill">
                                {{ $selectedCategory->name }}
                                <a href="{{ route('products.index', $search ? ['q' => $search] : []) }}" aria-label="Remove category filter">&times;</a>
                            </span>
                        </div>
                    @endif

                    @if($search)
                        <div class="catalogue-active-badge">
                            <span class="catalogue-active-badge__label">Khoj / Search:</span>
                            <span class="catalogue-filter-pill">
                                "{{ $search }}"
                                <a href="{{ route('products.index', $selectedCategory ? ['category' => $selectedCategory->slug] : []) }}" aria-label="Clear search filter">&times;</a>
                            </span>
                        </div>
                    @endif

                    <span class="catalogue-total-count">
                        {{ $products->total() }} Pavitra Seva Items
                    </span>
                </div>

                {{-- Quick Search Toolbar --}}
                <form method="GET" action="{{ route('products.index') }}" class="catalogue-search-bar" role="search">
                    @if($categorySlug)
                        <input type="hidden" name="category" value="{{ $categorySlug }}">
                    @endif
                    <div class="catalogue-search-bar__wrap">
                        <input type="search"
                               name="q"
                               value="{{ $search }}"
                               class="catalogue-search-bar__input"
                               placeholder="Is collection me search karein..."
                               aria-label="Search products">
                        <button type="submit" class="catalogue-search-bar__btn" aria-label="Submit search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Products Grid or Empty State --}}
            @if($products->isEmpty())
                <div class="catalogue-empty">
                    <div class="catalogue-empty__icon" aria-hidden="true">🛕</div>
                    <h2 class="catalogue-empty__title">Koi seva item nahi mila</h2>
                    <p class="catalogue-empty__desc">
                        @if($search || $selectedCategory)
                            Aapke chune hue filter ya search ke anusaar koi seva item uplabdh nahi hai.
                        @else
                            Naye pavitra utpaad jald hi is collection me jode jayenge.
                        @endif
                    </p>
                    <div style="margin-top: var(--space-5);">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            Sabhi Collections Dekhein
                        </a>
                    </div>
                </div>
            @else
                <div class="catalogue-grid">
                    @foreach($products as $product)
                        <x-product-card
                            :product="$product"
                            :id="$product->id"
                            :name="$product->title"
                            :price="$product->price"
                            :originalPrice="$product->original_price"
                            :image="$product->image_url"
                            :badge="$product->badge"
                            :rating="$product->rating"
                            :reviewsCount="$product->reviews_count"
                            :slug="$product->slug"
                            :isDemo="false"
                        />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="catalogue-pagination">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </section>

@endsection
