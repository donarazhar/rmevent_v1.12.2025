<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
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

                $media = Media::create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_type' => $this->getFileType($file->getMimeType()),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'disk' => 'public',
                    'collection' => $request->collection ?? 'general',
                    'uploaded_by' => auth()->id(),
                ]);

                $uploadedMedia[] = $media;
            }

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload.',
                'media' => $uploadedMedia
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $media->update($request->only(['title', 'description', 'alt_text']));

        return response()->json([
            'success' => true,
            'message' => 'Media berhasil diupdate.'
        ]);
    }

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

            return response()->json([
                'success' => true,
                'message' => 'Media berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

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