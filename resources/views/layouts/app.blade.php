<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Ecosystem Digital Terpadu Ramadhan 1447 H - Platform digital untuk kajian, tilawah, dan kegiatan spiritual Ramadhan">
    <meta name="keywords" content="ramadhan, kajian, tilawah, islamic, event, ramadan">
    <meta name="author" content="Ecosystem Digital Ramadhan">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="@yield('title', 'Ecosystem Digital Ramadhan 1447 H')">
    <meta property="og:description"
        content="Platform digital terpadu untuk kajian, tilawah, dan kegiatan spiritual Ramadhan">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <title>@yield('title', 'Ecosystem Digital Ramadhan 1447 H')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Tailwind CSS CDN (untuk development - ganti dengan compiled version di production) --}}
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
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Loading Spinner */
        .loading-spinner {
            border: 3px solid rgba(0, 83, 197, 0.1);
            border-radius: 50%;
            border-top: 3px solid #0053C5;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased bg-gray-50">
    {{-- ============================================================================ --}}
    {{-- NAVIGATION BAR --}}
    {{-- ============================================================================ --}}
    <nav x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 20"
        :class="scrolled ? 'bg-white shadow-lg' : 'bg-gradient-to-r from-[#0053C5] to-[#003d8f]'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">

        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        {{-- Logo Image (jika ada) --}}
                        <div class="w-10 h-10 rounded-full flex items-center justify-center"
                            :class="scrolled ? 'bg-gradient-to-r from-[#0053C5] to-[#003d8f]' : 'bg-white/20'">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <div :class="scrolled ? 'text-gray-900' : 'text-white'" class="text-xl font-bold">
                                Ramadhan 1447 H
                            </div>
                            <div :class="scrolled ? 'text-gray-500' : 'text-blue-100'" class="text-xs">
                                Ecosystem Digital
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-[#0053C5]' : 'text-white hover:text-blue-200'"
                        class="font-medium {{ request()->routeIs('home') ? 'font-bold' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('events.index') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-[#0053C5]' : 'text-white hover:text-blue-200'"
                        class="font-medium {{ request()->routeIs('events.*') ? 'font-bold' : '' }}">
                        Event
                    </a>
                    <a href="{{ route('posts.index') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-[#0053C5]' : 'text-white hover:text-blue-200'"
                        class="font-medium {{ request()->routeIs('posts.*') ? 'font-bold' : '' }}">
                        Artikel
                    </a>
                    <a href="{{ route('feedback.testimonials') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-[#0053C5]' : 'text-white hover:text-blue-200'"
                        class="font-medium {{ request()->routeIs('feedback.*') ? 'font-bold' : '' }}">
                        Testimoni
                    </a>
                    {{-- <a href="{{ route('about') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-[#0053C5]' : 'text-white hover:text-blue-200'"
                        class="font-medium {{ request()->routeIs('about') ? 'font-bold' : '' }}">
                        Tentang
                    </a>
                    <a href="{{ route('contact') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-[#0053C5]' : 'text-white hover:text-blue-200'"
                        class="font-medium {{ request()->routeIs('contact') ? 'font-bold' : '' }}">
                        Kontak
                    </a> --}}

                    {{-- Desktop Navigation - sebelum Auth Buttons --}}
                    <div class="hidden md:flex items-center space-x-2">
                        {{-- Search Form --}}
                        <form action="{{ route('search') }}" method="GET" class="relative">
                            <input type="text" name="q" placeholder="Search..."
                                :class="scrolled ? 'bg-gray-100 text-gray-900' : 'bg-white/20 text-white placeholder-white/70'"
                                class="pl-10 pr-4 py-2 rounded-full w-64 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all">
                            <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5" :class="scrolled ? 'text-gray-500' : 'text-white/70'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    
                    {{-- Auth Buttons --}}
                    @auth
                        <div x-data="{ profileOpen: false }" class="relative">
                            <button @click="profileOpen = !profileOpen"
                                class="flex items-center space-x-2 px-4 py-2 rounded-full"
                                :class="scrolled ? 'bg-gray-100 hover:bg-gray-200' : 'bg-white/20 hover:bg-white/30'">
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-r from-[#0053C5] to-[#003d8f] flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span :class="scrolled ? 'text-gray-700' : 'text-white'" class="font-medium">
                                    {{ auth()->user()->name }}
                                </span>
                                <svg class="w-4 h-4" :class="scrolled ? 'text-gray-700' : 'text-white'" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Dashboard
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Profil Saya
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Event Saya
                                </a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- <a href="{{ route('login') }}"
                            :class="scrolled ? 'text-gray-700 hover:text-[#0053C5]' : 'text-white hover:text-blue-200'"
                            class="font-medium">
                            Login
                        </a> --}}
                        <a href="{{ route('login') }}" class="px-6 py-2 rounded-full font-semibold"
                            :class="scrolled ? 'bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white hover:shadow-lg' :
                                'bg-white text-[#0053C5] hover:bg-blue-50'">
                            Login
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        :class="scrolled ? 'text-gray-700' : 'text-white'" class="p-2">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden pb-4">
                <div class="space-y-2">
                    <a href="{{ route('home') }}" :class="scrolled ? 'text-gray-700' : 'text-white'"
                        class="block py-2 font-medium {{ request()->routeIs('home') ? 'font-bold' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('events.index') }}" :class="scrolled ? 'text-gray-700' : 'text-white'"
                        class="block py-2 font-medium {{ request()->routeIs('events.*') ? 'font-bold' : '' }}">
                        Event
                    </a>
                    <a href="{{ route('posts.index') }}" :class="scrolled ? 'text-gray-700' : 'text-white'"
                        class="block py-2 font-medium {{ request()->routeIs('posts.*') ? 'font-bold' : '' }}">
                        Artikel
                    </a>
                    <a href="{{ route('feedback.testimonials') }}" :class="scrolled ? 'text-gray-700' : 'text-white'"
                        class="block py-2 font-medium {{ request()->routeIs('feedback.*') ? 'font-bold' : '' }}">
                        Testimoni
                    </a>
                    {{-- <a href="{{ route('about') }}" :class="scrolled ? 'text-gray-700' : 'text-white'"
                        class="block py-2 font-medium {{ request()->routeIs('about') ? 'font-bold' : '' }}">
                        Tentang
                    </a>
                    <a href="{{ route('contact') }}" :class="scrolled ? 'text-gray-700' : 'text-white'"
                        class="block py-2 font-medium {{ request()->routeIs('contact') ? 'font-bold' : '' }}">
                        Kontak
                    </a> --}}

                    @auth
                        <hr :class="scrolled ? 'border-gray-300' : 'border-white/20'" class="my-2">
                        <a href="{{ route('admin.dashboard') }}" :class="scrolled ? 'text-gray-700' : 'text-white'"
                            class="block py-2 font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" :class="scrolled ? 'text-red-600' : 'text-red-300'"
                                class="block w-full text-left py-2 font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <hr :class="scrolled ? 'border-gray-300' : 'border-white/20'" class="my-2">
                        {{-- <a href="{{ route('login') }}" :class="scrolled ? 'text-gray-700' : 'text-white'"
                            class="block py-2 font-medium">
                            Login
                        </a> --}}
                        <a href="{{ route('login') }}"
                            class="block mt-2 px-4 py-2 rounded-full font-semibold text-center"
                            :class="scrolled ? 'bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white' :
                                'bg-white text-[#0053C5]'">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Spacer for fixed navbar --}}
    <div class="h-20"></div>

    {{-- ============================================================================ --}}
    {{-- FLASH MESSAGES --}}
    {{-- ============================================================================ --}}
    @if (session('success') || session('error') || session('info') || session('warning'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-24 right-4 z-50 max-w-md">
            @if (session('success'))
                <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('info') }}</span>
                    <button @click="show = false" class="ml-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('warning'))
                <div class="bg-yellow-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('warning') }}</span>
                    <button @click="show = false" class="ml-auto">
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
    <main>
        @yield('content')
    </main>

    {{-- ============================================================================ --}}
    {{-- FOOTER --}}
    {{-- ============================================================================ --}}
    <footer class="bg-gradient-to-br from-[#002a5c] via-[#003d8f] to-[#0053C5] text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                {{-- About Section --}}
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Ramadhan 1447 H</h3>
                            <p class="text-sm text-blue-200">Ecosystem Digital</p>
                        </div>
                    </div>
                    <p class="text-blue-100 text-sm leading-relaxed mb-4">
                        Platform digital terpadu untuk mendukung kegiatan spiritual, kajian, dan tilawah selama bulan
                        Ramadhan yang penuh berkah.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-lg font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-blue-100">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('events.index') }}"
                                class="hover:text-white transition-colors">Event</a></li>
                        <li><a href="{{ route('posts.index') }}"
                                class="hover:text-white transition-colors">Artikel</a></li>
                        <li><a href="{{ route('feedback.testimonials') }}"
                                class="hover:text-white transition-colors">Testimoni</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang
                                Kami</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h4 class="text-lg font-bold mb-4">Kategori Event</h4>
                    <ul class="space-y-2 text-blue-100">
                        <li><a href="#" class="hover:text-white transition-colors">Kajian Ramadhan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tilawah Al-Qur'an</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Dzikir & Doa</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tarawih Berjamaah</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sahur On The Road</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Buka Puasa Bersama</a></li>
                    </ul>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h4 class="text-lg font-bold mb-4">Kontak Kami</h4>
                    <ul class="space-y-3 text-blue-100">
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Jl. Contoh No. 123, Jakarta Selatan 12345</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>info@ramadhan1447.id</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                            </svg>
                            <span>+62 812-3456-7890</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="border-t border-white/20 pt-8 mt-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-blue-100 text-sm text-center md:text-left">
                        © {{ date('Y') }} Ecosystem Digital Ramadhan 1447 H. All rights reserved.
                    </div>
                    <div class="flex flex-wrap justify-center gap-4 text-sm text-blue-100">
                        <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy
                            Policy</a>
                        <span>•</span>
                        <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of
                            Service</a>
                        <span>•</span>
                        <a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- ============================================================================ --}}
    {{-- SCROLL TO TOP BUTTON --}}
    {{-- ============================================================================ --}}
    <button x-data="{ show: false }" @scroll.window="show = window.pageYOffset > 300" x-show="show"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })" x-cloak x-transition
        class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white rounded-full shadow-xl hover:shadow-2xl transform hover:scale-110 transition-all duration-300 flex items-center justify-center z-40">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

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

        // Add active class to current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('nav a');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('font-bold');
                }
            });
        });
    </script>
</body>

</html>
