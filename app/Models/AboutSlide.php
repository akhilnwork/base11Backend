<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AboutSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'order',
        'is_active',
        'image_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
    
    // Helper methods for image URLs
    public function getImageUrl($conversion = null)
    {
        if (!$this->image) {
            return null;
        }
        
        if ($conversion && $this->image->hasGeneratedConversion($conversion)) {
            return $this->image->getUrl($conversion);
        }
        
        return $this->image->getUrl();
    }
    
    public function getImageThumbUrl()
    {
        return $this->getImageUrl('thumb');
    }
    
    public function getImageLargeUrl()
    {
        return $this->getImageUrl('large');
    }

    public function getImageMediumUrl()
    {
        return $this->getImageUrl('medium');
    }

    // Scope for active slides
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
