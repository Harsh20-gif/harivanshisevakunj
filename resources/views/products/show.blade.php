@extends('layouts.app')

@section('title', $product->title . ' — Harivanshi Seva Kunj | Vrindavan Dham')
@section('meta_description', $product->meta_description ?: ('Authentic ' . $product->title . ' blessed in Shri Dham Vrindavan. Pure devotional seva item from Harivanshi Seva Kunj.'))
@section('og_type', 'product')
@section('og_title', $product->title . ' — Harivanshi Seva Kunj')
@section('og_description', $product->meta_description ?: ('Authentic devotional item from Shri Dham Vrindavan.'))
@if($product->image_url)
@section('og_image', \App\Support\Seo::imageUrl($product->image_url))
@endif

@php
    use App\Support\Seo;

    $productCanonical = Seo::url('products/' . $product->slug);
    $whatsappNumber = \App\Support\WhatsApp::getBusinessNumber() ?: '919286070355';

    $whatsappMessage = "Radhe Radhe! I would like to enquire about this product from Harivanshi Seva Kunj.\n\n"
        . "Product: " . $product->title . "\n"
        . "Price: ₹" . number_format((float) $product->price) . "\n\n"
        . "Please confirm availability and ordering details. Thank you!";

    $whatsappUrl = "https://wa.me/" . $whatsappNumber . "?text=" . rawurlencode($whatsappMessage);

    $imageSrc = $product->image_url ? Seo::imageUrl($product->image_url) : asset('images/categories/logo.png');
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

@section('content')

    {{-- Breadcrumb Navigation with Microdata --}}
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

    {{-- Product Detail Main Section --}}
    <section class="product-detail-section" aria-label="{{ $product->title }} Details">
        <div class="container">
            <div class="product-detail-layout">

                {{-- Left: Media / Gallery --}}
                <div class="product-detail-gallery">
                    <div class="product-detail-media-wrap">
                        @if($webpSrc)
                            <picture>
                                <source srcset="{{ $webpSrc }}" type="image/webp">
                                <img src="{{ $imageSrc }}"
                                     alt="{{ $product->title }} — Harivanshi Seva Kunj"
                                     class="product-detail-image"
                                     width="600"
                                     height="600"
                                     loading="eager"
                                     fetchpriority="high">
                            </picture>
                        @else
                            <img src="{{ $imageSrc }}"
                                 alt="{{ $product->title }} — Harivanshi Seva Kunj"
                                 class="product-detail-image"
                                 width="600"
                                 height="600"
                                 loading="eager"
                                 fetchpriority="high">
                        @endif

                        @if($product->badge)
                            <span class="product-detail-badge">{{ $product->badge }}</span>
                        @endif
                    </div>
                </div>

                {{-- Right: Product Information & Purchase Actions --}}
                <div class="product-detail-info">

                    {{-- Category Link --}}
                    @if($product->category)
                        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                           class="product-detail-category-tag"
                           title="View all {{ $product->category->name }}">
                            <span aria-hidden="true">📿</span> {{ $product->category->name }}
                        </a>
                    @endif

                    {{-- Product H1 Heading --}}
                    <h1 class="product-detail-title">{{ $product->title }}</h1>

                    {{-- Pricing & Stock --}}
                    <div class="product-detail-pricing">
                        <span class="product-detail-price">₹{{ number_format((float) $product->price) }}</span>

                        @if($product->original_price && (float) $product->original_price > (float) $product->price)
                            <span class="product-detail-original-price">₹{{ number_format((float) $product->original_price) }}</span>
                        @endif

                        <span class="product-detail-stock-badge">
                            <span aria-hidden="true">●</span> Seva Hetu Uplabdh
                        </span>
                    </div>

                    {{-- Devotional Description --}}
                    <div class="product-detail-description">
                        @if(!empty($product->description))
                            {!! nl2br(e($product->description)) !!}
                        @else
                            <p>Shri Dham Vrindavan se pavitra aur shuddh seva samagri. Aapki nitya Thakurji seva, puja aur aadhayatmik upasana ke liye poore bhav se uplabdh karai gayi hai.</p>
                        @endif
                    </div>

                    {{-- Purchase Actions --}}
                    <div class="product-detail-actions">
                        {{-- Add to Cart Form --}}
                        <form action="{{ route('cart.store') }}" method="POST" class="product-detail-form" data-product-id="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="product-detail-qty-wrap" aria-label="Quantity">
                                <button type="button" class="product-detail-qty-btn" onclick="let input=this.nextElementSibling; if(input.value>1) input.value=parseInt(input.value)-1;" aria-label="Decrease quantity">&minus;</button>
                                <input type="number" name="quantity" value="1" min="1" max="99" class="product-detail-qty-input" aria-label="Quantity value">
                                <button type="button" class="product-detail-qty-btn" onclick="let input=this.previousElementSibling; input.value=parseInt(input.value)+1;" aria-label="Increase quantity">&plus;</button>
                            </div>

                            <button type="submit" class="btn product-detail-btn-cart">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                Cart Me Daalein
                            </button>
                        </form>

                        {{-- Buy on WhatsApp Button --}}
                        <a href="{{ $whatsappUrl }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn product-detail-btn-whatsapp"
                           aria-label="Enquire and order {{ $product->title }} on WhatsApp">
                            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                            WhatsApp Par Order Karein
                        </a>
                    </div>

                    {{-- Devotional Trust Strip --}}
                    <div class="product-detail-trust">
                        <div class="product-trust-item">
                            <span class="product-trust-icon" aria-hidden="true">🛕</span>
                            <span>Seedhe Shri Dham Vrindavan Se</span>
                        </div>
                        <div class="product-trust-item">
                            <span class="product-trust-icon" aria-hidden="true">🍃</span>
                            <span>100% Shuddh &amp; Pavitra Seva Samagri</span>
                        </div>
                        <div class="product-trust-item">
                            <span class="product-trust-icon" aria-hidden="true">📦</span>
                            <span>Aadar &amp; Surakshit Packaging</span>
                        </div>
                        <div class="product-trust-item">
                            <span class="product-trust-icon" aria-hidden="true">💬</span>
                            <span>WhatsApp Par Bhakt Sahayata</span>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Related Products Section --}}
            @if($relatedProducts->isNotEmpty())
                <section class="related-products-section" aria-label="Related Seva Items">
                    <div class="section-header-bar">
                        <div class="section-heading-wrap">
                            <span class="section-flourish">✤</span>
                            <h2 class="section-heading">Anya Pavitra Seva Items</h2>
                            <span class="section-flourish">✤</span>
                        </div>
                        <a href="{{ route('products.index') }}" class="section-view-all">Sabhi Seva Items Dekhein &rarr;</a>
                    </div>

                    <div class="featured-section__grid">
                        @foreach($relatedProducts as $related)
                            <x-product-card
                                :product="$related"
                                :id="$related->id"
                                :name="$related->title"
                                :price="$related->price"
                                :originalPrice="$related->original_price"
                                :badge="$related->badge"
                                :rating="$related->rating"
                                :reviewsCount="$related->reviews_count"
                                :image="$related->image_url"
                                :slug="$related->slug"
                                :isDemo="false"
                            />
                        @endforeach
                    </div>
                </section>
            @endif

        </div>
    </section>

@endsection
