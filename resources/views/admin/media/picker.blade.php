<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Media Picker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .media-item {
            border: 2px solid transparent;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .media-item:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
        }
        
        .media-item.selected {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                {{ $multiple ? 'Select Images' : 'Select Image' }}
            </h2>
            <div class="flex space-x-3">
                @if($multiple)
                    <button id="selectAllBtn" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900">Select All</button>
                    <button id="clearSelectionBtn" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hidden">Clear</button>
                @endif
                <button id="confirmBtn" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 disabled:opacity-50" disabled>
                    Confirm Selection
                </button>
                <button onclick="window.close()" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700">
                    Cancel
                </button>
            </div>
        </div>

        <!-- Selected Count -->
        @if($multiple)
            <div id="selectedCount" class="mb-4 text-sm text-gray-600 hidden">
                <span id="countText">0 images selected</span>
            </div>
        @endif

        <!-- Media Grid -->
        @if($media->count() > 0)
            <div class="media-grid" id="mediaGrid">
                @foreach($media as $item)
                    @if(str_starts_with($item->mime_type, 'image/'))
                        <div class="media-item bg-white border border-gray-200 rounded-lg overflow-hidden" 
                             data-media-id="{{ $item->id }}"
                             data-media-url="{{ $item->getUrl() }}"
                             data-media-thumb="{{ $item->hasGeneratedConversion('thumb') ? $item->getUrl('thumb') : $item->getUrl() }}"
                             data-media-name="{{ $item->name }}"
                             data-media-alt="{{ $item->getCustomProperty('alt', '') }}">
                            
                            <div class="aspect-w-1 aspect-h-1 bg-gray-200 relative">
                                <img src="{{ $item->hasGeneratedConversion('thumb') ? $item->getUrl('thumb') : $item->getUrl() }}" alt="{{ $item->name }}" class="w-full h-32 object-cover">
                                
                                <!-- Selection indicator -->
                                <div class="absolute inset-0 bg-green-500 bg-opacity-20 hidden items-center justify-center selection-overlay">
                                    <div class="bg-green-500 text-white rounded-full p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-2">
                                <div class="text-xs font-medium text-gray-900 truncate" title="{{ $item->name }}">
                                    {{ $item->name }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ strtoupper(pathinfo($item->file_name, PATHINFO_EXTENSION)) }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($media->hasPages())
                <div class="mt-6">
                    {{ $media->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No images found</h3>
                <p class="mt-2 text-sm text-gray-500">Upload some images to get started.</p>
                <button onclick="window.close()" class="mt-4 px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700">
                    Close
                </button>
            </div>
        @endif
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const multiple = {{ $multiple ? 'true' : 'false' }};
        const mediaItems = document.querySelectorAll('.media-item');
        const confirmBtn = document.getElementById('confirmBtn');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const clearSelectionBtn = document.getElementById('clearSelectionBtn');
        const selectedCount = document.getElementById('selectedCount');
        const countText = document.getElementById('countText');
        
        let selectedItems = [];

        // Media item click handler
        mediaItems.forEach(item => {
            item.addEventListener('click', function() {
                const mediaId = this.dataset.mediaId;
                
                if (multiple) {
                    // Toggle selection for multiple mode
                    if (this.classList.contains('selected')) {
                        // Remove from selection
                        this.classList.remove('selected');
                        this.querySelector('.selection-overlay').classList.add('hidden');
                        selectedItems = selectedItems.filter(id => id !== mediaId);
                    } else {
                        // Add to selection
                        this.classList.add('selected');
                        this.querySelector('.selection-overlay').classList.remove('hidden');
                        selectedItems.push(mediaId);
                    }
                } else {
                    // Single selection mode
                    mediaItems.forEach(otherItem => {
                        otherItem.classList.remove('selected');
                        otherItem.querySelector('.selection-overlay').classList.add('hidden');
                    });
                    
                    this.classList.add('selected');
                    this.querySelector('.selection-overlay').classList.remove('hidden');
                    selectedItems = [mediaId];
                }
                
                updateUI();
            });
        });

        // Select all button
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                mediaItems.forEach(item => {
                    if (!item.classList.contains('selected')) {
                        item.classList.add('selected');
                        item.querySelector('.selection-overlay').classList.remove('hidden');
                        selectedItems.push(item.dataset.mediaId);
                    }
                });
                updateUI();
            });
        }

        // Clear selection button
        if (clearSelectionBtn) {
            clearSelectionBtn.addEventListener('click', function() {
                mediaItems.forEach(item => {
                    item.classList.remove('selected');
                    item.querySelector('.selection-overlay').classList.add('hidden');
                });
                selectedItems = [];
                updateUI();
            });
        }

        // Confirm selection
        confirmBtn.addEventListener('click', function() {
            if (selectedItems.length === 0) return;
            
            const selectedMedia = selectedItems.map(id => {
                const item = document.querySelector(`[data-media-id="${id}"]`);
                if (!item) {
                    return null;
                }
                return {
                    id: parseInt(id),
                    url: item.dataset.mediaUrl || '',
                    thumb: item.dataset.mediaThumb || item.dataset.mediaUrl || '',
                    name: item.dataset.mediaName || 'Untitled',
                    alt: item.dataset.mediaAlt || ''
                };
            }).filter(Boolean);

            // Get callback function name from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const callbackName = urlParams.get('callback') || 'handleMediaSelection';
            
            // Send data back to parent window using the specified callback
            if (window.opener && typeof window.opener[callbackName] === 'function') {
                // For single selection, send the first item directly (not in an array)
                // For multiple selection, send the full array
                const dataToSend = multiple ? selectedMedia : selectedMedia[0];
                console.log('Sending data to parent:', dataToSend);
                console.log('Multiple mode:', multiple);
                console.log('Data type:', typeof dataToSend);
                console.log('Is array:', Array.isArray(dataToSend));
                
                window.opener[callbackName](dataToSend);
            } else if (window.opener && typeof window.opener.handleMediaSelection === 'function') {
                // Fallback to default function
                const dataToSend = multiple ? selectedMedia : selectedMedia[0];
                window.opener.handleMediaSelection(dataToSend);
            }
            
            window.close();
        });

        // Update UI based on selection
        function updateUI() {
            const count = selectedItems.length;
            
            // Enable/disable confirm button
            confirmBtn.disabled = count === 0;
            
            if (multiple) {
                // Update count display
                if (count > 0) {
                    selectedCount.classList.remove('hidden');
                    countText.textContent = `${count} image${count !== 1 ? 's' : ''} selected`;
                    selectAllBtn.classList.add('hidden');
                    clearSelectionBtn.classList.remove('hidden');
                } else {
                    selectedCount.classList.add('hidden');
                    selectAllBtn.classList.remove('hidden');
                    clearSelectionBtn.classList.add('hidden');
                }
            }
        }
    });
    </script>
</body>
</html>
