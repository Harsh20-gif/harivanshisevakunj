@extends('layouts.app')

@section('title', 'Harivanshi Seva Kunj — Shri Dham Vrindavan Se Pavitra Seva Samagri')
@section('meta_description', 'Vrindavan Dham se shuddh puja samagri, Thakurji poshak, pavitra Tulsi kanthi mala, chandan aur dhoop seedhe apne ghar mangwayein. ₹499 se upar Free Delivery.')

@section('content')

{{-- ══════════════════════════════════════════════════
     HERO SLIDER
     Slides are passed from HomeController.
     ══════════════════════════════════════════════════ --}}
<x-hero-slider :slides="$slides" />

{{-- ══════════════════════════════════════════════════
     SEARCH & TRUST BADGES STRIP (Devotional Hinglish)
     ══════════════════════════════════════════════════ --}}
<div class="trust-strip" aria-label="Key promises and search">
    <div class="container trust-strip__inner">
        <form action="{{ route('products.index') }}" method="GET" class="trust-strip__search" role="search">
            <svg class="trust-strip__search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="search" name="q" placeholder="Radha Krishna seva items, mala, vastra search karein..." aria-label="Seva items search karein" class="trust-strip__search-input">
        </form>

        <div class="trust-strip__features">
            <div class="trust-item">
                <span class="trust-item__icon" aria-hidden="true">🍃</span>
                <div class="trust-item__text">
                    <strong>100% Shuddh &amp; Pavitra</strong>
                    <span>Braj Seva Samagri</span>
                </div>
            </div>
            <div class="trust-item">
                <span class="trust-item__icon" aria-hidden="true">📦</span>
                <div class="trust-item__text">
                    <strong>Surakshit Packaging</strong>
                    <span>&amp; Fast Delivery</span>
                </div>
            </div>
            <div class="trust-item">
                <span class="trust-item__icon" aria-hidden="true"><i class="fa-brands fa-whatsapp" style="color: #25D366;"></i></span>
                <div class="trust-item__text">
                    <strong>WhatsApp Sahayata</strong>
                    <span>Bhakton Ke Liye</span>
                </div>
            </div>
            <div class="trust-item">
                <span class="trust-item__icon" aria-hidden="true">🛕</span>
                <div class="trust-item__text">
                    <strong>Seedhe Vrindavan Se</strong>
                    <span>Prem &amp; Bhakti Ke Saath</span>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════
     SHOP BY CATEGORY
     ══════════════════════════════════════════════════ --}}
<section class="section categories-section" aria-label="Shop by category" id="categories">
    <div class="container">
        <div class="section-header-bar">
            <div class="section-heading-wrap">
                <span class="section-flourish">✤</span>
                <h2 class="section-heading">Category Se Chunein</h2>
                <span class="section-flourish">✤</span>
            </div>
            <a href="{{ route('products.index') }}" class="section-view-all">Sabhi Dekhein &rarr;</a>
        </div>

        <div class="shop-category-scroll">
            <div class="shop-category-list">
                @foreach($categories as $cat)
                    <x-category-card
                        :name="$cat['name']"
                        :slug="$cat['slug']"
                        :icon="$cat['icon'] ?? '📿'"
                        :image="$cat['image'] ?? null"
                    />
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════
     FEATURED PRODUCTS
     ══════════════════════════════════════════════════ --}}
<section class="section featured-section" aria-label="Featured products" id="featured">
    <div class="container">
        <div class="section-header-bar">
            <div class="section-heading-wrap">
                <span class="section-flourish">✤</span>
                <h2 class="section-heading">Khaas Pavitra Seva Items</h2>
                <span class="section-flourish">✤</span>
            </div>
            <a href="{{ route('products.index') }}" class="section-view-all">Sabhi Dekhein &rarr;</a>
        </div>

        <div class="featured-section__grid">
            @foreach($demoProducts as $product)
                <x-product-card
                    :id="$product['id'] ?? null"
                    :name="$product['name']"
                    :price="$product['price']"
                    :originalPrice="$product['original_price'] ?? null"
                    :badge="$product['badge'] ?? null"
                    :rating="$product['rating'] ?? null"
                    :reviewsCount="$product['reviews_count'] ?? null"
                    :image="$product['image'] ?? null"
                    :slug="$product['slug'] ?? null"
                    :isDemo="$product['isDemo'] ?? true"
                />
            @endforeach
        </div>

        <div class="featured-section__footer">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                Sabhi Products Dekhein
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════
     WHY CHOOSE US
     ══════════════════════════════════════════════════ --}}
<section class="section why-section" aria-label="Why choose Harivanshi Seva Kunj" id="why-us">
    <div class="container">
        <h2 class="section-heading">Kyun Chunein Harivanshi Seva Kunj?</h2>
        <div class="divider-gold"></div>
        <p class="section-subheading">Har bhakt ke vishwas aur seva ke liye hamara pavitra sankalp</p>

        <div class="why-section__grid">
            <div class="why-card">
                <div class="why-card__icon" aria-hidden="true">🕍</div>
                <h3 class="why-card__title">Seedhe Vrindavan Dham Se</h3>
                <p class="why-card__body">Har ek pavitra vastu Vrindavan Dham ke vishwasniya mandiron aur karigaron se prapt hoti hai — koi beech ka madhyam nahi.</p>
            </div>
            <div class="why-card">
                <div class="why-card__icon" aria-hidden="true">✅</div>
                <h3 class="why-card__title">100% Shuddh &amp; Pavitra</h3>
                <p class="why-card__body">Pavitrata hi hamara dharm hai. Har product aap tak pahunchne se pehle poori shuddhata aur bhav ke saath check kiya jata hai.</p>
            </div>
            <div class="why-card">
                <div class="why-card__icon" aria-hidden="true">🚚</div>
                <h3 class="why-card__title">Surakshit &amp; Savdhani Se Delivery</h3>
                <p class="why-card__body">Pavitra seva samagri ko poore aadar ke saath pack kiya jata hai aur surakshit pahunchaya jata hai. ₹499 se upar Free Delivery.</p>
            </div>
            <div class="why-card">
                <div class="why-card__icon" aria-hidden="true"><i class="fa-brands fa-whatsapp" style="color: #25D366;"></i></div>
                <h3 class="why-card__title">WhatsApp Seva Sahayata</h3>
                <p class="why-card__body">Hamari team WhatsApp par hamesha aapki seva me uplabdh hai taaki aap apni upasana ke liye uchit seva samagri chun sakein.</p>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════
     ABOUT SECTION
     ══════════════════════════════════════════════════ --}}
<section class="section about-section" aria-label="About Harivanshi Seva Kunj" id="about">
    <div class="container about-section__inner">

        <div class="about-section__content">
            <span class="about-section__badge"> About us</span>
            <h2 class="about-section__heading">
                Bhakti Me Samarpit,<br>
                Vrindavan Dham Me Sthapit
            </h2>
            <div class="divider-gold" style="margin-inline: 0;"></div>
            <p class="about-section__body">
                Harivanshi Seva Kunj peedhiyon se bhakton ki seva karta aa raha hai, Shri Dham Vrindavan ke pavitra
                aashirwad aur seva samagri ko desh bhar ke gharon tak pahunchata hai. Hamare yahan har vastu
                bhakti bhav aur pavitrata ke saath chuni jati hai.
            </p>
            <ul class="about-section__features" aria-label="Key features">
                <li class="about-section__feature">
                    <span class="about-section__feature-icon" aria-hidden="true">🌸</span>
                    <span>Vrindavan Dham me peedhiyon ka vishwas</span>
                </li>
                <li class="about-section__feature">
                    <span class="about-section__feature-icon" aria-hidden="true">🪔</span>
                    <span>Har seva vastu shuddh aur pavitra</span>
                </li>
                <li class="about-section__feature">
                    <span class="about-section__feature-icon" aria-hidden="true">🤲</span>
                    <span>Braj ke local karigaron aur gau-seva ko samarpit</span>
                </li>
            </ul>
            <a href="{{ route('about') }}" class="btn btn-secondary">Hamari Pavitra Yatra Padhein</a>
        </div>

        <div class="about-section__image-wrap">
            <picture>
                @if(file_exists(public_path('images/devotional/barsana.webp')))
                    <source srcset="{{ asset('images/devotional/barsana.webp') }}" type="image/webp">
                @endif
                <img src="{{ asset('images/devotional/barsana.png') }}"
                     alt="Shri Radha Rani — Barsana Dham Seva"
                     class="about-section__image"
                     width="600"
                     height="450"
                     loading="lazy">
            </picture>
            <div class="about-section__image-badge">
                <span>🌸 श्री बरसाना धाम • Barsana Dham</span>
            </div>
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════════
     CONTACT SECTION
     ══════════════════════════════════════════════════ --}}
<section class="section contact-section" aria-label="Contact Harivanshi Seva Kunj" id="contact">
    <div class="container contact-section__grid">

        <div class="contact-section__info">
            <h2 class="contact-section__heading">Humse Sampark Karein</h2>
            <p class="contact-section__subheading">
                Koi bhi prashn ho ya seva samagri ke baare me poochna ho? Hum hamesha prem aur shraddha ke saath aapki seva me hain. Sampark karein, hum turant uttar denge. 🙏
            </p>

            <div class="contact-section__items">
                <a href="tel:+919286070355" class="contact-item" aria-label="Call us: +91 92860 70355">
                    <div class="contact-item__icon" aria-hidden="true">📞</div>
                    <div class="contact-item__content">
                        <p class="contact-item__label">Call Karein</p>
                        <p class="contact-item__value">+91 92860 70355</p>
                    </div>
                </a>
                @php
                    $homeWhatsNumber = \App\Support\WhatsApp::getBusinessNumber() ?: '919286070355';
                @endphp
                <a href="https://wa.me/{{ $homeWhatsNumber }}" target="_blank" rel="noopener noreferrer" class="contact-item contact-item--whatsapp" aria-label="Chat on WhatsApp">
                    <div class="contact-item__icon" aria-hidden="true"><i class="fa-brands fa-whatsapp" style="color: #25D366;"></i></div>
                    <div class="contact-item__content">
                        <p class="contact-item__label">WhatsApp</p>
                        <p class="contact-item__value">Baat Karein</p>
                    </div>
                </a>
                <a href="mailto:harivanshisevakunj@gmail.com" class="contact-item" aria-label="Email us: harivanshisevakunj@gmail.com">
                    <div class="contact-item__icon" aria-hidden="true">✉️</div>
                    <div class="contact-item__content">
                        <p class="contact-item__label">Email Bhejein</p>
                        <p class="contact-item__value">info@harivanshi...</p>
                    </div>
                </a>
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode('Sunrakh Banger, Sobhri Kund Road, Radha Vatika, Vrindavan, Mathura 281121') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="contact-item"
                   aria-label="Our Location: Sunrakh Banger, Sobhri Kund Road, Radha Vatika Ke Paas, Vrindavan, Mathura 281121"
                   title="Sunrakh Banger, Sobhri Kund Road, Radha Vatika Ke Paas, Vrindavan, Mathura - 281121">
                    <div class="contact-item__icon" aria-hidden="true">📍</div>
                    <div class="contact-item__content">
                        <p class="contact-item__label">Hamara Pata (Location)</p>
                        <p class="contact-item__value">Sunrakh Banger, Vrindavan</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="contact-section__whatsapp">
            <div class="contact-section__whatsapp-icon" aria-hidden="true"><i class="fa-brands fa-whatsapp" style="color: #25D366;"></i></div>
            <h3>WhatsApp Par Sampark Karein</h3>
            <p>Humse judne ka sabse aasan tareeka.<br>Hum kuch hi minutes me reply karte hain.</p>
            <a href="https://wa.me/{{ $homeWhatsNumber }}"
               class="btn btn-primary"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="WhatsApp Par Message Karein">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Abhi WhatsApp Par Baat Karein
            </a>
        </div>

    </div>
</section>

@endsection

@push('styles')
<style>
/* Inline-only: small utilities specific to this page instance */
.featured-demo-note {
    color: var(--color-text-muted);
    font-size: var(--text-sm);
}
</style>
@endpush
