@extends('admin.layouts.app')

@section('title', 'Pages Management')

@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-600">Frontend Management</span>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-semibold">Pages</span>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Pages Management</h2>
                <p class="text-gray-600 mt-1">Manage static pages and landing pages</p>
            </div>
            <a href="{{ route('admin.pages.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Page
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Pages</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $pages->total() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Published</p>
                        <h3 class="text-2xl font-bold text-green-600 mt-1">
                            {{ $pages->where('status', 'published')->count() }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Draft</p>
                        <h3 class="text-2xl font-bold text-yellow-600 mt-1">
                            {{ $pages->where('status', 'draft')->count() }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">In Menu</p>
                        <h3 class="text-2xl font-bold text-purple-600 mt-1">
                            {{ $pages->where('show_in_menu', true)->count() }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            {{-- Table Header --}}
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h3 class="text-lg font-semibold text-gray-900">All Pages</h3>
                </div>
                <div class="flex items-center space-x-3">
                    {{-- Search --}}
                    <div class="relative">
                        <input type="text" placeholder="Search pages..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    {{-- Filter --}}
                    <select
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Page Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Slug
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Template
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Menu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pages as $page)
                            <tr class="hover:bg-gray-50">
                                {{-- Order --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">{{ $page->order }}</span>
                                </td>

                                {{-- Title --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                {{-- Click title to view detail in admin --}}
                                                <a href="{{ route('admin.pages.show', $page) }}"
                                                    class="text-sm font-semibold text-gray-900 hover:text-[#0053C5]">
                                                    {{ $page->title }}
                                                </a>
                                                @if ($page->is_homepage)
                                                    <span
                                                        class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                                        Homepage
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($page->parent)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Parent: {{ $page->parent->title }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Slug --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <code class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                        /{{ $page->slug }}
                                    </code>
                                </td>

                                {{-- Template --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ ucfirst($page->template) }}</span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($page->status === 'published')
                                        <span
                                            class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            Published
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                {{-- Show in Menu --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($page->show_in_menu)
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        {{-- View Detail (Admin) --}}
                                        <a href="{{ route('admin.pages.show', $page) }}"
                                            class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        {{-- View Live (Frontend) --}}
                                        <a href="{{ route('pages.show', $page->slug) }}" target="_blank"
                                            class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                            title="View Live">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.pages.edit', $page) }}"
                                            class="p-2 text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        @unless ($page->is_homepage)
                                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this page?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No pages found</h3>
                                        <p class="text-gray-600 mb-4">Get started by creating your first page</p>
                                        <a href="{{ route('admin.pages.create') }}"
                                            class="px-6 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f]">
                                            Create Page
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($pages->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $pages->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
