{{--
  Flowing Sacred Vrindavan Ambient Background (Public Storefront)
  Features:
    1. Smooth shifting auroral gradient mesh
    2. Soft luminous drifting organic orbs (Gold, Sandalwood, Kadamba, Sunbeam)
    3. Silky flowing Yamuna river wave ribbons
    4. Floating sacred temple diya sparkles
  Hardware-accelerated, pointer-events: none, accessible with prefers-reduced-motion.
--}}
<div class="public-bg-flowing" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; pointer-events: none; overflow: hidden; z-index: 0;" aria-hidden="true">
    {{-- Animated Slow-Flowing Base Gradient Mesh --}}
    <div class="public-bg-flowing__mesh"></div>

    {{-- Luminous Floating Organic Orbs --}}
    <div class="public-bg-flowing__orb public-bg-flowing__orb--gold"></div>
    <div class="public-bg-flowing__orb public-bg-flowing__orb--sandalwood"></div>
    <div class="public-bg-flowing__orb public-bg-flowing__orb--kadamba"></div>
    <div class="public-bg-flowing__orb public-bg-flowing__orb--sunbeam"></div>

    {{-- Flowing Sacred Yamuna Silk Wave Ribbons --}}
    <svg class="public-bg-flowing__wave-svg" viewBox="0 0 1440 600" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="pubFlowWaveGrad1" x1="0%" y1="0%" x2="100%" y2="80%">
                <stop offset="0%" stop-color="#FFD900" stop-opacity="0.14" />
                <stop offset="50%" stop-color="#E8C896" stop-opacity="0.09" />
                <stop offset="100%" stop-color="#FAF5E8" stop-opacity="0.01" />
            </linearGradient>
            <linearGradient id="pubFlowWaveGrad2" x1="100%" y1="0%" x2="0%" y2="90%">
                <stop offset="0%" stop-color="#1E7040" stop-opacity="0.06" />
                <stop offset="45%" stop-color="#FFD900" stop-opacity="0.08" />
                <stop offset="100%" stop-color="#E5BE00" stop-opacity="0.02" />
            </linearGradient>
            <linearGradient id="pubFlowWaveGrad3" x1="30%" y1="0%" x2="70%" y2="100%">
                <stop offset="0%" stop-color="#FFF5A6" stop-opacity="0.18" />
                <stop offset="60%" stop-color="#F5ECE0" stop-opacity="0.07" />
                <stop offset="100%" stop-color="#FAF5E8" stop-opacity="0.00" />
            </linearGradient>
        </defs>
        <path class="public-bg-flowing__wave-path public-bg-flowing__wave-path--1"
              d="M-60,200 C220,330 460,80 760,210 C1060,340 1280,130 1520,230 L1520,600 L-60,600 Z"
              fill="url(#pubFlowWaveGrad1)" />
        <path class="public-bg-flowing__wave-path public-bg-flowing__wave-path--2"
              d="M-50,320 C200,190 480,430 790,280 C1100,140 1320,370 1520,270 L1520,600 L-50,600 Z"
              fill="url(#pubFlowWaveGrad2)" />
        <path class="public-bg-flowing__wave-path public-bg-flowing__wave-path--3"
              d="M-40,430 C280,350 560,500 920,400 C1260,310 1390,460 1520,390 L1520,600 L-40,600 Z"
              fill="url(#pubFlowWaveGrad3)" />
    </svg>

    {{-- Sacred Floating Diya Sparks / Golden Light Particles --}}
    <div class="public-bg-flowing__sparkles">
        <span class="public-bg-flowing__sparkle public-bg-flowing__sparkle--1"></span>
        <span class="public-bg-flowing__sparkle public-bg-flowing__sparkle--2"></span>
        <span class="public-bg-flowing__sparkle public-bg-flowing__sparkle--3"></span>
        <span class="public-bg-flowing__sparkle public-bg-flowing__sparkle--4"></span>
        <span class="public-bg-flowing__sparkle public-bg-flowing__sparkle--5"></span>
        <span class="public-bg-flowing__sparkle public-bg-flowing__sparkle--6"></span>
    </div>
</div>
