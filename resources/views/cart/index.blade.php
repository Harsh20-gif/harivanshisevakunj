@extends('layouts.app')

@section('title', 'Your Seva Cart — Harivanshi Seva Kunj')
@section('meta_description', 'View your selected sacred seva articles from Harivanshi Seva Kunj and enquire directly on WhatsApp.')

@section('content')
<section class="section cart-section" aria-label="Shopping Cart">
    <div class="container">

        {{-- Page Heading --}}
        <div class="section-heading-wrap" style="margin-bottom: var(--space-4);">
            <span class="section-flourish">✤</span>
            <h1 class="section-heading">Your Seva Cart</h1>
            <span class="section-flourish">✤</span>
        </div>
        <div class="divider-gold"></div>

        {{-- Flash Feedback Messages --}}
        @if(session('success'))
            <div class="cart-alert cart-alert--success" role="alert">
                <span>🌸</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="cart-alert cart-alert--error" role="alert">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(empty($cart))
            {{-- Empty Cart State --}}
            <div class="cart-empty">
                <div class="cart-empty__icon" aria-hidden="true">
                    <i class="fa-light fa-cart-shopping"></i>
                </div>
                <h2 class="cart-empty__title">Aapki Seva Cart Khaali Hai <span class="sr-only">Your Seva Cart is empty</span></h2>
                <p class="cart-empty__desc">
                    Aapne abhi tak koi pavitra seva vastu cart me nahi jodi hai. Shri Dham Vrindavan ke shuddh utpaad dekhein aur Thakurji ki seva me samarpit hon.
                </p>
                <div style="margin-top: var(--space-6);">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Pavitra Seva Items Dekhein <span class="sr-only">Explore Sacred Items</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        @else
            {{-- Cart Layout with Items and Order Summary --}}
            <div class="cart-layout">

                {{-- Items Table / List --}}
                <div class="cart-items-card">
                    <div class="cart-items-header">
                        <span class="cart-items-header__product">Item Vivaran</span>
                        <span class="cart-items-header__price">Mulya</span>
                        <span class="cart-items-header__qty">Matra</span>
                        <span class="cart-items-header__total">Subtotal</span>
                        <span class="cart-items-header__action"></span>
                    </div>

                    <div class="cart-items-list">
                        @foreach($cart as $id => $item)
                            <div class="cart-item" id="cart-item-{{ $id }}">
                                <div class="cart-item__visual">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="cart-item__img">
                                    @else
                                        <div class="cart-item__placeholder">🛍️</div>
                                    @endif
                                    <div>
                                        <h3 class="cart-item__title">{{ $item['title'] }}</h3>
                                        <span class="cart-item__unit-price-mobile">₹{{ number_format($item['price']) }}</span>
                                    </div>
                                </div>

                                <div class="cart-item__price">
                                    ₹{{ number_format($item['price']) }}
                                </div>

                                <div class="cart-item__qty">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="cart-qty-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number"
                                               name="quantity"
                                               value="{{ $item['quantity'] }}"
                                               min="1"
                                               max="99"
                                               class="cart-qty-input"
                                               onchange="this.form.submit()"
                                               aria-label="Quantity for {{ $item['title'] }}">
                                    </form>
                                </div>

                                <div class="cart-item__subtotal">
                                    ₹{{ number_format($item['price'] * $item['quantity']) }}
                                </div>

                                <div class="cart-item__action">
                                    <form action="{{ route('cart.destroy', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cart-remove-btn" title="Item hatayein" aria-label="Remove {{ $item['title'] }}">
                                            &times;
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="cart-card-footer">
                        <a href="{{ route('products.index') }}" class="btn btn-outline" style="border-radius: var(--radius-pill);">
                            &larr; Aur Seva Samagri Chunein
                        </a>
                    </div>
                </div>

                {{-- Order Summary & Invoice Sidebar --}}
                <div class="cart-summary-card cart-invoice-card" id="cart-invoice">
                    <div class="cart-invoice__header">
                        <div class="cart-invoice__emblem">✤ || राधा वल्लभ श्री हरिवंश || ✤</div>
                        <h2 class="cart-summary__title cart-invoice__title">Seva Order Invoice</h2>
                        <span class="cart-invoice__subtitle">Harivanshi Seva Kunj • Shri Dham Vrindavan</span>
                    </div>

                    <div class="cart-invoice__meta">
                        <div class="cart-invoice__meta-col">
                            <span class="cart-invoice__meta-lbl">Invoice / Est.</span>
                            <span class="cart-invoice__meta-val">#HSK-{{ strtoupper(substr(session()->getId() ?? 'ORD', 0, 6)) }}</span>
                        </div>
                        <div class="cart-invoice__meta-col" style="text-align: right;">
                            <span class="cart-invoice__meta-lbl">Tarikh (Date)</span>
                            <span class="cart-invoice__meta-val">{{ date('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="divider-gold" style="margin-inline: 0; margin-block: var(--space-3);"></div>

                    {{-- Invoice Calculation Breakdown --}}
                    <div class="cart-invoice__rows">
                        <div class="cart-summary__row">
                            <span class="cart-summary__row-lbl">
                                Items Subtotal
                                <small class="cart-summary__row-sub">({{ $totalItems }} {{ $totalItems === 1 ? 'item' : 'items' }})</small>
                            </span>
                            <span class="cart-summary__row-val">₹{{ number_format($subtotal) }}</span>
                        </div>

                        <div class="cart-summary__row">
                            <span class="cart-summary__row-lbl">
                                Delivery Charge
                                <small class="cart-summary__row-sub">(Vrindavan Se Aapke Ghar Tak)</small>
                            </span>
                            <span class="cart-summary__row-val cart-summary__delivery-val">+₹{{ number_format($deliveryCharge) }}</span>
                        </div>

                        <div class="cart-summary__row">
                            <span class="cart-summary__row-lbl">
                                Pavitra &amp; Surakshit Packaging
                            </span>
                            <span class="cart-summary__badge-free">FREE</span>
                        </div>
                    </div>

                    {{-- Grand Total Payable --}}
                    <div class="cart-summary__total-box">
                        <div>
                            <span class="cart-invoice__total-title">Total Payable Amount</span>
                            <span class="cart-invoice__total-sub">Includes ₹100 Delivery</span>
                        </div>
                        <span class="cart-summary__total-val">₹{{ number_format($grandTotal) }}</span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="cart-invoice__actions">
                        @if($whatsappUrl)
                            <a href="{{ $whatsappUrl }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="btn product-card__btn--whatsapp cart-summary__whatsapp-btn"
                               aria-label="Enquire and order cart items on WhatsApp">
                                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                                WhatsApp Par Order Karein <span class="sr-only">Order via WhatsApp</span>
                            </a>
                        @endif

                        <!-- <button type="button"
                                onclick="window.print()"
                                class="btn btn-outline cart-invoice__print-btn"
                                title="Print or Save Invoice as PDF">
                            Print / Save Invoice
                        </button> -->
                    </div>
                </div>

            </div>
        @endif

    </div>
</section>
@endsection
