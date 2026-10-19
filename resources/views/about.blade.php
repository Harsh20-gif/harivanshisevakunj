@extends('layouts.app')

@section('title', ' About us — Harivanshi Seva Kunj | Vrindavan Dham')
@section('meta_description', 'Harivanshi Seva Kunj ke baare me janein, jo Shri Dham Vrindavan se pavitra seva samagri, Tulsi mala, Braj Raj, Yamuna Jal aur devotional items seedhe bhakton tak pahunchata hai.')
@section('og_title', 'About Harivanshi Seva Kunj — Vrindavan Dham')
@section('og_description', 'Aapke Ghar Tak Vrindavan Dham Ki Seva — Authentic devotional items from Shri Dham Vrindavan.')

@section('content')

{{-- ══════════════════════════════════════════════════
     HERO SECTION
     ══════════════════════════════════════════════════ --}}
<section class="about-hero" aria-label="About Harivanshi Seva Kunj">
    <div class="container about-hero__inner">
        <div class="about-hero__content">
            <span class="about-hero__greeting" aria-label="Devotional greeting">
                <span></span> ✤ || राधा वल्लभ श्री हरिवंश || <span>✤</span>
            </span>

            <h1 class="about-hero__title">
                 About us <span class="about-hero__accent">Harivanshi Seva Kunj</span>
                <span class="sr-only">About Harivanshi Seva Kunj</span>
            </h1>

            <p class="about-hero__tagline">
                Aapke Ghar Tak Vrindavan Dham Ki Seva
            </p>

            <div class="about-hero__business-meta">
                <span class="about-hero__proprietor-badge">
                    <span class="about-hero__proprietor-icon" aria-hidden="true">🪷</span>
                    <span class="about-hero__proprietor-label"></span>
                    <strong class="about-hero__proprietor-name">Uma Harivanshi</strong>
                </span>
                <span class="about-hero__location-badge">
                    <span aria-hidden="true">📍</span> Shri Dham Vrindavan
                </span>
            </div>

            <div class="divider-gold" style="margin-inline: 0; margin-bottom: var(--space-4);"></div>

            <p class="about-hero__text">
                Harivanshi Seva Kunj ( Uma Harivanshi) Shri Dham Vrindavan se judi pramanik seva samagri aur pavitra bhent ko seedhe poore Bharat ke bhakton ke ghar tak pahunchata hai. Chahe pavitra Tulsi Mala ho, Braj Raj, Yamuna Jal, ya Thakur Ji ke manmohak Vastra — hamara uddeshya har bhakt ko daily seva aur Vrindavan Dham ke divya aashirwad se jode rakhna hai.
            </p>

            <div class="about-hero__actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Pavitra Seva Dekhein
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="#purpose" class="btn btn-secondary">Hamara Uddeshya</a>
            </div>
        </div>

        <div class="about-hero__visual">
            <div class="about-hero__frame">
                @if(file_exists(public_path('images/devotional/lalju.png')))
                    <img src="{{ asset('images/devotional/lalju.png') }}"
                         alt="Shri Radha Vallabh Lal Ju darshan in Pitambar"
                         class="about-hero__image"
                         loading="eager">
                @elseif(file_exists(public_path('images/categories/logo.png')))
                    <img src="{{ asset('images/categories/logo.png') }}"
                         alt="Harivanshi Seva Kunj Logo"
                         class="about-hero__image"
                         loading="eager">
                @endif
                <p class="about-hero__caption">
                    ✤ Shri Dham Vrindavan Seva ✤
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════
     OUR PURPOSE
     ══════════════════════════════════════════════════ --}}
<section class="section about-purpose" id="purpose" aria-label="Our Purpose">
    <div class="container">
        <div class="section-heading-wrap">
            <span class="section-flourish">✤</span>
            <h2 class="section-heading">Hamara Uddeshya <span class="sr-only">Our Purpose</span></h2>
            <span class="section-flourish">✤</span>
        </div>
        <div class="divider-gold"></div>
        <p class="section-subheading">Har bhakt ke ghar tak shuddh aur pavitra seva pahunchane ke liye samarpit</p>

        <div class="about-purpose__grid">
            <div class="purpose-card">
                <div class="purpose-card__icon" aria-hidden="true">🛕</div>
                <h3 class="purpose-card__title">Ghar Baithe Seva</h3>
                <p class="purpose-card__body">
                    Hum bhakton aur Vrindavan Dham ke beech ki doori ko mitate hain, jisse desh bhar ke parivar nitya puja aur Thakurji ki seva shuddh Braj samagri ke saath kar sakein.
                </p>
            </div>

            <div class="purpose-card">
                <div class="purpose-card__icon" aria-hidden="true">🌸</div>
                <h3 class="purpose-card__title">Pavitrata &amp; Shraddha</h3>
                <p class="purpose-card__body">
                    Har ek pavitra vastu ko poore aadar, prem aur pavitrata ke saath pack kiya jata hai taaki aapke ghar ke mandir tak poori shuddhata ke saath pahunche.
                </p>
            </div>

            <div class="purpose-card">
                <div class="purpose-card__icon" aria-hidden="true">🍃</div>
                <h3 class="purpose-card__title">Braj Ke Karigar</h3>
                <p class="purpose-card__body">
                    Vrindavan Dham ke parmparik karigaron dwara banayi gayi samagri ko prathmikta dekar hum Braj ki sadiyon purani seva paramparaon ko aage badha rahe hain.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════
     OUR SEVA (CATEGORIES SHOWCASE)
     ══════════════════════════════════════════════════ --}}
<section class="section about-seva" id="seva" aria-label="Our Seva Offerings">
    <div class="container">
        <div class="section-heading-wrap">
            <span class="section-flourish">✤</span>
            <h2 class="section-heading">Hamari Pavitra Seva Bhent</h2>
            <span class="section-flourish">✤</span>
        </div>
        <div class="divider-gold"></div>
        <p class="section-subheading">
            Braj Raj, Yamuna Jal, Tulsi Mala, Kanthi Mala, Thakur Ji Vastra, Pooja Samagri, Chhavi &amp; aur bhi bahut kuch
        </p>

        <div class="shop-category-scroll about-seva__list">
            <div class="shop-category-list">
                @foreach($categories as $category)
                    <x-category-card
                        :name="$category->name"
                        :slug="$category->slug"
                        :image="$category->image_url"
                        :url="route('products.index', ['category' => $category->slug])"
                    />
                @endforeach
            </div>
        </div>

        <div class="about-seva__footer">
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                Sabhi Seva Items Dekhein
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════
     INSTAGRAM COMMUNITY SECTION
     ══════════════════════════════════════════════════ --}}
<section class="section about-instagram" id="community" aria-label="Instagram Devotional Community">
    <div class="container">
        <div class="section-heading-wrap">
            <span class="section-flourish">✤</span>
            <h2 class="section-heading">Hamare Bhakt Parivar Se Judein <span class="sr-only">Connect With Our Devotional Community</span></h2>
            <span class="section-flourish">✤</span>
        </div>
        <div class="divider-gold"></div>
        <p class="section-subheading">Shri Dham Vrindavan ke nitya darshan aur satsang ke liye hamari family ka hissa banein</p>

        <div class="instagram-card">
            <div class="instagram-card__icon-wrap" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                </svg>
            </div>

            <h3 class="instagram-card__handle">@uma_harivanshi75</h3>

            <div>
                <span class="instagram-card__stat-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    33.3K Devotees Parivar
                </span>
            </div>

            <p class="instagram-card__body">
                Instagram par humse judkar nitya Vrindavan darshan, aarti reflections, aur pavitra seva paramparaon se jude rahein.
            </p>

            <a href="https://www.instagram.com/uma_harivanshi75/"
               target="_blank"
               rel="noopener noreferrer"
               class="btn-instagram"
               aria-label="Follow Harivanshi Seva Kunj on Instagram @uma_harivanshi75 (opens in new tab)">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                </svg>
                Instagram Par Follow Karein
            </a>

            <p class="instagram-card__disclaimer">
                Community figure: 33.3K followers as supplied by page owner.
            </p>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════
     DEVOTIONAL BRIDGE STRIP
     ══════════════════════════════════════════════════ --}}
<section class="about-bridge" aria-label="Visit Shop or Get in Touch">
    <div class="container">
        <div class="about-bridge__card">
            <div class="about-bridge__content">
                <h3>Kya aap apne ghar me Vrindavan Dham ki seva sthapit karna chahte hain?</h3>
                <p>Harivanshi Seva Kunj ( Uma Harivanshi) &bull; Sunrakh Banger, Sobhri Kund Road, Radha Vatika Ke Paas, Vrindavan, Mathura - 281121 &bull; harivanshisevakunj@gmail.com</p>
            </div>
            <div class="about-bridge__actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Shop Catalogue
                </a>
                <a href="https://wa.me/{{ config('services.whatsapp.number') ?: (config('seo.business.whatsapp') ?: '919286070355') }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp Par Baat Karein
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
