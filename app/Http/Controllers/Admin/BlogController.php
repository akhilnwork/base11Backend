<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::with(['user', 'featuredImage'])
            ->latest()
            ->paginate(12);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create(): View
    {
        $tinymceApiKey = config('tinymce.api_key');
        \Log::info('TinyMCE API Key in create method: ' . $tinymceApiKey);
        return view('admin.blogs.create', compact('tinymceApiKey'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image_id' => 'nullable|integer|min:1',
            'sub_image_ids' => 'nullable|array',
            'sub_image_ids.*' => 'integer',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|string',
            'is_published' => 'boolean',
            'publish_now' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_published'] = $request->has('is_published');

        // Handle publishing
        if ($request->publish_now && $validated['is_published']) {
            $validated['published_at'] = now();
        }

        // Validate that the media item exists
        if ($request->featured_image_id) {
            $media = Media::find($request->featured_image_id);
            if (!$media) {
                return back()->withErrors(['featured_image_id' => 'The selected featured image does not exist.'])->withInput();
            }
        }

        // Validate that sub images exist
        if ($request->sub_image_ids && is_array($request->sub_image_ids) && count($request->sub_image_ids) > 0) {
            $mediaCount = Media::whereIn('id', $request->sub_image_ids)->count();
            if ($mediaCount !== count($request->sub_image_ids)) {
                return back()->withErrors(['sub_image_ids' => 'One or more selected sub images do not exist.'])->withInput();
            }
        }

        $blog = Blog::create($validated);

        \Log::info('Blog created successfully with featured_image_id: ' . $request->featured_image_id);
        \Log::info('Blog created successfully with sub_image_ids: ' . json_encode($request->sub_image_ids));

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function show(Blog $blog): RedirectResponse
    {
        return redirect()->route('admin.blogs.index');
    }

    public function edit(Blog $blog): View
    {
        $tinymceApiKey = config('tinymce.api_key');
        \Log::info('TinyMCE API Key in edit method: ' . $tinymceApiKey);
        return view('admin.blogs.edit', compact('blog', 'tinymceApiKey'));
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image_id' => 'nullable|integer|min:1',
            'sub_image_ids' => 'nullable|array',
            'sub_image_ids.*' => 'integer',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|string',
            'is_published' => 'boolean',
            'publish_now' => 'boolean',
            'remove_featured' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        // Handle publishing
        if ($request->publish_now && $validated['is_published'] && !$blog->published_at) {
            $validated['published_at'] = now();
        }

        // Handle featured image removal
        if ($request->remove_featured) {
            $validated['featured_image_id'] = null;
        }

        // Validate that the media item exists
        if ($request->featured_image_id && !$request->remove_featured) {
            $media = Media::find($request->featured_image_id);
            if (!$media) {
                return back()->withErrors(['featured_image_id' => 'The selected featured image does not exist.'])->withInput();
            }
        }

        // Validate that sub images exist
        if ($request->sub_image_ids && is_array($request->sub_image_ids) && count($request->sub_image_ids) > 0) {
            $mediaCount = Media::whereIn('id', $request->sub_image_ids)->count();
            if ($mediaCount !== count($request->sub_image_ids)) {
                return back()->withErrors(['sub_image_ids' => 'One or more selected sub images do not exist.'])->withInput();
            }
        }

        $blog->update($validated);

        \Log::info('Blog updated successfully with featured_image_id: ' . $request->featured_image_id);
        \Log::info('Blog updated successfully with sub_image_ids: ' . json_encode($request->sub_image_ids));

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}