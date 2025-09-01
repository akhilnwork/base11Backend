<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSlide;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AboutSlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides = AboutSlide::ordered()->paginate(15);
        return view('admin.about-slides.index', compact('slides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.about-slides.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug the incoming request
        \Log::info('About slide store request:', $request->all());
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'image_id' => 'nullable|integer|min:1',
        ]);
        
        // Custom validation for media ID
        if ($request->image_id) {
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($request->image_id);
            if (!$media) {
                return back()->withErrors(['image_id' => 'The selected image does not exist.'])->withInput();
            }
        }

        // Debug the image_id
        if ($request->image_id) {
            \Log::info('About slide creation - Image ID received: ' . $request->image_id);
            $mediaExists = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($request->image_id);
            \Log::info('Media exists: ' . ($mediaExists ? 'Yes' : 'No'));
        }

        $slide = AboutSlide::create([
            'title' => $request->title,
            'order' => $request->order,
            'is_active' => $request->boolean('is_active', true),
            'image_id' => $request->image_id, // Store the media ID directly
        ]);

        \Log::info('About slide created successfully with image_id: ' . $request->image_id);

        return redirect()->route('admin.about-slides.index')
            ->with('success', 'About slide created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AboutSlide $aboutSlide)
    {
        return view('admin.about-slides.show', compact('aboutSlide'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AboutSlide $aboutSlide)
    {
        return view('admin.about-slides.edit', compact('aboutSlide'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AboutSlide $aboutSlide)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'image_id' => 'nullable|integer|min:1',
        ]);
        
        // Custom validation for media ID
        if ($request->image_id) {
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($request->image_id);
            if (!$media) {
                return back()->withErrors(['image_id' => 'The selected image does not exist.'])->withInput();
            }
        }

        $aboutSlide->update([
            'title' => $request->title,
            'order' => $request->order,
            'is_active' => $request->boolean('is_active', true),
            'image_id' => $request->has('remove_image') && $request->remove_image ? null : $request->image_id,
        ]);

        \Log::info('About slide updated successfully with image_id: ' . ($request->image_id ?: 'none'));

        return redirect()->route('admin.about-slides.index')
            ->with('success', 'About slide updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutSlide $aboutSlide)
    {
        $aboutSlide->delete();
        
        return redirect()->route('admin.about-slides.index')
            ->with('success', 'About slide deleted successfully.');
    }

    /**
     * Reorder slides
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'slides' => 'required|array',
            'slides.*.id' => 'required|exists:about_slides,id',
            'slides.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->slides as $slideData) {
            AboutSlide::where('id', $slideData['id'])
                ->update(['order' => $slideData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Slides reordered successfully',
        ]);
    }
}
