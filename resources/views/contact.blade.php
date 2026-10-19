@extends('layouts.app')

@section('title', 'Humse Sampark Karein — Harivanshi Seva Kunj | Vrindavan Dham')
@section('meta_description', 'Harivanshi Seva Kunj se sampark karein. Pavitra seva samagri, Tulsi Mala, Thakurji Vastra ke order ya kisi sahayata ke liye hamare Vrindavan Dham kendra par sampark karein ya WhatsApp karein.')
@section('og_title', 'Contact Harivanshi Seva Kunj — Vrindavan Dham')
@section('og_description', 'Sunrakh Banger, Sobhri Kund Road, Radha Vatika Ke Paas, Vrindavan, Mathura - 281121. WhatsApp: +91 92860 70355.')

@section('content')

{{-- ══════════════════════════════════════════════════
     HERO SECTION
     ══════════════════════════════════════════════════ --}}
<section class="about-hero" aria-label="Contact Harivanshi Seva Kunj">
    <div class="container about-hero__inner">
        <div class="about-hero__content">
            <span class="about-hero__greeting" aria-label="Devotional greeting">
                <span>✤</span> ✤ || राधा वल्लभ श्री हरिवंश || <span>✤</span>
            </span>

            <h1 class="about-hero__title">
                Humse Sampark Karein <span class="about-hero__accent">Harivanshi Seva Kunj</span>
            </h1>

            <p class="about-hero__tagline">
                Shri Dham Vrindavan Seva Kendra — Nitya Sahayata &amp; Pavitra Order
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
                Chahe aapko pavitra Tulsi Kanthi Mala ka chayan karna ho, Thakur Ji ke vastra ka naap lena ho, ya apne ghar me Braj Raj aur Yamuna Jal mangwana ho — hamari seva team poori shraddha ke saath aapki sahayata ke liye tatpar hai.
            </p>

            <div class="about-hero__actions">
                <a href="https://wa.me/{{ config('services.whatsapp.number') ?: (config('seo.business.whatsapp') ?: '919286070355') }}?text={{ rawurlencode('Radhe Radhe! Mujhe Harivanshi Seva Kunj se pavitra seva samagri ke baare me jankari chahiye.') }}"
                   class="btn btn-primary"
                   target="_blank"
                   rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp Par Message Karein
                </a>
                <a href="tel:+919286070355" class="btn btn-secondary">
                    📞 +91 92860 70355
                </a>
            </div>
        </div>

        <div class="about-hero__visual">
            <div class="about-hero__frame">
                @if(file_exists(public_path('images/categories/logo.webp')))
                    <img src="{{ asset('images/categories/logo.webp') }}"
                         alt="Harivanshi Seva Kunj Logo"
                         class="about-hero__image"
                         loading="eager">
                @elseif(file_exists(public_path('images/categories/logo.png')))
                    <img src="{{ asset('images/categories/logo.png') }}"
                         alt="Harivanshi Seva Kunj Logo"
                         class="about-hero__image"
                         loading="eager">
                @endif
                <p class="about-hero__caption">
                    ✤ Shri Dham Vrindavan Seva Kendra ✤
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════
     CONTACT DETAILS CARDS GRID
     ══════════════════════════════════════════════════ --}}
<section class="section" aria-label="Contact Details">
    <div class="container">
        <div class="section-heading-wrap">
            <span class="section-flourish">✤</span>
            <h2 class="section-heading">Sampark Madhyam</h2>
            <span class="section-flourish">✤</span>
        </div>
        <div class="divider-gold"></div>
        <p class="section-subheading">Humse judne aur sahayata pane ke saral vikalp</p>

        <div class="about-purpose__grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">

            {{-- Physical Shop Card --}}
            <div class="purpose-card">
                <div class="purpose-card__icon" aria-hidden="true">📍</div>
                <h3 class="purpose-card__title">Vrindavan Kendra</h3>
                <p class="purpose-card__body">
                    <strong></strong> Uma Harivanshi<br>
                    <strong>Pratisthan:</strong> Harivanshi Seva Kunj<br>
                    <strong>Pata:</strong><br>
                    Sunrakh Banger, Sobhri Kund Road,<br>
                    Radha Vatika Ke Paas,<br>
                    Vrindavan, Mathura - 281121<br>
                    (Uttar Pradesh, India)
                </p>
            </div>

            {{-- WhatsApp Support Card --}}
            <div class="purpose-card">
                <div class="purpose-card__icon" aria-hidden="true">💬</div>
                <h3 class="purpose-card__title">WhatsApp Seva Helpline</h3>
                <p class="purpose-card__body">
                    Photo bhejiye, prashna puchiye ya direct order book karein:<br>
                    <strong>+91 92860 70355</strong><br>
                    <a href="https://wa.me/{{ config('services.whatsapp.number') ?: (config('seo.business.whatsapp') ?: '919286070355') }}?text={{ rawurlencode('Radhe Radhe! Mujhe seva samagri order karni hai.') }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       style="display: inline-block; margin-top: 10px; color: #b8621b; font-weight: 600; text-decoration: underline;">
                        Chat Shuru Karein &rarr;
                    </a>
                </p>
            </div>

            {{-- Phone & Email Card --}}
            <div class="purpose-card">
                <div class="purpose-card__icon" aria-hidden="true">📞</div>
                <h3 class="purpose-card__title">Phone &amp; Email</h3>
                <p class="purpose-card__body">
                    <strong>Phone Call:</strong> <a href="tel:+919286070355" style="color: inherit; text-decoration: underline;">+91 92860 70355</a><br><br>
                    <strong>Email:</strong> <a href="mailto:harivanshisevakunj@gmail.com" style="color: inherit; text-decoration: underline;">harivanshisevakunj@gmail.com</a>
                </p>
            </div>

            {{-- Hours & Timing Card --}}
            <div class="purpose-card">
                <div class="purpose-card__icon" aria-hidden="true">⏰</div>
                <h3 class="purpose-card__title">Seva Samay</h3>
                <p class="purpose-card__body">
                    <strong>Somwar se Ravivar:</strong><br>
                    Praatah 9:00 AM se Sandhya 8:00 PM tak<br>
                    <em>(Nitya Seva Sahayata Online &amp; Offline Uplabdh)</em>
                </p>
            </div>

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
                <h3>Vrindavan Dham Ki Pavitra Seva Samagri Dekhein</h3>
                <p>Harivanshi Seva Kunj ( Uma Harivanshi) — Authentic Tulsi Kanthi, Radha Naam Chunri, Braj Raj, Yamuna Jal aur Thakurji Vastra ghar baithe mangwayein.</p>
            </div>
            <div class="about-bridge__actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Shop Catalogue
                </a>
                <a href="{{ route('about') }}" class="btn btn-secondary">
                     About us Janein
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
