@extends('admin.layouts.app')

@section('title', 'Categories Management')
@section('page_title', 'Categories')

@section('breadcrumbs')
    @include('admin.components.breadcrumbs', [
        'crumbs' => [],
        'active' => 'Categories'
    ])
@endsection

@section('content')

    {{-- Header Action & Stats Toolbar --}}
    <div class="admin-toolbar">
        <div class="admin-toolbar__left">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="admin-search-form" role="search">
                <div class="media-search-wrap">
                    <span class="media-search-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="search"
                           name="q"
                           value="{{ $search }}"
                           class="media-search-input"
                           placeholder="Search categories..."
                           aria-label="Search categories">
                </div>
                @if ($search)
                    <a href="{{ route('admin.categories.index') }}" class="admin-link-clear">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="admin-toolbar__right">
            <div class="media-stat-pill" title="Total Categories in Catalogue">
                <span aria-hidden="true">📁</span>
                <span>{{ $totalCategories }} {{ Str::plural('Category', $totalCategories) }}</span>
            </div>
            <div class="media-stat-pill" title="Active Categories Visible on Storefront">
                <span aria-hidden="true">🟢</span>
                <span>{{ $activeCategories }} Active</span>
            </div>

            <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Add Category</span>
            </a>
        </div>
    </div>

    @if($categories->isEmpty())
        {{-- Empty State --}}
        <div class="admin-card admin-empty-state">
            <div class="admin-empty-state__icon" aria-hidden="true">📁</div>
            <h3 class="admin-empty-state__title">
                {{ $search ? 'No matching categories found' : 'No Categories Created Yet' }}
            </h3>
            <p class="admin-empty-state__desc">
                {{ $search ? "No categories matched '{$search}'. Try a different keyword." : 'Start organizing the Harivanshi Seva Kunj catalogue by creating categories (e.g. Raj, Jal, Tulsi Mala, Kanthi Mala, Thakur Ji Vastra, Yamuna Jal, Pooja Samagri, Other Seva).' }}
            </p>
            <div style="margin-top: var(--space-4);">
                @if($search)
                    <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn--secondary">View All Categories</a>
                @else
                    <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn--primary">Create First Category</a>
                @endif
            </div>
        </div>
    @else

        {{-- Categories Table Listing --}}
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Image</th>
                            <th>Category</th>
                            <th>Short Description</th>
                            <th style="width: 100px;">Sort Order</th>
                            <th style="width: 100px;">Products</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 130px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr id="category-row-{{ $category->id }}">
                                {{-- Thumbnail --}}
                                <td>
                                    <div class="admin-table-thumb">
                                        @if($category->media && $category->media->existsOnDisk())
                                            <img src="{{ $category->media->url }}" alt="{{ $category->name }}" loading="lazy">
                                        @else
                                            <span class="admin-table-thumb__placeholder">📁</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Name & Slug --}}
                                <td>
                                    <div style="font-weight: var(--font-semibold); color: var(--color-heading); font-size: var(--text-sm);">
                                        {{ $category->name }}
                                    </div>
                                    <div style="font-size: var(--text-xs); color: var(--color-text-muted); font-family: monospace;">
                                        /{{ $category->slug }}
                                    </div>
                                </td>

                                {{-- Short Description --}}
                                <td>
                                    @if($category->short_description)
                                        <p style="margin: 0; font-size: var(--text-xs); color: var(--color-text-muted); max-width: 320px; line-height: 1.4;">
                                            {{ Str::limit($category->short_description, 100) }}
                                        </p>
                                    @else
                                        <span style="color: var(--color-border); font-size: var(--text-xs);">&mdash;</span>
                                    @endif
                                </td>

                                {{-- Sort Order --}}
                                <td>
                                    <span style="font-size: var(--text-sm); font-weight: var(--font-medium);">
                                        {{ $category->sort_order }}
                                    </span>
                                </td>

                                {{-- Associated Products Count --}}
                                <td>
                                    <span class="admin-badge admin-badge--parent">
                                        {{ $category->products_count }} {{ Str::plural('item', $category->products_count) }}
                                    </span>
                                </td>

                                {{-- Active/Inactive Status Toggle --}}
                                <td>
                                    <form method="POST" action="{{ route('admin.categories.toggle', $category) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="admin-badge-toggle {{ $category->is_active ? 'admin-badge-toggle--active' : 'admin-badge-toggle--inactive' }}"
                                                title="Click to toggle category status">
                                            <span class="admin-badge-toggle__dot"></span>
                                            <span>{{ $category->is_active ? 'Active' : 'Inactive' }}</span>
                                        </button>
                                    </form>
                                </td>

                                {{-- Actions --}}
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 4px; align-items: center;">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="admin-btn admin-btn--sm admin-btn--secondary"
                                           title="Edit category">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.categories.destroy', $category) }}"
                                              onsubmit="return confirm('Are you sure you want to delete category \'{{ addslashes($category->name) }}\'? If it has associated products, the deletion will be blocked.');"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="admin-btn admin-btn--sm admin-btn--outline-danger"
                                                    title="Safely delete category">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div style="padding: var(--space-4); border-top: 1px solid var(--color-border);">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

    @endif

@endsection
