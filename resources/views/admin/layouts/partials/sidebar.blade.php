{{-- File: resources/views/layouts/partials/sidebar.blade.php --}}

{{-- Mobile Overlay --}}
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden" x-cloak>
</div>

{{-- Sidebar --}}
<aside x-cloak
    :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen && window.innerWidth < 1024,
        'w-72': !sidebarCollapsed,
        'w-20': sidebarCollapsed
    }"
    class="fixed lg:static inset-y-0 left-0 z-50 bg-gradient-to-b from-[#0053C5] via-[#004AB0] to-[#003280] text-white transform transition-all duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-2xl">

    {{-- Header --}}
    <div class="flex-shrink-0 px-4 py-5 border-b border-white/10">
        <div class="flex items-center" :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <h1 class="font-bold text-lg tracking-tight whitespace-nowrap">Ramadhan 1447H</h1>
                    <p class="text-xs text-blue-200">Panitia Dashboard</p>
                </div>
            </div>
            {{-- Close button for mobile --}}
            <button @click="sidebarOpen = false" x-show="!sidebarCollapsed"
                class="lg:hidden p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Collapse Toggle Button (Desktop Only) --}}
    <button @click="sidebarCollapsed = !sidebarCollapsed"
        class="hidden lg:flex absolute -right-3 top-20 w-6 h-6 bg-[#0053C5] border-2 border-white/20 rounded-full items-center justify-center text-white hover:bg-[#004AB0] transition-colors shadow-lg z-10">
        <svg class="w-3 h-3 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    {{-- Navigation Container --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6 sidebar-scrollbar">

        {{-- Dashboard --}}
        <div>
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200
                      {{ request()->routeIs('admin.dashboard')
                          ? 'bg-white text-[#0053C5] shadow-lg'
                          : 'text-white/90 hover:bg-white/10' }}"
                :class="sidebarCollapsed ? 'justify-center px-2' : ''" x-data x-tooltip.raw="Dashboard"
                :x-tooltip.disable="!sidebarCollapsed">
                <div
                    class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'bg-[#0053C5]/10' : 'bg-white/10' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Dashboard</span>
            </a>
        </div>

        {{-- ============================================================ --}}
        {{-- 1. PELAYANAN PUBLIK --}}
        {{-- ============================================================ --}}
        <div class="space-y-1">
            <h3 x-show="!sidebarCollapsed"
                class="px-4 text-[11px] font-semibold text-blue-200/70 uppercase tracking-wider mb-2">
                Pelayanan Publik
            </h3>
            <div x-show="sidebarCollapsed" class="px-4 mb-2">
                <div class="border-t border-white/20"></div>
            </div>

            {{-- Frontend Management --}}
            <x-sidebar-dropdown-collapsible title="Frontend Mgmt"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'
                :active="request()->routeIs('admin.pages.*', 'admin.sliders.*', 'admin.faqs.*', 'admin.media.*')">
                <x-sidebar-link href="{{ route('admin.pages.index') }}" :active="request()->routeIs('admin.pages.*')">Pages</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.sliders.index') }}"
                    :active="request()->routeIs('admin.sliders.*')">Sliders/Banners</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.faqs.index') }}" :active="request()->routeIs('admin.faqs.*')">FAQs</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.media.index') }}" :active="request()->routeIs('admin.media.*')">Gallery</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>

            {{-- Event Management --}}
            <x-sidebar-dropdown-collapsible title="Event Mgmt"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'
                :active="request()->routeIs('admin.events.*', 'admin.registrations.*')">
                <x-sidebar-link href="{{ route('admin.events.index') }}" :active="request()->routeIs('admin.events.*')">Events</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.registrations.index') }}"
                    :active="request()->routeIs('admin.registrations.*')">Registrations</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>

            {{-- Content Management --}}
            <x-sidebar-dropdown-collapsible title="Content Mgmt"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'
                :active="request()->routeIs('admin.posts.*', 'admin.categories.*', 'admin.tags.*')">
                <x-sidebar-link href="{{ route('admin.posts.index') }}" :active="request()->routeIs('admin.posts.*')">Blog Posts</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.categories.index') }}"
                    :active="request()->routeIs('admin.categories.*')">Categories</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.tags.index') }}" :active="request()->routeIs('admin.tags.*')">Tags</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>

            {{-- Engagement --}}
            <x-sidebar-dropdown-collapsible title="Engagement"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>'
                :active="request()->routeIs('admin.feedbacks.*', 'admin.contact-messages.*')">
                <x-sidebar-link href="{{ route('admin.feedbacks.index') }}"
                    :active="request()->routeIs('admin.feedbacks.*')">Feedbacks</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.contact-messages.index') }}" :active="request()->routeIs('admin.contact-messages.*')">Contact
                    Messages</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>
        </div>

        {{-- ============================================================ --}}
        {{-- 2. MANAJEMEN KINERJA --}}
        {{-- ============================================================ --}}
        <div class="space-y-1">
            <h3 x-show="!sidebarCollapsed"
                class="px-4 text-[11px] font-semibold text-blue-200/70 uppercase tracking-wider mb-2">
                Manajemen Kinerja
            </h3>
            <div x-show="sidebarCollapsed" class="px-4 mb-2">
                <div class="border-t border-white/20"></div>
            </div>

            {{-- Kepanitiaan --}}
            <x-sidebar-dropdown-collapsible title="Kepanitiaan"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>'
                :active="request()->routeIs('admin.committee.*', 'admin.jobdescs.*', 'admin.evaluations.*')">
                <x-sidebar-link href="{{ route('admin.committee.structure.index') }}" :active="request()->routeIs('admin.committee.structure.index')">Struktur
                    Organisasi</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.jobdescs.index') }}" :active="request()->routeIs('admin.jobdescs.*')">Jobdesc &
                    Assignment</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.evaluations.index') }}" :active="request()->routeIs('admin.evaluations.*')">Performance
                    Evaluation</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>

            {{-- Timeline & Milestone --}}
            <x-sidebar-dropdown-collapsible title="Timeline & Milestone"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>'
                :active="request()->routeIs('admin.timeline.*', 'admin.milestone.*', 'admin.progress-reports.*')">
                <x-sidebar-link href="{{ route('admin.timeline.index') }}" :active="request()->routeIs('admin.timeline.index')">Project
                    Timeline</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.milestone.index') }}" :active="request()->routeIs('admin.milestone.*')">Milestone
                    Tracking</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.progress-reports.index') }}" :active="request()->routeIs('admin.progress-reports.*')">Progress
                    Reports</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>
        </div>

        {{-- ============================================================ --}}
        {{-- 3. MANAJEMEN KEUANGAN --}}
        {{-- ============================================================ --}}
        <div class="space-y-1">
            <h3 x-show="!sidebarCollapsed"
                class="px-4 text-[11px] font-semibold text-blue-200/70 uppercase tracking-wider mb-2">
                Manajemen Keuangan
            </h3>
            <div x-show="sidebarCollapsed" class="px-4 mb-2">
                <div class="border-t border-white/20"></div>
            </div>

            {{-- Perencanaan --}}
            <x-sidebar-dropdown-collapsible title="Perencanaan"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>'
                :active="request()->routeIs('admin.budgets.*', 'admin.budget-allocations.*', 'admin.sponsorships.*')">
                <x-sidebar-link href="{{ route('admin.budgets.index') }}" :active="request()->routeIs('admin.budgets.*')">Budget Planning
                    (RAB)</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.budget-allocations.index') }}" :active="request()->routeIs('admin.budget-allocations.*')">Budget
                    Allocation</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.sponsorships.index') }}" :active="request()->routeIs('admin.sponsorships.*')">Sponsorship
                    Management</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>

            {{-- Transaksi --}}
            <x-sidebar-dropdown-collapsible title="Transaksi"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>'
                :active="request()->routeIs('admin.incomes.*', 'admin.expenses.*', 'admin.cash-flow.*')">
                <x-sidebar-link href="{{ route('admin.incomes.index') }}" :active="request()->routeIs('admin.incomes.*')">Income
                    (Pemasukan)</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.expenses.index') }}" :active="request()->routeIs('admin.expenses.*')">Expenses
                    (Pengeluaran)</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.cash-flow.index') }}" :active="request()->routeIs('admin.cash-flow.*')">Cash
                    Flow</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>

            {{-- Laporan Keuangan --}}
            <x-sidebar-dropdown-collapsible title="Laporan Keuangan"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
                :active="request()->routeIs('admin.financial-reports.*')">
                <x-sidebar-link href="{{ route('admin.financial-reports.budget-vs-actual') }}"
                    :active="request()->routeIs('admin.financial-reports.budget-vs-actual')">Budget vs Actual</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.financial-reports.income-statement') }}"
                    :active="request()->routeIs('admin.financial-reports.income-statement')">Income Statement</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.financial-reports.variance-analysis') }}"
                    :active="request()->routeIs('admin.financial-reports.variance-analysis')">Variance Analysis</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.financial-reports.summary') }}" :active="request()->routeIs('admin.financial-reports.summary')">Financial
                    Summary</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>
        </div>

        {{-- ============================================================ --}}
        {{-- 4. ASET & PENGETAHUAN --}}
        {{-- ============================================================ --}}
        <div class="space-y-1">
            <h3 x-show="!sidebarCollapsed"
                class="px-4 text-[11px] font-semibold text-blue-200/70 uppercase tracking-wider mb-2">
                Aset & Pengetahuan
            </h3>
            <div x-show="sidebarCollapsed" class="px-4 mb-2">
                <div class="border-t border-white/20"></div>
            </div>

            {{-- Dokumentasi --}}
            <x-sidebar-dropdown-collapsible title="Dokumentasi"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'
                :active="request()->routeIs(
                    'admin.sops.*',
                    'admin.work-instructions.*',
                    'admin.templates.*',
                    'admin.documents.*',
                )">
                <x-sidebar-link href="{{ route('admin.sops.index') }}" :active="request()->routeIs('admin.sops.*')">SOP Library</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.work-instructions.index') }}" :active="request()->routeIs('admin.work-instructions.*')">Work
                    Instructions</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.templates.index') }}" :active="request()->routeIs('admin.templates.*')">Templates
                    Library</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.documents.index') }}" :active="request()->routeIs('admin.documents.*')">Documentation
                    Repository</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>

            {{-- Administrasi --}}
            <x-sidebar-dropdown-collapsible title="Administrasi"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
                :active="request()->routeIs(
                    'admin.proposals.*',
                    'admin.meeting-minutes.*',
                    'admin.contracts.*',
                    'admin.official-letters.*',
                )">
                <x-sidebar-link href="{{ route('admin.proposals.index') }}" :active="request()->routeIs('admin.proposals.*')">Proposals &
                    Reports</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.meeting-minutes.index') }}" :active="request()->routeIs('admin.meeting-minutes.*')">Meeting
                    Minutes</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.contracts.index') }}" :active="request()->routeIs('admin.contracts.*')">Contracts &
                    Agreements</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.official-letters.index') }}" :active="request()->routeIs('admin.official-letters.*')">Official
                    Letters</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>
        </div>

        {{-- ============================================================ --}}
        {{-- 5. ANALISIS & LAPORAN --}}
        {{-- ============================================================ --}}
        <div class="space-y-1">
            <h3 x-show="!sidebarCollapsed"
                class="px-4 text-[11px] font-semibold text-blue-200/70 uppercase tracking-wider mb-2">
                Analisis & Laporan
            </h3>
            <div x-show="sidebarCollapsed" class="px-4 mb-2">
                <div class="border-t border-white/20"></div>
            </div>

            {{-- Analytics Dashboard --}}
            <x-sidebar-dropdown-collapsible title="Analytics Dashboard"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'
                :active="request()->routeIs('admin.analytics.*')">
                <x-sidebar-link href="{{ route('admin.analytics.event') }}" :active="request()->routeIs('admin.analytics.event')">Event
                    Analytics</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.analytics.registration') }}" :active="request()->routeIs('admin.analytics.registration')">Registration
                    Trends</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.analytics.financial') }}" :active="request()->routeIs('admin.analytics.financial')">Financial
                    Trends</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.analytics.performance') }}" :active="request()->routeIs('admin.analytics.performance')">Performance
                    Metrics</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>

            {{-- Report Builder --}}
            <x-sidebar-dropdown-collapsible title="Report Builder"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
                :active="request()->routeIs('admin.reports.*')">
                <x-sidebar-link href="{{ route('admin.reports.custom') }}" :active="request()->routeIs('admin.reports.custom')">Custom
                    Reports</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.reports.executive-summary') }}" :active="request()->routeIs('admin.reports.executive-summary')">Executive
                    Summary</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.reports.final-event') }}" :active="request()->routeIs('admin.reports.final-event')">Final Event
                    Report</x-sidebar-link>
                <x-sidebar-link href="{{ route('admin.reports.comparative') }}" :active="request()->routeIs('admin.reports.comparative')">Comparative
                    Analysis (YoY)</x-sidebar-link>
            </x-sidebar-dropdown-collapsible>
        </div>

        {{-- Divider --}}
        <div class="px-4">
            <div class="border-t border-white/10"></div>
        </div>

        {{-- ============================================================ --}}
        {{-- SYSTEM --}}
        {{-- ============================================================ --}}
        <div class="space-y-1">
            <h3 x-show="!sidebarCollapsed"
                class="px-4 text-[11px] font-semibold text-blue-200/70 uppercase tracking-wider mb-2">
                System
            </h3>
            <div x-show="sidebarCollapsed" class="px-4 mb-2">
                <div class="border-t border-white/20"></div>
            </div>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200
                      {{ request()->routeIs('admin.users.*')
                          ? 'bg-white/20 text-white font-medium'
                          : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                :class="sidebarCollapsed ? 'justify-center px-2' : ''" title="Users & Roles">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span x-show="!sidebarCollapsed" x-transition>Users & Roles</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200
                      {{ request()->routeIs('admin.settings.*')
                          ? 'bg-white/20 text-white font-medium'
                          : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                :class="sidebarCollapsed ? 'justify-center px-2' : ''" title="Settings">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="!sidebarCollapsed" x-transition>Settings</span>
            </a>
            <a href="{{ route('admin.activity-logs.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200
                                  {{ request()->routeIs('admin.activity-logs.*')
                                      ? 'bg-white/20 text-white font-medium'
                                      : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                :class="sidebarCollapsed ? 'justify-center px-2' : ''" title="Activity Logs">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-show="!sidebarCollapsed" x-transition>Activity Logs</span>
            </a>
        </div>
    </nav>

    {{-- Footer User Info --}}
    <div class="flex-shrink-0 p-4 border-t border-white/10 bg-[#002860]">
        <div class="flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center px-0' : 'px-2'">
            <div
                class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
            <div x-show="!sidebarCollapsed" x-transition class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-blue-200 truncate">{{ ucfirst(auth()->user()->role ?? 'Administrator') }}</p>
            </div>
        </div>
    </div>
</aside>
