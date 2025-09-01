@extends('admin.layouts.app')

@section('title', 'About Slides')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">About Slides</h2>
        <a href="{{ route('admin.about-slides.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Slide
        </a>
    </div>

    <!-- Slides List -->
    <div class="bg-white shadow rounded-lg">
        @if($slides->count() > 0)
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Drag and drop to reorder slides</p>
                </div>
                
                <div id="slidesList" class="space-y-4">
                    @foreach($slides as $slide)
                        <div class="slide-item bg-gray-50 border border-gray-200 rounded-lg p-4 cursor-move" data-slide-id="{{ $slide->id }}" data-order="{{ $slide->order }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Drag Handle -->
                                    <div class="drag-handle text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                        </svg>
                                    </div>
                                    
                                    <!-- Image Thumbnail -->
                                    <div class="flex-shrink-0">
                                        @if($slide->image)
                                            <img src="{{ $slide->getImageThumbUrl() ?: $slide->getImageUrl() }}" alt="{{ $slide->title }}" class="h-16 w-16 object-cover rounded-lg">
                                        @else
                                            <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Slide Info -->
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center space-x-3">
                                            <h3 class="text-lg font-medium text-gray-900 truncate">{{ $slide->title }}</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $slide->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <p class="text-sm text-gray-500">Order: {{ $slide->order }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.about-slides.edit', $slide) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.about-slides.destroy', $slide) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this slide?')">
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
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $slides->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No slides yet</h3>
                <p class="mt-2 text-sm text-gray-500">Get started by creating your first about slide.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.about-slides.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add First Slide
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slidesList = document.getElementById('slidesList');
    
    if (slidesList) {
        // Initialize Sortable for drag and drop
        const sortable = Sortable.create(slidesList, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                // Get the new order
                const slides = [];
                const slideItems = slidesList.querySelectorAll('.slide-item');
                
                slideItems.forEach((item, index) => {
                    slides.push({
                        id: parseInt(item.dataset.slideId),
                        order: index
                    });
                });
                
                // Send AJAX request to update order
                fetch('{{ route("admin.about-slides.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                    },
                    body: JSON.stringify({
                        slides: slides
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the order display
                        slideItems.forEach((item, index) => {
                            const orderElement = item.querySelector('p');
                            if (orderElement) {
                                orderElement.textContent = `Order: ${index}`;
                            }
                            item.dataset.order = index;
                        });
                        
                        // Show success message
                        showFlashMessage('Slides reordered successfully', 'success');
                    } else {
                        console.error('Reorder failed:', data.message);
                        showFlashMessage('Failed to reorder slides', 'error');
                    }
                })
                .catch(error => {
                    console.error('Reorder error:', error);
                    showFlashMessage('Failed to reorder slides', 'error');
                });
            }
        });
    }
    
    // Flash message function
    function showFlashMessage(message, type) {
        const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        const flash = document.createElement('div');
        flash.className = `mb-4 ${alertClass} px-4 py-3 rounded relative`;
        flash.innerHTML = `<span class="block sm:inline">${message}</span>`;
        
        const container = document.querySelector('.space-y-6');
        container.insertBefore(flash, container.firstChild);
        
        // Remove after 3 seconds
        setTimeout(() => {
            flash.remove();
        }, 3000);
    }
});
</script>

<style>
.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    background-color: #f3f4f6;
}

.sortable-drag {
    transform: rotate(5deg);
}

.slide-item {
    transition: all 0.2s ease;
}

.slide-item:hover {
    background-color: #f9fafb;
}
</style>
@endpush
@endsection
