@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Testimonials</h2>
        <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Testimonial
        </a>
    </div>

    <!-- Testimonials Grid -->
    <div class="bg-white shadow rounded-lg">
        @if($testimonials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($testimonials as $testimonial)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <!-- Header with photo and status -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                @if($testimonial->photo)
                                    <img src="{{ $testimonial->getPhotoThumbUrl() ?: $testimonial->getPhotoUrl() }}" alt="{{ $testimonial->name }}" class="h-12 w-12 rounded-full object-cover">
                                @else
                                    <div class="h-12 w-12 bg-gray-300 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $testimonial->name }}</h3>
                                    @if($testimonial->designation)
                                        <p class="text-sm text-gray-500">{{ $testimonial->designation }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $testimonial->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <!-- Testimonial Text -->
                        <div class="mb-4">
                            <blockquote class="text-gray-700 italic">
                                "{{ Str::limit($testimonial->testimonial, 150) }}"
                            </blockquote>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="text-xs text-gray-500">
                                {{ $testimonial->created_at->format('M j, Y') }}
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Edit
                                </a>
                                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $testimonials->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No testimonials yet</h3>
                <p class="mt-2 text-sm text-gray-500">Get started by adding your first customer testimonial.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add First Testimonial
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
