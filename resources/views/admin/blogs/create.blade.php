@extends('admin.layouts.app')

@section('title', 'Create Blog Post')

@push('head')
<!-- Debug: TinyMCE API Key = {{ $tinymceApiKey }} -->
<!-- Alternative: Direct config access = {{ config('tinymce.api_key') }} -->
<script src="https://cdn.tiny.cloud/1/{{$tinymceApiKey}}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Create Blog Post</h2>
        <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Blog Posts
        </a>
        
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.blogs.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Blog Title</label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('title') border-red-300 @enderror"
                               placeholder="Enter blog title">
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
                    <textarea name="description" id="description" rows="3" maxlength="500"
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('description') border-red-300 @enderror"
                              placeholder="Brief description of the blog post (500 chars max)">{{ old('description') }}</textarea>
                </div>
                <p class="mt-1 text-sm text-gray-500">Brief summary shown in listings and search results</p>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Featured Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Featured Image</label>
                <div class="mt-1">
                    <div id="featuredPreview" class="hidden mb-4">
                        <div class="relative inline-block">
                            <img id="selectedFeatured" src="" alt="Selected featured image" class="h-32 w-48 object-cover rounded-lg border">
                            <button type="button" id="removeFeatured" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="button" id="selectFeaturedBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Select Featured Image
                    </button>
                    
                    <input type="hidden" name="featured_image_id" id="featuredImageId" value="{{ old('featured_image_id') }}">
                    <p class="mt-2 text-sm text-gray-500">Main image displayed with the blog post</p>
                </div>
                @error('featured_image_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Additional Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Additional Images</label>
                <div class="mt-1">
                    <div id="subImagesPreview" class="hidden mb-4">
                        <p class="text-sm text-gray-600 mb-2">Selected Images:</p>
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
                    <p class="mt-2 text-sm text-gray-500">Choose additional images to display with the blog post</p>
                </div>
                @error('sub_image_ids')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <div class="flex space-x-2">
                        <button type="button" id="cleanContentBtn" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Clean Content
                        </button>
                        <button type="button" id="showCleaningOptionsBtn" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Options
                        </button>
                    </div>
                </div>
                
                <!-- Cleaning Options Panel (Hidden by default) -->
                <div id="cleaningOptionsPanel" class="hidden mb-4 p-4 bg-gray-50 rounded-md border">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Content Cleaning Options</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="removeStyles" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="removeStyles" class="ml-2 block text-sm text-gray-700">Remove style attributes</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="removeClasses" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="removeClasses" class="ml-2 block text-sm text-gray-700">Remove class attributes</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="removeIds" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="removeIds" class="ml-2 block text-sm text-gray-700">Remove ID attributes</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="removeEmptyTags" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="removeEmptyTags" class="ml-2 block text-sm text-gray-700">Remove empty tags</label>
                        </div>
                    </div>
                    <div class="mt-3 flex space-x-2">
                        <button type="button" id="applyCleaningOptionsBtn" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Apply Options
                        </button>
                        <button type="button" id="resetCleaningOptionsBtn" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Reset to Defaults
                        </button>
                    </div>
                </div>
                
                <div class="mt-1">
                    <textarea name="content" id="content" rows="15" 
                              class="shadow-sm focus:border-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2 @error('content') border-red-300 @enderror">{{ old('content') }}</textarea>
                </div>
                @error('content')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
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

            <!-- Publishing Options -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing Options</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="is_published" class="ml-2 block text-sm text-gray-900">
                            Published (visible on website)
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="publish_now" id="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }}
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="publish_now" class="ml-2 block text-sm text-gray-900">
                            Set publish date to now
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.blogs.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Blog Post
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

    // Initialize TinyMCE
    tinymce.init({
        selector: '#content',
        height: 400,
        plugins: [
            'autolink', 'link', 'lists'
        ],
        toolbar: 'undo redo | blocks | bold italic underline | link | bullist numlist',
        block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        menubar: false,
        statusbar: false,
        elementpath: false,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    // Content cleaning functionality
    const cleanContentBtn = document.getElementById('cleanContentBtn');
    const showCleaningOptionsBtn = document.getElementById('showCleaningOptionsBtn');
    const cleaningOptionsPanel = document.getElementById('cleaningOptionsPanel');
    const applyCleaningOptionsBtn = document.getElementById('applyCleaningOptionsBtn');
    const resetCleaningOptionsBtn = document.getElementById('resetCleaningOptionsBtn');
    
    function cleanTinyMCEContent() {
        if (tinymce.activeEditor) {
            // Get the current content from TinyMCE
            let content = tinymce.activeEditor.getContent();
            
            // Clean the content by removing unwanted HTML attributes
            // This regex removes style, class, id, and other common attributes
            let cleanContent = content.replace(/\s\w+="[^"]*"/g, '');
            
            // Additional cleaning: remove empty paragraphs and excessive whitespace
            cleanContent = cleanContent.replace(/<p>\s*<\/p>/g, '');
            cleanContent = cleanContent.replace(/\s+/g, ' ');
            
            // Set the cleaned content back to the editor
            tinymce.activeEditor.setContent(cleanContent);
            
            // Show success message
            showNotification('Content cleaned successfully!', 'success');
            
            console.log('Content cleaned successfully');
            console.log('Original content length:', content.length);
            console.log('Cleaned content length:', cleanContent.length);
        } else {
            console.error('TinyMCE editor not found');
        }
    }
    
    // Add click event listener to clean content button
    cleanContentBtn.addEventListener('click', cleanTinyMCEContent);
    
    // Toggle cleaning options panel
    showCleaningOptionsBtn.addEventListener('click', function() {
        cleaningOptionsPanel.classList.toggle('hidden');
    });
    
    // Apply cleaning options
    applyCleaningOptionsBtn.addEventListener('click', function() {
        if (tinymce.activeEditor) {
            let content = tinymce.activeEditor.getContent();
            let cleanContent = content;
            
            // Apply selected cleaning options
            if (document.getElementById('removeStyles').checked) {
                cleanContent = cleanContent.replace(/\sstyle="[^"]*"/g, '');
            }
            if (document.getElementById('removeClasses').checked) {
                cleanContent = cleanContent.replace(/\sclass="[^"]*"/g, '');
            }
            if (document.getElementById('removeIds').checked) {
                cleanContent = cleanContent.replace(/\sid="[^"]*"/g, '');
            }
            if (document.getElementById('removeEmptyTags').checked) {
                cleanContent = cleanContent.replace(/<p>\s*<\/p>/g, '');
                cleanContent = cleanContent.replace(/<div>\s*<\/div>/g, '');
                cleanContent = cleanContent.replace(/<span>\s*<\/span>/g, '');
            }
            
            // Remove excessive whitespace
            cleanContent = cleanContent.replace(/\s+/g, ' ');
            
            // Set the cleaned content back to the editor
            tinymce.activeEditor.setContent(cleanContent);
            
            // Show success message
            showNotification('Content cleaned with selected options!', 'success');
            
            console.log('Content cleaned with selected options');
        }
    });
    
    // Reset cleaning options to defaults
    resetCleaningOptionsBtn.addEventListener('click', function() {
        document.getElementById('removeStyles').checked = true;
        document.getElementById('removeClasses').checked = true;
        document.getElementById('removeIds').checked = true;
        document.getElementById('removeEmptyTags').checked = true;
        showNotification('Options reset to defaults!', 'info');
    });
    
    // Helper function to show notifications
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-blue-500';
        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-md shadow-lg z-50`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Auto-clean content before form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Clean content before submitting
        if (tinymce.activeEditor) {
            let content = tinymce.activeEditor.getContent();
            let cleanContent = content.replace(/\s\w+="[^"]*"/g, '');
            cleanContent = cleanContent.replace(/<p>\s*<\/p>/g, '');
            cleanContent = cleanContent.replace(/\s+/g, ' ');
            
            // Update the textarea with cleaned content
            tinymce.activeEditor.setContent(cleanContent);
            
            // Save the cleaned content to the textarea
            tinymce.activeEditor.save();
            
            console.log('Content automatically cleaned before submission');
        }
    });
    
    // Featured image selection
    const selectFeaturedBtn = document.getElementById('selectFeaturedBtn');
    const removeFeaturedBtn = document.getElementById('removeFeatured');
    const featuredPreview = document.getElementById('featuredPreview');
    const selectedFeatured = document.getElementById('selectedFeatured');
    const featuredImageId = document.getElementById('featuredImageId');

    // Featured image selection
    window.handleFeaturedImageSelection = function(media) {
        console.log('Featured image selection received:', media);
        
        // Handle array response from media picker
        if (Array.isArray(media) && media.length > 0) {
            media = media[0]; // Take first item from array
        }
        
        if (media && media.id) {
            featuredImageId.value = media.id;
            selectedFeatured.src = media.thumb || media.url || '';
            selectedFeatured.alt = media.name || 'Selected image';
            featuredPreview.classList.remove('hidden');
            selectFeaturedBtn.textContent = 'Change Featured Image';
            console.log('Featured Image ID set to:', media.id);
            console.log('Thumbnail URL:', media.thumb);
        } else {
            console.error('Invalid media data received:', media);
        }
    };

    // Select featured image
    selectFeaturedBtn.addEventListener('click', function() {
        const popup = window.open(
            '{{ route("admin.media.picker") }}?multiple=false&callback=handleFeaturedImageSelection',
            'MediaPicker',
            'width=800,height=600,scrollbars=yes,resizable=yes'
        );
    });

    // Remove featured image
    removeFeaturedBtn.addEventListener('click', function() {
        featuredImageId.value = '';
        selectedFeatured.src = '';
        featuredPreview.classList.add('hidden');
        selectFeaturedBtn.textContent = 'Select Featured Image';
    });

    // Additional images functionality
    const selectSubImagesBtn = document.getElementById('selectSubImagesBtn');
    const subImagesPreview = document.getElementById('subImagesPreview');
    const selectedSubImages = document.getElementById('selectedSubImages');
    const subImagesInputs = document.getElementById('subImagesInputs');
    
    // Handle media selection for additional images
    window.handleSubImagesSelection = function(media) {
        console.log('Sub images selection received:', media);
        
        // Clear previous selection
        selectedSubImages.innerHTML = '';
        subImagesInputs.innerHTML = '';
        
        if (Array.isArray(media) && media.length > 0) {
            // Show preview
            subImagesPreview.classList.remove('hidden');
            
            // Create hidden inputs for form submission
            media.forEach(function(item) {
                // Create preview image
                const imgDiv = document.createElement('div');
                imgDiv.className = 'relative group';
                imgDiv.innerHTML = `
                    <img src="${item.thumb || item.url}" alt="${item.name || 'Selected image'}" class="w-full h-16 object-cover rounded border">
                `;
                selectedSubImages.appendChild(imgDiv);
                
                // Create hidden input
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'sub_image_ids[]';
                input.value = item.id;
                subImagesInputs.appendChild(input);
            });
            
            console.log('Sub images selected:', media.length);
        } else {
            subImagesPreview.classList.add('hidden');
        }
    };
    
    // Select additional images
    selectSubImagesBtn.addEventListener('click', function() {
        const popup = window.open(
            '{{ route("admin.media.picker") }}?multiple=true&callback=handleSubImagesSelection',
            'MediaPicker',
            'width=800,height=600,scrollbars=yes,resizable=yes'
        );
    });
    
    // Log form data before submission
    form.addEventListener('submit', function(e) {
        console.log('Form submission - featured_image_id:', featuredImageId.value);
        console.log('- sub_image_ids:', Array.from(subImagesInputs.querySelectorAll('input')).map(input => input.value));
    });
});
</script>
@endpush
@endsection
