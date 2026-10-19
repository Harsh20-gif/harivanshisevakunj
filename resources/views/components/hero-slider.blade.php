{{--
    Hero Slider Component
    ─────────────────────────────────────────────────────────────
    Original Image Only — No Text, Buttons, Overlay or Zoom.
    Props:
      $slides  – array of slide objects:
                 { image, alt, primaryUrl }
    ─────────────────────────────────────────────────────────────
--}}
@props(['slides' => []])

@if(count($slides) > 0)
<section class="hero-slider" aria-label="Hero banner slideshow" id="hero-slider">

    {{-- Slide track --}}
    <div class="hero-slider__track" id="hero-track" aria-live="polite">
        @foreach($slides as $i => $slide)
            @php
                $isFirst = ($i === 0);
                $hasMobile = !empty($slide['mobile_image']);
                // Alternating Ken Burns motion:
                // img1 (i=0): zoomout slowly
                // img2 (i=1): zoomin  slowly
                // img3 (i=2): zoomout slowly
                // img4 (i=3): zoomin  slowly
                $zoomMode = ($i % 2 === 0) ? 'hero-slider__slide--zoomout' : 'hero-slider__slide--zoomin';
            @endphp
            <div class="hero-slider__slide {{ $zoomMode }} @if($isFirst) is-active @endif"
                 id="hero-slide-{{ $i }}"
                 role="group"
                 aria-roledescription="slide"
                 aria-label="{{ $i + 1 }} of {{ count($slides) }}"
                 @if(!$isFirst) aria-hidden="true" inert @else aria-hidden="false" @endif>

                @if(!empty($slide['primaryUrl']))
                    <a href="{{ $slide['primaryUrl'] }}"
                       class="hero-slider__link"
                       tabindex="{{ $isFirst ? '0' : '-1' }}"
                       aria-label="{{ $slide['alt'] ?? 'View products' }}">
                        <picture>
                            @if($hasMobile)
                                <source media="(max-width: 640px)" srcset="{{ $slide['mobile_image'] }}" type="image/webp" width="720" height="242">
                            @endif
                            @if(isset($slide['image']) && str_ends_with($slide['image'], '.webp'))
                                <source srcset="{{ $slide['image'] }}" type="image/webp">
                            @endif
                            <img src="{{ $slide['png_fallback'] ?? $slide['image'] }}"
                                 alt="{{ $slide['alt'] ?? 'Harivanshi Seva Kunj Banner' }}"
                                 class="hero-slider__img @if($isFirst) is-zooming @endif"
                                 width="1400"
                                 height="471"
                                 loading="{{ $isFirst ? 'eager' : 'lazy' }}"
                                 @if($isFirst) fetchpriority="high" @endif>
                        </picture>
                    </a>
                @else
                    <picture>
                        @if($hasMobile)
                            <source media="(max-width: 640px)" srcset="{{ $slide['mobile_image'] }}" type="image/webp" width="720" height="242">
                        @endif
                        @if(isset($slide['image']) && str_ends_with($slide['image'], '.webp'))
                            <source srcset="{{ $slide['image'] }}" type="image/webp">
                        @endif
                        <img src="{{ $slide['png_fallback'] ?? $slide['image'] }}"
                             alt="{{ $slide['alt'] ?? 'Harivanshi Seva Kunj Banner' }}"
                             class="hero-slider__img @if($isFirst) is-zooming @endif"
                             width="1400"
                             height="471"
                             loading="{{ $isFirst ? 'eager' : 'lazy' }}"
                             @if($isFirst) fetchpriority="high" @endif>
                    </picture>
                @endif
            </div>
        @endforeach
    </div>

    @if(count($slides) > 1)
        {{-- Navigation arrows --}}
        <button type="button" class="hero-slider__arrow hero-slider__arrow--prev" id="hero-slider-prev" aria-label="Previous slide">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button type="button" class="hero-slider__arrow hero-slider__arrow--next" id="hero-slider-next" aria-label="Next slide">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>

        {{-- Dots pagination --}}
        <div class="hero-slider__dots" id="hero-slider-dots" role="tablist" aria-label="Slide indicators">
            @foreach($slides as $i => $slide)
                <button type="button"
                        class="hero-slider__dot @if($i === 0) is-active @endif"
                        role="tab"
                        aria-label="Go to slide {{ $i + 1 }}"
                        aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                        data-index="{{ $i }}">
                </button>
            @endforeach
        </div>
    @endif

</section>

@push('scripts')
<script>
(function initHeroSlider() {
    const track = document.getElementById('hero-track');
    if (!track) return;

    const slides = Array.from(track.querySelectorAll('.hero-slider__slide'));
    if (slides.length < 2) return;

    const prevBtn = document.getElementById('hero-slider-prev');
    const nextBtn = document.getElementById('hero-slider-next');
    const dotsContainer = document.getElementById('hero-slider-dots');
    const dots = dotsContainer ? Array.from(dotsContainer.querySelectorAll('.hero-slider__dot')) : [];

    let current = 0;
    let timer = null;
    const DELAY = 5500; // 5.5s slow cinematic duration for breathing zoom

    function restartAnimation(slide) {
        const img = slide.querySelector('.hero-slider__img');
        if (!img) return;
        // Clean CSSOM property reset with forced reflow for instantaneous animation reboot
        img.style.setProperty('animation', 'none', 'important');
        img.classList.remove('is-zooming');
        void img.offsetWidth;
        img.style.removeProperty('animation');
        img.classList.add('is-zooming');
    }

    function goTo(index) {
        current = (index + slides.length) % slides.length;
        track.style.transform = `translateX(-${current * 100}%)`;

        slides.forEach((s, i) => {
            const isActive = (i === current);
            s.classList.toggle('is-active', isActive);
            s.setAttribute('aria-hidden', (!isActive).toString());
            s.inert = !isActive;

            const link = s.querySelector('.hero-slider__link');
            if (link) {
                link.setAttribute('tabindex', isActive ? '0' : '-1');
            }

            const img = s.querySelector('.hero-slider__img');
            if (img) {
                if (isActive) {
                    restartAnimation(s);
                } else {
                    img.classList.remove('is-zooming');
                }
            }
        });

        // Update dots indicators
        dots.forEach((dot, i) => {
            const isDotActive = (i === current);
            dot.classList.toggle('is-active', isDotActive);
            dot.setAttribute('aria-selected', isDotActive.toString());
        });
    }

    function startAutoplay() {
        stopAutoplay();
        timer = setInterval(() => goTo(current + 1), DELAY);
    }
    function stopAutoplay() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    }

    // Arrow button controls
    prevBtn?.addEventListener('click', () => {
        goTo(current - 1);
        startAutoplay();
    });
    nextBtn?.addEventListener('click', () => {
        goTo(current + 1);
        startAutoplay();
    });

    // Dot pagination controls
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            goTo(i);
            startAutoplay();
        });
    });

    // Pause on hover / focus
    const section = document.getElementById('hero-slider');
    section?.addEventListener('mouseenter', stopAutoplay);
    section?.addEventListener('mouseleave', startAutoplay);
    section?.addEventListener('focusin',    stopAutoplay);
    section?.addEventListener('focusout',   stopAutoplay);

    // Keyboard (left / right arrows)
    section?.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft')  { goTo(current - 1); startAutoplay(); }
        if (e.key === 'ArrowRight') { goTo(current + 1); startAutoplay(); }
    });

    // Touch swipe support for smooth mobile sliding
    let touchStartX = 0;
    let touchEndX = 0;
    section?.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        stopAutoplay();
    }, { passive: true });
    section?.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 45) {
            goTo(current + 1);
        } else if (touchEndX - touchStartX > 45) {
            goTo(current - 1);
        }
        startAutoplay();
    }, { passive: true });

    // Trigger initial slow zoomout on img1 immediately
    if (slides[0]) {
        restartAnimation(slides[0]);
    }

    startAutoplay();
}());
</script>
@endpush
@endif
