<header class="bg-white shadow-sm z-10">
    <div class="flex items-center justify-between px-6 py-4">
        {{-- Left: Toggle Sidebar + Breadcrumb --}}
        <div class="flex items-center space-x-4">
            {{-- Toggle Sidebar --}}
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-gray-900 lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Breadcrumb --}}
            <div class="hidden md:block">
                <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-600 mt-1">
                    @yield('breadcrumb')
                </nav>
            </div>
        </div>

        {{-- Right: Notifications + User Menu --}}
        <div class="flex items-center space-x-4">
            {{-- View Site --}}
            <a href="{{ route('home') }}" target="_blank"
                class="flex items-center space-x-2 px-4 py-2 text-gray-600 hover:text-[#0053C5] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span class="hidden md:inline">View Site</span>
            </a>

            {{-- Notifications --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                {{-- Dropdown --}}
                <div x-show="open" @click.away="open = false" x-cloak
                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl py-2 z-50">
                    <div class="px-4 py-2 border-b">
                        <h3 class="font-semibold text-gray-900">Notifications</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        {{-- Sample notification --}}
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">New Registration</p>
                                    <p class="text-xs text-gray-600">Ahmad registered for Kajian Ramadhan</p>
                                    <p class="text-xs text-gray-400 mt-1">5 minutes ago</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="border-t px-4 py-2">
                        <a href="#" class="text-sm text-[#0053C5] hover:text-[#003d8f]">View all notifications</a>
                    </div>
                </div>
            </div>

            {{-- User Menu --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-[#0053C5] to-[#003d8f] rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-600">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown --}}
                <div x-show="open" @click.away="open = false" x-cloak
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>
                    <hr class="my-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
