@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
           
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['users'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Published Blogs</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['published_blogs'] }} / {{ $stats['blogs'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Venues</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['active_venues'] }} / {{ $stats['venues'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Unread Contacts</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                <span class="text-red-600 font-bold">{{ $stats['unread_contacts'] }}</span> / {{ $stats['contact_submissions'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">About Slides</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>{{ $stats['about_slides'] }} slides configured</p>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.about-slides.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                        Manage slides →
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Testimonials</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>{{ $stats['testimonials'] }} testimonials</p>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.testimonials.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                        Manage testimonials →
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Galleries</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>{{ $stats['galleries'] }} galleries</p>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.galleries.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                        Manage galleries →
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Media Library</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>{{ $stats['media_files'] }} files uploaded</p>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.media.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                        Browse media →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Contact Submissions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Contact Submissions</h3>
                @if($recent_contacts->count() > 0)
                    <div class="space-y-3">
                        @foreach($recent_contacts as $contact)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $contact->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $contact->subject }}</div>
                                    <div class="text-xs text-gray-400">{{ $contact->created_at->diffForHumans() }}</div>
                                </div>
                                @if(!$contact->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        New
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.contact-submissions.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                            View all submissions →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500">No contact submissions yet.</p>
                @endif
            </div>
        </div>

        <!-- Recent Blogs -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Blogs</h3>
                @if($recent_blogs->count() > 0)
                    <div class="space-y-3">
                        @foreach($recent_blogs as $blog)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $blog->title }}</div>
                                    <div class="text-sm text-gray-500">by {{ $blog->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $blog->created_at->diffForHumans() }}</div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $blog->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $blog->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.blogs.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                            Manage all blogs →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500">No blogs created yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
