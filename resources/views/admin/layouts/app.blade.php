<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-nav a.active {
            background-color: #f3f4f6;
            color: #1f2937;
            border-right: 3px solid #4f46e5;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @stack('head')
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800">CMS Admin</h2>
            </div>
            
            <nav class="mt-6 sidebar-nav">
                <!-- Overview Section -->
                <div class="px-6 py-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Overview</p>
                </div>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    Dashboard
                    @if(request()->routeIs('admin.dashboard'))
                        <div class="ml-auto w-2 h-2 bg-indigo-500 rounded-full"></div>
                    @endif
                </a>

                <!-- System Management Section -->
                <div class="px-6 py-2 mt-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>
                </div>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Users
                    @if(request()->routeIs('admin.users.*'))
                        <div class="ml-auto w-2 h-2 bg-indigo-500 rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('admin.media.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Media Library
                    @if(request()->routeIs('admin.media.*'))
                        <div class="ml-auto w-2 h-2 bg-indigo-500 rounded-full"></div>
                    @endif
                </a>

                <!-- Content Management Section -->
                <div class="px-6 py-2 mt-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Content</p>
                </div>
                
                <a href="{{ route('admin.about-slides.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.about-slides.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                    About Slides
                    @if(request()->routeIs('admin.about-slides.*'))
                        <div class="ml-auto w-2 h-2 bg-indigo-500 rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Testimonials
                    @if(request()->routeIs('admin.testimonials.*'))
                        <div class="ml-auto w-2 h-2 bg-indigo-500 rounded-full"></div>
                    @endif
                </a>

                <!-- Gallery & Venues Section -->
                <div class="px-6 py-2 mt-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gallery & Venues</p>
                </div>
                
                <a href="{{ route('admin.galleries.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Galleries
                    @if(request()->routeIs('admin.galleries.*'))
                        <div class="ml-auto w-2 h-2 bg-indigo-500 rounded-full"></div>
                    @endif
                </a>
                
                <a href="{{ route('admin.venues.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.venues.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Venues
                    @if(request()->routeIs('admin.venues.*'))
                        <div class="ml-auto w-2 h-2 bg-indigo-500 rounded-full"></div>
                    @endif
                </a>

                <!-- Publishing Section -->
                <div class="px-6 py-2 mt-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Publishing</p>
                </div>
                
                <a href="{{ route('admin.blogs.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Blog Posts
                    @if(request()->routeIs('admin.blogs.*'))
                        <div class="ml-auto w-2 h-2 bg-indigo-500 rounded-full"></div>
                    @endif
                </a>



                <!-- Communications Section -->
                <div class="px-6 py-2 mt-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Communications</p>
                </div>
                
                <a href="{{ route('admin.contact-submissions.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.contact-submissions.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Contact Submissions
                    @if(request()->routeIs('admin.contact-submissions.*'))
                        <div class="ml-auto w-2 h-2 bg-indigo-500 rounded-full"></div>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div class="flex items-center">
                       <!-- <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Admin Panel')</h1> -->
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Publish to Next.js
                        </button>
                        <div class="relative">
                            <button class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                                <span>{{ auth()->user()->name }}</span>
                                <!--<svg class="ml-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>-->
                            </button>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // CSRF token for AJAX requests
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    @stack('scripts')
</body>
</html>
