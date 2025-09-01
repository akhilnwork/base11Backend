<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MediaLibraryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaLibraryController extends Controller
{
    public function __construct(private MediaLibraryService $mediaService)
    {
    }

    /**
     * Display media library index
     */
    public function index()
    {
        $media = Media::with('model')
            ->orderBy('created_at', 'desc')
            ->paginate(24);
            
        return view('admin.media.index', compact('media'));
    }

    /**
     * Handle file upload
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
        ]);

        try {
            $uploadedMedia = [];
            
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $media = $this->mediaService->uploadFile($file);
                    $uploadedMedia[] = $this->formatMediaResponse($media);
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedMedia) . ' file(s) uploaded successfully',
                'media' => $uploadedMedia,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a media file
     */
    public function destroy(Media $media): JsonResponse
    {
        try {
            $media->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Batch delete media files
     */
    public function batchDelete(Request $request): JsonResponse
    {
        $request->validate([
            'media_ids' => 'required|array',
            'media_ids.*' => 'exists:media,id',
        ]);

        try {
            $deletedCount = Media::whereIn('id', $request->media_ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' media file(s) deleted successfully',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Batch delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Media picker popup for selecting media in forms
     */
    public function picker(Request $request)
    {
        $media = Media::orderBy('created_at', 'desc')->paginate(20);
        
        $multiple = $request->get('multiple', false);
        $collection = $request->get('collection', null);
        
        return view('admin.media.picker', compact('media', 'multiple', 'collection'));
    }

    /**
     * Format media response for API
     */
    private function formatMediaResponse(Media $media): array
    {
        return [
            'id' => $media->id,
            'name' => $media->name,
            'file_name' => $media->file_name,
            'mime_type' => $media->mime_type,
            'size' => $media->size,
            'url' => $media->getUrl(),
            'thumb_url' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl(),
            'medium_url' => $media->hasGeneratedConversion('medium') ? $media->getUrl('medium') : $media->getUrl(),
            'created_at' => $media->created_at->toISOString(),
        ];
    }

    /**
     * Regenerate all media conversions
     */
    public function regenerateConversions()
    {
        try {
            // Get all image media items
            $mediaItems = Media::where('mime_type', 'like', 'image/%')->get();
            
            $count = 0;
            foreach ($mediaItems as $media) {
                // Find the model that owns this media
                $model = $media->model;
                if ($model) {
                    // Re-perform conversions for this media
                    \Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob::dispatch($media);
                    $count++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Conversion regeneration started',
                'count' => $count,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Regeneration failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
