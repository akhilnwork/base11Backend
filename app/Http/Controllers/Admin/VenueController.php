<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VenueController extends Controller
{
    public function index(): View
    {
        $venues = Venue::with(['gallery', 'coverImage'])
            ->latest()
            ->paginate(12);

        return view('admin.venues.index', compact('venues'));
    }

    public function create(): View
    {
        $galleries = Gallery::active()->orderBy('title')->get();
        return view('admin.venues.create', compact('galleries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:venues,slug',
            'description' => 'nullable|string',
            'gallery_id' => 'nullable|exists:galleries,id',
            'cover_image_id' => 'nullable|integer',
            'remove_cover' => 'nullable|boolean',
            'sub_image_ids' => 'nullable|array',
            'sub_image_ids.*' => 'integer',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Prepare data for creation
        $createData = [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'gallery_id' => $validated['gallery_id'],
            'is_active' => $validated['is_active'],
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'og_title' => $validated['og_title'],
            'og_description' => $validated['og_description'],
            'og_image' => $validated['og_image'],
        ];

        // Handle cover image
        if ($request->cover_image_id && $request->cover_image_id > 0 && !$request->boolean('remove_cover')) {
            $createData['cover_image_id'] = $request->cover_image_id;
        } else {
            $createData['cover_image_id'] = null;
        }

        // Handle sub images
        if ($request->sub_image_ids && is_array($request->sub_image_ids) && count($request->sub_image_ids) > 0) {
            $createData['sub_image_ids'] = $request->sub_image_ids;
        }

        // Validate that the media items exist (only if not removing cover)
        if ($request->cover_image_id && $request->cover_image_id > 0 && !$request->boolean('remove_cover')) {
            $media = Media::find($request->cover_image_id);
            if (!$media) {
                return back()->withErrors(['cover_image_id' => 'The selected cover image does not exist.'])->withInput();
            }
        }

        if ($request->sub_image_ids && is_array($request->sub_image_ids) && count($request->sub_image_ids) > 0) {
            $mediaCount = Media::whereIn('id', $request->sub_image_ids)->count();
            if ($mediaCount !== count($request->sub_image_ids)) {
                return back()->withErrors(['sub_image_ids' => 'One or more selected sub images do not exist.'])->withInput();
            }
        }

        $venue = Venue::create($createData);

        \Log::info('Venue created successfully with data: ' . json_encode($createData));
        \Log::info('Request cover_image_id: ' . $request->cover_image_id);
        \Log::info('Request sub_image_ids: ' . json_encode($request->sub_image_ids));

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue created successfully.');
    }

    public function show(Venue $venue): View
    {
        $venue->load(['gallery', 'coverImage']);
        return view('admin.venues.show', compact('venue'));
    }

    public function edit(Venue $venue): View
    {
        $galleries = Gallery::active()->orderBy('title')->get();
        return view('admin.venues.edit', compact('venue', 'galleries'));
    }

    public function update(Request $request, Venue $venue): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:venues,slug,' . $venue->id,
            'description' => 'nullable|string',
            'gallery_id' => 'nullable|exists:galleries,id',
            'cover_image_id' => 'nullable|integer',
            'remove_cover' => 'nullable|boolean',
            'sub_image_ids' => 'nullable|array',
            'sub_image_ids.*' => 'integer',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Prepare data for update
        $updateData = [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'gallery_id' => $validated['gallery_id'],
            'is_active' => $validated['is_active'],
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'og_title' => $validated['og_title'],
            'og_description' => $validated['og_description'],
            'og_image' => $validated['og_image'],
        ];

        // Handle cover image
        if ($request->boolean('remove_cover')) {
            $updateData['cover_image_id'] = null;
        } elseif ($request->cover_image_id && $request->cover_image_id > 0) {
            $updateData['cover_image_id'] = $request->cover_image_id;
        } else {
            // Keep existing cover image if no changes
            $updateData['cover_image_id'] = $venue->cover_image_id;
        }

        // Handle sub images
        if ($request->sub_image_ids && is_array($request->sub_image_ids) && count($request->sub_image_ids) > 0) {
            $updateData['sub_image_ids'] = $request->sub_image_ids;
        } else {
            // Keep existing sub images if no changes
            $updateData['sub_image_ids'] = $venue->sub_image_ids;
        }

        // Validate that the media items exist (only if not removing cover)
        if ($request->cover_image_id && $request->cover_image_id > 0 && !$request->boolean('remove_cover')) {
            $media = Media::find($request->cover_image_id);
            if (!$media) {
                return back()->withErrors(['cover_image_id' => 'The selected cover image does not exist.'])->withInput();
            }
        }

        if ($request->sub_image_ids && is_array($request->sub_image_ids) && count($request->sub_image_ids) > 0) {
            $mediaCount = Media::whereIn('id', $request->sub_image_ids)->count();
            if ($mediaCount !== count($request->sub_image_ids)) {
                return back()->withErrors(['sub_image_ids' => 'One or more selected sub images do not exist.'])->withInput();
            }
        }

        $venue->update($updateData);

        \Log::info('Venue updated successfully with data: ' . json_encode($updateData));
        \Log::info('Request cover_image_id: ' . $request->cover_image_id);
        \Log::info('Request sub_image_ids: ' . json_encode($request->sub_image_ids));

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue): RedirectResponse
    {
        $venue->delete();

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue deleted successfully.');
    }
}