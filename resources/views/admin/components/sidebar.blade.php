<aside class="admin-sidebar" id="admin-sidebar" aria-label="Administrator Navigation">
    {{-- Sidebar Header --}}
    <div class="admin-sidebar__header">
        <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__brand">
            @if (file_exists(public_path('images/categories/logo.webp')))
                <picture>
                    <source srcset="{{ asset('images/categories/logo.webp') }}" type="image/webp">
                    <img src="{{ asset('images/categories/logo.png') }}"
                         alt="Harivanshi Seva Kunj logo"
                         class="admin-sidebar__logo-img"
                         width="42"
                         height="42"
                         loading="eager">
                </picture>
            @elseif (file_exists(public_path('images/categories/logo.png')))
                <img src="{{ asset('images/categories/logo.png') }}"
                     alt="Harivanshi Seva Kunj logo"
                     class="admin-sidebar__logo-img"
                     width="42"
                     height="42"
                     loading="eager">
            @else
                <div class="admin-sidebar__logo-mark" aria-hidden="true">🛕</div>
            @endif
            <div class="admin-sidebar__brand-text">
                <span class="admin-sidebar__brand-title">हरिवंशी सेवा कुंज</span>
                <span class="admin-sidebar__brand-subtitle">Admin Control</span>
            </div>
        </a>
        <button type="button" class="admin-sidebar__close-btn" id="admin-sidebar-close" aria-label="Close navigation sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    {{-- Navigation Menu --}}
    <nav class="admin-sidebar__nav" role="navigation">
        <div class="admin-sidebar__section-title">Core Management</div>

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'admin-sidebar__link--active' : '' }}"
           @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
            <span class="admin-sidebar__link-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9"></rect>
                    <rect x="14" y="3" width="7" height="5"></rect>
                    <rect x="14" y="12" width="7" height="9"></rect>
                    <rect x="3" y="16" width="7" height="5"></rect>
                </svg>
            </span>
            <span>Dashboard</span>
        </a>

        {{-- Media Library --}}
        <a href="{{ route('admin.media.index') }}"
           class="admin-sidebar__link {{ request()->routeIs('admin.media.*') ? 'admin-sidebar__link--active' : '' }}"
           @if(request()->routeIs('admin.media.*')) aria-current="page" @endif
           title="Central Media Library">
            <span class="admin-sidebar__link-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
            </span>
            <span>Media Library</span>
            <span class="admin-sidebar__link-badge">Active</span>
        </a>

        <div class="admin-sidebar__section-title">Commerce Catalog</div>

        {{-- Products --}}
        <a href="{{ route('admin.products.index') }}"
           class="admin-sidebar__link {{ request()->routeIs('admin.products.*') ? 'admin-sidebar__link--active' : '' }}"
           @if(request()->routeIs('admin.products.*')) aria-current="page" @endif
           title="Manage Products Catalog">
            <span class="admin-sidebar__link-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </span>
            <span>Products</span>
            <span class="admin-sidebar__link-badge">Active</span>
        </a>

        {{-- Categories --}}
        <a href="{{ route('admin.categories.index') }}"
           class="admin-sidebar__link {{ request()->routeIs('admin.categories.*') ? 'admin-sidebar__link--active' : '' }}"
           @if(request()->routeIs('admin.categories.*')) aria-current="page" @endif
           title="Hierarchical Categories">
            <span class="admin-sidebar__link-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
            </span>
            <span>Categories</span>
            <span class="admin-sidebar__link-badge">Active</span>
        </a>

        {{-- Orders --}}
        <a href="#orders"
           class="admin-sidebar__link"
           title="Orders (Sprint 2 - Task 5)">
            <span class="admin-sidebar__link-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </span>
            <span>Orders</span>
            <span class="admin-sidebar__link-badge">Task 5</span>
        </a>
    </nav>

    {{-- Sidebar Footer --}}
    <div class="admin-sidebar__footer">
        <a href="{{ route('home') }}" target="_blank" class="admin-sidebar__storefront-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            <span>View Live Storefront</span>
        </a>
    </div>
</aside>
