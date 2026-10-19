@extends('admin.layouts.app')

@section('title', 'Media Library')
@section('page_title', 'Central Media Library')

@section('breadcrumbs')
    @include('admin.components.breadcrumbs', [
        'crumbs' => [],
        'active' => 'Media Library'
    ])
@endsection

@section('content')

    {{-- Top Action & Search Bar --}}
    <div class="media-header-bar">
        {{-- Search Form --}}
        <form method="GET" action="{{ route('admin.media.index') }}" class="media-search-form" role="search">
            @if ($isPickerMode)
                <input type="hidden" name="picker" value="1">
            @endif
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
                       placeholder="Search images by title or alt text..."
                       aria-label="Search media files">
            </div>
            @if ($search)
                <a href="{{ route('admin.media.index', $isPickerMode ? ['picker' => 1] : []) }}"
                   style="font-size: var(--text-xs); color: var(--color-text-muted); text-decoration: underline; white-space: nowrap;">
                    Clear
                </a>
            @endif
        </form>

        {{-- Stats & Upload Trigger --}}
        <div class="media-header-stats">
            <div class="media-stat-pill" title="Total media assets in library">
                <span aria-hidden="true">🖼️</span>
                <span>{{ number_format($totalItems) }} {{ Str::plural('asset', $totalItems) }}</span>
            </div>
            <div class="media-stat-pill" title="Disk space used in public storage">
                <span aria-hidden="true">💾</span>
                <span>{{ $storageUsed }}</span>
            </div>
            <button type="button" class="media-btn-upload" id="btn-toggle-upload" aria-expanded="false" aria-controls="upload-panel">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Upload Image</span>
            </button>
        </div>
    </div>

    {{-- Upload Panel (Collapsible / Toggleable) --}}
    <div class="media-upload-panel" id="upload-panel" style="{{ $errors->has('image') ? '' : 'display: none;' }}">
        <div class="media-upload-panel__header">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="color: var(--color-primary-dark); font-size: 1.1rem;" aria-hidden="true">✦</span>
                <h2 style="font-family: var(--font-heading); font-size: var(--text-base); font-weight: var(--font-bold); color: var(--color-heading); margin: 0;">
                    Upload Sacred Media Asset
                </h2>
            </div>
            <button type="button"
                    onclick="document.getElementById('upload-panel').style.display='none'; document.getElementById('btn-toggle-upload').setAttribute('aria-expanded', 'false');"
                    style="background: none; border: none; font-size: 1.25rem; color: var(--color-text-muted); cursor: pointer;"
                    aria-label="Close upload panel">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" id="media-upload-form">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-6); align-items: start;">
                {{-- Left: Dropzone --}}
                <div>
                    <div class="media-dropzone" id="media-dropzone">
                        <input type="file"
                               name="image"
                               id="media-file-input"
                               accept="image/jpeg,image/png,image/webp,image/svg+xml,image/gif"
                               class="media-dropzone__input"
                               required>
                        <div class="media-dropzone__icon" aria-hidden="true">📸</div>
                        <div style="font-weight: var(--font-semibold); color: var(--color-heading); margin-bottom: 4px;" id="dropzone-label">
                            Click to select image or drag &amp; drop here
                        </div>
                        <div style="font-size: var(--text-xs); color: var(--color-text-muted);">
                            JPG, PNG, WebP, SVG, GIF up to 10 MB
                        </div>
                        <div id="dropzone-preview" style="margin-top: 12px; display: none;">
                            <img id="preview-thumb" src="" alt="Selected preview" style="max-height: 100px; border-radius: 6px; box-shadow: var(--shadow-sm); margin: 0 auto; display: block;">
                            <span id="preview-filename" style="font-size: 0.75rem; color: var(--color-heading); font-weight: var(--font-medium); margin-top: 4px; display: block;"></span>
                        </div>
                    </div>
                    @error('image')
                        <span class="admin-form-error" style="margin-top: 6px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Right: Metadata Fields & Submit --}}
                <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                    <div class="admin-form-group">
                        <label for="upload-title" class="admin-form-label">Title (Optional)</label>
                        <input type="text"
                               id="upload-title"
                               name="title"
                               value="{{ old('title') }}"
                               placeholder="e.g. Shri Radha Vallabh Pitambar Darshan"
                               class="admin-form-input @error('title') admin-form-input--error @enderror">
                        <span style="font-size: 0.72rem; color: var(--color-text-muted);">
                            Defaults to sanitized filename if left blank.
                        </span>
                        @error('title')
                            <span class="admin-form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="upload-alt" class="admin-form-label">Alt Text (Accessibility / SEO)</label>
                        <input type="text"
                               id="upload-alt"
                               name="alt_text"
                               value="{{ old('alt_text') }}"
                               placeholder="e.g. Close-up darshan of Thakur Ji wearing yellow silk vastra"
                               class="admin-form-input @error('alt_text') admin-form-input--error @enderror">
                        <span style="font-size: 0.72rem; color: var(--color-text-muted);">
                            Descriptive text for screen readers and SEO alt attribute.
                        </span>
                    </div>

                    <div style="margin-top: var(--space-2); display: flex; align-items: center; gap: var(--space-3);">
                        <button type="submit" class="admin-btn-primary" id="btn-submit-upload">
                            <span>Save to Library</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </button>
                        <button type="button"
                                onclick="document.getElementById('upload-panel').style.display='none'; document.getElementById('btn-toggle-upload').setAttribute('aria-expanded', 'false');"
                                style="background: none; border: 1px solid var(--color-border); padding: 0.65rem 1.2rem; border-radius: var(--radius-pill); font-size: var(--text-xs); color: var(--color-text); cursor: pointer;">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Gallery Listing --}}
    @if ($mediaItems->count() > 0)
        <div class="media-grid">
            @foreach ($mediaItems as $media)
                <div class="media-card" id="media-card-{{ $media->id }}">
                    {{-- Thumbnail & Hover Overlay --}}
                    <div class="media-card__thumb-wrap">
                        @if ($media->existsOnDisk())
                            <img src="{{ Storage::disk('public')->url($media->file_path) }}"
                                 alt="{{ $media->alt_text ?: $media->title }}"
                                 class="media-card__thumb"
                                 loading="lazy">
                        @else
                            <div class="media-card__missing">
                                <span style="font-size: 1.5rem;" aria-hidden="true">⚠️</span>
                                <strong>File Missing</strong>
                                <span style="font-size: 0.65rem; opacity: 0.75;">from disk</span>
                            </div>
                        @endif

                        {{-- Hover Actions --}}
                        <div class="media-card__overlay">
                            {{-- View / Inspect Button --}}
                            <button type="button"
                                    class="media-action-btn"
                                    title="View Details & Preview"
                                    aria-label="View media details"
                                    onclick="openMediaDetailModal({{ json_encode([
                                        'id'             => $media->id,
                                        'title'          => $media->title,
                                        'alt_text'       => $media->alt_text ?? '',
                                        'url'            => Storage::disk('public')->url($media->file_path),
                                        'file_path'      => $media->file_path,
                                        'formatted_size' => $media->formatted_size,
                                        'mime_type'      => $media->mime_type,
                                        'created_at'     => $media->created_at?->format('d M Y, h:i A'),
                                        'exists'         => $media->existsOnDisk(),
                                    ]) }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>

                            {{-- Edit Metadata Button --}}
                            <button type="button"
                                    class="media-action-btn"
                                    title="Edit Title & Alt Text"
                                    aria-label="Edit media metadata"
                                    onclick="openMediaEditModal({{ $media->id }}, {{ json_encode($media->title) }}, {{ json_encode($media->alt_text ?? '') }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>

                            {{-- Fast Copy URL Button --}}
                            <button type="button"
                                    class="media-action-btn"
                                    title="Copy Image URL to Clipboard"
                                    aria-label="Copy image URL"
                                    onclick="copyToClipboard('{{ Storage::disk('public')->url($media->file_path) }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </button>

                            {{-- Safe Delete Button --}}
                            <form method="POST"
                                  action="{{ route('admin.media.destroy', $media) }}"
                                  onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($media->title) }}\'? This will remove the file permanently.');"
                                  style="margin: 0; display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="media-action-btn media-action-btn--delete" title="Delete Asset" aria-label="Delete media file">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Card Footer Details --}}
                    <div class="media-card__body">
                        <span class="media-card__title" title="{{ $media->title }}">{{ $media->title }}</span>
                        <div class="media-card__meta">
                            <span>{{ $media->formatted_size }}</span>
                            <span class="media-badge-mime">{{ str_replace('image/', '', $media->mime_type) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="margin-top: var(--space-6); display: flex; justify-content: center;">
            {{ $mediaItems->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="admin-panel-card">
            <div class="admin-empty-state">
                <div class="admin-empty-state__icon" aria-hidden="true">
                    @if ($search) 🔍 @else 🖼️ @endif
                </div>
                <h3 class="admin-empty-state__title">
                    @if ($search)
                        No Media Matching "{{ $search }}"
                    @else
                        No Media Assets in Library Yet
                    @endif
                </h3>
                <p class="admin-empty-state__description">
                    @if ($search)
                        Try checking your search spelling or clear the filter to browse all devotional assets.
                    @else
                        Upload deity chhavis, sacred mala pictures, vastra darshan, and banner media to build the central visual assets catalog.
                    @endif
                </p>
                @if ($search)
                    <a href="{{ route('admin.media.index') }}" class="admin-btn-primary" style="margin-top: var(--space-2);">
                        <span>View All Media</span>
                    </a>
                @else
                    <button type="button" class="admin-btn-primary" onclick="document.getElementById('upload-panel').style.display='block'; window.scrollTo({top: 0, behavior: 'smooth'});">
                        <span>Upload Your First Image</span>
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- Detail & Preview Modal --}}
    <div class="media-modal-backdrop" id="modal-detail" role="dialog" aria-modal="true" aria-labelledby="detail-modal-title">
        <div class="media-modal">
            <div class="media-modal__header">
                <h3 id="detail-modal-title" style="font-family: var(--font-heading); font-size: var(--text-lg); font-weight: var(--font-bold); color: var(--color-heading); margin: 0;">
                    Media Asset Preview
                </h3>
                <button type="button" onclick="closeModal('modal-detail')" style="background: none; border: none; font-size: 1.5rem; color: var(--color-text-muted); cursor: pointer;" aria-label="Close dialog">
                    &times;
                </button>
            </div>
            <div class="media-modal__body">
                <div class="media-modal__preview-box">
                    <img id="detail-img" src="" alt="" class="media-modal__preview-img">
                </div>

                {{-- Copy URL Group --}}
                <div>
                    <label class="admin-form-label" style="margin-bottom: 4px; display: block;">Public Storage URL</label>
                    <div class="media-copy-input-group">
                        <input type="text" id="detail-url" readonly class="media-copy-input">
                        <button type="button" class="media-btn-upload" style="padding: 0.4rem 1rem; font-size: var(--text-xs);" onclick="copyToClipboard(document.getElementById('detail-url').value)">
                            Copy URL
                        </button>
                    </div>
                </div>

                {{-- Metadata Table --}}
                <div style="background-color: var(--color-surface-soft); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--space-4);">
                    <div style="display: grid; grid-template-columns: 120px 1fr; row-gap: 8px; font-size: var(--text-xs);">
                        <strong style="color: var(--color-heading);">Title:</strong>
                        <span id="detail-title" style="color: var(--color-text);"></span>

                        <strong style="color: var(--color-heading);">Alt Text:</strong>
                        <span id="detail-alt" style="color: var(--color-text);"></span>

                        <strong style="color: var(--color-heading);">Stored Path:</strong>
                        <code id="detail-path" style="background: var(--color-surface); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--color-border); word-break: break-all;"></code>

                        <strong style="color: var(--color-heading);">Dimensions/Size:</strong>
                        <span id="detail-size" style="color: var(--color-text);"></span>

                        <strong style="color: var(--color-heading);">MIME Type:</strong>
                        <span id="detail-mime" style="color: var(--color-text);"></span>

                        <strong style="color: var(--color-heading);">Uploaded:</strong>
                        <span id="detail-date" style="color: var(--color-text);"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Metadata Modal --}}
    <div class="media-modal-backdrop" id="modal-edit" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title">
        <div class="media-modal" style="max-width: 500px;">
            <div class="media-modal__header">
                <h3 id="edit-modal-title" style="font-family: var(--font-heading); font-size: var(--text-lg); font-weight: var(--font-bold); color: var(--color-heading); margin: 0;">
                    Edit Media Metadata
                </h3>
                <button type="button" onclick="closeModal('modal-edit')" style="background: none; border: none; font-size: 1.5rem; color: var(--color-text-muted); cursor: pointer;" aria-label="Close dialog">
                    &times;
                </button>
            </div>
            <form id="edit-form" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="media-modal__body">
                    <div class="admin-form-group">
                        <label for="edit-title-input" class="admin-form-label">Asset Title</label>
                        <input type="text" id="edit-title-input" name="title" required class="admin-form-input">
                    </div>
                    <div class="admin-form-group">
                        <label for="edit-alt-input" class="admin-form-label">Alt Text (Accessibility / SEO)</label>
                        <input type="text" id="edit-alt-input" name="alt_text" class="admin-form-input">
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: var(--space-3); margin-top: var(--space-2);">
                        <button type="button" onclick="closeModal('modal-edit')" style="background: none; border: 1px solid var(--color-border); padding: 0.6rem 1.2rem; border-radius: var(--radius-pill); font-size: var(--text-xs); color: var(--color-text); cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" class="admin-btn-primary">
                            <span>Save Changes</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Instant Clipboard Toast Feedback --}}
    <div class="media-toast" id="media-toast">
        <span aria-hidden="true">✓</span>
        <span id="media-toast-msg">Copied to clipboard!</span>
    </div>

    {{-- Interactive JavaScript for Drag & Drop, Modals & Copy --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle upload drawer
            const toggleBtn = document.getElementById('btn-toggle-upload');
            const uploadPanel = document.getElementById('upload-panel');
            if (toggleBtn && uploadPanel) {
                toggleBtn.addEventListener('click', function () {
                    const isHidden = uploadPanel.style.display === 'none' || uploadPanel.style.display === '';
                    uploadPanel.style.display = isHidden ? 'block' : 'none';
                    toggleBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                    if (isHidden) {
                        uploadPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                });
            }

            // Drag and drop preview handling
            const dropzone = document.getElementById('media-dropzone');
            const fileInput = document.getElementById('media-file-input');
            const previewWrap = document.getElementById('dropzone-preview');
            const previewThumb = document.getElementById('preview-thumb');
            const previewFilename = document.getElementById('preview-filename');
            const titleInput = document.getElementById('upload-title');

            if (dropzone && fileInput) {
                ['dragenter', 'dragover'].forEach(evt => {
                    dropzone.addEventListener(evt, (e) => {
                        e.preventDefault();
                        dropzone.classList.add('is-dragover');
                    });
                });
                ['dragleave', 'drop'].forEach(evt => {
                    dropzone.addEventListener(evt, (e) => {
                        e.preventDefault();
                        dropzone.classList.remove('is-dragover');
                    });
                });

                fileInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        previewFilename.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                previewThumb.src = e.target.result;
                                previewWrap.style.display = 'block';
                            };
                            reader.readAsDataURL(file);
                        }
                        if (titleInput && !titleInput.value) {
                            const rawName = file.name.substring(0, file.name.lastIndexOf('.')) || file.name;
                            titleInput.value = rawName.replace(/[-_]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        }
                    }
                });
            }

            // Close modals on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal('modal-detail');
                    closeModal('modal-edit');
                }
            });
        });

        // Modal Helpers
        function openMediaDetailModal(data) {
            document.getElementById('detail-img').src = data.url;
            document.getElementById('detail-img').alt = data.alt_text || data.title;
            document.getElementById('detail-url').value = data.url;
            document.getElementById('detail-title').textContent = data.title;
            document.getElementById('detail-alt').textContent = data.alt_text || '— (None)';
            document.getElementById('detail-path').textContent = data.file_path;
            document.getElementById('detail-size').textContent = data.formatted_size;
            document.getElementById('detail-mime').textContent = data.mime_type;
            document.getElementById('detail-date').textContent = data.created_at || '—';

            const modal = document.getElementById('modal-detail');
            modal.classList.add('is-open');
        }

        function openMediaEditModal(id, title, alt) {
            document.getElementById('edit-form').action = '/admin/media/' + id;
            document.getElementById('edit-title-input').value = title;
            document.getElementById('edit-alt-input').value = alt || '';

            const modal = document.getElementById('modal-edit');
            modal.classList.add('is-open');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('is-open');
            }
        }

        // Clipboard Copy with Toast
        function copyToClipboard(text) {
            if (!navigator.clipboard) {
                const el = document.createElement('textarea');
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                showToast('URL copied to clipboard!');
                return;
            }
            navigator.clipboard.writeText(text).then(function() {
                showToast('URL copied to clipboard!');
            }).catch(function() {
                showToast('Failed to copy');
            });
        }

        function showToast(msg) {
            const toast = document.getElementById('media-toast');
            const msgEl = document.getElementById('media-toast-msg');
            if (!toast || !msgEl) return;
            msgEl.textContent = msg;
            toast.classList.add('is-visible');
            setTimeout(() => {
                toast.classList.remove('is-visible');
            }, 2500);
        }
    </script>
    @endpush

@endsection
