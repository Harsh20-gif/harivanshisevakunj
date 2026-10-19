<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaController extends Controller
{
    /**
     * Display a paginated listing of media assets with search and filtering.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Media::query();

        // Search by title or alt text
        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%")
                  ->orWhere('file_path', 'like', "%{$search}%");
            });
        }

        $mediaItems = $query->latest()->paginate(24)->withQueryString();

        // Library statistics
        $totalItems = Media::count();
        $totalBytes = (int) Media::sum('file_size');
        $storageUsed = $this->formatBytes($totalBytes);

        // JSON response for future picker modal or API consumption
        if ($request->wantsJson() || $request->boolean('json')) {
            return response()->json([
                'data'        => $mediaItems->items(),
                'total'       => $mediaItems->total(),
                'has_more'    => $mediaItems->hasMorePages(),
                'total_bytes' => $totalBytes,
            ]);
        }

        $isPickerMode = $request->boolean('picker');

        return view('admin.media.index', compact(
            'mediaItems',
            'search',
            'totalItems',
            'storageUsed',
            'isPickerMode'
        ));
    }

    /**
     * Store a newly uploaded media file on the public disk.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'image' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,webp,svg,gif',
                'max:10240', // Max 10MB
            ],
            'title'    => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ], [
            'image.required' => 'Please select an image file to upload.',
            'image.file'     => 'The uploaded file must be a valid file.',
            'image.mimes'    => 'Only JPEG, PNG, WebP, SVG, and GIF image files are allowed.',
            'image.max'      => 'The image size may not exceed 10 MB.',
        ]);

        $file = $request->file('image');

        // Clean, human-readable default title from original file name if omitted
        $originalName = $file->getClientOriginalName();
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $title = !empty(trim((string) $validated['title']))
            ? trim($validated['title'])
            : Str::headline($baseName);

        // Store file strictly under public disk's media directory
        // Generates relative path like: media/2026/10/hash.ext
        $dateFolder = date('Y/m');
        $storedPath = $file->store("media/{$dateFolder}", 'public');

        // Relative path normalized with forward slashes (never absolute Windows paths)
        $relativePath = str_replace('\\', '/', $storedPath);

        $media = Media::create([
            'title'     => $title,
            'file_path' => $relativePath,
            'alt_text'  => $validated['alt_text'] ?? null,
            'mime_type' => $file->getClientMimeType() ?: $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Image '{$media->title}' uploaded successfully.",
                'media'   => $media,
            ], 201);
        }

        return redirect()->route('admin.media.index')
            ->with('success', "Image '{$media->title}' uploaded successfully to the Media Library.");
    }

    /**
     * Return media details for preview or picker consumption.
     */
    public function show(Media $media): JsonResponse
    {
        return response()->json([
            'id'             => $media->id,
            'title'          => $media->title,
            'alt_text'       => $media->alt_text,
            'file_path'      => $media->file_path,
            'url'            => $media->url,
            'mime_type'      => $media->mime_type,
            'file_size'      => $media->file_size,
            'formatted_size' => $media->formatted_size,
            'exists_on_disk' => $media->existsOnDisk(),
            'created_at'     => $media->created_at?->format('d M Y, h:i A'),
        ]);
    }

    /**
     * Update metadata (title and alt text) of an existing media record.
     */
    public function update(Request $request, Media $media): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ], [
            'title.required' => 'The image title is required.',
        ]);

        $media->update([
            'title'    => trim($validated['title']),
            'alt_text' => isset($validated['alt_text']) ? trim($validated['alt_text']) : null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Media metadata updated successfully.',
                'media'   => $media,
            ]);
        }

        return redirect()->back()
            ->with('success', "Metadata for '{$media->title}' updated successfully.");
    }

    /**
     * Safely delete a media record and its physical file from disk.
     */
    public function destroy(Request $request, Media $media): RedirectResponse|JsonResponse
    {
        // Safety check: Never delete if referenced by products, categories, etc.
        if ($media->isReferenced()) {
            $msg = "Cannot delete '{$media->title}' because it is currently referenced by other storefront records.";

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return redirect()->back()->with('error', $msg);
        }

        // Delete file from disk if present (gracefully handles missing files)
        $media->deleteFile();

        // Delete database record
        $mediaTitle = $media->title;
        $media->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Media asset '{$mediaTitle}' has been safely removed.",
            ]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', "Media asset '{$mediaTitle}' has been safely removed.");
    }

    /**
     * Helper to format raw bytes into human readable string.
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }

        return $bytes . ' B';
    }
}
