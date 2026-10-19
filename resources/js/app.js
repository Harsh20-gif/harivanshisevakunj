/**
 * app.js — Harivanshi Seva Kunj
 * Global scripts. Keep each feature scoped to its own function.
 */

// ── Mobile navigation drawer ────────────────────────────────
(function initMobileNav() {
    const toggle   = document.getElementById('menu-toggle');
    const drawer   = document.getElementById('mobile-drawer');
    const backdrop = document.getElementById('mobile-nav-backdrop');
    const closeBtn = document.getElementById('mobile-drawer-close');

    if (!toggle || !drawer) return;

    function openDrawer() {
        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'Close navigation menu');
        drawer.setAttribute('aria-hidden', 'false');
        if (backdrop) backdrop.setAttribute('aria-hidden', 'false');

        drawer.classList.add('is-open');
        if (backdrop) backdrop.classList.add('is-open');

        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';

        if (closeBtn) {
            setTimeout(() => closeBtn.focus(), 50);
        }
    }

    function closeDrawer() {
        if (!drawer.classList.contains('is-open')) return;

        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Open navigation menu');
        drawer.setAttribute('aria-hidden', 'true');
        if (backdrop) backdrop.setAttribute('aria-hidden', 'true');

        drawer.classList.remove('is-open');
        if (backdrop) backdrop.classList.remove('is-open');

        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';

        toggle.focus();
    }

    toggle.addEventListener('click', () => {
        const isOpen = drawer.classList.contains('is-open');
        if (isOpen) {
            closeDrawer();
        } else {
            openDrawer();
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeDrawer);
    }

    if (backdrop) {
        backdrop.addEventListener('click', closeDrawer);
    }

    // Close when clicking any link inside the drawer
    drawer.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            closeDrawer();
        });
    });

    // Close on Escape & trap Tab key focus
    document.addEventListener('keydown', (e) => {
        if (!drawer.classList.contains('is-open')) return;

        if (e.key === 'Escape') {
            e.preventDefault();
            closeDrawer();
            return;
        }

        if (e.key === 'Tab') {
            const focusable = drawer.querySelectorAll(
                'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );
            if (!focusable.length) return;

            const firstElem = focusable[0];
            const lastElem  = focusable[focusable.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === firstElem) {
                    e.preventDefault();
                    lastElem.focus();
                }
            } else {
                if (document.activeElement === lastElem) {
                    e.preventDefault();
                    firstElem.focus();
                }
            }
        }
    });

    // Close drawer on desktop screen resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768 && drawer.classList.contains('is-open')) {
            closeDrawer();
        }
    }, { passive: true });
}());

// ── Sticky header shadow enhancement ──────────────────────
(function initStickyHeader() {
    const header = document.getElementById('site-header');
    if (!header) return;
    const onScroll = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 4);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}());

// ── Devotional Toast Notification ─────────────────────────
function showCartNotification(message) {
    let container = document.getElementById('cart-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'cart-toast-container';
        container.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = 'cart-toast';
    toast.style.cssText = 'background:#2E1608;color:#FFF5A6;border:1px solid #FFD900;padding:12px 20px;border-radius:999px;font-size:14px;font-weight:600;box-shadow:0 8px 24px rgba(46,22,8,0.3);display:flex;align-items:center;gap:10px;pointer-events:auto;transition:opacity 300ms ease, transform 300ms ease;';
    toast.innerHTML = `<span>🌸</span><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ── AJAX Add-to-Cart Handling ─────────────────────────────
(function initAddToCart() {
    document.addEventListener('submit', async (e) => {
        const form = e.target.closest('.product-card__cart-form');
        if (!form) return;

        e.preventDefault();
        const submitBtn = e.submitter || form.querySelector('.product-card__btn--cart') || form.querySelector('button[type="submit"]');
        if (!submitBtn || submitBtn.disabled) return;

        const originalHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.7';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                // If validation error or unexpected status, fallback to natural submit
                form.submit();
                return;
            }

            const data = await response.json();
            if (data.success) {
                // Update cart count badge and accessible name
                const countBadge = document.getElementById('cart-count');
                if (countBadge && typeof data.cart_count !== 'undefined') {
                    countBadge.textContent = data.cart_count;
                    countBadge.classList.remove('cart-count--bump');
                    void countBadge.offsetWidth; // Force reflow
                    countBadge.classList.add('cart-count--bump');
                }

                // Temporary button state: checkmark icon without breaking row width
                submitBtn.style.opacity = '';
                submitBtn.classList.add('is-added');
                submitBtn.innerHTML = `<svg class="product-card__btn-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span class="sr-only">Added to Cart!</span>`;

                showCartNotification(data.message || 'Item added to cart!');

                setTimeout(() => {
                    submitBtn.classList.remove('is-added');
                    submitBtn.innerHTML = originalHtml;
                    submitBtn.disabled = false;
                }, 1800);
            } else {
                form.submit();
            }
        } catch (err) {
            console.error('Cart submission error:', err);
            form.submit();
        }
    });
}());

// ── Wishlist Heart Button Interaction ──────────────────────
(function initWishlist() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.product-card__wishlist');
        if (!btn || btn.hasAttribute('tabindex')) return; // ignore in admin preview
        if (btn.tagName === 'A') return; // let storefront links work
        e.preventDefault();
        btn.classList.toggle('is-active');
        const isActive = btn.classList.contains('is-active');
        if (typeof showCartNotification === 'function') {
            showCartNotification(isActive ? 'Item saved to your Wishlist!' : 'Item removed from Wishlist.');
        }
    });
}());

