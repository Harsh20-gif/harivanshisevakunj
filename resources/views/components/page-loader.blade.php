{{--
    Balanced Devotional Page Loader
    - Provides a smooth, harmonious loading transition
    - Automatically dissolves on window load / DOMContentLoaded (max 850ms)
    - Non-blocking, accessible, zero layout shift
--}}
<div id="page-balanced-loader" class="page-loader" role="status" aria-live="polite" aria-label="Harivanshi Seva Kunj Loading">
    <div class="page-loader__content">
        {{-- Sacred Rotating Golden Ring & Center Emblem --}}
        <div class="page-loader__ring-wrap">
            <div class="page-loader__ring"></div>
            <div class="page-loader__ring-inner"></div>
            <div class="page-loader__emblem">
                @if (file_exists(public_path('images/categories/logo.webp')))
                    <picture>
                        <source srcset="{{ asset('images/categories/logo.webp') }}" type="image/webp">
                        <img src="{{ asset('images/categories/logo.png') }}"
                             alt="Harivanshi Seva Kunj"
                             width="44"
                             height="44"
                             class="page-loader__logo">
                    </picture>
                @elseif (file_exists(public_path('images/categories/logo.png')))
                    <img src="{{ asset('images/categories/logo.png') }}"
                         alt="Harivanshi Seva Kunj"
                         width="44"
                         height="44"
                         class="page-loader__logo">
                @else
                    <span class="page-loader__icon">🪔</span>
                @endif
            </div>
        </div>

        {{-- Devotional Mantra Caption --}}
        <div class="page-loader__caption">
            <span class="page-loader__mantra">॥ राधावल्लभ श्री हरिवंश ॥</span>
            <span class="page-loader__subtext">Shri Dham Vrindavan Seva</span>
        </div>

        {{-- Balanced Subtle Progress Indicator --}}
        <div class="page-loader__bar">
            <div class="page-loader__bar-fill"></div>
        </div>
    </div>
</div>

<script>
    // Self-contained safety dismiss for immediate responsiveness
    (function () {
        var loader = document.getElementById('page-balanced-loader');
        if (!loader) return;
        var dismissed = false;
        function dismiss() {
            if (dismissed) return;
            dismissed = true;
            loader.classList.add('page-loader--hidden');
            setTimeout(function () {
                if (loader && loader.parentNode) {
                    loader.parentNode.removeChild(loader);
                }
            }, 420);
        }
        if (document.readyState === 'complete') {
            setTimeout(dismiss, 120);
        } else {
            window.addEventListener('load', function () { setTimeout(dismiss, 100); }, { once: true });
            document.addEventListener('DOMContentLoaded', function () { setTimeout(dismiss, 280); }, { once: true });
        }
        setTimeout(dismiss, 850);
    })();
</script>
