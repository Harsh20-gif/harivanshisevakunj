@extends('layouts.app')

@section('title', 'Braj Katha & Pavitra Seva Mahima — Harivanshi Seva Kunj | Vrindavan Dham')
@section('meta_description', 'Shri Dham Vrindavan ki divya katha, Shri Radha Vallabh Lal Ju ka mahatmya, Tulsi Mala dharan karne ke niyam, aur Braj Raj va Yamuna Jal ki pavitrata ke baare me janein.')
@section('og_title', 'Braj Katha & Devotional Wisdom — Harivanshi Seva Kunj')
@section('og_description', 'Vrindavan Dham ki pavitra paramparayein, katha aur nitya seva niyam.')

@section('content')

{{-- ══════════════════════════════════════════════════
     HERO SECTION
     ══════════════════════════════════════════════════ --}}
<section class="about-hero" aria-label="Braj Katha and Devotional Wisdom">
    <div class="container about-hero__inner">
        <div class="about-hero__content">
            <span class="about-hero__greeting" aria-label="Devotional greeting">
                <span>✤</span> ✤ || राधा वल्लभ श्री हरिवंश || <span>✤</span>
            </span>

            <h1 class="about-hero__title">
                Braj Katha &amp; <span class="about-hero__accent">Seva Mahima</span>
            </h1>

            <p class="about-hero__tagline">
                Shri Dham Vrindavan Ke Divya Prasang Aur Nitya Seva Vidhi
            </p>

            <div class="divider-gold" style="margin-inline: 0; margin-bottom: var(--space-4);"></div>

            <p class="about-hero__text">
                Vrindavan ki pavitra raj, Shri Yamuna ji ka shuddh jal aur Thakurji ki Tulsi kanthi ke peechhe sadiyon ki bhakti parampara judi hui hai. Yahan janein pavitra niyam, kathaayein aur seva bhav.
            </p>

            <div class="about-hero__actions">
                <a href="#katha-grid" class="btn btn-primary">
                    Katha Padhein
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    Pavitra Seva Samagri Dekhein
                </a>
            </div>
        </div>

        <div class="about-hero__visual">
            <div class="about-hero__frame">
                @if(file_exists(public_path('images/devotional/barsana.webp')))
                    <img src="{{ asset('images/devotional/barsana.webp') }}"
                         alt="Shri Radha Rani Darshan — Barsana Dham"
                         class="about-hero__image"
                         loading="eager">
                @elseif(file_exists(public_path('images/devotional/barsana.png')))
                    <img src="{{ asset('images/devotional/barsana.png') }}"
                         alt="Shri Radha Rani Darshan — Barsana Dham"
                         class="about-hero__image"
                         loading="eager">
                @endif
                <p class="about-hero__caption">
                    ✤ Braj Dham Divya Prasang ✤
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════
     KATHA & DEVOTIONAL WISDOM GRID
     ══════════════════════════════════════════════════ --}}
<section class="section" id="katha-grid" aria-label="Braj Katha Articles">
    <div class="container">
        <div class="section-heading-wrap">
            <span class="section-flourish">✤</span>
            <h2 class="section-heading">Divya Prasang &amp; Seva Niyam</h2>
            <span class="section-flourish">✤</span>
        </div>
        <div class="divider-gold"></div>
        <p class="section-subheading">Vrindavan Dham ki pavitra paramparaon se judi anmol kathaayein</p>

        <div class="about-purpose__grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">

            {{-- Article 1: Shri Radha Vallabh Prakatya --}}
            <article class="purpose-card" style="text-align: left;">
                <div class="purpose-card__icon" aria-hidden="true">🛕</div>
                <h3 class="purpose-card__title">Shri Radha Vallabh Lal Ju Ka Prakatya</h3>
                <p class="purpose-card__body">
                    Shri Hit Harivansh Mahaprabhu ji dwara sthapit Shri Radha Vallabh Lal Ju ka darshan prem aur ras-upasana ka adbhut srot hai. Braj me Thakurji ko prem-ras se bhoga, vastra aur aarti samarpan karne ki parampara param kalyankari maani gayi hai.
                </p>
                <div style="margin-top: 14px;">
                    <a href="{{ route('products.index', ['category' => 'deity-wear']) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem; padding: 6px 14px;">
                        Thakurji Vastra Dekhein &rarr;
                    </a>
                </div>
            </article>

            {{-- Article 2: Tulsi Mala Dharan Ke Niyam --}}
            <article class="purpose-card" style="text-align: left;">
                <div class="purpose-card__icon" aria-hidden="true">📿</div>
                <h3 class="purpose-card__title">Tulsi Mala Dharan Karne Ke Niyam</h3>
                <p class="purpose-card__body">
                    Pavitra Tulsi Kanthi Mala gale me dharan karne se mann shant hota hai aur sakaratmak urja ka sanchar hota hai. Tulsi mala dharan karne wale bhakt ko shuddh satvik aahar aur nitya Radha-Naam ya Harivansh naam ka jap karna chahiye.
                </p>
                <div style="margin-top: 14px;">
                    <a href="{{ route('products.index', ['category' => 'mala-beads']) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem; padding: 6px 14px;">
                        Shuddh Tulsi Mala Dekhein &rarr;
                    </a>
                </div>
            </article>

            {{-- Article 3: Braj Raj & Yamuna Jal Mahatmya --}}
            <article class="purpose-card" style="text-align: left;">
                <div class="purpose-card__icon" aria-hidden="true">🌸</div>
                <h3 class="purpose-card__title">Braj Raj Aur Yamuna Jal Ki Mahima</h3>
                <p class="purpose-card__body">
                    Vrindavan Dham ki pavitra raj (Braj Raj) me Shri Radha-Krishna ki lilaon ke pad-chinh samaye hain. Nitya pooja me mastak par Braj Raj ka tilak lagana aur Shri Yamuna ji ke jal ka chhidkaav ghar ko mandir samaan pavan bana deta hai.
                </p>
                <div style="margin-top: 14px;">
                    <a href="{{ route('products.index', ['category' => 'puja-items']) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem; padding: 6px 14px;">
                        Puja Samagri Dekhein &rarr;
                    </a>
                </div>
            </article>

            {{-- Article 4: Pujya Gurudev Kripa & Naam Mahima --}}
            <article class="purpose-card" style="text-align: left;">
                <div class="purpose-card__icon" aria-hidden="true">🪔</div>
                <h3 class="purpose-card__title">Pujya Gurudev Premanand Ji Ka Sandesh</h3>
                <p class="purpose-card__body">
                    Pujya Maharaj Ji sadaiv kripa purvak batate hain ki jeevan me satvik aacharan, mata-pita ki seva aur nirantar 'Radhe Radhe' naam jap se hi jeevan saral aur anandmay banta hai. Sanatan seva parampara har ghar ka aadhar hai.
                </p>
                <div style="margin-top: 14px;">
                    <a href="{{ route('products.index', ['category' => 'chhavi-guruji']) }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem; padding: 6px 14px;">
                        Pujya Guruji Chhavi Dekhein &rarr;
                    </a>
                </div>
            </article>

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
                <p>Hamari seva team se WhatsApp par seedhe baat karein ya shop catalogue dekhein.</p>
            </div>
            <div class="about-bridge__actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Shop Catalogue
                </a>
                <a href="{{ route('contact') }}" class="btn btn-secondary">
                    Humse Sampark Karein
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
