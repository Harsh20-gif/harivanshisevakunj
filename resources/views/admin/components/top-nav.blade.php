@props(['title' => 'Dashboard'])

<header class="admin-top-nav" role="banner">
    {{-- Left: Mobile Toggle & Title --}}
    <div class="admin-top-nav__left">
        <button type="button"
                class="admin-top-nav__menu-toggle"
                id="admin-menu-toggle"
                aria-label="Toggle navigation menu"
                aria-expanded="false"
                aria-controls="admin-sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <div class="admin-top-nav__title-wrap">
            <span class="admin-top-nav__greeting">
                <span>✤</span> राधा वल्लभ श्री हरिवंश— Harivanshi Seva Kunj
            </span>
            <h1 class="admin-top-nav__page-title">{{ $title }}</h1>
        </div>
    </div>

    {{-- Right: Storefront, Profile & Logout --}}
    <div class="admin-top-nav__right">
        {{-- View Storefront Link --}}
        <a href="{{ route('home') }}" class="admin-top-nav__store-link" target="_blank" title="Open storefront in new tab">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Live Shop</span>
        </a>

        {{-- Admin User Badge --}}
        <div class="admin-top-nav__user">
            <div class="admin-top-nav__avatar" aria-hidden="true">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="admin-top-nav__user-info">
                <span class="admin-top-nav__user-name">{{ auth()->user()->name ?? 'Administrator' }}</span>
                <span class="admin-top-nav__user-role">Super Admin</span>
            </div>
        </div>

        {{-- Secure Logout Form --}}
        <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0; display: inline;">
            @csrf
            <button type="submit" class="admin-top-nav__logout-btn" title="Sign out of admin session">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</header>
