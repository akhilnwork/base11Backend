@extends('admin.layouts.app')

@section('title', 'Gallery Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Gallery Details</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Gallery
            </a>
            <a href="{{ route('admin.galleries.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Galleries
            </a>
        </div>
    </div>

    <!-- Gallery Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Gallery Information</h3>
        </div>
        <div class="px-6 py-4 space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Title</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->title }}</dd>
            </div>
            
            @if($gallery->description)
            <div>
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->description }}</dd>
            </div>
            @endif
            
            <div class="flex space-x-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gallery->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $gallery->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Public View</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gallery->view_in_gallery ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $gallery->view_in_gallery ? 'Public' : 'Private' }}
                        </span>
                    </dd>
                </div>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Created</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->created_at->format('F j, Y \a\t g:i A') }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $gallery->updated_at->format('F j, Y \a\t g:i A') }}</dd>
            </div>
        </div>
    </div>

    <!-- Cover Image -->
    @if($gallery->coverImage)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Cover Image</h3>
        </div>
        <div class="px-6 py-4">
            <div class="flex justify-center">
                <img src="{{ $gallery->getCoverImageLargeUrl() }}" 
                     alt="{{ $gallery->title }}" 
                     class="max-w-full h-auto rounded-lg shadow-md">
            </div>
        </div>
    </div>
    @endif

    <!-- Gallery Images -->
    @if($gallery->gallery_image_ids && count($gallery->gallery_image_ids) > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Gallery Images ({{ count($gallery->gallery_image_ids) }})</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($gallery->getGalleryImageThumbUrls() as $index => $imageUrl)
                <div class="group relative">
                    <img src="{{ $imageUrl }}" 
                         alt="Gallery image {{ $index + 1 }}" 
                         class="w-full h-32 object-cover rounded-lg border shadow-sm group-hover:shadow-md transition-shadow">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                        <span class="text-white opacity-0 group-hover:opacity-100 text-sm font-medium">Image {{ $index + 1 }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Associated Venues -->
    @if($gallery->venues && $gallery->venues->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Associated Venues ({{ $gallery->venues->count() }})</h3>
        </div>
        <div class="px-6 py-4">
            <div class="space-y-3">
                @foreach($gallery->venues as $venue)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $venue->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $venue->address }}</p>
                    </div>
                    <a href="{{ route('admin.venues.show', $venue) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                        View Venue
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

