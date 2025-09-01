<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'user_id',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'is_published',
        'published_at',
        'featured_image_id',
        'sub_image_ids',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'sub_image_ids' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
                
                // Ensure uniqueness
                $originalSlug = $blog->slug;
                $count = 2;
                while (static::where('slug', $blog->slug)->exists()) {
                    $blog->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('title') && empty($blog->getOriginal('slug'))) {
                $blog->slug = Str::slug($blog->title);
                
                // Ensure uniqueness
                $originalSlug = $blog->slug;
                $count = 2;
                while (static::where('slug', $blog->slug)->where('id', '!=', $blog->id)->exists()) {
                    $blog->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }

            // Set published_at when publishing
            if ($blog->isDirty('is_published') && $blog->is_published && !$blog->published_at) {
                $blog->published_at = now();
            }
        });
    }

    // Relationships
    public function featuredImage()
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods for image URLs
    public function getFeaturedImageUrl($conversion = null)
    {
        if (!$this->featuredImage) {
            return null;
        }
        
        if ($conversion && $this->featuredImage->hasGeneratedConversion($conversion)) {
            return $this->featuredImage->getUrl($conversion);
        }
        
        return $this->featuredImage->getUrl();
    }

    public function getFeaturedImageThumbUrl()
    {
        return $this->getFeaturedImageUrl('thumb');
    }

    public function getFeaturedImageMediumUrl()
    {
        return $this->getFeaturedImageUrl('medium');
    }

    public function getFeaturedImageLargeUrl()
    {
        return $this->getFeaturedImageUrl('large');
    }

    // Sub images relationship
    public function subImages()
    {
        if (!$this->sub_image_ids || empty($this->sub_image_ids)) {
            return collect();
        }
        
        return Media::whereIn('id', $this->sub_image_ids)->get();
    }

    // Helper methods for sub image URLs
    public function getSubImageThumbUrls()
    {
        if (!$this->sub_image_ids || empty($this->sub_image_ids)) {
            return [];
        }
        
        $mediaItems = Media::whereIn('id', $this->sub_image_ids)->get();
        return $mediaItems->map(function ($media) {
            return $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl();
        })->toArray();
    }

    public function getSubImageUrls()
    {
        if (!$this->sub_image_ids || empty($this->sub_image_ids)) {
            return [];
        }
        
        $mediaItems = Media::whereIn('id', $this->sub_image_ids)->get();
        return $mediaItems->map(function ($media) {
            return $media->getUrl();
        })->toArray();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    // Route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
