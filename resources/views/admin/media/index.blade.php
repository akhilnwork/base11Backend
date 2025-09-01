@extends('admin.layouts.app')

@section('title', 'Media Library')

@push('styles')
<style>
    .media-item {
        position: relative;
    }
    
    .media-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .media-item:hover .delete-media {
        opacity: 1 !important;
    }
    
    .delete-media {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .delete-media:hover {
        transform: scale(1.1);
        background-color: #dc2626 !important;
    }
    
    .conversion-status {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .conversion-status.complete {
        background-color: #10B981;
    }
    
    .conversion-status.pending {
        background-color: #F59E0B;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    /* Loading state styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Error state styles */
    .error-message {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        padding: 1rem;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }
</style>
@endpush

@section('content')
<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay hidden">
    <div class="text-center">
        <div class="loading-spinner mx-auto mb-4"></div>
        <p class="text-lg font-medium text-gray-700">Loading Media Library...</p>
    </div>
</div>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Media Library</h2>
        <button id="uploadBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Upload Files
        </button>
    </div>

    <!-- Error Display -->
    <div id="errorDisplay" class="error-message hidden"></div>

    <!-- Upload Area -->
    <div id="uploadArea" class="hidden">
        <div class="dropzone bg-white border-2 border-gray-300 border-dashed rounded-lg p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="mt-4">
                <label for="file-upload" class="cursor-pointer">
                    <span class="mt-2 block text-sm font-medium text-gray-900">
                        Drop files here or click to upload
                    </span>
                    <input id="file-upload" name="files[]" type="file" class="sr-only" multiple accept="image/*">
                </label>
                <p class="mt-2 text-xs text-gray-500">
                    PNG, JPG, GIF, WEBP up to 10MB each
                </p>
            </div>
        </div>
        
        <!-- Upload Progress -->
        <div id="uploadProgress" class="hidden mt-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Uploading files...</span>
                    <span id="progressText" class="text-sm text-gray-500">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Controls -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <!-- Selection Actions -->
            <div class="flex items-center space-x-4">
                <button id="selectAllBtn" class="text-sm text-gray-600 hover:text-gray-900">Select All</button>
                <button id="deselectAllBtn" class="text-sm text-gray-600 hover:text-gray-900 hidden">Deselect All</button>
                <button id="deleteSelectedBtn" class="text-sm text-red-600 hover:text-red-800 hidden">Delete Selected</button>
            </div>
            
            <!-- Media Tools -->
            <div class="flex items-center space-x-3">
                <button id="regenerateConversions" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Regenerate All
                </button>
                <button id="refreshBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
            
            <!-- Stats -->
            <div class="text-sm text-gray-500">
                <span id="mediaCount">{{ $media->total() }}</span> files total
            </div>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="bg-white rounded-lg shadow">
        @if($media->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 p-6" id="mediaGrid">
                @foreach($media as $item)
                    <div class="media-item group w-full max-w-[200px] bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-200 ease-in-out" data-media-id="{{ $item->id }}">
                        <div class="aspect-w-1 aspect-h-1 bg-gray-200 relative">
                            @if(str_starts_with($item->mime_type, 'image/'))
                                <img src="{{ $item->hasGeneratedConversion('thumb') ? $item->getUrl('thumb') : $item->getUrl() }}" 
                                     alt="{{ $item->name }}" 
                                     class="w-full h-48 object-cover"
                                     onerror="this.src='{{ $item->getUrl() }}'">
                            @else
                                <div class="w-full h-48 flex items-center justify-center bg-gray-100">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Selection Checkbox -->
                            <div class="absolute top-2 left-2">
                                <input type="checkbox" class="media-checkbox w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="{{ $item->id }}">
                            </div>
                            
                            <!-- Actions -->
                            <div class="absolute top-2 right-2 opacity-100 transition-opacity z-10">
                                <button class="delete-media bg-red-600 text-white p-1 rounded hover:bg-red-700 transition-colors" data-media-id="{{ $item->id }}" title="Delete this file">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-3">
                            <div class="text-sm font-medium text-gray-900 truncate" title="{{ $item->name }}">
                                {{ $item->name }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ strtoupper(pathinfo($item->file_name, PATHINFO_EXTENSION)) }} • {{ number_format($item->size / 1024, 1) }} KB
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $item->created_at->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $media->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No media files</h3>
                <p class="mt-2 text-sm text-gray-500">Get started by uploading your first files.</p>
                <div class="mt-6">
                    <button type="button" id="uploadEmptyBtn" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Upload Files
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Media Library initialized');
    
    // Utility functions
    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }
    
    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    }
    
    function showError(message) {
        const errorDisplay = document.getElementById('errorDisplay');
        errorDisplay.textContent = message;
        errorDisplay.classList.remove('hidden');
        setTimeout(() => {
            errorDisplay.classList.add('hidden');
        }, 5000);
    }
    
    function hideError() {
        document.getElementById('errorDisplay').classList.add('hidden');
    }
    
    // Initialize page
    hideLoading();
    hideError();
    
    // Upload functionality
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadArea = document.getElementById('uploadArea');
    const fileUpload = document.getElementById('file-upload');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            uploadArea.classList.toggle('hidden');
        });
    }
    
    if (fileUpload) {
        fileUpload.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                uploadFiles(e.target.files);
            }
        });
    }
    
    function uploadFiles(files) {
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }
        
        uploadProgress.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressText.textContent = '0%';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            showError('Security token not found. Please refresh the page.');
            return;
        }
        
        fetch('/admin/media/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showError('Upload successful! Refreshing page...');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showError('Upload failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            showError('Upload failed: ' + error.message);
        })
        .finally(() => {
            uploadProgress.classList.add('hidden');
            fileUpload.value = '';
        });
    }
    
    // Selection functionality
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const mediaCheckboxes = document.querySelectorAll('.media-checkbox');
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            mediaCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectionUI();
        });
    }
    
    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            mediaCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectionUI();
        });
    }
    
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', function() {
            const selectedIds = Array.from(mediaCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                showError('No files selected for deletion.');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${selectedIds.length} selected file(s)?`)) {
                deleteSelectedMedia(selectedIds);
            }
        });
    }
    
    mediaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionUI);
    });
    
    function updateSelectionUI() {
        const checkedBoxes = document.querySelectorAll('.media-checkbox:checked');
        const hasSelection = checkedBoxes.length > 0;
        
        if (selectAllBtn) selectAllBtn.classList.toggle('hidden', hasSelection);
        if (deselectAllBtn) deselectAllBtn.classList.toggle('hidden', !hasSelection);
        if (deleteSelectedBtn) deleteSelectedBtn.classList.toggle('hidden', !hasSelection);
    }
    
    function deleteSelectedMedia(mediaIds) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            showError('Security token not found. Please refresh the page.');
            return;
        }
        
        showLoading();
        
        fetch('/admin/media/batch-delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ media_ids: mediaIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showError('Files deleted successfully! Refreshing page...');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showError('Delete failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Batch delete error:', error);
            showError('Delete failed: ' + error.message);
        })
        .finally(() => {
            hideLoading();
        });
    }
    
    // Individual delete buttons
    document.querySelectorAll('.delete-media').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const mediaId = this.dataset.mediaId;
            
            console.log('Delete button clicked for media ID:', mediaId);
            
            if (confirm('Are you sure you want to delete this file?')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    showError('Security token not found. Please refresh the page.');
                    return;
                }
                
                const button = this;
                const originalHTML = button.innerHTML;
                button.disabled = true;
                button.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                `;
                
                fetch(`/admin/media/${mediaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const mediaItem = this.closest('.media-item');
                        if (mediaItem) {
                            mediaItem.remove();
                            updateMediaCount();
                        } else {
                            window.location.reload();
                        }
                    } else {
                        showError('Delete failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showError('Delete failed: ' + error.message);
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                });
            }
        });
    });
    
    // Regenerate conversions
    const regenerateBtn = document.getElementById('regenerateConversions');
    if (regenerateBtn) {
        regenerateBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to regenerate all image conversions? This may take a while.')) {
                const button = this;
                const originalText = button.innerHTML;
                
                button.disabled = true;
                button.innerHTML = `
                    <svg class="w-4 h-4 inline mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Processing...
                `;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    showError('Security token not found. Please refresh the page.');
                    button.disabled = false;
                    button.innerHTML = originalText;
                    return;
                }
                
                fetch('/admin/media/regenerate-conversions', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showError(`Regeneration started! ${data.count} files queued for processing.`);
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showError('Regeneration failed: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Regeneration error:', error);
                    showError('Regeneration failed');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            }
        });
    }
    
    // Refresh button
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            showLoading();
            window.location.reload();
        });
    }
    
    // Update media count
    function updateMediaCount() {
        const mediaCount = document.getElementById('mediaCount');
        const currentCount = parseInt(mediaCount.textContent);
        mediaCount.textContent = Math.max(0, currentCount - 1);
    }
    
    // Prevent infinite loops by adding a flag
    let isInitialized = false;
    if (!isInitialized) {
        isInitialized = true;
        console.log('Media Library initialization complete');
    }
});
</script>
@endpush
@endsection



