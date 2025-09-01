<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'gallery_id',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'is_active',
        'cover_image_id',
        'sub_image_ids',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sub_image_ids' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($venue) {
            if (empty($venue->slug)) {
                $venue->slug = Str::slug($venue->title);
                
                // Ensure uniqueness
                $originalSlug = $venue->slug;
                $count = 2;
                while (static::where('slug', $venue->slug)->exists()) {
                    $venue->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        static::updating(function ($venue) {
            if ($venue->isDirty('title') && empty($venue->getOriginal('slug'))) {
                $venue->slug = Str::slug($venue->title);
                
                // Ensure uniqueness
                $originalSlug = $venue->slug;
                $count = 2;
                while (static::where('slug', $venue->slug)->where('id', '!=', $venue->id)->exists()) {
                    $venue->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });
    }

    // Relationships
    public function coverImage()
    {
        return $this->belongsTo(Media::class, 'cover_image_id');
    }

    public function subImages()
    {
        return $this->belongsToMany(Media::class, null, null, null, 'sub_image_ids')
            ->whereIn('id', $this->sub_image_ids ?? []);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
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

    public function getSubImageUrls($conversion = null)
    {
        if (!$this->sub_image_ids || empty($this->sub_image_ids)) {
            return [];
        }

        $mediaItems = Media::whereIn('id', $this->sub_image_ids)->get();
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

    public function getSubImageThumbUrls()
    {
        return $this->getSubImageUrls('thumb');
    }

    public function getSubImageMediumUrls()
    {
        return $this->getSubImageUrls('medium');
    }

    public function getSubImageLargeUrls()
    {
        return $this->getSubImageUrls('large');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
