@extends('admin.layouts.app')

@section('title', 'Create Testimonial')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Create Testimonial</h2>
        <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Testimonials
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.testimonials.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                <div class="mt-1">
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('name') border-red-300 @enderror"
                           placeholder="Enter customer name">
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Designation -->
            <div>
                <label for="designation" class="block text-sm font-medium text-gray-700">Designation/Title</label>
                <div class="mt-1">
                    <input type="text" name="designation" id="designation" value="{{ old('designation') }}" 
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('designation') border-red-300 @enderror"
                           placeholder="e.g., CEO, Project Manager, etc.">
                </div>
                @error('designation')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Testimonial Text -->
            <div>
                <label for="testimonial" class="block text-sm font-medium text-gray-700">Testimonial</label>
                <div class="mt-1">
                    <textarea name="testimonial" id="testimonial" rows="5" 
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('testimonial') border-red-300 @enderror"
                              placeholder="Enter the customer's testimonial...">{{ old('testimonial') }}</textarea>
                </div>
                @error('testimonial')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Photo -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Customer Photo</label>
                <div class="mt-1">
                    <div id="photoPreview" class="hidden mb-4">
                        <div class="relative inline-block">
                            <img id="selectedPhoto" src="" alt="Selected photo" class="h-24 w-24 object-cover rounded-full border">
                            <button type="button" id="removePhoto" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="button" id="selectPhotoBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Select Photo
                    </button>
                    
                    <input type="hidden" name="photo_id" id="photoId" value="{{ old('photo_id') }}">
                    <p class="mt-2 text-sm text-gray-500">Optional: Upload a photo of the customer</p>
                </div>
                @error('photo_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active (display on website)
                </label>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.testimonials.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Testimonial
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectPhotoBtn = document.getElementById('selectPhotoBtn');
    const removePhotoBtn = document.getElementById('removePhoto');
    const photoPreview = document.getElementById('photoPreview');
    const selectedPhoto = document.getElementById('selectedPhoto');
    const photoId = document.getElementById('photoId');

    // Media picker handler
    window.handleMediaSelection = function(media) {
        console.log('Media selection received:', media);
        
        // Handle array response from media picker
        if (Array.isArray(media) && media.length > 0) {
            media = media[0]; // Take first item from array
        }
        
        if (media && media.id) {
            photoId.value = media.id;
            selectedPhoto.src = media.thumb || media.url || '';
            selectedPhoto.alt = media.name || 'Selected image';
            photoPreview.classList.remove('hidden');
            selectPhotoBtn.textContent = 'Change Photo';
            console.log('Photo ID set to:', media.id);
            console.log('Thumbnail URL:', media.thumb);
        } else {
            console.error('Invalid media data received:', media);
        }
    };

    // Select photo button
    selectPhotoBtn.addEventListener('click', function() {
        const popup = window.open(
            '{{ route("admin.media.picker") }}?multiple=false',
            'MediaPicker',
            'width=800,height=600,scrollbars=yes,resizable=yes'
        );
    });

    // Remove photo button
    removePhotoBtn.addEventListener('click', function() {
        photoId.value = '';
        selectedPhoto.src = '';
        photoPreview.classList.add('hidden');
        selectPhotoBtn.textContent = 'Select Photo';
    });
});
</script>
@endpush
@endsection
