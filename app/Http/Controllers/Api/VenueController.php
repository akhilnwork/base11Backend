<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;

class VenueController extends Controller
{
    /**
     * Display a listing of active venues
     */
    public function index(): JsonResponse
    {
        $venues = Venue::active()
            ->with(['gallery'])
            ->latest()
            ->get()
            ->map(function ($venue) {
                return [
                    'id' => $venue->id,
                    'title' => $venue->title,
                    'slug' => $venue->slug,
                    'description' => $venue->description,
                    'cover_image' => $venue->coverImage ? [
                        'url' => $venue->getCoverImageUrl(),
                        'thumb' => $venue->getCoverImageThumbUrl(),
                        'medium' => $venue->getCoverImageMediumUrl(),
                        'large' => $venue->getCoverImageLargeUrl(),
                        'alt' => $venue->title,
                    ] : null,
                    'sub_images' => $venue->getSubImageUrls() ? collect($venue->getSubImageUrls())->map(function ($url, $index) use ($venue) {
                        $mediaId = $venue->sub_image_ids[$index] ?? null;
                        $media = $mediaId ? \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId) : null;
                        return [
                            'id' => $mediaId,
                            'name' => $media ? $media->name : 'Venue image',
                            'url' => $url,
                            'thumb' => $venue->getSubImageThumbUrls()[$index] ?? $url,
                            'medium' => $venue->getSubImageMediumUrls()[$index] ?? $url,
                            'large' => $venue->getSubImageLargeUrls()[$index] ?? $url,
                            'alt' => $media ? $media->getCustomProperty('alt', '') : '',
                        ];
                    }) : collect(),
                    'gallery' => $venue->gallery ? [
                        'id' => $venue->gallery->id,
                        'title' => $venue->gallery->title,
                        'slug' => $venue->gallery->slug ?? null,
                        'images_count' => count($venue->gallery->gallery_image_ids ?? []),
                    ] : null,
                    'meta' => [
                        'title' => $venue->meta_title,
                        'description' => $venue->meta_description,
                        'og_title' => $venue->og_title,
                        'og_description' => $venue->og_description,
                        'og_image' => $venue->og_image,
                    ],
                    'created_at' => $venue->created_at->toISOString(),
                    'updated_at' => $venue->updated_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $venues,
            'meta' => [
                'total' => $venues->count(),
            ]
        ]);
    }

    /**
     * Display the specified venue
     */
    public function show(Venue $venue): JsonResponse
    {
        if (!$venue->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Venue not found or not available',
            ], 404);
        }

        $venue->load(['gallery']);

        $venueData = [
            'id' => $venue->id,
            'title' => $venue->title,
            'slug' => $venue->slug,
            'description' => $venue->description,
            'cover_image' => $venue->getFirstMediaUrl('cover') ? [
                'url' => $venue->getFirstMediaUrl('cover'),
                'thumb' => $venue->getFirstMediaUrl('cover', 'thumb'),
                'medium' => $venue->getFirstMediaUrl('cover', 'medium'),
                'large' => $venue->getFirstMediaUrl('cover', 'large'),
                'alt' => $venue->title,
            ] : null,
            'sub_images' => $venue->getMedia('sub_images')->map(function ($image) {
                return [
                    'id' => $image->id,
                    'name' => $image->name,
                    'url' => $image->getUrl(),
                    'thumb' => $image->getUrl('thumb'),
                    'medium' => $image->getUrl('medium'),
                    'large' => $image->getUrl('large'),
                    'alt' => $image->getCustomProperty('alt', ''),
                ];
            }),
            'gallery' => $venue->gallery ? [
                'id' => $venue->gallery->id,
                'title' => $venue->gallery->title,
                'slug' => $venue->gallery->slug ?? null,
                'description' => $venue->gallery->description,
                'cover_image' => $venue->gallery->getFirstMediaUrl('cover') ? [
                    'url' => $venue->gallery->getFirstMediaUrl('cover'),
                    'thumb' => $venue->gallery->getFirstMediaUrl('cover', 'thumb'),
                    'medium' => $venue->gallery->getFirstMediaUrl('cover', 'medium'),
                    'large' => $venue->gallery->getFirstMediaUrl('cover', 'large'),
                    'alt' => $venue->gallery->title,
                ] : null,
                'images' => $venue->gallery->getMedia('images')->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'name' => $image->name,
                        'url' => $image->getUrl(),
                        'thumb' => $image->getUrl('thumb'),
                        'medium' => $image->getUrl('medium'),
                        'large' => $image->getUrl('large'),
                        'alt' => $image->getCustomProperty('alt', ''),
                    ];
                }),
            ] : null,
            'meta' => [
                'title' => $venue->meta_title,
                'description' => $venue->meta_description,
                'og_title' => $venue->og_title,
                'og_description' => $venue->og_description,
                'og_image' => $venue->og_image,
            ],
            'created_at' => $venue->created_at->toISOString(),
            'updated_at' => $venue->updated_at->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'data' => $venueData,
        ]);
    }
}
