<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Library Dashboard')</title>
    
    {{-- Tailwind CSS CDN for development --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Custom styles and animations --}}
    <style>
        /* Smooth transitions for all interactive elements */
        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Fade in animation for page load */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        /* Slide in from left for sidebar items */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.4s ease-out;
        }
        
        /* Hover scale effect for cards */
        .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Book card hover effect */
        .book-card {
            transition: all 0.3s ease;
        }
        
        .book-card:hover {
            transform: scale(1.05);
        }
        
        /* Shimmer loading effect */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
        
        .shimmer {
            background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
        
        /* Sidebar active indicator animation */
        .sidebar-item-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #3b82f6;
            border-radius: 0 4px 4px 0;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    
    <div class="flex h-screen overflow-hidden">
        
        {{-- Sidebar Navigation --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col slide-in-left">
            
            {{-- Logo/Brand Section --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800">THE BOOKS</span>
                </div>
            </div>
            
            {{-- Navigation Menu --}}
            <nav class="flex-1 overflow-y-auto p-4">
                <ul class="space-y-2">
                    {{-- Discover --}}
                    <li style="animation-delay: 0.1s" class="slide-in-left">
                        <a href="{{ route('dashboard') }}" 
                           class="relative flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 sidebar-item-active' : 'text-gray-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                            <span class="font-medium">Discover</span>
                        </a>
                    </li>
                    
                    {{-- Explore --}}
                    <li style="animation-delay: 0.2s" class="slide-in-left">
                        <a href="{{ route('books.explore') }}" 
                           class="relative flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('books.explore') ? 'bg-blue-50 text-blue-600 sidebar-item-active' : 'text-gray-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="font-medium">Explore</span>
                        </a>
                    </li>
                    
                    {{-- My Library --}}
                    <li style="animation-delay: 0.3s" class="slide-in-left">
                        <a href="{{ route('library') }}" 
                           class="relative flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('library') ? 'bg-blue-50 text-blue-600 sidebar-item-active' : 'text-gray-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="font-medium">My Library</span>
                        </a>
                    </li>
                    
                    {{-- Favorites --}}
                    <li style="animation-delay: 0.4s" class="slide-in-left">
                        <a href="{{ route('favorites') }}" 
                           class="relative flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('favorites') ? 'bg-blue-50 text-blue-600 sidebar-item-active' : 'text-gray-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span class="font-medium">Favorites</span>
                        </a>
                    </li>
                    
                    {{-- Divider --}}
                    <li class="pt-4">
                        <div class="border-t border-gray-200"></div>
                    </li>
                    
                    {{-- Settings --}}
                    <li style="animation-delay: 0.5s" class="slide-in-left">
                        <a href="{{ route('settings') }}" 
                           class="relative flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('settings') ? 'bg-blue-50 text-blue-600 sidebar-item-active' : 'text-gray-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium">Settings</span>
                        </a>
                    </li>
                    
                    {{-- Help --}}
                    <li style="animation-delay: 0.6s" class="slide-in-left">
                        <a href="{{ route('help') }}" 
                           class="relative flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-50 group text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Help</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            {{-- User Profile Section at Bottom --}}
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3 px-2 py-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <img src="{{ auth()->user()->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->username) }}" 
                         alt="User" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->username }}</p>
                        <p class="text-xs text-gray-500 truncate">Credit: ${{ number_format(auth()->user()->credit, 2) }}</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
            
        </aside>
        
        {{-- Main Content Area --}}
        <main class="flex-1 overflow-y-auto">
            
            {{-- Top Header Bar --}}
            <header class="bg-white border-b border-gray-200 sticky top-0 z-10 fade-in">
                <div class="px-8 py-4">
                    <div class="flex items-center justify-between">
                        {{-- Search Bar --}}
                        <div class="flex-1 max-w-xl">
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Search books, authors, categories..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        {{-- Right Side Actions --}}
                        <div class="flex items-center space-x-4 ml-6">
                            {{-- Notifications --}}
                            <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            {{-- Theme Toggle --}}
                            <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            {{-- Content Area --}}
            <div class="p-8 fade-in">
                @yield('content')
            </div>
            
        </main>
        
    </div>
    
    {{-- Alpine.js for interactivity (optional but useful) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
    
</body>
</html>