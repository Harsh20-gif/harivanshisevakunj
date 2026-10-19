{{-- 
  Flowing Sacred Vrindavan Ambient Background
  Ultra-smooth, slow-flowing, hardware-accelerated ambient gradients, luminous orbs, and Yamuna silk waves.
  Accessible & performance-optimized: pointer-events: none, z-index: 0, respects prefers-reduced-motion.
--}}
<div class="admin-bg-flowing" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; pointer-events: none; overflow: hidden; z-index: 0;" aria-hidden="true">
    {{-- Animated Slow-Flowing Base Gradient Mesh --}}
    <div class="admin-bg-flowing__mesh"></div>

    {{-- Luminous Floating Organic Orbs --}}
    <div class="admin-bg-flowing__orb admin-bg-flowing__orb--gold"></div>
    <div class="admin-bg-flowing__orb admin-bg-flowing__orb--sandalwood"></div>
    <div class="admin-bg-flowing__orb admin-bg-flowing__orb--kadamba"></div>
    <div class="admin-bg-flowing__orb admin-bg-flowing__orb--sunbeam"></div>

    {{-- Flowing Sacred Yamuna Silk Wave Ribbons --}}
    <svg class="admin-bg-flowing__wave-svg" viewBox="0 0 1440 600" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="flowWaveGrad1" x1="0%" y1="0%" x2="100%" y2="80%">
                <stop offset="0%" stop-color="#FFD900" stop-opacity="0.16" />
                <stop offset="45%" stop-color="#E8C896" stop-opacity="0.11" />
                <stop offset="100%" stop-color="#FAF5E8" stop-opacity="0.02" />
            </linearGradient>
            <linearGradient id="flowWaveGrad2" x1="100%" y1="0%" x2="0%" y2="90%">
                <stop offset="0%" stop-color="#1E7040" stop-opacity="0.07" />
                <stop offset="50%" stop-color="#FFD900" stop-opacity="0.10" />
                <stop offset="100%" stop-color="#E5BE00" stop-opacity="0.03" />
            </linearGradient>
            <linearGradient id="flowWaveGrad3" x1="40%" y1="0%" x2="60%" y2="100%">
                <stop offset="0%" stop-color="#FFF5A6" stop-opacity="0.20" />
                <stop offset="65%" stop-color="#F5ECE0" stop-opacity="0.08" />
                <stop offset="100%" stop-color="#FAF5E8" stop-opacity="0.00" />
            </linearGradient>
        </defs>
        <path class="admin-bg-flowing__wave-path admin-bg-flowing__wave-path--1"
              d="M-60,210 C220,340 460,90 760,220 C1060,350 1280,140 1520,240 L1520,600 L-60,600 Z"
              fill="url(#flowWaveGrad1)" />
        <path class="admin-bg-flowing__wave-path admin-bg-flowing__wave-path--2"
              d="M-50,330 C200,200 480,440 790,290 C1100,150 1320,380 1520,280 L1520,600 L-50,600 Z"
              fill="url(#flowWaveGrad2)" />
        <path class="admin-bg-flowing__wave-path admin-bg-flowing__wave-path--3"
              d="M-40,440 C280,360 560,510 920,410 C1260,320 1390,470 1520,400 L1520,600 L-40,600 Z"
              fill="url(#flowWaveGrad3)" />
    </svg>
</div>
