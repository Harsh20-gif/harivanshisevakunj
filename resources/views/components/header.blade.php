{{--
    Site Header Component
    Sticky navigation with logo, primary nav links, and cart/CTA actions.
    Includes mobile drawer navigation inspired by Vrindavan devotional aesthetics.
--}}
<header class="site-header" id="site-header" role="banner">
    <div class="container site-header__inner">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="site-header__logo" aria-label="Harivanshi Seva Kunj – Home">
            @if (file_exists(public_path('images/categories/logo.webp')))
                <picture>
                    <source srcset="{{ asset('images/categories/logo.webp') }}" type="image/webp">
                    <img src="{{ asset('images/categories/logo.png') }}"
                         alt="Harivanshi Seva Kunj logo"
                         class="site-header__logo-img"
                         width="52"
                         height="52"
                         loading="eager"
                         fetchpriority="high">
                </picture>
            @elseif (file_exists(public_path('images/categories/logo.png')))
                <img src="{{ asset('images/categories/logo.png') }}"
                     alt="Harivanshi Seva Kunj logo"
                     class="site-header__logo-img"
                     width="52"
                     height="52"
                     loading="eager">
            @endif
            <div class="site-header__brand-info">
                <span class="site-header__brand-name"><b>हरिवंशी सेवा कुंज</b></span>
                <span class="site-header__proprietor"> Uma Harivanshi</span>
            </div>
        </a>

        {{-- Primary Navigation (Desktop) --}}
        <nav class="site-nav" aria-label="Primary navigation" id="primary-nav">
            <ul class="site-nav__list" role="list">
                <li><a href="{{ route('home') }}" class="site-nav__link @if(request()->routeIs('home')) site-nav__link--active @endif">Home</a></li>
                <li><a href="{{ route('products.index') }}" class="site-nav__link @if(request()->routeIs('products.*')) site-nav__link--active @endif">Shop</a></li>
                <li><a href="{{ route('about') }}" class="site-nav__link @if(request()->routeIs('about')) site-nav__link--active @endif"> About us</a></li>
                <li><a href="{{ route('blog') }}" class="site-nav__link @if(request()->routeIs('blog')) site-nav__link--active @endif">Braj Katha</a></li>
            </ul>
        </nav>

        {{-- Actions --}}
        <div class="site-header__actions">
            {{-- Header search bar --}}
            <form action="{{ route('products.index') }}" method="GET" class="site-header__search" role="search">
                <svg class="site-header__search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="search" name="q" placeholder="Radha Krishna seva items, mala, vastra search karein..." aria-label="Seva items search karein" class="site-header__search-input">
            </form>

            @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
            {{-- Cart --}}
            <a href="{{ route('cart.index') }}" class="site-header__icon-btn site-header__cart" id="cart-icon" title="Aapki Seva Cart Dekhein">
                <i class="fa-light fa-cart-shopping" aria-hidden="true"></i>
                <span class="site-header__cart-count" id="cart-count" aria-live="polite">{{ $cartCount }}</span>
                <span class="sr-only"> items in cart</span>
            </a>

            {{-- Mobile Menu Hamburger Toggle --}}
            <button class="site-header__menu-toggle"
                    id="menu-toggle"
                    aria-controls="mobile-drawer"
                    aria-expanded="false"
                    aria-label="Open navigation menu">
                <span class="hamburger" aria-hidden="true">
                    <span></span><span></span><span></span>
                </span>
            </button>
        </div>

    </div>
</header>

{{-- Separate Mobile Drawer Backdrop --}}
<div class="mobile-nav-backdrop" id="mobile-nav-backdrop" aria-hidden="true" tabindex="-1"></div>

{{-- Mobile Navigation Drawer (Warm Ivory Slide-In) --}}
<aside class="mobile-drawer"
       id="mobile-drawer"
       role="dialog"
       aria-modal="true"
       aria-label="Navigation Menu"
       aria-hidden="true"
       tabindex="-1">
    <div class="mobile-drawer__inner">

        {{-- Drawer Brand Header --}}
        <div class="mobile-drawer__header">
            <a href="{{ route('home') }}" class="mobile-drawer__brand" aria-label="Harivanshi Seva Kunj – Home">
                @if (file_exists(public_path('images/categories/logo.webp')))
                    <picture>
                        <source srcset="{{ asset('images/categories/logo.webp') }}" type="image/webp">
                        <img src="{{ asset('images/categories/logo.png') }}"
                             alt="Harivanshi Seva Kunj logo"
                             class="mobile-drawer__logo-img"
                             width="38"
                             height="38">
                    </picture>
                @elseif (file_exists(public_path('images/categories/logo.png')))
                    <img src="{{ asset('images/categories/logo.png') }}"
                         alt="Harivanshi Seva Kunj logo"
                         class="mobile-drawer__logo-img"
                         width="38"
                         height="38">
                @endif
                <div class="mobile-drawer__brand-text">
                    <span class="mobile-drawer__brand-title">हरिवंशी सेवा कुंज</span>
                    <span class="mobile-drawer__brand-subtitle">Vrindavan Dham</span>
                </div>
            </a>
            <button type="button"
                    class="mobile-drawer__close"
                    id="mobile-drawer-close"
                    aria-label="Close navigation menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Devotional Divider --}}
        <div class="mobile-drawer__divider" aria-hidden="true">
            <span class="mobile-drawer__divider-line"></span>
            <span class="mobile-drawer__divider-symbol">❀</span>
            <span class="mobile-drawer__divider-line"></span>
        </div>

        {{-- Drawer Navigation Rows with Lightweight Icons --}}
        <nav class="mobile-drawer__nav" aria-label="Mobile navigation">
            <ul class="mobile-drawer__list" role="list">
                <li class="mobile-drawer__item">
                    <a href="{{ route('home') }}"
                       class="mobile-drawer__link {{ request()->routeIs('home') ? 'mobile-drawer__link--active' : '' }}">
                        <span class="mobile-drawer__link-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </span>
                        <span class="mobile-drawer__link-text">Home</span>
                        @if(request()->routeIs('home'))
                            <span class="mobile-drawer__active-indicator" aria-hidden="true"></span>
                        @endif
                    </a>
                </li>

                <li class="mobile-drawer__item">
                    <a href="{{ route('products.index') }}"
                       class="mobile-drawer__link {{ request()->routeIs('products.*') ? 'mobile-drawer__link--active' : '' }}">
                        <span class="mobile-drawer__link-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </span>
                        <span class="mobile-drawer__link-text">Shop / Dukan</span>
                        @if(request()->routeIs('products.*'))
                            <span class="mobile-drawer__active-indicator" aria-hidden="true"></span>
                        @endif
                    </a>
                </li>

                <li class="mobile-drawer__item">
                    <a href="{{ route('about') }}"
                       class="mobile-drawer__link {{ request()->routeIs('about') ? 'mobile-drawer__link--active' : '' }}">
                        <span class="mobile-drawer__link-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 16v-4"/>
                                <path d="M12 8h.01"/>
                            </svg>
                        </span>
                        <span class="mobile-drawer__link-text"> About us</span>
                        @if(request()->routeIs('about'))
                            <span class="mobile-drawer__active-indicator" aria-hidden="true"></span>
                        @endif
                    </a>
                </li>

                <li class="mobile-drawer__item">
                    <a href="{{ route('blog') }}"
                       class="mobile-drawer__link {{ request()->routeIs('blog') ? 'mobile-drawer__link--active' : '' }}">
                        <span class="mobile-drawer__link-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                <path d="M6 6h10"/>
                                <path d="M6 10h10"/>
                            </svg>
                        </span>
                        <span class="mobile-drawer__link-text">Braj Katha</span>
                        @if(request()->routeIs('blog'))
                            <span class="mobile-drawer__active-indicator" aria-hidden="true"></span>
                        @endif
                    </a>
                </li>

                <li class="mobile-drawer__item">
                    <a href="{{ route('contact') }}"
                       class="mobile-drawer__link {{ request()->routeIs('contact') ? 'mobile-drawer__link--active' : '' }}">
                        <span class="mobile-drawer__link-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </span>
                        <span class="mobile-drawer__link-text">Sampark Karein</span>
                        @if(request()->routeIs('contact'))
                            <span class="mobile-drawer__active-indicator" aria-hidden="true"></span>
                        @endif
                    </a>
                </li>
            </ul>
        </nav>

        {{-- WhatsApp Contact Row --}}
        @php
            $whatsappNum = config('seo.business.whatsapp', '919286070355');
            $whatsappUrl = 'https://wa.me/' . $whatsappNum . '?text=' . urlencode('Radhe Radhe! I would like to enquire about seva items from Harivanshi Seva Kunj.');
        @endphp
        <div class="mobile-drawer__contact">
            <a href="{{ $whatsappUrl }}"
               target="_blank"
               rel="noopener noreferrer"
               class="mobile-drawer__whatsapp-btn"
               aria-label="Chat with us on WhatsApp (+91 92860 70355)">
                <span class="mobile-drawer__whatsapp-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16" fill="currentColor">
                        <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                    </svg>
                </span>
                <span class="mobile-drawer__whatsapp-text">
                    <span class="mobile-drawer__whatsapp-title">WhatsApp Seva Sahayata</span>
                    <span class="mobile-drawer__whatsapp-sub">+91 92860 70355</span>
                </span>
                <span class="mobile-drawer__whatsapp-arrow" aria-hidden="true">→</span>
            </a>
        </div>

        {{-- Devotional Footer --}}
        <div class="mobile-drawer__footer">
            <p class="mobile-drawer__mantra">राधावल्लभ श्री हरिवंश</p>
            <p class="mobile-drawer__proprietor"> Uma Harivanshi</p>
            <p class="mobile-drawer__tagline">Shri Dham Vrindavan Se Seedhe Aapke Ghar</p>
        </div>

    </div>
</aside>
