<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::with('photo')->latest()->paginate(15);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'testimonial' => 'required|string',
            'designation' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'photo_id' => 'nullable|integer|min:1',
        ]);

        // Validate that the media item exists
        if ($request->photo_id) {
            $media = Media::find($request->photo_id);
            if (!$media) {
                return back()->withErrors(['photo_id' => 'The selected photo does not exist.'])->withInput();
            }
        }

        $testimonial = Testimonial::create([
            'name' => $request->name,
            'testimonial' => $request->testimonial,
            'designation' => $request->designation,
            'is_active' => $request->boolean('is_active', true),
            'photo_id' => $request->photo_id,
        ]);

        \Log::info('Testimonial created successfully with photo_id: ' . $request->photo_id);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        $testimonial->load('photo');
        return view('admin.testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'testimonial' => 'required|string',
            'designation' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'photo_id' => 'nullable|integer',
            'remove_photo' => 'nullable|boolean',
        ]);

        // Validate that the media item exists (only if not removing photo)
        if ($request->photo_id && $request->photo_id > 0 && !$request->boolean('remove_photo')) {
            $media = Media::find($request->photo_id);
            if (!$media) {
                return back()->withErrors(['photo_id' => 'The selected photo does not exist.'])->withInput();
            }
        }

        $updateData = [
            'name' => $request->name,
            'testimonial' => $request->testimonial,
            'designation' => $request->designation,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle photo removal
        if ($request->boolean('remove_photo')) {
            $updateData['photo_id'] = null;
        } elseif ($request->photo_id && $request->photo_id > 0) {
            $updateData['photo_id'] = $request->photo_id;
        } else {
            // If no photo_id and not removing, keep existing photo_id
            $updateData['photo_id'] = $testimonial->photo_id;
        }

        $testimonial->update($updateData);

        \Log::info('Testimonial updated successfully with photo_id: ' . $request->photo_id);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        
        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }
}
