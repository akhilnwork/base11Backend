@extends('admin.layouts.app')

@section('title', 'Galleries')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Galleries</h2>
        <a href="{{ route('admin.galleries.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Gallery
        </a>
    </div>

    <!-- Galleries Grid -->
    <div class="bg-white shadow rounded-lg">
        @if($galleries->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($galleries as $gallery)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Cover Image -->
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                                                    @if($gallery->coverImage)
                            <img src="{{ $gallery->getCoverImageMediumUrl() ?: $gallery->getCoverImageUrl() }}" alt="{{ $gallery->title }}" class="w-full h-48 object-cover">
                        @else
                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 truncate">{{ $gallery->title }}</h3>
                                    @if($gallery->description)
                                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit($gallery->description, 100) }}</p>
                                    @endif
                                </div>
                                <div class="ml-2 flex-shrink-0 flex space-x-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gallery->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $gallery->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($gallery->view_in_gallery)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Public
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Gallery Stats -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ count($gallery->gallery_image_ids ?? []) }} images
                                    </span>
                                </div>
                                <span>{{ $gallery->created_at->format('M j, Y') }}</span>
                            </div>

                            <!-- Image Thumbnails -->
                            @if($gallery->gallery_image_ids && count($gallery->gallery_image_ids) > 0)
                                <div class="flex -space-x-2 mb-4">
                                    @foreach($gallery->getGalleryImageThumbUrls() as $index => $imageUrl)
                                        @if($index < 4)
                                            <img src="{{ $imageUrl }}" alt="Gallery image" class="h-8 w-8 rounded-full border-2 border-white object-cover">
                                        @endif
                                    @endforeach
                                    @if(count($gallery->gallery_image_ids) > 4)
                                        <div class="h-8 w-8 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">
                                            +{{ count($gallery->gallery_image_ids) - 4 }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.galleries.show', $gallery) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View Details
                                </a>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.galleries.edit', $gallery) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this gallery?')">
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
                {{ $galleries->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No galleries yet</h3>
                <p class="mt-2 text-sm text-gray-500">Get started by creating your first image gallery.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.galleries.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create First Gallery
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
