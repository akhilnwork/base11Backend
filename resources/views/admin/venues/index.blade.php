@extends('admin.layouts.app')

@section('title', 'Venues')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Venues</h2>
        <a href="{{ route('admin.venues.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Venue
        </a>
    </div>

    <!-- Venues Grid -->
    <div class="bg-white shadow rounded-lg">
        @if($venues->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($venues as $venue)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Cover Image -->
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                            @if($venue->coverImage)
                                <img src="{{ $venue->getCoverImageMediumUrl() ?: $venue->getCoverImageUrl() }}" alt="{{ $venue->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 truncate">{{ $venue->title }}</h3>
                                    @if($venue->description)
                                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit($venue->description, 100) }}</p>
                                    @endif
                                </div>
                                <div class="ml-2 flex-shrink-0 flex space-x-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $venue->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $venue->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Venue Info -->
                            <div class="space-y-2 mb-4">
                                <!-- Slug -->
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.102m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    <span class="truncate">{{ $venue->slug }}</span>
                                </div>

                                <!-- Gallery Connection -->
                                @if($venue->gallery)
                                    <div class="flex items-center text-sm text-blue-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        <span class="truncate">{{ $venue->gallery->title }}</span>
                                    </div>
                                @endif

                                <!-- Images Count -->
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ count($venue->sub_image_ids ?? []) }} additional images</span>
                                </div>
                            </div>

                            <!-- Image Thumbnails -->
                            @if($venue->sub_image_ids && count($venue->sub_image_ids) > 0)
                                <div class="flex -space-x-2 mb-4">
                                    @foreach($venue->getSubImageThumbUrls() as $index => $imageUrl)
                                        @if($index < 4)
                                            <img src="{{ $imageUrl }}" alt="Venue image" class="h-8 w-8 rounded-full border-2 border-white object-cover">
                                        @endif
                                    @endforeach
                                    @if(count($venue->sub_image_ids) > 4)
                                        <div class="h-8 w-8 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">
                                            +{{ count($venue->sub_image_ids) - 4 }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- SEO Indicators -->
                            <div class="flex items-center space-x-2 mb-4">
                                @if($venue->meta_title)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        SEO
                                    </span>
                                @endif
                                @if($venue->og_title)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        OG
                                    </span>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.venues.show', $venue) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View Details
                                </a>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.venues.edit', $venue) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this venue?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $venues->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No venues yet</h3>
                <p class="mt-2 text-sm text-gray-500">Get started by creating your first venue.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.venues.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create First Venue
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
