<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Login — Harivanshi Seva Kunj</title>
    <meta name="robots" content="noindex, nofollow">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Preconnect to Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Google Fonts (Playfair Display & Inter) --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap">

    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>
<body class="admin-auth-page">

    {{-- Ambient Flowing Sacred Vrindavan Background Animation --}}
    @include('admin.components.flowing-bg')

    <div class="admin-auth-card">
        {{-- Top devotional accent bar --}}
        <div class="admin-auth-card__top-bar"></div>

        <div class="admin-auth-card__body">
            {{-- Branding --}}
            <div class="admin-auth-card__brand">
                <div class="admin-auth-card__logo-wrap">
                    @if (file_exists(public_path('images/categories/logo.webp')))
                        <picture>
                            <source srcset="{{ asset('images/categories/logo.webp') }}" type="image/webp">
                            <img src="{{ asset('images/categories/logo.png') }}"
                                 alt="Harivanshi Seva Kunj logo"
                                 class="admin-auth-card__logo"
                                 width="64"
                                 height="64"
                                 loading="eager">
                        </picture>
                    @elseif (file_exists(public_path('images/categories/logo.png')))
                        <img src="{{ asset('images/categories/logo.png') }}"
                             alt="Harivanshi Seva Kunj logo"
                             class="admin-auth-card__logo"
                             width="64"
                             height="64"
                             loading="eager">
                    @else
                        <div class="admin-auth-card__icon-wrap" aria-hidden="true">🛕</div>
                    @endif
                </div>
                <h1 class="admin-auth-card__title">हरिवंशी सेवा कुंज</h1>
                <p class="admin-auth-card__subtitle">Administrator Portal • Braj Dham Seva</p>
            </div>

            {{-- Flash / Status Messages --}}
            @if (session('status'))
                <div class="admin-alert admin-alert--success" style="margin-bottom: var(--space-4);" role="alert">
                    <span class="admin-alert__icon" aria-hidden="true">✓</span>
                    <div class="admin-alert__content">{{ session('status') }}</div>
                </div>
            @endif

            @if (session('warning'))
                <div class="admin-alert admin-alert--warning" style="margin-bottom: var(--space-4);" role="alert">
                    <span class="admin-alert__icon" aria-hidden="true">✦</span>
                    <div class="admin-alert__content">{{ session('warning') }}</div>
                </div>
            @endif

            @if (session('info'))
                <div class="admin-alert admin-alert--info" style="margin-bottom: var(--space-4);" role="alert">
                    <span class="admin-alert__icon" aria-hidden="true">ℹ</span>
                    <div class="admin-alert__content">{{ session('info') }}</div>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('admin.login.submit') }}" class="admin-auth-form" novalidate>
                @csrf

                {{-- Email Address Field --}}
                <div class="admin-form-group">
                    <label for="admin-email" class="admin-form-label">Administrator Email</label>
                    <input type="email"
                           id="admin-email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username"
                           class="admin-form-input @error('email') admin-form-input--error @enderror"
                           placeholder="admin@example.com">
                    @error('email')
                        <span class="admin-form-error" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password Field with View Toggle --}}
                <div class="admin-form-group">
                    <label for="admin-password" class="admin-form-label">Password</label>
                    <div class="admin-password-wrap">
                        <input type="password"
                               id="admin-password"
                               name="password"
                               required
                               autocomplete="current-password"
                               class="admin-form-input @error('password') admin-form-input--error @enderror"
                               placeholder="••••••••">
                        <button type="button"
                                id="toggle-password"
                                class="admin-password-toggle"
                                aria-label="Show password"
                                title="Show password"
                                aria-pressed="false"
                                aria-controls="admin-password">
                            <svg class="admin-password-icon admin-password-icon--show" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="admin-password-icon admin-password-icon--hide" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                <line x1="2" x2="22" y1="2" y2="22"></line>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="admin-form-error" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember Me & Help --}}
                <div class="admin-form-row">
                    <label class="admin-form-checkbox-label" for="remember">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember session</span>
                    </label>
                    <span style="color: var(--color-text-muted); font-size: 0.75rem;">Protected Area</span>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="admin-btn-primary" id="btn-admin-login">
                    <span>Sign In to Admin Panel</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <div class="admin-auth-card__footer">
            <a href="{{ route('home') }}">&larr; Return to Harivanshi Seva Kunj Storefront</a>
        </div>
    </div>

    {{-- Password Visibility Toggle Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('admin-password');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    const newType = isPassword ? 'text' : 'password';
                    passwordInput.setAttribute('type', newType);

                    const newAriaPressed = isPassword ? 'true' : 'false';
                    toggleBtn.setAttribute('aria-pressed', newAriaPressed);

                    const newLabel = isPassword ? 'Hide password' : 'Show password';
                    toggleBtn.setAttribute('aria-label', newLabel);
                    toggleBtn.setAttribute('title', newLabel);

                    passwordInput.focus();
                });
            }
        });
    </script>
</body>
</html>
