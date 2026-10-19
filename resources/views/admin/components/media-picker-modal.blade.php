{{--
    Media Picker Component
    Harivanshi Seva Kunj Admin Panel
    Props:
      $selectedMedia – ?App\Models\Media
      $fieldName     – string (default 'media_id')
      $label         – string (default 'Image')
      $helpText      – string (optional)
      $mediaAssets   – Collection of App\Models\Media
--}}
@props([
    'selectedMedia' => null,
    'fieldName'     => 'media_id',
    'label'         => 'Image',
    'helpText'      => 'Select a sacred image asset from the Central Media Library.',
    'mediaAssets'   => collect(),
])

<div class="media-picker-widget" id="media-picker-widget-{{ $fieldName }}">
    <label class="admin-form-label">
        {{ $label }}
    </label>

    {{-- Hidden Input for form submission --}}
    <input type="hidden"
           name="{{ $fieldName }}"
           id="input-{{ $fieldName }}"
           value="{{ old($fieldName, $selectedMedia?->id) }}">

    {{-- Visual Preview Card --}}
    <div class="media-picker-preview" id="preview-box-{{ $fieldName }}">
        <div class="media-picker-preview__image-wrap">
            <img id="preview-img-{{ $fieldName }}"
                 src="{{ ($selectedMedia && $selectedMedia->existsOnDisk()) ? $selectedMedia->url : '' }}"
                 alt="{{ $selectedMedia ? ($selectedMedia->alt_text ?: $selectedMedia->title) : 'Selected Media' }}"
                 class="media-picker-preview__img"
                 style="{{ ($selectedMedia && $selectedMedia->existsOnDisk()) ? 'display: block;' : 'display: none;' }}">

            <div id="preview-placeholder-{{ $fieldName }}"
                 class="media-picker-preview__placeholder"
                 style="{{ ($selectedMedia && $selectedMedia->existsOnDisk()) ? 'display: none;' : 'display: flex;' }}">
                <span class="media-picker-preview__placeholder-icon">🖼️</span>
                <span class="media-picker-preview__placeholder-text">{{ ($selectedMedia && !$selectedMedia->existsOnDisk()) ? 'File missing from disk' : 'No image selected' }}</span>
            </div>
        </div>

        <div class="media-picker-preview__meta">
            <div class="media-picker-preview__title" id="preview-title-{{ $fieldName }}">
                {{ $selectedMedia ? $selectedMedia->title : 'No Media Attached' }}
            </div>
            <div class="media-picker-preview__sub" id="preview-sub-{{ $fieldName }}">
                @if($selectedMedia)
                    {{ $selectedMedia->formatted_size }} &bull; {{ strtoupper(str_replace('image/', '', $selectedMedia->mime_type)) }}
                @else
                    Images are referenced by ID from the Central Media Library
                @endif
            </div>

            <div class="media-picker-preview__actions">
                <button type="button"
                        class="admin-btn admin-btn--secondary admin-btn--sm"
                        onclick="openMediaPickerModal('{{ $fieldName }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span>{{ $selectedMedia ? 'Change Image' : 'Choose from Media Library' }}</span>
                </button>

                <button type="button"
                        class="admin-btn admin-btn--outline-danger admin-btn--sm"
                        id="btn-remove-{{ $fieldName }}"
                        style="{{ $selectedMedia ? 'display: inline-flex;' : 'display: none;' }}"
                        onclick="clearSelectedMedia('{{ $fieldName }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    <span>Remove</span>
                </button>
            </div>
        </div>
    </div>

    @if($helpText)
        <p class="admin-form-help">{{ $helpText }}</p>
    @endif

    @error($fieldName)
        <p class="admin-form-error">{{ $message }}</p>
    @enderror
</div>

{{-- Media Picker Modal Dialog --}}
<div class="media-modal-backdrop" id="media-picker-modal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="media-picker-title">
    <div class="media-modal media-modal--lg">
        <div class="media-modal__header">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 1.25rem;">🖼️</span>
                <h3 id="media-picker-title" style="font-family: var(--font-heading); font-size: 1.15rem; margin: 0; color: var(--color-heading);">
                    Select from Central Media Library
                </h3>
            </div>
            <button type="button" class="admin-btn-close" onclick="closeMediaPickerModal()" aria-label="Close dialog">&times;</button>
        </div>

        {{-- Toolbar: Search + Quick Upload Toggle --}}
        <div class="media-picker-toolbar">
            <div class="media-search-wrap" style="flex: 1; max-width: 400px;">
                <span class="media-search-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text"
                       id="media-picker-search-input"
                       class="media-search-input"
                       placeholder="Filter media assets by title..."
                       oninput="filterMediaPickerAssets(this.value)">
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <label class="admin-btn admin-btn--primary admin-btn--sm" style="cursor: pointer; margin: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Upload New Asset</span>
                    <input type="file"
                           id="media-picker-quick-upload"
                           accept="image/jpeg,image/png,image/webp,image/svg+xml,image/gif"
                           style="display: none;"
                           onchange="handleQuickUpload(this)">
                </label>
                <span id="quick-upload-spinner" style="display: none; font-size: 0.85rem; color: var(--color-secondary);">Uploading...</span>
            </div>
        </div>

        {{-- Scrollable Assets Grid --}}
        <div class="media-modal__body" style="padding: var(--space-4); max-height: 55vh; overflow-y: auto;">
            <div class="media-picker-grid" id="media-picker-grid">
                @forelse($mediaAssets as $media)
                    <div class="media-picker-card"
                         data-id="{{ $media->id }}"
                         data-title="{{ e($media->title) }}"
                         data-url="{{ $media->url }}"
                         data-meta="{{ $media->formatted_size }} &bull; {{ strtoupper(str_replace('image/', '', $media->mime_type)) }}"
                         onclick="selectMediaItem({{ $media->id }}, '{{ addslashes($media->title) }}', '{{ $media->url }}', '{{ $media->formatted_size }} &bull; {{ strtoupper(str_replace('image/', '', $media->mime_type)) }}')">
                        <div class="media-picker-card__thumb">
                            @if($media->existsOnDisk())
                                <img src="{{ $media->url }}" alt="{{ $media->alt_text ?: $media->title }}" loading="lazy">
                            @else
                                <span style="font-size: 1.25rem;" title="File missing from disk">⚠️</span>
                            @endif
                        </div>
                        <div class="media-picker-card__name" title="{{ $media->title }}">
                            {{ $media->title }}
                        </div>
                    </div>
                @empty
                    <div class="media-picker-empty" id="media-picker-empty">
                        <p style="margin: 0; color: var(--color-text-muted);">No images found in the Media Library. Click "Upload New Asset" above to add one.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="media-modal__footer" style="padding: var(--space-3) var(--space-6); background: var(--color-surface-soft); border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: var(--text-xs); color: var(--color-text-muted);">
                Click on any image to select it. Only relative paths and IDs are saved.
            </span>
            <button type="button" class="admin-btn admin-btn--secondary admin-btn--sm" onclick="closeMediaPickerModal()">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentPickerTarget = '{{ $fieldName }}';

    function openMediaPickerModal(targetField) {
        currentPickerTarget = targetField || '{{ $fieldName }}';
        const modal = document.getElementById('media-picker-modal');
        if (modal) {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            const search = document.getElementById('media-picker-search-input');
            if (search) {
                search.value = '';
                filterMediaPickerAssets('');
                search.focus();
            }
        }
    }

    function closeMediaPickerModal() {
        const modal = document.getElementById('media-picker-modal');
        if (modal) {
            modal.classList.remove('is-open');
            document.body.style.overflow = '';
        }
    }

    function selectMediaItem(id, title, url, meta) {
        const input = document.getElementById('input-' + currentPickerTarget);
        const img = document.getElementById('preview-img-' + currentPickerTarget);
        const placeholder = document.getElementById('preview-placeholder-' + currentPickerTarget);
        const titleEl = document.getElementById('preview-title-' + currentPickerTarget);
        const subEl = document.getElementById('preview-sub-' + currentPickerTarget);
        const removeBtn = document.getElementById('btn-remove-' + currentPickerTarget);

        if (input) input.value = id;
        if (img) {
            img.src = url;
            img.style.display = 'block';
        }
        if (placeholder) placeholder.style.display = 'none';
        if (titleEl) titleEl.textContent = title;
        if (subEl) subEl.textContent = meta || '';
        if (removeBtn) removeBtn.style.display = 'inline-flex';

        window.dispatchEvent(new CustomEvent('media-picker:selected', {
            detail: { id: id, title: title, url: url, meta: meta, targetField: currentPickerTarget }
        }));

        closeMediaPickerModal();
    }

    function clearSelectedMedia(targetField) {
        const input = document.getElementById('input-' + targetField);
        const img = document.getElementById('preview-img-' + targetField);
        const placeholder = document.getElementById('preview-placeholder-' + targetField);
        const titleEl = document.getElementById('preview-title-' + targetField);
        const subEl = document.getElementById('preview-sub-' + targetField);
        const removeBtn = document.getElementById('btn-remove-' + targetField);

        if (input) input.value = '';
        if (img) {
            img.src = '';
            img.style.display = 'none';
        }
        if (placeholder) placeholder.style.display = 'flex';
        if (titleEl) titleEl.textContent = 'No Media Attached';
        if (subEl) subEl.textContent = 'Images are referenced by ID from the Central Media Library';
        if (removeBtn) removeBtn.style.display = 'none';

        window.dispatchEvent(new CustomEvent('media-picker:cleared', {
            detail: { targetField: targetField }
        }));
    }

    function filterMediaPickerAssets(keyword) {
        keyword = keyword.toLowerCase().trim();
        const cards = document.querySelectorAll('#media-picker-grid .media-picker-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const title = (card.getAttribute('data-title') || '').toLowerCase();
            if (!keyword || title.includes(keyword)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const emptyMsg = document.getElementById('media-picker-empty');
        if (emptyMsg) {
            emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    function handleQuickUpload(fileInput) {
        if (!fileInput.files || fileInput.files.length === 0) return;

        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append('image', file);
        formData.append('title', file.name.replace(/\.[^/.]+$/, ""));

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

        const spinner = document.getElementById('quick-upload-spinner');
        if (spinner) spinner.style.display = 'inline';

        fetch('{{ route('admin.media.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error('Upload failed');
            return res.json();
        })
        .then(json => {
            if (spinner) spinner.style.display = 'none';
            if (json.media) {
                const media = json.media;
                // Add card dynamically to grid
                const grid = document.getElementById('media-picker-grid');
                const card = document.createElement('div');
                card.className = 'media-picker-card';
                card.setAttribute('data-id', media.id);
                card.setAttribute('data-title', media.title);
                card.setAttribute('data-url', media.url);
                card.setAttribute('data-meta', (media.formatted_size || '') + ' • ' + (media.mime_type || '').replace('image/', '').toUpperCase());
                card.onclick = function() {
                    selectMediaItem(media.id, media.title, media.url, card.getAttribute('data-meta'));
                };
                card.innerHTML = `
                    <div class="media-picker-card__thumb">
                        <img src="${media.url}" alt="${media.title}">
                    </div>
                    <div class="media-picker-card__name" title="${media.title}">${media.title}</div>
                `;
                if (grid.firstChild) {
                    grid.insertBefore(card, grid.firstChild);
                } else {
                    grid.appendChild(card);
                }

                // Immediately select it
                selectMediaItem(media.id, media.title, media.url, card.getAttribute('data-meta'));
            }
        })
        .catch(err => {
            if (spinner) spinner.style.display = 'none';
            alert('Image upload failed. Please ensure the file is a valid image (max 10MB).');
        })
        .finally(() => {
            fileInput.value = '';
        });
    }

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMediaPickerModal();
        }
    });
</script>
@endpush
