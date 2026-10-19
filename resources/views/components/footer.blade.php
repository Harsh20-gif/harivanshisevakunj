{{--
    Site Footer Component
--}}
<footer class="site-footer" role="contentinfo">
    <div class="site-footer__top">
        <div class="container site-footer__grid">

            {{-- Brand column --}}
            <div class="site-footer__col site-footer__col--brand">
                <a href="{{ route('home') }}" class="site-footer__logo-link" aria-label="Harivanshi Seva Kunj – Home">
                    @if (file_exists(public_path('images/categories/logo.webp')))
                        <picture>
                            <source srcset="{{ asset('images/categories/logo.webp') }}" type="image/webp">
                            <img src="{{ asset('images/categories/logo.png') }}"
                                 alt="Harivanshi Seva Kunj logo"
                                 class="site-footer__logo-img"
                                 width="76"
                                 height="76"
                                 loading="lazy">
                        </picture>
                    @elseif (file_exists(public_path('images/categories/logo.png')))
                        <img src="{{ asset('images/categories/logo.png') }}"
                             alt="Harivanshi Seva Kunj logo"
                             class="site-footer__logo-img"
                             width="76"
                             height="76"
                             loading="lazy">
                    @else
                        <span class="site-footer__logo-text">हरिवंशी Seva Kunj</span>
                    @endif
                </a>
                <div class="site-footer__business-info">
                    <div class="site-footer__proprietor-badge" aria-label="Business Proprietor">
                        <span class="site-footer__proprietor-icon" aria-hidden="true">🪷</span>
                        <span class="site-footer__proprietor-label"></span>
                        <strong class="site-footer__proprietor-name">Uma Harivanshi</strong>
                    </div>
                </div>
                <p class="site-footer__tagline">
                    Shri Dham Vrindavan ke hriday se pavitra seva samagri — poore prem aur bhakti ke saath.
                </p>
                <p class="site-footer__dham">
                    📍 Sunrakh Banger, Sobhri Kund Road, Radha Vatika Ke Paas, Vrindavan, Mathura - 281121
                </p>
            </div>

            {{-- Quick links --}}
            <div class="site-footer__col">
                <h3 class="site-footer__heading">Zaroori Links</h3>
                <ul class="site-footer__links" role="list">
                    <li><a href="{{ route('home') }}" class="site-footer__link">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="site-footer__link">Shop (Dukan)</a></li>
                    <li><a href="{{ route('about') }}" class="site-footer__link"> About us</a></li>
                    <li><a href="{{ route('contact') }}" class="site-footer__link">Sampark Karein</a></li>
                </ul>
            </div>

            {{-- Categories (static for now; will be dynamic once categories are seeded) --}}
            <div class="site-footer__col">
                <h3 class="site-footer__heading">Pavitra Categories</h3>
                <ul class="site-footer__links" role="list">
                    <li><a href="{{ route('products.index', ['category' => 'puja-items']) }}" class="site-footer__link">Puja Samagri</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'deity-wear']) }}" class="site-footer__link">Thakurji Vastra &amp; Poshak</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'mala-beads']) }}" class="site-footer__link">Tulsi Mala &amp; Beads</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'incense']) }}" class="site-footer__link">Incense &amp; Sugandhit Dhoop</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'books']) }}" class="site-footer__link">Granth &amp; Braj Sahitya</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="site-footer__col">
                <h3 class="site-footer__heading">Humse Sampark Karein</h3>
                <ul class="site-footer__contact" role="list">
                    <li>
                        <span aria-hidden="true">📞</span>
                        <a href="tel:+919286070355" class="site-footer__link">+91 92860 70355</a>
                    </li>
                    <li>
                        <span aria-hidden="true">✉️</span>
                        <a href="mailto:harivanshisevakunj@gmail.com" class="site-footer__link">harivanshisevakunj@gmail.com</a>
                    </li>
                    <li>
                        <span aria-hidden="true"><i class="fa-brands fa-whatsapp" style="color: #25D366;"></i></span>
                        <a href="https://wa.me/{{ config('services.whatsapp.number') ?: (config('seo.business.whatsapp') ?: '919286070355') }}"
                           class="site-footer__link"
                           target="_blank"
                           rel="noopener noreferrer">WhatsApp Par Message Karein</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="site-footer__bottom">
        <div class="container site-footer__bottom-inner">
            <p class="site-footer__copy">
                &copy; {{ date('Y') }} Harivanshi Seva Kunj &bull;  Uma Harivanshi. Sabhi adhikar surakshit.
            </p>
            <p class="site-footer__credit">
                <a href="https://wa.me/917505150687" style="color: #fff; font-size: 15px; font-weight: bolder; ">Developed By Harsh Bhardwaj</a>
            </p>
        </div>
    </div>
</footer>
