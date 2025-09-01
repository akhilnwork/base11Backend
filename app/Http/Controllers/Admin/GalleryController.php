<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::latest()->paginate(15);
        return view('admin.galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.galleries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'view_in_gallery' => 'boolean',
            'is_active' => 'boolean',
            'cover_image_id' => 'nullable|integer',
            'remove_cover' => 'nullable|boolean',
            'gallery_image_ids' => 'nullable|array',
            'gallery_image_ids.*' => 'integer',
        ]);

        // Validate that the media items exist (only if not removing cover)
        if ($request->cover_image_id && $request->cover_image_id > 0 && !$request->boolean('remove_cover')) {
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($request->cover_image_id);
            if (!$media) {
                return back()->withErrors(['cover_image_id' => 'The selected cover image does not exist.'])->withInput();
            }
        }

        if ($request->gallery_image_ids) {
            $mediaCount = \Spatie\MediaLibrary\MediaCollections\Models\Media::whereIn('id', $request->gallery_image_ids)->count();
            if ($mediaCount !== count($request->gallery_image_ids)) {
                return back()->withErrors(['gallery_image_ids' => 'One or more selected gallery images do not exist.'])->withInput();
            }
        }

        $createData = [
            'title' => $request->title,
            'description' => $request->description,
            'view_in_gallery' => $request->boolean('view_in_gallery', true),
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle cover image
        if ($request->cover_image_id && $request->cover_image_id > 0 && !$request->boolean('remove_cover')) {
            $createData['cover_image_id'] = $request->cover_image_id;
        } else {
            $createData['cover_image_id'] = null;
        }

        // Handle gallery images
        if ($request->gallery_image_ids && is_array($request->gallery_image_ids) && count($request->gallery_image_ids) > 0) {
            $createData['gallery_image_ids'] = $request->gallery_image_ids;
        }

        \Log::info('Creating gallery with data: ' . json_encode($createData));
        \Log::info('Request cover_image_id: ' . $request->cover_image_id);
        \Log::info('Request gallery_image_ids: ' . json_encode($request->gallery_image_ids));
        
        $gallery = Gallery::create($createData);

        \Log::info('Gallery created successfully with ID: ' . $gallery->id . ', cover_image_id: ' . $gallery->cover_image_id . ' and gallery_image_ids: ' . json_encode($gallery->gallery_image_ids));

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        return view('admin.galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'view_in_gallery' => 'boolean',
            'is_active' => 'boolean',
            'cover_image_id' => 'nullable|integer',
            'remove_cover' => 'nullable|boolean',
            'gallery_image_ids' => 'nullable|array',
            'gallery_image_ids.*' => 'integer',
        ]);

        // Validate that the media items exist (only if not removing cover)
        if ($request->cover_image_id && $request->cover_image_id > 0 && !$request->boolean('remove_cover')) {
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($request->cover_image_id);
            if (!$media) {
                return back()->withErrors(['cover_image_id' => 'The selected cover image does not exist.'])->withInput();
            }
        }

        if ($request->gallery_image_ids) {
            $mediaCount = \Spatie\MediaLibrary\MediaCollections\Models\Media::whereIn('id', $request->gallery_image_ids)->count();
            if ($mediaCount !== count($request->gallery_image_ids)) {
                return back()->withErrors(['gallery_image_ids' => 'One or more selected gallery images do not exist.'])->withInput();
            }
        }

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'view_in_gallery' => $request->boolean('view_in_gallery', true),
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle cover image
        if ($request->boolean('remove_cover')) {
            $updateData['cover_image_id'] = null;
        } elseif ($request->cover_image_id && $request->cover_image_id > 0) {
            $updateData['cover_image_id'] = $request->cover_image_id;
        } else {
            // Keep existing cover image if no changes
            $updateData['cover_image_id'] = $gallery->cover_image_id;
        }

        // Handle gallery images
        if ($request->gallery_image_ids) {
            $updateData['gallery_image_ids'] = $request->gallery_image_ids;
        } else {
            // Keep existing gallery images if no changes
            $updateData['gallery_image_ids'] = $gallery->gallery_image_ids;
        }

        \Log::info('Updating gallery with data: ' . json_encode($updateData));
        \Log::info('Request cover_image_id: ' . $request->cover_image_id);
        \Log::info('Request gallery_image_ids: ' . json_encode($request->gallery_image_ids));
        
        $gallery->update($updateData);

        \Log::info('Gallery updated successfully with ID: ' . $gallery->id . ', cover_image_id: ' . $gallery->cover_image_id . ' and gallery_image_ids: ' . json_encode($gallery->gallery_image_ids));

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        
        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery deleted successfully.');
    }
}
