@if (session('success') || session('status') || session('error') || session('danger') || session('warning') || session('info') || $errors->any())
    <div class="admin-flash" role="region" aria-label="Notifications">
        {{-- Success / Status Alert --}}
        @if (session('success') || session('status'))
            <div class="admin-alert admin-alert--success" role="alert">
                <span class="admin-alert__icon" aria-hidden="true">✓</span>
                <div class="admin-alert__content">
                    <div class="admin-alert__title">Success</div>
                    <div>{{ session('success') ?? session('status') }}</div>
                </div>
                <button type="button" class="admin-alert__close" onclick="this.parentElement.remove()" aria-label="Dismiss alert">&times;</button>
            </div>
        @endif

        {{-- Error / Danger Alert --}}
        @if (session('error') || session('danger'))
            <div class="admin-alert admin-alert--danger" role="alert">
                <span class="admin-alert__icon" aria-hidden="true">⚠</span>
                <div class="admin-alert__content">
                    <div class="admin-alert__title">Error</div>
                    <div>{{ session('error') ?? session('danger') }}</div>
                </div>
                <button type="button" class="admin-alert__close" onclick="this.parentElement.remove()" aria-label="Dismiss alert">&times;</button>
            </div>
        @endif

        {{-- Warning Alert --}}
        @if (session('warning'))
            <div class="admin-alert admin-alert--warning" role="alert">
                <span class="admin-alert__icon" aria-hidden="true">✦</span>
                <div class="admin-alert__content">
                    <div class="admin-alert__title">Notice</div>
                    <div>{{ session('warning') }}</div>
                </div>
                <button type="button" class="admin-alert__close" onclick="this.parentElement.remove()" aria-label="Dismiss alert">&times;</button>
            </div>
        @endif

        {{-- Info Alert --}}
        @if (session('info'))
            <div class="admin-alert admin-alert--info" role="alert">
                <span class="admin-alert__icon" aria-hidden="true">ℹ</span>
                <div class="admin-alert__content">
                    <div class="admin-alert__title">Information</div>
                    <div>{{ session('info') }}</div>
                </div>
                <button type="button" class="admin-alert__close" onclick="this.parentElement.remove()" aria-label="Dismiss alert">&times;</button>
            </div>
        @endif

        {{-- Validation Errors Alert --}}
        @if ($errors->any() && ! request()->routeIs('admin.login'))
            <div class="admin-alert admin-alert--danger" role="alert">
                <span class="admin-alert__icon" aria-hidden="true">⚠</span>
                <div class="admin-alert__content">
                    <div class="admin-alert__title">Please correct the following:</div>
                    <ul style="margin: 4px 0 0 16px; list-style-type: disc;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="admin-alert__close" onclick="this.parentElement.remove()" aria-label="Dismiss alert">&times;</button>
            </div>
        @endif
    </div>
@endif
