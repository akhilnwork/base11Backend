@extends('admin.layouts.app')

@section('title', 'Media Library')

@push('styles')
<style>
    .media-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
</style>
@endpush

@section('content')
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
                

            </div>
            
            <!-- Stats -->
            <div class="text-sm text-gray-500">
                {{ $media->total() }} files total
            </div>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="bg-white rounded-lg shadow">
        @if($media->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 p-6" id="mediaGrid">
                @foreach($media as $item)
                    <div class="w-full max-w-[200px] bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-200 ease-in-out" data-media-id="{{ $item->id }}">
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
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="delete-media bg-red-600 text-white p-1 rounded hover:bg-red-700" data-media-id="{{ $item->id }}">
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
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadEmptyBtn = document.getElementById('uploadEmptyBtn');
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('file-upload');
    const dropzone = document.querySelector('.dropzone');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const mediaCheckboxes = document.querySelectorAll('.media-checkbox');
    const regenerateBtn = document.getElementById('regenerateConversions');


    // Toggle upload area
    [uploadBtn, uploadEmptyBtn].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function() {
                uploadArea.classList.toggle('hidden');
            });
        }
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            uploadFiles(e.target.files);
        }
    });

    // Drag and drop
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropzone.classList.remove('dragover');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        uploadFiles(e.dataTransfer.files);
    });

    // Upload files function
    function uploadFiles(files) {
        const formData = new FormData();
        
        Array.from(files).forEach(file => {
            formData.append('files[]', file);
        });

        // Show progress
        uploadProgress.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressText.textContent = '0%';

        fetch('{{ route("admin.media.upload") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Simulate progress for better UX
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 10;
                    progressBar.style.width = progress + '%';
                    progressText.textContent = progress + '%';
                    
                    if (progress >= 100) {
                        clearInterval(interval);
                        setTimeout(() => {
                            uploadProgress.classList.add('hidden');
                            location.reload(); // Refresh to show new files
                        }, 500);
                    }
                }, 100);
            } else {
                alert('Upload failed: ' + data.message);
                uploadProgress.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('Upload failed');
            uploadProgress.classList.add('hidden');
        });
    }

    // Selection management
    function updateSelectionUI() {
        const checked = document.querySelectorAll('.media-checkbox:checked');
        if (checked.length > 0) {
            selectAllBtn.classList.add('hidden');
            deselectAllBtn.classList.remove('hidden');
            deleteSelectedBtn.classList.remove('hidden');
        } else {
            selectAllBtn.classList.remove('hidden');
            deselectAllBtn.classList.add('hidden');
            deleteSelectedBtn.classList.add('hidden');
        }
    }

    // Select all
    selectAllBtn.addEventListener('click', function() {
        mediaCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
            checkbox.closest('.media-item').classList.add('selected');
        });
        updateSelectionUI();
    });

    // Deselect all
    deselectAllBtn.addEventListener('click', function() {
        mediaCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
            checkbox.closest('.media-item').classList.remove('selected');
        });
        updateSelectionUI();
    });

    // Individual checkbox change
    mediaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.closest('.media-item').classList.add('selected');
            } else {
                this.closest('.media-item').classList.remove('selected');
            }
            updateSelectionUI();
        });
    });

    // Delete selected
    deleteSelectedBtn.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.media-checkbox:checked'));
        if (selected.length === 0) return;

        if (confirm(`Are you sure you want to delete ${selected.length} file(s)?`)) {
            const mediaIds = selected.map(cb => cb.value);
            
            fetch('{{ route("admin.media.batch-delete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                },
                body: JSON.stringify({
                    media_ids: mediaIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Delete failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('Delete failed');
            });
        }
    });

    // Individual delete buttons
    document.querySelectorAll('.delete-media').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const mediaId = this.dataset.mediaId;
            
            if (confirm('Are you sure you want to delete this file?')) {
                fetch(`/admin/media/${mediaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.media-item').remove();
                    } else {
                        alert('Delete failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    alert('Delete failed');
                });
            }
        });
    });



    
    // Regenerate conversions
    if (regenerateBtn) {
        regenerateBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to regenerate all image conversions? This may take a while.')) {
                const button = this;
                const originalText = button.innerHTML;
                
                // Show loading state
                button.disabled = true;
                button.innerHTML = `
                    <svg class="w-4 h-4 inline mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Processing...
                `;
                
                fetch('/admin/media/regenerate-conversions', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Regeneration started! ${data.count} files queued for processing.`);
                        // Reload page after a short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        alert('Regeneration failed: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Regeneration error:', error);
                    alert('Regeneration failed');
                })
                .finally(() => {
                    // Restore button state
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            }
        });
    }
});
</script>
@endpush
@endsection


