@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Overview Dashboard')

@section('breadcrumbs')
    @include('admin.components.breadcrumbs', [
        'crumbs' => [],
        'active' => 'Dashboard'
    ])
@endsection

@section('content')


    {{-- Section: Key Metrics Overview --}}
    <div class="admin-section-header">
        <h2 class="admin-section-title">
            <span class="admin-section-flourish">✤</span>
            Storefront Statistics
        </h2>
        <span style="font-size: var(--text-xs); color: var(--color-text-muted);">
            Live Database Telemetry
        </span>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="admin-stats-grid">
        {{-- Stat 1: Total Products --}}
        <div class="admin-stat-card">
            <div class="admin-stat-card__header">
                <div class="admin-stat-card__icon-wrap" aria-hidden="true">
                    📦
                </div>
                @if ($stats['products']['available'])
                    <span class="admin-stat-card__badge admin-stat-card__badge--active">Active</span>
                @else
                    <span class="admin-stat-card__badge admin-stat-card__badge--setup">Pending Setup</span>
                @endif
            </div>

            @if ($stats['products']['available'])
                <div class="admin-stat-card__value">{{ number_format($stats['products']['value']) }}</div>
            @else
                <div class="admin-stat-card__value admin-stat-card__value--unavailable">—</div>
            @endif

            <div class="admin-stat-card__label">{{ $stats['products']['label'] }}</div>
            <div class="admin-stat-card__meta">{{ $stats['products']['module'] }}</div>
        </div>

        {{-- Stat 2: Total Categories --}}
        <div class="admin-stat-card">
            <div class="admin-stat-card__header">
                <div class="admin-stat-card__icon-wrap" aria-hidden="true">
                    🏷️
                </div>
                @if ($stats['categories']['available'])
                    <span class="admin-stat-card__badge admin-stat-card__badge--active">Active</span>
                @else
                    <span class="admin-stat-card__badge admin-stat-card__badge--setup">Pending Setup</span>
                @endif
            </div>

            @if ($stats['categories']['available'])
                <div class="admin-stat-card__value">{{ number_format($stats['categories']['value']) }}</div>
            @else
                <div class="admin-stat-card__value admin-stat-card__value--unavailable">—</div>
            @endif

            <div class="admin-stat-card__label">{{ $stats['categories']['label'] }}</div>
            <div class="admin-stat-card__meta">{{ $stats['categories']['module'] }}</div>
        </div>

        {{-- Stat 3: Total Orders --}}
        <div class="admin-stat-card">
            <div class="admin-stat-card__header">
                <div class="admin-stat-card__icon-wrap" aria-hidden="true">
                    🛍️
                </div>
                @if ($stats['orders']['available'])
                    <span class="admin-stat-card__badge admin-stat-card__badge--active">Active</span>
                @else
                    <span class="admin-stat-card__badge admin-stat-card__badge--setup">Pending Setup</span>
                @endif
            </div>

            @if ($stats['orders']['available'])
                <div class="admin-stat-card__value">{{ number_format($stats['orders']['value']) }}</div>
            @else
                <div class="admin-stat-card__value admin-stat-card__value--unavailable">—</div>
            @endif

            <div class="admin-stat-card__label">{{ $stats['orders']['label'] }}</div>
            <div class="admin-stat-card__meta">{{ $stats['orders']['module'] }}</div>
        </div>

        {{-- Stat 4: Pending Orders --}}
        <div class="admin-stat-card">
            <div class="admin-stat-card__header">
                <div class="admin-stat-card__icon-wrap" aria-hidden="true">
                    ⏳
                </div>
                @if ($stats['pending_orders']['available'])
                    <span class="admin-stat-card__badge admin-stat-card__badge--active">Active</span>
                @else
                    <span class="admin-stat-card__badge admin-stat-card__badge--setup">Pending Setup</span>
                @endif
            </div>

            @if ($stats['pending_orders']['available'])
                <div class="admin-stat-card__value">{{ number_format($stats['pending_orders']['value']) }}</div>
            @else
                <div class="admin-stat-card__value admin-stat-card__value--unavailable">—</div>
            @endif

            <div class="admin-stat-card__label">{{ $stats['pending_orders']['label'] }}</div>
            <div class="admin-stat-card__meta">{{ $stats['pending_orders']['module'] }}</div>
        </div>
    </div>

    {{-- Section: Quick Navigation Shortcuts --}}
    <div class="admin-section-header">
        <h2 class="admin-section-title">
            <span class="admin-section-flourish">✤</span>
            Quick Module Access
        </h2>
        <span style="font-size: var(--text-xs); color: var(--color-text-muted);">
            Sprint 2 Development Roadmap
        </span>
    </div>

    <div class="admin-quick-links-grid">
        @foreach ($quickLinks as $link)
            <a href="{{ $link['route'] ?? 'javascript:void(0)' }}"
               class="admin-quick-card {{ !$link['is_ready'] ? 'admin-quick-card--disabled' : '' }}"
               @if(!$link['is_ready']) aria-disabled="true" @endif>
                <div class="admin-quick-card__icon-box" aria-hidden="true">
                    @if ($link['title'] === 'Media Library') 🖼️
                    @elseif ($link['title'] === 'Products') 📿
                    @elseif ($link['title'] === 'Categories') 🏷️
                    @else 📦
                    @endif
                </div>

                <div class="admin-quick-card__title">
                    <span>{{ $link['title'] }}</span>
                    <span style="font-size: 0.85rem; color: var(--color-primary-dark);" aria-hidden="true">&rarr;</span>
                </div>

                <div class="admin-quick-card__desc">
                    {{ $link['description'] }}
                </div>

                <div class="admin-quick-card__status-bar">
                    <span style="color: var(--color-text-muted);">Status</span>
                    @if ($link['is_ready'])
                        <span class="admin-status-pill admin-status-pill--active">● Active</span>
                    @else
                        <span class="admin-status-pill admin-status-pill--pending">⏳ {{ $link['badge'] }}</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    {{-- Section: Recent Orders --}}
    <div class="admin-section-header">
        <h2 class="admin-section-title">
            <span class="admin-section-flourish">✤</span>
            Recent Orders
        </h2>
    </div>

    <div class="admin-panel-card">
        @if ($hasOrders && $recentOrders->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: var(--text-sm);">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-heading);">
                            <th style="padding: 12px 16px;">Order #</th>
                            <th style="padding: 12px 16px;">Customer</th>
                            <th style="padding: 12px 16px;">Date</th>
                            <th style="padding: 12px 16px;">Total</th>
                            <th style="padding: 12px 16px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr style="border-bottom: 1px solid var(--color-border);">
                                <td style="padding: 12px 16px; font-weight: var(--font-semibold);">#{{ $order->id }}</td>
                                <td style="padding: 12px 16px;">{{ $order->customer_name ?? 'Devotee' }}</td>
                                <td style="padding: 12px 16px;">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                                <td style="padding: 12px 16px; font-weight: var(--font-semibold);">₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                <td style="padding: 12px 16px;">
                                    <span class="admin-status-pill admin-status-pill--active">{{ ucfirst($order->status ?? 'Completed') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Honest setup/unavailable state according to prompt requirements --}}
            <div class="admin-empty-state">
                <div class="admin-empty-state__icon" aria-hidden="true">
                    📜
                </div>
                <h3 class="admin-empty-state__title">Order Tracking Table Pending Setup</h3>
                <p class="admin-empty-state__description">
                    The database schema for orders has not been migrated yet. Once the Order Management module (Sprint 2 - Task 5) is implemented, all incoming devotee seva requests and shipping updates will be listed here.
                </p>
                <div class="admin-empty-state__roadmap-badge">
                    <span>Scheduled for Sprint 2 • Task 5 (Order Management & WhatsApp Checkout)</span>
                </div>
            </div>
        @endif
    </div>

@endsection
