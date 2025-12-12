<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of media.
     */
    public function index(Request $request)
    {
        $query = Media::with(['uploader', 'mediable']);

        // Filter by type
        if ($request->type) {
            $query->where('file_type', $request->type);
        }

        // Filter by collection
        if ($request->collection) {
            $query->inCollection($request->collection);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('file_name', 'like', "%{$request->search}%");
            });
        }

        $media = $query->orderBy('created_at', 'desc')->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    /**
     * Display the specified media.
     */
    public function show(Media $media)
    {
        $media->load(['uploader', 'mediable']);

        return view('admin.media.show', compact('media'));
    }

    /**
     * Show the form for editing the specified media.
     */
    public function edit(Media $media)
    {
        return view('admin.media.edit', compact('media'));
    }

    /**
     * Upload new media files.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB max
            'collection' => 'nullable|string',
        ]);

        try {
            $uploadedMedia = [];

            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->store('media/' . ($request->collection ?? 'general'), 'public');

                // Get image dimensions if it's an image
                $width = null;
                $height = null;
                $mimeType = $file->getMimeType();

                if (str_starts_with($mimeType, 'image/')) {
                    try {
                        list($width, $height) = getimagesize($file->getRealPath());
                    } catch (\Exception $e) {
                        // If fails, leave null
                    }
                }

                $media = Media::create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_type' => $this->getFileType($mimeType),
                    'mime_type' => $mimeType,
                    'file_size' => $file->getSize(),
                    'disk' => 'public',
                    'collection' => $request->collection ?? 'general',
                    'uploaded_by' => auth()->id(),
                    'width' => $width,
                    'height' => $height,
                    // Set nullable fields explicitly to null
                    'mediable_type' => null,
                    'mediable_id' => null,
                    'title' => null,
                    'description' => null,
                    'alt_text' => null,
                    'thumbnail_path' => null,
                    'metadata' => null,
                    'order' => 0,
                ]);

                $uploadedMedia[] = $media;
            }

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload.',
                'media' => $uploadedMedia
            ]);
        } catch (\Exception $e) {
            \Log::error('Media upload error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified media.
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
        ]);

        try {
            $media->update($request->only(['title', 'description', 'alt_text']));

            // If AJAX request (from modal), return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Media berhasil diupdate.',
                    'media' => $media
                ]);
            }

            // If form submission (from edit page), redirect
            return redirect()
                ->route('admin.media.index')
                ->with('success', 'Media berhasil diupdate.');
        } catch (\Exception $e) {
            \Log::error('Media update error: ' . $e->getMessage());

            // AJAX error response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            // Form error response
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified media from storage.
     */
    public function destroy(Media $media)
    {
        try {
            // Delete physical file
            if (\Storage::disk($media->disk)->exists($media->file_path)) {
                \Storage::disk($media->disk)->delete($media->file_path);
            }

            // Delete thumbnail if exists
            if ($media->thumbnail_path && \Storage::disk($media->disk)->exists($media->thumbnail_path)) {
                \Storage::disk($media->disk)->delete($media->thumbnail_path);
            }

            $media->delete();

            // If AJAX request (from grid), return JSON
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Media berhasil dihapus.'
                ]);
            }

            // If form submission (from edit/show page), redirect
            return redirect()
                ->route('admin.media.index')
                ->with('success', 'Media berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Media delete error: ' . $e->getMessage());

            // AJAX error response
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            // Form error response
            return redirect()
                ->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Get file type from MIME type.
     */
    private function getFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } else {
            return 'document';
        }
    }
}
