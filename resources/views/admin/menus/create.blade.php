@extends('admin.layouts.app')

@section('title', 'Create Menu Item')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Create Menu Item</h2>
        <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Menu Items
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.menus.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Menu Title</label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('title') border-red-300 @enderror"
                               placeholder="Enter menu title">
                    </div>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                    <div class="mt-1">
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('slug') border-red-300 @enderror"
                               placeholder="Auto-generated from title">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- URL -->
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                <div class="mt-1">
                    <input type="text" name="url" id="url" value="{{ old('url') }}" 
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('url') border-red-300 @enderror"
                           placeholder="e.g., /about, /contact, https://external-site.com">
                </div>
                <p class="mt-1 text-sm text-gray-500">Relative URL (e.g., /about) or absolute URL (e.g., https://example.com)</p>
                @error('url')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Menu Configuration -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Menu Type</label>
                    <div class="mt-1">
                        <select name="type" id="type" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('type') border-red-300 @enderror">
                            <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Custom Link</option>
                            <option value="page" {{ old('type') == 'page' ? 'selected' : '' }}>Page</option>
                            <option value="category" {{ old('type') == 'category' ? 'selected' : '' }}>Category</option>
                            <option value="module" {{ old('type') == 'module' ? 'selected' : '' }}>Module</option>
                        </select>
                    </div>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target -->
                <div>
                    <label for="target" class="block text-sm font-medium text-gray-700">Link Target</label>
                    <div class="mt-1">
                        <select name="target" id="target" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('target') border-red-300 @enderror">
                            <option value="_self" {{ old('target', '_self') == '_self' ? 'selected' : '' }}>Same Window</option>
                            <option value="_blank" {{ old('target') == '_blank' ? 'selected' : '' }}>New Window</option>
                        </select>
                    </div>
                    @error('target')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <div class="mt-1">
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('sort_order') border-red-300 @enderror">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Parent Menu -->
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Menu</label>
                <div class="mt-1">
                    <select name="parent_id" id="parent_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('parent_id') border-red-300 @enderror">
                        <option value="">None (Top Level)</option>
                        @foreach($parentMenus as $parentMenu)
                            <option value="{{ $parentMenu->id }}" {{ old('parent_id') == $parentMenu->id ? 'selected' : '' }}>
                                {{ $parentMenu->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="mt-1 text-sm text-gray-500">Select a parent menu to create a sub-menu item</p>
                @error('parent_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta Data (Optional) -->
            <div>
                <label for="meta" class="block text-sm font-medium text-gray-700">Meta Data (JSON)</label>
                <div class="mt-1">
                    <textarea name="meta" id="meta" rows="3"
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('meta') border-red-300 @enderror"
                              placeholder='{"icon": "home", "description": "Homepage link"}'>{{ old('meta') }}</textarea>
                </div>
                <p class="mt-1 text-sm text-gray-500">Optional JSON metadata for the menu item</p>
                @error('meta')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active (visible in menus)
                </label>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.menus.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Menu Item
                </button>
            </div>
        </form>
    </div>

    <!-- Examples Section -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Menu Examples</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Internal Page:</strong> Title: "About Us", URL: "/about", Type: "page"</li>
                        <li><strong>Module Link:</strong> Title: "Blog", URL: "/blog", Type: "module"</li>
                        <li><strong>External Link:</strong> Title: "Facebook", URL: "https://facebook.com", Type: "custom", Target: "_blank"</li>
                        <li><strong>Sub-menu:</strong> Set a Parent Menu to create dropdown items</li>
                    </ul>
                </div>
            </div>
        </div>
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
});
</script>
@endpush
@endsection
