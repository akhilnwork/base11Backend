<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\User;

class MediaLibraryService
{
    /**
     * Upload a file and create a media record
     */
    public function uploadFile(UploadedFile $file, $model = null): Media
    {
        // If no model is provided, use the authenticated user
        if (!$model) {
            $model = auth()->user();
        }

        // Add the file to the media library
        $media = $model->addMedia($file)
            ->toMediaCollection('uploads');

        return $media;
    }

    /**
     * Upload multiple files
     */
    public function uploadMultipleFiles(array $files, $model = null): array
    {
        $uploadedMedia = [];
        
        foreach ($files as $file) {
            $uploadedMedia[] = $this->uploadFile($file, $model);
        }
        
        return $uploadedMedia;
    }

    /**
     * Get media by collection
     */
    public function getMediaByCollection(string $collection = null): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Media::query();
        
        if ($collection) {
            $query->where('collection_name', $collection);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(24);
    }

    /**
     * Search media by filename
     */
    public function searchMedia(string $search): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Media::where('name', 'like', "%{$search}%")
            ->orWhere('file_name', 'like', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(24);
    }

    /**
     * Get file size in human readable format
     */
    public function formatFileSize(int $bytes): string
    {
        if ($bytes === 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor(log($bytes) / log(1024));
        
        return sprintf('%.1f %s', $bytes / pow(1024, $factor), $units[$factor]);
    }

    /**
     * Check if file is an image
     */
    public function isImage(Media $media): bool
    {
        return str_starts_with($media->mime_type, 'image/');
    }

    /**
     * Get optimized image URL based on size requirement
     */
    public function getOptimizedImageUrl(Media $media, string $size = 'medium'): string
    {
        if (!$this->isImage($media)) {
            return $media->getUrl();
        }

        // Return conversion if it exists, otherwise original
        if ($media->hasGeneratedConversion($size)) {
            return $media->getUrl($size);
        }

        return $media->getUrl();
    }
}
