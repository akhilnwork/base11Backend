<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutSlide;
use Illuminate\Http\JsonResponse;

class AboutSlideController extends Controller
{
    /**
     * Display a listing of active about slides
     */
    public function index(): JsonResponse
    {
        $slides = AboutSlide::active()
            ->ordered()
            ->get()
            ->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'order' => $slide->order,
                    'image' => $slide->image ? [
                        'url' => $slide->getImageUrl(),
                        'thumb' => $slide->getImageThumbUrl(),
                        'medium' => $slide->getImageMediumUrl(),
                        'large' => $slide->getImageLargeUrl(),
                        'alt' => $slide->title,
                    ] : null,
                    'created_at' => $slide->created_at->toISOString(),
                    'updated_at' => $slide->updated_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $slides,
            'meta' => [
                'total' => $slides->count(),
            ]
        ]);
    }
}
