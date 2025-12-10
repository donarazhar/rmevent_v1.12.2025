<?php

namespace App\Models\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;

trait HasMedia
{
    /**
     * Get all media
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('order');
    }

    /**
     * Get media by collection
     */
    public function getMedia(string $collection = null)
    {
        if ($collection) {
            return $this->media()->where('collection', $collection)->get();
        }
        return $this->media;
    }

    /**
     * Get first media from collection
     */
    public function getFirstMedia(string $collection = null): ?Media
    {
        if ($collection) {
            return $this->media()->where('collection', $collection)->first();
        }
        return $this->media()->first();
    }

    /**
     * Get first media URL from collection
     */
    public function getFirstMediaUrl(string $collection = null, string $default = null): ?string
    {
        $media = $this->getFirstMedia($collection);
        return $media ? $media->url : $default;
    }

    /**
     * Add media from file upload
     */
    public function addMedia(UploadedFile $file, string $collection = 'default', array $customProperties = []): Media
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->store('media/' . $collection, 'public');

        $fileType = $this->getFileType($file->getMimeType());

        $mediaData = [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'disk' => 'public',
            'collection' => $collection,
            'uploaded_by' => auth()->id(),
            'order' => $this->media()->where('collection', $collection)->count() + 1,
        ];

        // If image, get dimensions
        if ($fileType === 'image') {
            $imageSize = getimagesize($file->getRealPath());
            if ($imageSize) {
                $mediaData['width'] = $imageSize[0];
                $mediaData['height'] = $imageSize[1];
            }
        }

        $mediaData = array_merge($mediaData, $customProperties);

        return $this->media()->create($mediaData);
    }

    /**
     * Add media from URL
     */
    public function addMediaFromUrl(string $url, string $collection = 'default'): Media
    {
        $contents = file_get_contents($url);
        $fileName = basename(parse_url($url, PHP_URL_PATH));
        $filePath = 'media/' . $collection . '/' . time() . '_' . $fileName;

        \Storage::disk('public')->put($filePath, $contents);

        $mimeType = \Storage::disk('public')->mimeType($filePath);
        $fileSize = \Storage::disk('public')->size($filePath);

        return $this->media()->create([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $this->getFileType($mimeType),
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'disk' => 'public',
            'collection' => $collection,
            'uploaded_by' => auth()->id(),
            'order' => $this->media()->where('collection', $collection)->count() + 1,
        ]);
    }

    /**
     * Clear media collection
     */
    public function clearMediaCollection(string $collection = null): bool
    {
        $query = $this->media();
        
        if ($collection) {
            $query->where('collection', $collection);
        }

        $mediaItems = $query->get();

        foreach ($mediaItems as $media) {
            // Delete physical file
            if (\Storage::disk($media->disk)->exists($media->file_path)) {
                \Storage::disk($media->disk)->delete($media->file_path);
            }
            
            // Delete thumbnail if exists
            if ($media->thumbnail_path && \Storage::disk($media->disk)->exists($media->thumbnail_path)) {
                \Storage::disk($media->disk)->delete($media->thumbnail_path);
            }

            // Delete database record
            $media->delete();
        }

        return true;
    }

    /**
     * Delete specific media
     */
    public function deleteMedia(int $mediaId): bool
    {
        $media = $this->media()->find($mediaId);

        if (!$media) {
            return false;
        }

        // Delete physical file
        if (\Storage::disk($media->disk)->exists($media->file_path)) {
            \Storage::disk($media->disk)->delete($media->file_path);
        }

        // Delete thumbnail if exists
        if ($media->thumbnail_path && \Storage::disk($media->disk)->exists($media->thumbnail_path)) {
            \Storage::disk($media->disk)->delete($media->thumbnail_path);
        }

        return $media->delete();
    }

    /**
     * Has media in collection
     */
    public function hasMedia(string $collection = null): bool
    {
        if ($collection) {
            return $this->media()->where('collection', $collection)->exists();
        }
        return $this->media()->exists();
    }

    /**
     * Determine file type from MIME type
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