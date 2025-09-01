<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'view_in_gallery',
        'is_active',
        'cover_image_id',
        'gallery_image_ids',
    ];

    protected function casts(): array
    {
        return [
            'view_in_gallery' => 'boolean',
            'is_active' => 'boolean',
            'gallery_image_ids' => 'array',
        ];
    }

    // Relationships
    public function coverImage()
    {
        return $this->belongsTo(Media::class, 'cover_image_id');
    }

    public function galleryImages()
    {
        return $this->belongsToMany(Media::class, null, null, null, 'gallery_image_ids')
            ->whereIn('id', $this->gallery_image_ids ?? []);
    }

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }

    // Helper methods for image URLs
    public function getCoverImageUrl($conversion = null)
    {
        if (!$this->coverImage) {
            return null;
        }
        
        if ($conversion && $this->coverImage->hasGeneratedConversion($conversion)) {
            return $this->coverImage->getUrl($conversion);
        }
        
        return $this->coverImage->getUrl();
    }

    public function getCoverImageThumbUrl()
    {
        return $this->getCoverImageUrl('thumb');
    }

    public function getCoverImageMediumUrl()
    {
        return $this->getCoverImageUrl('medium');
    }

    public function getCoverImageLargeUrl()
    {
        return $this->getCoverImageUrl('large');
    }

    public function getGalleryImageUrls($conversion = null)
    {
        if (!$this->gallery_image_ids || empty($this->gallery_image_ids)) {
            return [];
        }

        $mediaItems = Media::whereIn('id', $this->gallery_image_ids)->get();
        $urls = [];

        foreach ($mediaItems as $media) {
            if ($conversion && $media->hasGeneratedConversion($conversion)) {
                $urls[] = $media->getUrl($conversion);
            } else {
                $urls[] = $media->getUrl();
            }
        }

        return $urls;
    }

    public function getGalleryImageThumbUrls()
    {
        return $this->getGalleryImageUrls('thumb');
    }

    public function getGalleryImageMediumUrls()
    {
        return $this->getGalleryImageUrls('medium');
    }

    public function getGalleryImageLargeUrls()
    {
        return $this->getGalleryImageUrls('large');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeViewInGallery($query)
    {
        return $query->where('view_in_gallery', true);
    }
}
