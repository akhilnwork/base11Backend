<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts
     */
    public function index(Request $request): JsonResponse
    {
        $query = Blog::published()
            ->with(['user'])
            ->latest('published_at');

        // Add pagination
        $perPage = min($request->get('per_page', 10), 50); // Max 50 per page
        $blogs = $query->paginate($perPage);

        $blogData = $blogs->getCollection()->map(function ($blog) {
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'description' => $blog->description,
                'featured_image' => $blog->featuredImage ? [
                    'url' => $blog->getFeaturedImageUrl(),
                    'thumb' => $blog->getFeaturedImageThumbUrl(),
                    'medium' => $blog->getFeaturedImageMediumUrl(),
                    'large' => $blog->getFeaturedImageLargeUrl(),
                    'alt' => $blog->title,
                ] : null,
                'author' => [
                    'id' => $blog->user->id,
                    'name' => $blog->user->name,
                ],
                'published_at' => $blog->published_at?->toISOString(),
                'created_at' => $blog->created_at->toISOString(),
                'updated_at' => $blog->updated_at->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $blogData,
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
                'last_page' => $blogs->lastPage(),
                'from' => $blogs->firstItem(),
                'to' => $blogs->lastItem(),
            ]
        ]);
    }

    /**
     * Display the specified blog post
     */
    public function show(Blog $blog): JsonResponse
    {
        if (!$blog->is_published || !$blog->published_at || $blog->published_at->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found or not available',
            ], 404);
        }

        $blog->load(['user']);

        $blogData = [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'description' => $blog->description,
            'content' => $blog->content,
                            'featured_image' => $blog->featuredImage ? [
                    'url' => $blog->getFeaturedImageUrl(),
                    'thumb' => $blog->getFeaturedImageThumbUrl(),
                    'medium' => $blog->getFeaturedImageMediumUrl(),
                    'large' => $blog->getFeaturedImageLargeUrl(),
                    'alt' => $blog->title,
                ] : null,
            'author' => [
                'id' => $blog->user->id,
                'name' => $blog->user->name,
            ],
            'meta' => [
                'title' => $blog->meta_title,
                'description' => $blog->meta_description,
                'og_title' => $blog->og_title,
                'og_description' => $blog->og_description,
                'og_image' => $blog->og_image,
            ],
            'published_at' => $blog->published_at?->toISOString(),
            'created_at' => $blog->created_at->toISOString(),
            'updated_at' => $blog->updated_at->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'data' => $blogData,
        ]);
    }
}
