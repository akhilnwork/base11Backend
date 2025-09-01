@extends('admin.layouts.app')

@section('title', 'View Blog Post')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">View Blog Post</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.blogs.edit', $blog) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Post
            </a>
            <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Posts
            </a>
        </div>
    </div>

    <!-- Blog Content -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Featured Image -->
        @if($blog->featured_image_id)
            <div class="w-full h-64 bg-gray-200">
                <img src="{{ $blog->getFeaturedImageUrl() }}" 
                     alt="{{ $blog->title }}" 
                     class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-6">
            <!-- Title and Meta -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $blog->title }}</h1>
                <div class="flex items-center text-sm text-gray-500 space-x-4">
                    <span>Slug: {{ $blog->slug }}</span>
                    <span>Status: 
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $blog->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $blog->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </span>
                    <span>Created: {{ $blog->created_at->format('M d, Y') }}</span>
                    @if($blog->updated_at != $blog->created_at)
                        <span>Updated: {{ $blog->updated_at->format('M d, Y') }}</span>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($blog->description)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700">{{ $blog->description }}</p>
                </div>
            @endif

            <!-- Additional Images -->
            @if($blog->sub_image_ids && count($blog->sub_image_ids) > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Additional Images ({{ count($blog->sub_image_ids) }})</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($blog->subImages() as $image)
                            <div class="relative group">
                                <img src="{{ $image->getUrl('thumb') ?: $image->getUrl() }}" 
                                     alt="{{ $image->name }}" 
                                     class="w-full h-24 object-cover rounded-lg border">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                    <a href="{{ $image->getUrl() }}" target="_blank" class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Content</h3>
                <div class="prose max-w-none">
                    {!! $blog->content !!}
                </div>
            </div>

            <!-- SEO Information -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Meta Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Meta Title</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-900">{{ $blog->meta_title ?: 'Not set' }}</p>
                        </div>
                    </div>

                    <!-- OG Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Open Graph Title</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-900">{{ $blog->og_title ?: 'Not set' }}</p>
                        </div>
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-900">{{ $blog->meta_description ?: 'Not set' }}</p>
                        </div>
                    </div>

                    <!-- OG Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Open Graph Description</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-900">{{ $blog->og_description ?: 'Not set' }}</p>
                        </div>
                    </div>

                    <!-- OG Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Open Graph Image</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            @if($blog->og_image)
                                <a href="{{ $blog->og_image }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                    {{ $blog->og_image }}
                                </a>
                            @else
                                <p class="text-sm text-gray-500">Not set</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-3">
        <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this blog post? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Post
            </button>
        </form>
    </div>
</div>

@push('styles')
<style>
    .prose {
        color: #374151;
        line-height: 1.75;
    }
    
    .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
        color: #111827;
        font-weight: 600;
        margin-top: 1.5em;
        margin-bottom: 0.5em;
    }
    
    .prose h1 { font-size: 1.875rem; }
    .prose h2 { font-size: 1.5rem; }
    .prose h3 { font-size: 1.25rem; }
    .prose h4 { font-size: 1.125rem; }
    
    .prose p {
        margin-bottom: 1em;
    }
    
    .prose ul, .prose ol {
        margin-bottom: 1em;
        padding-left: 1.5em;
    }
    
    .prose li {
        margin-bottom: 0.5em;
    }
    
    .prose a {
        color: #2563eb;
        text-decoration: underline;
    }
    
    .prose a:hover {
        color: #1d4ed8;
    }
    
    .prose blockquote {
        border-left: 4px solid #e5e7eb;
        padding-left: 1em;
        margin: 1.5em 0;
        font-style: italic;
        color: #6b7280;
    }
    
    .prose img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5em 0;
    }
</style>
@endpush
@endsection
