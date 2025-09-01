@extends('admin.layouts.app')

@section('title', 'Edit Gallery')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Edit Gallery</h2>
        <a href="{{ route('admin.galleries.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Galleries
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Gallery Title</label>
                <div class="mt-1">
                    <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}" 
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('title') border-red-300 @enderror"
                           placeholder="Enter gallery title">
                </div>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <div class="mt-1">
                    <textarea name="description" id="description" rows="3" 
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-300 @enderror"
                              placeholder="Enter gallery description (optional)">{{ old('description', $gallery->description) }}</textarea>
                </div>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cover Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Cover Image</label>
                <div class="mt-1">
                    <div id="coverPreview" class="{{ $gallery->cover_image_id && $gallery->cover_image_id > 0 ? '' : 'hidden' }} mb-4">
                        <div class="relative inline-block">
                            <img id="selectedCover" 
                                 src="{{ $gallery->cover_image_id && $gallery->cover_image_id > 0 ? ($gallery->getCoverImageThumbUrl() ?: $gallery->getCoverImageUrl()) : '' }}" 
                                 alt="{{ $gallery->title }}" 
                                 class="h-32 w-48 object-cover rounded-lg border">
                            <button type="button" id="removeCover" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="button" id="selectCoverBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $gallery->cover_image_id && $gallery->cover_image_id > 0 ? 'Change Cover Image' : 'Select Cover Image' }}
                    </button>
                    
                    <input type="hidden" name="cover_image_id" id="coverImageId" value="{{ old('cover_image_id', $gallery->cover_image_id) }}">
                    <input type="hidden" name="remove_cover" id="removeCoverFlag" value="0">
                    <p class="mt-2 text-sm text-gray-500">Choose a cover image for the gallery</p>
                </div>
                @error('cover_image_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gallery Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Gallery Images</label>
                <div class="mt-1">
                    <div id="currentImages" class="{{ $gallery->gallery_image_ids && count($gallery->gallery_image_ids) > 0 ? '' : 'hidden' }} mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current Images:</p>
                        <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                            @foreach($gallery->getGalleryImageThumbUrls() as $index => $imageUrl)
                                <div class="relative group" data-image-index="{{ $index }}">
                                    <img src="{{ $imageUrl }}" alt="Gallery image" class="w-full h-16 object-cover rounded border">
                                    <button type="button" class="remove-gallery-image absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div id="imagesPreview" class="hidden mb-4">
                        <p class="text-sm text-gray-600 mb-2">New Selection:</p>
                        <div id="selectedImages" class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                            <!-- Selected images will be displayed here -->
                        </div>
                    </div>
                    
                    <button type="button" id="selectImagesBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Select New Gallery Images
                    </button>
                    
                    <div id="galleryImagesInputs"></div>
                    <p class="mt-2 text-sm text-gray-500">Choose new images to replace current gallery images</p>
                </div>
                @error('gallery_image_ids')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="view_in_gallery" id="view_in_gallery" value="1" {{ old('view_in_gallery', $gallery->view_in_gallery) ? 'checked' : '' }}
                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    <label for="view_in_gallery" class="ml-2 block text-sm text-gray-900">
                        View in Gallery (display publicly)
                    </label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}
                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.galleries.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Gallery
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectCoverBtn = document.getElementById('selectCoverBtn');
    const removeCoverBtn = document.getElementById('removeCover');
    const coverPreview = document.getElementById('coverPreview');
    const selectedCover = document.getElementById('selectedCover');
    const coverImageId = document.getElementById('coverImageId');
    const removeCoverFlag = document.getElementById('removeCoverFlag');
    
    const selectImagesBtn = document.getElementById('selectImagesBtn');
    const imagesPreview = document.getElementById('imagesPreview');
    const selectedImages = document.getElementById('selectedImages');
    const galleryImagesInputs = document.getElementById('galleryImagesInputs');
    const currentImages = document.getElementById('currentImages');
    
    let galleryImageIds = [];

    // Initialize form with existing data
    if (coverImageId.value && coverImageId.value !== '' && coverImageId.value !== '0') {
        removeCoverFlag.value = '0';
        
        // Ensure cover preview is visible
        if (coverPreview.classList.contains('hidden')) {
            coverPreview.classList.remove('hidden');
        }
        
        console.log('Edit view: Cover image initialized with ID:', coverImageId.value);
        console.log('Edit view: Cover preview should be visible');
    } else {
        removeCoverFlag.value = '1';
        console.log('Edit view: No cover image, remove_cover flag set to 1');
    }

    // Initialize gallery images
    if (currentImages && !currentImages.classList.contains('hidden')) {
        // Gallery has existing images, initialize the array
        @if($gallery->gallery_image_ids)
            galleryImageIds = @json($gallery->gallery_image_ids);
            console.log('Initialized with existing gallery images:', galleryImageIds);
        @endif
    }
    
    // Initialize hidden inputs for existing gallery images
    updateGalleryImagesInputs();
    
    // Handle remove button clicks for current gallery images
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-gallery-image')) {
            const imageContainer = e.target.closest('[data-image-index]');
            const imageIndex = parseInt(imageContainer.dataset.imageIndex);
            
            // Remove from array
            galleryImageIds.splice(imageIndex, 1);
            
            // Remove from DOM
            imageContainer.remove();
            
            // Update hidden inputs
            updateGalleryImagesInputs();
            
            // Update display
            if (galleryImageIds.length === 0) {
                currentImages.classList.add('hidden');
            }
            
            console.log('Removed gallery image at index:', imageIndex);
            console.log('Updated galleryImageIds:', galleryImageIds);
        }
    });

    // Cover image selection
    window.handleCoverImageSelection = function(media) {
        console.log('=== EDIT COVER IMAGE SELECTION START ===');
        console.log('Cover image selection received:', media);
        console.log('Media type:', typeof media);
        console.log('Media is array:', Array.isArray(media));
        
        // Handle case where media picker sends an array with one item
        if (Array.isArray(media) && media.length > 0) {
            media = media[0]; // Take first item from array
            console.log('Extracted first item from array:', media);
        }
        
        if (media && media.id) {
            coverImageId.value = media.id;
            selectedCover.src = media.thumb || media.url || '';
            selectedCover.alt = media.name || 'Selected image';
            coverPreview.classList.remove('hidden');
            selectCoverBtn.textContent = 'Change Cover Image';
            removeCoverFlag.value = '0';
            
            console.log('Cover image selected:', media.id);
            console.log('coverImageId value set to:', coverImageId.value);
            console.log('Cover preview should now be visible');
            console.log('Selected cover src:', selectedCover.src);
            console.log('Cover preview hidden class:', coverPreview.classList.contains('hidden'));
        } else {
            console.error('Invalid cover image data received:', media);
        }
        
        console.log('=== EDIT COVER IMAGE SELECTION END ===');
    };

    // Gallery images selection
    window.handleGalleryImagesSelection = function(media) {
        console.log('=== EDIT GALLERY IMAGES SELECTION START ===');
        console.log('Gallery images selection received:', media);
        console.log('Media type:', typeof media);
        console.log('Media is array:', Array.isArray(media));
        
        // Ensure we have an array
        if (!Array.isArray(media)) {
            if (media && media.id) {
                // Single item received, convert to array
                media = [media];
                console.log('Converted single item to array:', media);
            } else {
                console.error('Invalid gallery images data received:', media);
                return;
            }
        }
        
        if (media.length > 0) {
            // Add new images to existing array instead of replacing
            media.forEach(function(item) {
                if (!galleryImageIds.includes(item.id)) {
                    galleryImageIds.push(item.id);
                }
            });
            
            // Update hidden inputs
            updateGalleryImagesInputs();
            
            // Show preview of newly selected images
            selectedImages.innerHTML = '';
            media.forEach(function(item) {
                const imgDiv = document.createElement('div');
                imgDiv.className = 'relative group';
                imgDiv.innerHTML = `
                    <img src="${item.thumb || item.url}" alt="${item.name || 'Selected image'}" class="w-full h-16 object-cover rounded border">
                `;
                selectedImages.appendChild(imgDiv);
            });
            
            imagesPreview.classList.remove('hidden');
            console.log('Added images to existing selection. Total:', galleryImageIds.length);
        } else {
            imagesPreview.classList.add('hidden');
        }
        
        console.log('=== EDIT GALLERY IMAGES SELECTION END ===');
    };

    // Select cover image
    selectCoverBtn.addEventListener('click', function() {
        const popup = window.open(
            '{{ route("admin.media.picker") }}?multiple=false&callback=handleCoverImageSelection',
            'MediaPicker',
            'width=800,height=600,scrollbars=yes,resizable=yes'
        );
    });

    // Remove cover image
    removeCoverBtn.addEventListener('click', function() {
        coverImageId.value = '';
        selectedCover.src = '';
        coverPreview.classList.add('hidden');
        selectCoverBtn.textContent = 'Select Cover Image';
        removeCoverFlag.value = '1';
    });

    // Select gallery images
    selectImagesBtn.addEventListener('click', function() {
        const popup = window.open(
            '{{ route("admin.media.picker") }}?multiple=true&callback=handleGalleryImagesSelection',
            'MediaPicker',
            'width=800,height=600,scrollbars=yes,resizable=yes'
        );
    });

    // Update gallery images display
    function updateGalleryImagesDisplay(images) {
        selectedImages.innerHTML = '';
        
        if (images.length > 0) {
            imagesPreview.classList.remove('hidden');
            currentImages.classList.add('hidden');
            selectImagesBtn.textContent = `Change Images (${images.length} selected)`;
            
            images.forEach((image, index) => {
                const imageDiv = document.createElement('div');
                imageDiv.className = 'relative group';
                imageDiv.innerHTML = `
                    <img src="${image.thumb}" alt="${image.name}" class="w-full h-16 object-cover rounded border">
                    <button type="button" class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full p-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity remove-image" data-index="${index}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                selectedImages.appendChild(imageDiv);
            });
            
            // Add remove handlers
            selectedImages.querySelectorAll('.remove-image').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    images.splice(index, 1);
                    galleryImageIds.splice(index, 1);
                    updateGalleryImagesDisplay(images);
                    updateGalleryImagesInputs();
                });
            });
        } else {
            imagesPreview.classList.add('hidden');
            currentImages.classList.remove('hidden');
            selectImagesBtn.textContent = 'Select New Gallery Images';
        }
    }

    // Update hidden inputs for gallery images
    function updateGalleryImagesInputs() {
        galleryImagesInputs.innerHTML = '';
        galleryImageIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'gallery_image_ids[]';
            input.value = id;
            galleryImagesInputs.appendChild(input);
        });
    }

    // Form submission handler to ensure proper data handling
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('=== EDIT FORM SUBMISSION START ===');
        
        // If cover_image_id is empty and we're not explicitly removing, set remove_cover flag
        if ((!coverImageId.value || coverImageId.value === '' || coverImageId.value === '0') && removeCoverFlag.value !== '1') {
            removeCoverFlag.value = '1';
            console.log('Cover image empty, remove_cover flag set to 1');
        }
        
        // Ensure gallery images are properly set
        updateGalleryImagesInputs();
        
        // Debug logging
        console.log('Form submitting with:');
        console.log('- cover_image_id:', coverImageId.value);
        console.log('- remove_cover:', removeCoverFlag.value);
        console.log('- gallery_image_ids:', galleryImageIds);
        console.log('- Current gallery images element:', currentImages);
        console.log('- Current gallery images visibility:', currentImages.classList.contains('hidden'));
        
        // Show form data
        const formData = new FormData(this);
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}: ${value}`);
        }
        
        // Validate data before submission
        if (!coverImageId.value && removeCoverFlag.value !== '1') {
            console.warn('Warning: No cover image selected but remove_cover flag is not set');
        }
        
        if (galleryImageIds.length === 0) {
            console.warn('Warning: No gallery images selected');
        }
        
        console.log('=== EDIT FORM SUBMISSION END ===');
    });
});
</script>
@endpush
@endsection
