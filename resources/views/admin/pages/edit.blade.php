@extends('admin.layouts.app')

@section('title', 'Edit Page - ' . $page->title)

@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-600">Frontend Management</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.pages.index') }}" class="text-gray-600 hover:text-gray-900">Pages</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-semibold">Edit</span>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Page</h2>
                    <p class="text-gray-600 mt-1">Update page: {{ $page->title }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    {{-- View Page --}}
                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank"
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Page
                    </a>

                    <a href="{{ route('admin.pages.index') }}"
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Update Page
                    </button>
                </div>
            </div>

            {{-- Main Form Card --}}
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 space-y-6">
                    {{-- Basic Information --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Basic Information</h3>

                        <div class="grid grid-cols-1 gap-6">
                            {{-- Title --}}
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Page Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror"
                                    placeholder="Enter page title">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div>
                                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Slug
                                </label>
                                <div class="flex items-center">
                                    <span
                                        class="px-4 py-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-600">
                                        {{ url('/pages/') }}/
                                    </span>
                                    <input type="text" id="slug" name="slug"
                                        value="{{ old('slug', $page->slug) }}" readonly
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg bg-gray-50 text-gray-600">
                                </div>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Template --}}
                            <div>
                                <label for="template" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Page Template <span class="text-red-500">*</span>
                                </label>
                                <select id="template" name="template" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('template') border-red-500 @enderror">
                                    @foreach ($templates as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('template', $page->template) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('template')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Parent Page --}}
                            <div>
                                <label for="parent_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Parent Page <span class="text-gray-500 text-xs">(Optional)</span>
                                </label>
                                <select id="parent_id" name="parent_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <option value="">None (Top Level)</option>
                                    @foreach ($pages as $parentPage)
                                        <option value="{{ $parentPage->id }}"
                                            {{ old('parent_id', $page->parent_id) == $parentPage->id ? 'selected' : '' }}>
                                            {{ $parentPage->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Content</h3>

                        <div>
                            <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                                Page Content
                            </label>
                            <textarea id="content" name="content" rows="15"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('content') border-red-500 @enderror"
                                placeholder="Enter page content (HTML supported)">{{ old('content', $page->content) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">You can use HTML tags for formatting</p>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- SEO Settings --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">SEO Settings</h3>

                        <div class="space-y-4">
                            {{-- Meta Title --}}
                            <div>
                                <label for="meta_title" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Meta Title
                                </label>
                                <input type="text" id="meta_title" name="meta_title"
                                    value="{{ old('meta_title', $page->meta_title) }}" maxlength="60"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="SEO title (max 60 characters)">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to use page title</p>
                            </div>

                            {{-- Meta Description --}}
                            <div>
                                <label for="meta_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Meta Description
                                </label>
                                <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="SEO description (max 160 characters)">{{ old('meta_description', $page->meta_description) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                            </div>

                            {{-- Meta Keywords --}}
                            <div>
                                <label for="meta_keywords" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Meta Keywords
                                </label>
                                <input type="text" id="meta_keywords" name="meta_keywords"
                                    value="{{ old('meta_keywords', $page->meta_keywords) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="keyword1, keyword2, keyword3">
                                <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                            </div>
                        </div>
                    </div>

                    {{-- Settings --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Page Settings</h3>

                        <div class="space-y-4">
                            {{-- Order --}}
                            <div>
                                <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Display Order
                                </label>
                                <input type="number" id="order" name="order"
                                    value="{{ old('order', $page->order) }}" min="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <option value="draft"
                                        {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>
                                        Draft
                                    </option>
                                    <option value="published"
                                        {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>
                                        Published
                                    </option>
                                </select>
                            </div>

                            {{-- Checkboxes --}}
                            <div class="space-y-3">
                                {{-- Show in Menu --}}
                                <label class="flex items-start">
                                    <input type="checkbox" name="show_in_menu" value="1"
                                        {{ old('show_in_menu', $page->show_in_menu) ? 'checked' : '' }}
                                        class="w-5 h-5 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5] mt-0.5">
                                    <span class="ml-3">
                                        <span class="block text-sm font-semibold text-gray-700">Show in Navigation
                                            Menu</span>
                                        <span class="block text-xs text-gray-500">Display this page in the main website
                                            navigation</span>
                                    </span>
                                </label>

                                {{-- Is Homepage --}}
                                <label class="flex items-start">
                                    <input type="checkbox" name="is_homepage" value="1"
                                        {{ old('is_homepage', $page->is_homepage) ? 'checked' : '' }}
                                        class="w-5 h-5 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5] mt-0.5">
                                    <span class="ml-3">
                                        <span class="block text-sm font-semibold text-gray-700">Set as Homepage</span>
                                        <span class="block text-xs text-gray-500">Use this page as the site's homepage
                                            (will
                                            override current homepage)</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Metadata --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Page Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Created:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ $page->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Last Updated:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ $page->updated_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        {{-- Delete Button --}}
                        @unless ($page->is_homepage)
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                    Delete Page
                                </button>
                            </form>
                        @endunless
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.pages.index') }}"
                            class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-[#0053C5] to-[#003d8f] text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                            Update Page
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-generate slug from title (only if title changed)
        const originalTitle = "{{ $page->title }}";
        document.getElementById('title').addEventListener('input', function(e) {
            const title = e.target.value;
            if (title !== originalTitle) {
                const slug = title
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .trim();
                document.getElementById('slug').value = slug;
            }
        });
    </script>
@endpush
