<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    /**
     * Display a listing of active testimonials
     */
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::active()
            ->latest()
            ->get()
            ->map(function ($testimonial) {
                return [
                    'id' => $testimonial->id,
                    'name' => $testimonial->name,
                    'testimonial' => $testimonial->testimonial,
                    'designation' => $testimonial->designation,
                    'photo' => $testimonial->photo ? [
                        'url' => $testimonial->getPhotoUrl(),
                        'thumb' => $testimonial->getPhotoThumbUrl(),
                        'medium' => $testimonial->getPhotoMediumUrl(),
                        'large' => $testimonial->getPhotoLargeUrl(),
                        'alt' => $testimonial->name,
                    ] : null,
                    'created_at' => $testimonial->created_at->toISOString(),
                    'updated_at' => $testimonial->updated_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $testimonials,
            'meta' => [
                'total' => $testimonials->count(),
            ]
        ]);
    }
}
