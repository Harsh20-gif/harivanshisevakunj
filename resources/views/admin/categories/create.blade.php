@extends('admin.layouts.app')

@section('title', 'Create Category')
@section('page_title', 'Create Category')

@section('breadcrumbs')
    @include('admin.components.breadcrumbs', [
        'crumbs' => [
            ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ],
        'active' => 'Create'
    ])
@endsection

@section('content')

    <div class="admin-form-container">
        <div class="admin-card">
            <div class="admin-card__header">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 1.25rem;">✨</span>
                    <div>
                        <h2 style="font-family: var(--font-heading); font-size: 1.15rem; color: var(--color-heading); margin: 0;">
                            New Category Information
                        </h2>
                        <p style="font-size: var(--text-xs); color: var(--color-text-muted); margin: 2px 0 0 0;">
                            Create a standalone category (e.g. Raj, Jal, Tulsi Mala, Kanthi Mala, Thakur Ji Vastra, Yamuna Jal, Pooja Samagri, Other Seva).
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.categories.store') }}" class="admin-form">
                @csrf

                {{-- Category Name / Title --}}
                <div class="admin-form-group">
                    <label for="name" class="admin-form-label">
                        Category Name / Title <span class="admin-required">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           class="admin-form-input @error('name') admin-form-input--error @enderror"
                           placeholder="e.g. Tulsi Mala, Yamuna Jal, Braj Raj"
                           required
                           autofocus
                           oninput="autoGenerateSlug(this.value)">
                    @error('name')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- URL Slug --}}
                <div class="admin-form-group">
                    <label for="slug" class="admin-form-label">
                        URL Slug <span class="admin-required">*</span>
                    </label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="color: var(--color-text-muted); font-size: var(--text-sm); font-family: monospace;">/category/</span>
                        <input type="text"
                               name="slug"
                               id="slug"
                               value="{{ old('slug') }}"
                               class="admin-form-input @error('slug') admin-form-input--error @enderror"
                               placeholder="tulsi-mala"
                               required
                               style="flex: 1;">
                    </div>
                    <p class="admin-form-help">Unique URL identifier used for category filtering on the storefront.</p>
                    @error('slug')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Short Description --}}
                <div class="admin-form-group">
                    <label for="short_description" class="admin-form-label">
                        Short Description
                    </label>
                    <textarea name="short_description"
                              id="short_description"
                              rows="3"
                              class="admin-form-textarea @error('short_description') admin-form-input--error @enderror"
                              placeholder="Brief description of this devotional seva collection...">{{ old('short_description') }}</textarea>
                    <p class="admin-form-help">Displays on category banners and collection highlights.</p>
                    @error('short_description')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category Image from Media Library --}}
                <div class="admin-form-group">
                    <label class="admin-form-label">Category Image</label>
                    @include('admin.components.media-picker-modal', [
                        'selectedMedia' => null,
                        'fieldName'     => 'media_id',
                        'label'         => 'Select or Upload Category Image (Shared Media Library)',
                        'helpText'      => 'Select a clean representative image from your centralized Media Library.',
                        'mediaAssets'   => $mediaAssets
                    ])
                    @error('media_id')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sort Order & Status Row --}}
                <div class="admin-form-row">
                    <div class="admin-form-group" style="flex: 1;">
                        <label for="sort_order" class="admin-form-label">
                            Sort Order
                        </label>
                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', 0) }}"
                               class="admin-form-input @error('sort_order') admin-form-input--error @enderror"
                               min="0"
                               step="1">
                        <p class="admin-form-help">Lower numbers appear first in storefront navigation tabs.</p>
                        @error('sort_order')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-form-group" style="flex: 1; display: flex; align-items: center; padding-top: var(--space-6);">
                        <label class="admin-form-checkbox-label">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="admin-form-checkbox"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <span>Active / Live on Storefront</span>
                        </label>
                    </div>
                </div>

                {{-- Submit Controls --}}
                <div class="admin-form-actions">
                    <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn--secondary">
                        Cancel
                    </a>
                    <button type="submit" class="admin-btn admin-btn--primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Save Category</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let slugManualOverride = false;

    document.getElementById('slug')?.addEventListener('input', function() {
        slugManualOverride = true;
    });

    function autoGenerateSlug(text) {
        if (slugManualOverride) return;
        const slugInput = document.getElementById('slug');
        if (!slugInput) return;

        const slug = text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/&/g, '-and-')
            .replace(/[\s\W-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        slugInput.value = slug;
    }
</script>
@endpush
