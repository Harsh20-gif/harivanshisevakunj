{{--
    Announcement Bar Component
    Usage: <x-announcement-bar :message="$message" />
    Accepts an optional :message prop; falls back to a default.
--}}
<div class="announcement-bar" role="region" aria-label="Site announcement">
    <div class="container announcement-bar__inner">
        <span class="announcement-bar__icon" aria-hidden="true">✦</span>
        <p class="announcement-bar__text">
            <marquee>
                {{ $message ?? '॥ राधावल्लभ श्री हरिवंश ॥  ✦  Vrindavan Dham Se Pavitra Seva Samagri  ✦  Free Delivery on Orders Over ₹499  ✦  Seedhe Braj Dham Se Aapke Ghar Tak  ✦  ॥ राधावल्लभ श्री हरिवंश ॥' }}
            </marquee>  
        </p>
        <span class="announcement-bar__icon" aria-hidden="true">✦</span>
    </div>
</div>
