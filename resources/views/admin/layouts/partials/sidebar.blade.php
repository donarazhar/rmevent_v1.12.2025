<aside x-show="sidebarOpen" x-transition
    class="w-64 bg-gradient-to-b from-[#0053C5] to-[#003d8f] text-white flex-shrink-0 overflow-y-auto">
    <div class="p-6">
        {{-- Logo --}}
        <div class="flex items-center space-x-3 mb-8">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <div class="font-bold text-lg">Organizational</div>
                <div class="text-xs text-blue-200">Data Bank</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="space-y-6">
            {{-- ============================================================ --}}
            {{-- DASHBOARD --}}
            {{-- ============================================================ --}}
            <div>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'hover:bg-white/10' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </div>

            {{-- ============================================================ --}}
            {{-- 1. PELAYANAN PUBLIK --}}
            {{-- ============================================================ --}}
            <div>
                <div class="px-4 mb-2">
                    <h3 class="text-xs font-semibold text-blue-200 uppercase tracking-wider">1. Pelayanan Publik</h3>
                </div>

                {{-- Frontend Management --}}
                <div x-data="{ open: {{ request()->routeIs('admin.pages.*', 'admin.sliders.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2 hover:bg-white/10 rounded-lg transition-colors">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm">Frontend Mgmt</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
                        <a href="{{ route('admin.pages.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.pages.*') ? 'bg-white/10' : '' }}">
                            Pages
                        </a>
                        <a href="{{ route('admin.sliders.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.sliders.*') ? 'bg-white/10' : '' }}">
                            Sliders/Banners
                        </a>
                        <a href="{{ route('admin.faqs.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.faqs.*') ? 'bg-white/10' : '' }}">
                            FAQs
                        </a>
                        <a href="{{ route('admin.media.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.media.*') ? 'bg-white/10' : '' }}">
                            Gallery
                        </a>
                    </div>
                </div>

                {{-- Event Management --}}
                <div x-data="{ open: {{ request()->routeIs('admin.events.*', 'admin.registrations.*') ? 'true' : 'false' }} }" class="mt-1">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2 hover:bg-white/10 rounded-lg transition-colors">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm">Event Mgmt</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
                        <a href="{{ route('admin.events.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.events.*') ? 'bg-white/10' : '' }}">
                            Events
                        </a>
                        <a href="{{ route('admin.registrations.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.registrations.*') ? 'bg-white/10' : '' }}">
                            Registrations
                        </a>
                    </div>
                </div>

                {{-- Content Management --}}
                <div x-data="{ open: {{ request()->routeIs('admin.posts.*', 'admin.categories.*', 'admin.tags.*') ? 'true' : 'false' }} }" class="mt-1">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2 hover:bg-white/10 rounded-lg transition-colors">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            <span class="text-sm">Content Mgmt</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
                        <a href="{{ route('admin.posts.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.posts.*') ? 'bg-white/10' : '' }}">
                            Blog Posts
                        </a>
                        <a href="{{ route('admin.categories.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.categories.*') ? 'bg-white/10' : '' }}">
                            Categories
                        </a>
                        <a href="{{ route('admin.tags.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.tags.*') ? 'bg-white/10' : '' }}">
                            Tags
                        </a>
                    </div>
                </div>

                {{-- Engagement --}}
                <div x-data="{ open: {{ request()->routeIs('admin.feedbacks.*', 'admin.contact-messages.*') ? 'true' : 'false' }} }" class="mt-1">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2 hover:bg-white/10 rounded-lg transition-colors">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <span class="text-sm">Engagement</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
                        <a href="{{ route('admin.feedbacks.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.feedbacks.*') ? 'bg-white/10' : '' }}">
                            Feedbacks
                        </a>
                        <a href="{{ route('admin.contact-messages.index') }}"
                            class="block px-4 py-2 text-sm hover:bg-white/10 rounded {{ request()->routeIs('admin.contact-messages.*') ? 'bg-white/10' : '' }}">
                            Contact Messages
                        </a>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- 2. MANAJEMEN KINERJA (Coming Soon Badge) --}}
            {{-- ============================================================ --}}
            <div>
                <div class="px-4 mb-2 flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-blue-200 uppercase tracking-wider">2. Manajemen Kinerja</h3>
                    <span class="px-2 py-0.5 bg-yellow-500 text-xs font-bold rounded">Soon</span>
                </div>
                <div class="px-4 py-2 text-sm text-blue-200 opacity-50">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Task & Performance
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- 3. MANAJEMEN KEUANGAN (Coming Soon) --}}
            {{-- ============================================================ --}}
            <div>
                <div class="px-4 mb-2 flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-blue-200 uppercase tracking-wider">3. Manajemen Keuangan</h3>
                    <span class="px-2 py-0.5 bg-yellow-500 text-xs font-bold rounded">Soon</span>
                </div>
                <div class="px-4 py-2 text-sm text-blue-200 opacity-50">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Budget & Cash Flow
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- 4. ASET & PENGETAHUAN (Coming Soon) --}}
            {{-- ============================================================ --}}
            <div>
                <div class="px-4 mb-2 flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-blue-200 uppercase tracking-wider">4. Aset & Pengetahuan
                    </h3>
                    <span class="px-2 py-0.5 bg-yellow-500 text-xs font-bold rounded">Soon</span>
                </div>
                <div class="px-4 py-2 text-sm text-blue-200 opacity-50">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Inventory & SOP
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- 5. ANALISIS DATA (Coming Soon) --}}
            {{-- ============================================================ --}}
            <div>
                <div class="px-4 mb-2 flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-blue-200 uppercase tracking-wider">5. Analisis Data</h3>
                    <span class="px-2 py-0.5 bg-yellow-500 text-xs font-bold rounded">Soon</span>
                </div>
                <div class="px-4 py-2 text-sm text-blue-200 opacity-50">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Reports & Analytics
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-white/20"></div>

            {{-- ============================================================ --}}
            {{-- SYSTEM ADMINISTRATION --}}
            {{-- ============================================================ --}}
            <div>
                <div class="px-4 mb-2">
                    <h3 class="text-xs font-semibold text-blue-200 uppercase tracking-wider">System</h3>
                </div>

                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center space-x-3 px-4 py-2 hover:bg-white/10 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-white/10' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm">Users</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center space-x-3 px-4 py-2 hover:bg-white/10 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-white/10' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm">Settings</span>
                </a>
            </div>
        </nav>
    </div>
</aside>