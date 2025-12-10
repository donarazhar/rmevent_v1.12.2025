<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Authentication - Ecosystem Digital Ramadhan 1447 H">

    <title>@yield('title', 'Authentication - Ecosystem Digital Ramadhan 1447 H')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#0053C5',
                            dark: '#003d8f',
                            darker: '#002a5c',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js for interactive components --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Custom Styles --}}
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            /* Prevent horizontal scroll */
            overflow-y: auto;
            /* Allow vertical scroll */
        }

        /* Background Pattern */
        .auth-bg {
            background: linear-gradient(135deg, #0053C5 0%, #003d8f 50%, #002a5c 100%);
            position: relative;
            min-height: 100vh;
            /* overflow: hidden removed untuk allow scrolling */
        }

        .auth-bg::before {
            content: '';
            position: fixed;
            /* Changed to fixed so it stays during scroll */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            opacity: 0.3;
            pointer-events: none;
            z-index: 0;
        }

        .auth-bg::after {
            content: '';
            position: fixed;
            /* Changed to fixed so it stays during scroll */
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(45deg,
                    transparent,
                    transparent 50px,
                    rgba(255, 255, 255, 0.03) 50px,
                    rgba(255, 255, 255, 0.03) 51px);
            animation: slide 20s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes slide {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(50px, 50px);
            }
        }

        /* Floating Animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Firefox Scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) rgba(255, 255, 255, 0.1);
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased auth-bg">
    {{-- ============================================================================ --}}
    {{-- DECORATIVE ELEMENTS --}}
    {{-- ============================================================================ --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        {{-- Floating Circles --}}
        <div class="absolute top-20 left-10 w-64 h-64 bg-white/5 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-white/5 rounded-full blur-3xl float-animation"
            style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-white/5 rounded-full blur-3xl float-animation"
            style="animation-delay: -1.5s;"></div>
    </div>

    {{-- ============================================================================ --}}
    {{-- TOP BAR (Minimal) --}}
    {{-- ============================================================================ --}}
    <div class="relative z-10">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-white/30 transition-all">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-white text-xl font-bold">Ramadhan 1447 H</div>
                        <div class="text-blue-200 text-xs">Ecosystem Digital</div>
                    </div>
                </a>

                {{-- Back to Home Button --}}
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm text-white rounded-full hover:bg-white/20 transition-all group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Kembali ke</span>
                    <span class="sm:ml-1">Beranda</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================================ --}}
    {{-- FLASH MESSAGES --}}
    {{-- ============================================================================ --}}
    @if (session('success') || session('error') || session('info') || session('warning'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-24 right-4 z-50 max-w-md">
            @if (session('success'))
                <div
                    class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center space-x-3 backdrop-blur-sm">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center space-x-3 backdrop-blur-sm">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button @click="show = false" class="flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('info'))
                <div
                    class="bg-blue-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center space-x-3 backdrop-blur-sm">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="flex-1">{{ session('info') }}</span>
                    <button @click="show = false" class="flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('warning'))
                <div
                    class="bg-yellow-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center space-x-3 backdrop-blur-sm">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="flex-1">{{ session('warning') }}</span>
                    <button @click="show = false" class="flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- ============================================================================ --}}
    {{-- MAIN CONTENT --}}
    {{-- ============================================================================ --}}
    <main class="relative z-10 py-12 px-4">
        <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
            @yield('content')
        </div>
    </main>

    {{-- ============================================================================ --}}
    {{-- FOOTER (Minimal) --}}
    {{-- ============================================================================ --}}
    <footer class="relative z-10 py-6 text-center text-white/60 text-sm">
        <div class="container mx-auto px-4">
            <p>© {{ date('Y') }} Ecosystem Digital Ramadhan 1447 H. All rights reserved.</p>
        </div>
    </footer>

    {{-- ============================================================================ --}}
    {{-- SCRIPTS --}}
    {{-- ============================================================================ --}}
    @stack('scripts')

    {{-- Global Scripts --}}
    <script>
        // Add smooth loading indicator
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });

        // Form validation feedback
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                            <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        `;
                    }
                });
            });
        });
    </script>
</body>

</html>
