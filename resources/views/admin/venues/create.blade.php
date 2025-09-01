@extends('admin.layouts.app')

@section('title', 'Create Venue')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Create Venue</h2>
        <a href="{{ route('admin.venues.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Venues
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.venues.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Venue Title</label>
                                    <div class="mt-1">
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('title') border-red-300 @enderror"
                           placeholder="Enter venue title">
                </div>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL)</label>
                    <div class="mt-1">
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('slug') border-red-300 @enderror"
                               placeholder="Auto-generated from title">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <div class="mt-1">
                    <textarea name="description" id="description" rows="4" 
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('description') border-red-300 @enderror"
                              placeholder="Enter venue description">{{ old('description') }}</textarea>
                </div>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gallery Selection -->
            <div>
                <label for="gallery_id" class="block text-sm font-medium text-gray-700">Linked Gallery</label>
                <div class="mt-1">
                    <select name="gallery_id" id="gallery_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('gallery_id') border-red-300 @enderror">
                        <option value="">Select a gallery (optional)</option>
                        @foreach($galleries as $gallery)
                            <option value="{{ $gallery->id }}" {{ old('gallery_id') == $gallery->id ? 'selected' : '' }}>
                                {{ $gallery->title }} ({{ $gallery->gallery_image_ids ? count($gallery->gallery_image_ids) : 0 }} images)
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="mt-1 text-sm text-gray-500">Link this venue to an existing gallery</p>
                @error('gallery_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Images Section -->
            <div class="space-y-6">
                <!-- Cover Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cover Image</label>
                    <div class="mt-1">
                        <div id="coverPreview" class="hidden mb-4">
                            <div class="relative inline-block">
                                <img id="selectedCover" src="" alt="Selected cover" class="h-32 w-48 object-cover rounded-lg border">
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
                            Select Cover Image
                        </button>
                        
                        <input type="hidden" name="cover_image_id" id="coverImageId" value="{{ old('cover_image_id') }}">
                        <input type="hidden" name="remove_cover" id="removeCoverFlag" value="0">
                        <p class="mt-2 text-sm text-gray-500">Main image for the venue</p>
                    </div>
                    @error('cover_image_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sub Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Additional Images</label>
                    <div class="mt-1">
                        <div id="subImagesPreview" class="hidden mb-4">
                            <div id="selectedSubImages" class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                                <!-- Selected images will be displayed here -->
                            </div>
                        </div>
                        
                        <button type="button" id="selectSubImagesBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Select Additional Images
                        </button>
                        
                        <div id="subImagesInputs"></div>
                        <p class="mt-2 text-sm text-gray-500">Additional venue images (separate from gallery)</p>
                    </div>
                    @error('sub_image_ids')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Meta Title -->
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                        <div class="mt-1">
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" maxlength="60"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('meta_title') border-red-300 @enderror"
                                   placeholder="SEO title (60 chars max)">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Recommended: 50-60 characters</p>
                        @error('meta_title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- OG Title -->
                    <div>
                        <label for="og_title" class="block text-sm font-medium text-gray-700">Open Graph Title</label>
                        <div class="mt-1">
                            <input type="text" name="og_title" id="og_title" value="{{ old('og_title') }}" maxlength="60"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('og_title') border-red-300 @enderror"
                                   placeholder="Social media title">
                        </div>
                        @error('og_title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Meta Description -->
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <div class="mt-1">
                            <textarea name="meta_description" id="meta_description" rows="3" maxlength="160"
                                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('meta_description') border-red-300 @enderror"
                                      placeholder="SEO description (160 chars max)">{{ old('meta_description') }}</textarea>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Recommended: 150-160 characters</p>
                        @error('meta_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- OG Description -->
                    <div>
                        <label for="og_description" class="block text-sm font-medium text-gray-700">Open Graph Description</label>
                        <div class="mt-1">
                            <textarea name="og_description" id="og_description" rows="3" maxlength="160"
                                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('og_description') border-red-300 @enderror"
                                      placeholder="Social media description">{{ old('og_description') }}</textarea>
                        </div>
                        @error('og_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- OG Image -->
                <div class="mt-6">
                    <label for="og_image" class="block text-sm font-medium text-gray-700">Open Graph Image URL</label>
                    <div class="mt-1">
                        <input type="url" name="og_image" id="og_image" value="{{ old('og_image') }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('og_image') border-red-300 @enderror"
                               placeholder="https://example.com/image.jpg">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Image for social media sharing (1200x630px recommended)</p>
                    @error('og_image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active (visible on website)
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.venues.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Venue
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.manual !== 'true') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });

    // Media selection
    const selectCoverBtn = document.getElementById('selectCoverBtn');
    const removeCoverBtn = document.getElementById('removeCover');
    const coverPreview = document.getElementById('coverPreview');
    const selectedCover = document.getElementById('selectedCover');
    const coverImageId = document.getElementById('coverImageId');
    const removeCoverFlag = document.getElementById('removeCoverFlag');
    
    const selectSubImagesBtn = document.getElementById('selectSubImagesBtn');
    const subImagesPreview = document.getElementById('subImagesPreview');
    const selectedSubImages = document.getElementById('selectedSubImages');
    const subImagesInputs = document.getElementById('subImagesInputs');
    
    let subImageIds = [];

    // Debug initialization
    console.log('Form initialized with:');
    console.log('- coverImageId element:', coverImageId);
    console.log('- coverImageId value:', coverImageId.value);
    console.log('- removeCoverFlag element:', removeCoverFlag);
    console.log('- removeCoverFlag value:', removeCoverFlag.value);
    
    // Test if the function is available globally
    console.log('Global handleCoverImageSelection function:', typeof window.handleCoverImageSelection);
    console.log('Global handleSubImagesSelection function:', typeof window.handleSubImagesSelection);

    // Cover image selection
    window.handleCoverImageSelection = function(media) {
        console.log('=== COVER IMAGE SELECTION START ===');
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
        
        console.log('=== COVER IMAGE SELECTION END ===');
    };

    // Additional images selection
    window.handleSubImagesSelection = function(media) {
        console.log('=== ADDITIONAL IMAGES SELECTION START ===');
        console.log('Additional images selection received:', media);
        console.log('Media type:', typeof media);
        console.log('Media is array:', Array.isArray(media));
        
        // Ensure we have an array
        if (!Array.isArray(media)) {
            if (media && media.id) {
                // Single item received, convert to array
                media = [media];
                console.log('Converted single item to array:', media);
            } else {
                console.error('Invalid additional images data received:', media);
                return;
            }
        }
        
        if (media.length > 0) {
            subImageIds = media.map(item => item.id);
            updateSubImagesDisplay(media);
            updateSubImagesInputs();
            console.log('Additional images selected:', subImageIds);
        } else {
            console.error('Empty additional images array received');
        }
        
        console.log('=== ADDITIONAL IMAGES SELECTION END ===');
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

    // Select sub images
    selectSubImagesBtn.addEventListener('click', function() {
        const popup = window.open(
            '{{ route("admin.media.picker") }}?multiple=true&callback=handleSubImagesSelection',
            'MediaPicker',
            'width=800,height=600,scrollbars=yes,resizable=yes'
        );
    });

    // Update sub images display
    function updateSubImagesDisplay(images) {
        selectedSubImages.innerHTML = '';
        
        if (images.length > 0) {
            subImagesPreview.classList.remove('hidden');
            selectSubImagesBtn.textContent = `Change Images (${images.length} selected)`;
            
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
                selectedSubImages.appendChild(imageDiv);
            });
            
            // Add remove handlers
            selectedSubImages.querySelectorAll('.remove-image').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    images.splice(index, 1);
                    subImageIds.splice(index, 1);
                    updateSubImagesDisplay(images);
                    updateSubImagesInputs();
                });
            });
        } else {
            subImagesPreview.classList.add('hidden');
            selectSubImagesBtn.textContent = 'Select Additional Images';
        }
    }

    // Update hidden inputs for sub images
    function updateSubImagesInputs() {
        subImagesInputs.innerHTML = '';
        subImageIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sub_image_ids[]';
            input.value = id;
            subImagesInputs.appendChild(input);
        });
    }

    // Form submission handler to ensure proper data handling
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('=== FORM SUBMISSION START ===');
        
        // Ensure cover_image_id is properly set
        if (coverImageId.value && coverImageId.value !== '' && coverImageId.value !== '0') {
            removeCoverFlag.value = '0';
            console.log('Cover image found, remove_cover set to 0');
        } else {
            removeCoverFlag.value = '1';
            console.log('No cover image, remove_cover set to 1');
        }
        
        // Ensure additional images are properly set
        updateSubImagesInputs();
        
        // Debug logging
        console.log('Form submitting with:');
        console.log('- cover_image_id:', coverImageId.value);
        console.log('- remove_cover:', removeCoverFlag.value);
        console.log('- sub_image_ids:', subImageIds);
        
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
        
        if (subImageIds.length === 0) {
            console.warn('Warning: No additional images selected');
        }
        
        console.log('=== FORM SUBMISSION END ===');
    });
});
</script>
@endpush
@endsection
