<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    /**
     * Display a listing of active galleries
     */
    public function index(): JsonResponse
    {
        $galleries = Gallery::active()
            ->viewInGallery()
            ->latest()
            ->get()
            ->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'description' => $gallery->description,
                    'cover_image' => $gallery->coverImage ? [
                        'url' => $gallery->getCoverImageUrl(),
                        'thumb' => $gallery->getCoverImageThumbUrl(),
                        'medium' => $gallery->getCoverImageMediumUrl(),
                        'large' => $gallery->getCoverImageLargeUrl(),
                        'alt' => $gallery->title,
                    ] : null,
                    'images_count' => count($gallery->gallery_image_ids ?? []),
                    'created_at' => $gallery->created_at->toISOString(),
                    'updated_at' => $gallery->updated_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $galleries,
            'meta' => [
                'total' => $galleries->count(),
            ]
        ]);
    }

    /**
     * Display the specified gallery with all images
     */
    public function show(Gallery $gallery): JsonResponse
    {
        if (!$gallery->is_active || !$gallery->view_in_gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery not found or not available',
            ], 404);
        }

        $galleryData = [
            'id' => $gallery->id,
            'title' => $gallery->title,
            'description' => $gallery->description,
            'cover_image' => $gallery->coverImage ? [
                'url' => $gallery->getCoverImageUrl(),
                'thumb' => $gallery->getCoverImageThumbUrl(),
                'medium' => $gallery->getCoverImageMediumUrl(),
                'large' => $gallery->getCoverImageLargeUrl(),
                'alt' => $gallery->title,
            ] : null,
            'images' => $gallery->getGalleryImageUrls() ? collect($gallery->getGalleryImageUrls())->map(function ($url, $index) use ($gallery) {
                $mediaId = $gallery->gallery_image_ids[$index] ?? null;
                $media = $mediaId ? \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId) : null;
                return [
                    'id' => $mediaId,
                    'name' => $media ? $media->name : 'Gallery Image',
                    'url' => $url,
                    'thumb' => $gallery->getGalleryImageThumbUrls()[$index] ?? $url,
                    'medium' => $gallery->getGalleryImageMediumUrls()[$index] ?? $url,
                    'large' => $gallery->getGalleryImageLargeUrls()[$index] ?? $url,
                    'alt' => $media ? $media->getCustomProperty('alt', '') : '',
                ];
            }) : collect(),
            'created_at' => $gallery->created_at->toISOString(),
            'updated_at' => $gallery->updated_at->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'data' => $galleryData,
        ]);
    }
}
