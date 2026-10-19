<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Canonical Base URL Strategy
    |--------------------------------------------------------------------------
    | Production domain has not yet been purchased.
    | When domain is purchased, set CANONICAL_URL in .env to the production domain,
    | e.g. CANONICAL_URL=https://www.harivanshisevakunj.com
    |
    | When CANONICAL_URL is empty:
    | - Falls back to APP_URL (e.g. http://127.0.0.1:8000 in local development)
    |
    | This guarantees:
    | 1. No hardcoded or fabricated domain in codebase.
    | 2. Local development URLs never appear in production tags once CANONICAL_URL is set.
    | 3. Switching to production domain is a one-line .env change.
    */
    'canonical_url' => rtrim(
        (!empty(env('CANONICAL_URL')) ? env('CANONICAL_URL') : (!empty(env('APP_URL')) ? env('APP_URL') : 'http://127.0.0.1:8000')),
        '/'
    ),

    /*
    |--------------------------------------------------------------------------
    | Site Branding & Default Meta
    |--------------------------------------------------------------------------
    */
    'site_name' => 'Harivanshi Seva Kunj',
    'default_title' => 'Harivanshi Seva Kunj — Sacred Items from Vrindavan Dham',
    'default_description' => 'Authentic Vrindavan seva items, pure Tulsi malas, Braj Raj, Yamuna Jal, Thakur Ji Vastra, and devotional samagri delivered with devotion to your home.',
    'default_image' => '/images/categories/logo.png',

    /*
    |--------------------------------------------------------------------------
    | Vrindavan Local Business Information
    |--------------------------------------------------------------------------
    | The business operates online and has a physical shop in Vrindavan Dham.
    | Specific street address and coordinates are left null until confirmed by owner.
    | Genuine contact details (WhatsApp, phone, city, state) are pre-configured.
    */
    'business' => [
        'name'            => 'Harivanshi Seva Kunj',
        'legal_name'      => 'Harivanshi Seva Kunj',
        'proprietor'      => env('BUSINESS_PROPRIETOR', 'Uma Harivanshi'),
        'telephone'       => env('BUSINESS_PHONE', '+91 92860 70355'),
        'whatsapp'        => env('WHATSAPP_NUMBER', '919286070355'),
        'email'           => env('BUSINESS_EMAIL', 'harivanshisevakunj@gmail.com'),
        // Physical shop in Vrindavan Dham:
        'street_address'  => env('BUSINESS_STREET_ADDRESS', 'Sunrakh Banger, Sobhri Kund Road, Radha Vatika Ke Paas'),
        'address_locality'=> env('BUSINESS_LOCALITY', 'Vrindavan, Mathura'),
        'address_region'  => env('BUSINESS_REGION', 'Uttar Pradesh'),
        'postal_code'     => env('BUSINESS_POSTAL_CODE', '281121'),
        'address_country' => 'IN',
        'geo'             => [
            'latitude'    => env('BUSINESS_LATITUDE', null),
            'longitude'   => env('BUSINESS_LONGITUDE', null),
        ],
        'opening_hours'   => env('BUSINESS_OPENING_HOURS', null), // e.g. 'Mo-Su 09:00-20:00'
        'price_range'     => '₹₹',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social & Community Profiles
    |--------------------------------------------------------------------------
    */
    'social' => [
        'instagram' => 'https://www.instagram.com/uma_harivanshi75/',
        'whatsapp'  => 'https://wa.me/919286070355',
    ],
];
