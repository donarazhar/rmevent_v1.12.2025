<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - Ramadhan 1447 H</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#0053C5',
                            dark: '#003d8f',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js with Collapse Plugin --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Default zoom 80% */
        html {
            zoom: 80%;
        }

        /* Atau menggunakan transform untuk browser yang tidak support zoom */
        @supports not (zoom: 80%) {
            html {
                transform: scale(0.8);
                transform-origin: top left;
                width: 125%;
                height: 125%;
            }
        }

        /* Custom scrollbar untuk sidebar */
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        /* Firefox */
        .sidebar-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        /* Smooth transition untuk sidebar */
        .sidebar-transition {
            transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        /* Popover animation */
        .popover-enter {
            animation: popoverIn 0.2s ease-out;
        }

        @keyframes popoverIn {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Main content transition when sidebar collapses */
        .main-content-transition {
            transition: margin-left 0.3s ease-in-out;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100">
    {{-- Zoom Control Widget --}}
    <div x-data="{
        showZoomControl: false,
        currentZoom: localStorage.getItem('appZoom') || '80'
    }" x-init="document.documentElement.style.zoom = currentZoom + '%'" class="fixed bottom-4 right-4 z-[100]">
        {{-- Zoom Toggle Button --}}
        <button @click="showZoomControl = !showZoomControl"
            class="w-10 h-10 bg-[#0053C5] hover:bg-[#004AB0] text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
            </svg>
        </button>

        {{-- Zoom Control Panel --}}
        <div x-show="showZoomControl" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2" @click.away="showZoomControl = false"
            class="absolute bottom-12 right-0 bg-white rounded-xl shadow-xl p-4 w-48 border border-gray-200">
            <div class="text-sm font-semibold text-gray-700 mb-3">Zoom Level</div>
            <div class="flex items-center gap-2">
                <button
                    @click="currentZoom = Math.max(50, parseInt(currentZoom) - 10); document.documentElement.style.zoom = currentZoom + '%'; localStorage.setItem('appZoom', currentZoom)"
                    class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>
                <div class="flex-1 text-center">
                    <span class="text-lg font-bold text-[#0053C5]" x-text="currentZoom + '%'"></span>
                </div>
                <button
                    @click="currentZoom = Math.min(150, parseInt(currentZoom) + 10); document.documentElement.style.zoom = currentZoom + '%'; localStorage.setItem('appZoom', currentZoom)"
                    class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>
            {{-- Quick Zoom Buttons --}}
            <div class="flex gap-1 mt-3">
                <button
                    @click="currentZoom = '70'; document.documentElement.style.zoom = '70%'; localStorage.setItem('appZoom', '70')"
                    :class="currentZoom == '70' ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="flex-1 py-1.5 rounded-lg text-xs font-medium transition-colors">70%</button>
                <button
                    @click="currentZoom = '80'; document.documentElement.style.zoom = '80%'; localStorage.setItem('appZoom', '80')"
                    :class="currentZoom == '80' ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="flex-1 py-1.5 rounded-lg text-xs font-medium transition-colors">80%</button>
                <button
                    @click="currentZoom = '90'; document.documentElement.style.zoom = '90%'; localStorage.setItem('appZoom', '90')"
                    :class="currentZoom == '90' ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="flex-1 py-1.5 rounded-lg text-xs font-medium transition-colors">90%</button>
                <button
                    @click="currentZoom = '100'; document.documentElement.style.zoom = '100%'; localStorage.setItem('appZoom', '100')"
                    :class="currentZoom == '100' ? 'bg-[#0053C5] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="flex-1 py-1.5 rounded-lg text-xs font-medium transition-colors">100%</button>
            </div>
            {{-- Reset Button --}}
            <button
                @click="currentZoom = '80'; document.documentElement.style.zoom = '80%'; localStorage.setItem('appZoom', '80')"
                class="w-full mt-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-medium rounded-lg transition-colors">
                Reset ke 80%
            </button>
        </div>
    </div>

    {{-- Main Application Container --}}
    <div x-data="{
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        init() {
            this.$watch('sidebarCollapsed', value => {
                localStorage.setItem('sidebarCollapsed', value)
            })
        }
    }" class="min-h-screen flex">

        {{-- Sidebar --}}
        @include('admin.layouts.partials.sidebar')

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col min-h-screen main-content-transition"
            :class="sidebarCollapsed ? 'lg:ml-0' : 'lg:ml-0'">

            {{-- Header --}}
            @include('admin.layouts.partials.header')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-green-400 hover:text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-400 hover:text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('warning'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                        </div>
                        <button @click="show = false" class="text-yellow-400 hover:text-yellow-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('info'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                        </div>
                        <button @click="show = false" class="text-blue-400 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-800">Terdapat {{ $errors->count() }}
                                    kesalahan:</p>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button @click="show = false" class="text-red-400 hover:text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="bg-white border-t border-gray-200 px-6 py-4">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} Panitia Ramadhan 1447H. All rights reserved.</p>
                    <p class="flex items-center gap-1">
                        Made with
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                        </svg>
                        by DAL Army
                    </p>
                </div>
            </footer>
        </div>
    </div>

    {{-- Global Loading Overlay --}}
    <div x-data="{ loading: false }" x-on:loading.window="loading = true" x-on:loaded.window="loading = false"
        x-show="loading" x-cloak
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[200] flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 shadow-2xl flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-[#0053C5] border-t-transparent rounded-full animate-spin"></div>
            <p class="text-gray-700 font-medium">Memproses...</p>
        </div>
    </div>

    @stack('scripts')

    {{-- Initialize Zoom from localStorage --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const savedZoom = localStorage.getItem('appZoom');
            if (savedZoom) {
                document.documentElement.style.zoom = savedZoom + '%';
            } else {
                // Default 80%
                document.documentElement.style.zoom = '80%';
                localStorage.setItem('appZoom', '80');
            }
        });
    </script>
</body>

</html>
