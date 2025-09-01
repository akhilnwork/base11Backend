@extends('admin.layouts.app')

@section('title', 'Edit Testimonial')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Edit Testimonial</h2>
        <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Testimonials
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                <div class="mt-1">
                    <input type="text" name="name" id="name" value="{{ old('name', $testimonial->name) }}" 
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror"
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
                    <input type="text" name="designation" id="designation" value="{{ old('designation', $testimonial->designation) }}" 
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('designation') border-red-300 @enderror"
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
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('testimonial') border-red-300 @enderror"
                              placeholder="Enter the customer's testimonial...">{{ old('testimonial', $testimonial->testimonial) }}</textarea>
                </div>
                @error('testimonial')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Photo -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Customer Photo</label>
                <div class="mt-1">
                    <div id="photoPreview" class="{{ $testimonial->photo_id && $testimonial->photo_id > 0 ? '' : 'hidden' }} mb-4">
                        <div class="relative inline-block">
                            <img id="selectedPhoto" 
                                 src="{{ $testimonial->photo_id && $testimonial->photo_id > 0 ? ($testimonial->getPhotoThumbUrl() ?: $testimonial->getPhotoUrl()) : '' }}" 
                                 alt="{{ $testimonial->name }}" 
                                 class="h-24 w-24 object-cover rounded-full border">
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
                        {{ $testimonial->photo_id && $testimonial->photo_id > 0 ? 'Change Photo' : 'Select Photo' }}
                    </button>
                    
                    <input type="hidden" name="photo_id" id="photoId" value="{{ old('photo_id', $testimonial->photo_id) }}">
                    <input type="hidden" name="remove_photo" id="removePhotoFlag" value="0">
                    <p class="mt-2 text-sm text-gray-500">Optional: Upload a photo of the customer</p>

                </div>
                @error('photo_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}
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
                    Update Testimonial
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
    const removePhotoFlag = document.getElementById('removePhotoFlag');

    // Initialize form with existing photo data
    if (photoId.value && photoId.value !== '' && photoId.value !== '0') {
        removePhotoFlag.value = '0';
        
        // Ensure photo preview is visible
        if (photoPreview.classList.contains('hidden')) {
            photoPreview.classList.remove('hidden');
        }
    } else {
        // No photo or invalid photo_id, set remove flag
        removePhotoFlag.value = '1';
    }

    // Media picker handler
    window.handleMediaSelection = function(media) {
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
            removePhotoFlag.value = '0';
        }
    };

    // Select photo button
    selectPhotoBtn.addEventListener('click', function() {
        const popup = window.open(
            '{{ route("admin.media.picker") }}?multiple=false',
            'MediaPicker',
            'width=800,height=600,scrollbars=yes,resizable=yes'
        );
        
        if (!popup) {
            alert('Please allow popups for this site to select photos');
        }
    });

    // Remove photo button
    removePhotoBtn.addEventListener('click', function() {
        photoId.value = '';
        selectedPhoto.src = '';
        photoPreview.classList.add('hidden');
        selectPhotoBtn.textContent = 'Select Photo';
        removePhotoFlag.value = '1';
    });

    // Form submission handler to ensure proper photo_id handling
    document.querySelector('form').addEventListener('submit', function(e) {
        // If photo_id is empty, null, or 0, and we're not explicitly removing, set remove_photo flag
        if ((!photoId.value || photoId.value === '' || photoId.value === '0') && removePhotoFlag.value !== '1') {
            removePhotoFlag.value = '1';
        }
    });
});
</script>
@endpush
@endsection
