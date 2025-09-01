@extends('admin.layouts.app')

@section('title', 'View Venue')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">View Venue</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.venues.edit', $venue) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Venue
            </a>
            <a href="{{ route('admin.venues.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Venues
            </a>
        </div>
    </div>

    <!-- Venue Details -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Venue Information</h3>
        </div>
        
        <div class="px-6 py-4 space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $venue->title }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $venue->slug }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <p class="mt-1 text-sm text-gray-900">{{ $venue->description ?: 'No description provided' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $venue->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $venue->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <!-- Linked Gallery -->
            @if($venue->gallery)
            <div>
                <label class="block text-sm font-medium text-gray-700">Linked Gallery</label>
                <div class="mt-1">
                    <a href="{{ route('admin.galleries.show', $venue->gallery) }}" class="text-indigo-600 hover:text-indigo-900">
                        {{ $venue->gallery->title }}
                    </a>
                    <span class="text-sm text-gray-500 ml-2">
                        ({{ $venue->gallery->gallery_image_ids ? count($venue->gallery->gallery_image_ids) : 0 }} images)
                    </span>
                </div>
            </div>
            @else
            <div>
                <label class="block text-sm font-medium text-gray-700">Linked Gallery</label>
                <p class="mt-1 text-sm text-gray-500">No gallery linked</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Images Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Images</h3>
        </div>
        
        <div class="px-6 py-4 space-y-6">
            <!-- Cover Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Cover Image</label>
                @if($venue->coverImage)
                <div class="mt-2">
                    <img src="{{ $venue->getCoverImageThumbUrl() ?: $venue->getCoverImageUrl() }}" 
                         alt="{{ $venue->title }}" 
                         class="h-32 w-48 object-cover rounded-lg border">
                </div>
                @else
                <p class="mt-1 text-sm text-gray-500">No cover image set</p>
                @endif
            </div>

            <!-- Additional Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Additional Images</label>
                @if($venue->sub_image_ids && count($venue->sub_image_ids) > 0)
                <div class="mt-2">
                    <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                        @foreach($venue->getSubImageThumbUrls() as $imageUrl)
                        <div class="relative group">
                            <img src="{{ $imageUrl }}" alt="Venue image" class="w-full h-16 object-cover rounded border">
                        </div>
                        @endforeach
                    </div>
                    <p class="mt-2 text-sm text-gray-500">{{ count($venue->sub_image_ids) }} additional images</p>
                </div>
                @else
                <p class="mt-1 text-sm text-gray-500">No additional images</p>
                @endif
            </div>
        </div>
    </div>

    <!-- SEO Information -->
    @if($venue->meta_title || $venue->meta_description || $venue->og_title || $venue->og_description || $venue->og_image)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">SEO Settings</h3>
        </div>
        
        <div class="px-6 py-4 space-y-4">
            @if($venue->meta_title)
            <div>
                <label class="block text-sm font-medium text-gray-700">Meta Title</label>
                <p class="mt-1 text-sm text-gray-900">{{ $venue->meta_title }}</p>
            </div>
            @endif

            @if($venue->meta_description)
            <div>
                <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                <p class="mt-1 text-sm text-gray-900">{{ $venue->meta_description }}</p>
            </div>
            @endif

            @if($venue->og_title)
            <div>
                <label class="block text-sm font-medium text-gray-700">Open Graph Title</label>
                <p class="mt-1 text-sm text-gray-900">{{ $venue->og_title }}</p>
            </div>
            @endif

            @if($venue->og_description)
            <div>
                <label class="block text-sm font-medium text-gray-700">Open Graph Description</label>
                <p class="mt-1 text-sm text-gray-900">{{ $venue->og_description }}</p>
            </div>
            @endif

            @if($venue->og_image)
            <div>
                <label class="block text-sm font-medium text-gray-700">Open Graph Image</label>
                <p class="mt-1 text-sm text-gray-900">{{ $venue->og_image }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Timestamps -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Timestamps</h3>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Created</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $venue->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $venue->updated_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




