<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'testimonial',
        'designation',
        'is_active',
        'photo_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function photo()
    {
        return $this->belongsTo(Media::class, 'photo_id');
    }

    // Helper methods for image URLs
    public function getPhotoUrl($conversion = null)
    {
        if (!$this->photo) {
            return null;
        }
        
        if ($conversion && $this->photo->hasGeneratedConversion($conversion)) {
            return $this->photo->getUrl($conversion);
        }
        
        return $this->photo->getUrl();
    }

    public function getPhotoThumbUrl()
    {
        return $this->getPhotoUrl('thumb');
    }

    public function getPhotoMediumUrl()
    {
        return $this->getPhotoUrl('medium');
    }

    // Scope for active testimonials
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
