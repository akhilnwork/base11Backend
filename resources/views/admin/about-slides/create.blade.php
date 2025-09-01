@extends('admin.layouts.app')

@section('title', 'Create About Slide')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Create About Slide</h2>
        <a href="{{ route('admin.about-slides.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Slides
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.about-slides.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                    <div class="mt-1">
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('title') border-red-300 @enderror"
                               placeholder="Enter slide title">
                    </div>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order -->
            <div>
                <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                                    <div class="mt-1">
                        <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('order') border-red-300 @enderror">
                    </div>
                <p class="mt-2 text-sm text-gray-500">Lower numbers appear first</p>
                @error('order')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Image</label>
                <div class="mt-1">
                    <div id="imagePreview" class="hidden mb-4">
                        <div class="relative inline-block">
                            <img id="selectedImage" src="" alt="Selected image" class="h-32 w-32 object-cover rounded-lg border">
                            <button type="button" id="removeImage" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="button" id="selectImageBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Select Image
                    </button>
                    
                    <input type="hidden" name="image_id" id="imageId" value="{{ old('image_id') }}">
                    <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
                </div>
                @error('image_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active
                </label>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.about-slides.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Slide
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectImageBtn = document.getElementById('selectImageBtn');
    const removeImageBtn = document.getElementById('removeImage');
    const imagePreview = document.getElementById('imagePreview');
    const selectedImage = document.getElementById('selectedImage');
    const imageId = document.getElementById('imageId');
    const removeImageFlag = document.getElementById('removeImageFlag');

    // Media picker handler
    window.handleAboutSlideImageSelection = function(media) {
        console.log('Media selection received:', media);
        
        // Handle array response from media picker
        if (Array.isArray(media) && media.length > 0) {
            media = media[0]; // Take first item from array
        }
        
        if (media && media.id) {
            imageId.value = media.id;
            selectedImage.src = media.thumb || media.url || '';
            selectedImage.alt = media.name || 'Selected image';
            imagePreview.classList.remove('hidden');
            selectImageBtn.innerHTML = `
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Change Image
            `;
            removeImageFlag.value = '0';
            console.log('Image ID set to:', media.id);
            console.log('Thumbnail URL:', media.thumb);
        } else {
            console.error('Invalid media data received:', media);
        }
    };

    // Select image button
    selectImageBtn.addEventListener('click', function() {
        const popup = window.open(
            '{{ route("admin.media.picker") }}?multiple=false&callback=handleAboutSlideImageSelection',
            'MediaPicker',
            'width=800,height=600,scrollbars=yes,resizable=yes'
        );
    });

    // Remove image button
    removeImageBtn.addEventListener('click', function() {
        imageId.value = '';
        selectedImage.src = '';
        imagePreview.classList.add('hidden');
        selectImageBtn.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Select Image
        `;
        removeImageFlag.value = '1';
        console.log('Image removed');
    });

    // Show existing image if editing
    @if(old('image_id'))
        // If there's an old image_id, we should show it
        // This would need to be implemented to fetch the image details
    @endif
});
</script>
@endpush
@endsection
