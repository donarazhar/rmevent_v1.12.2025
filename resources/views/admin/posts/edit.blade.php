@extends('admin.layouts.app')

@section('title', 'Edit Post: ' . $post->title)

@section('content')
    <div x-data="postForm()">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Post</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $post->title }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('posts.show', $post->slug) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Post
                    </a>
                    <a href="{{ route('admin.posts.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <p class="font-medium">Terdapat {{ $errors->count() }} kesalahan:</p>
                        <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                    <div class="space-y-4">
                        {{-- Current Slug --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <p class="text-sm text-blue-800">
                                <span class="font-medium">Slug saat ini:</span>
                                <code class="ml-2 text-xs bg-blue-100 px-2 py-1 rounded">{{ $post->slug }}</code>
                            </p>
                        </div>

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Post <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Excerpt --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ringkasan</label>
                            <textarea name="excerpt" rows="3" maxlength="500"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $post->excerpt) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maksimal 500 karakter</p>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konten <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" class="w-full @error('content') border-red-500 @enderror">{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Featured Image --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>

                            {{-- Current Image --}}
                            @if ($post->featured_image)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 mb-2">Gambar Saat Ini:</p>
                                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                                        class="h-32 rounded-lg object-cover">
                                </div>
                            @endif

                            <input type="file" name="featured_image" accept="image/*" @change="previewImage($event)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('featured_image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WEBP. Maksimal 2MB. Kosongkan jika tidak
                                ingin mengubah.</p>
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- New Image Preview --}}
                            <div x-show="imagePreview" class="mt-3">
                                <p class="text-sm text-gray-600 mb-2">Preview Gambar Baru:</p>
                                <img :src="imagePreview" class="h-32 rounded-lg object-cover">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Category & Tags --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Kategori & Tags</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('category_id') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tags --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>

                            {{-- Current Tags Display --}}
                            @if ($post->tags->count() > 0)
                                <div class="mb-2 flex flex-wrap gap-1">
                                    <span class="text-xs text-gray-600">Tags saat ini:</span>
                                    @foreach ($post->tags as $tag)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <select name="tags[]" multiple
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                        {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Tahan Ctrl/Cmd untuk pilih multiple tags</p>
                        </div>
                    </div>
                </div>

                {{-- SEO Settings --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">SEO Settings</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}"
                                maxlength="255"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Kosongkan untuk menggunakan judul post</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" maxlength="500"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('meta_description', $post->meta_description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maksimal 500 karakter</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords"
                                value="{{ old('meta_keywords', $post->meta_keywords) }}" maxlength="255"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma</p>
                        </div>
                    </div>
                </div>

                {{-- Status & Publishing --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status & Publishing</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required x-model="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>
                                    Draft</option>
                                <option value="published"
                                    {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="scheduled"
                                    {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="archived"
                                    {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div x-show="status === 'published'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publish</label>
                            <input type="datetime-local" name="published_at"
                                value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div x-show="status === 'scheduled'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal Publish</label>
                            <input type="datetime-local" name="scheduled_at"
                                value="{{ old('scheduled_at', $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Fitur Post</h2>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                <span class="font-medium">Post Unggulan</span>
                                <span class="text-gray-500">- Tampilkan di slider atau section featured</span>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_sticky" id="is_sticky" value="1"
                                {{ old('is_sticky', $post->is_sticky) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="is_sticky" class="ml-2 block text-sm text-gray-700">
                                <span class="font-medium">Post Sticky</span>
                                <span class="text-gray-500">- Selalu tampil di atas daftar post</span>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="allow_comments" id="allow_comments" value="1"
                                {{ old('allow_comments', $post->allow_comments) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="allow_comments" class="ml-2 block text-sm text-gray-700">
                                <span class="font-medium">Izinkan Komentar</span>
                                <span class="text-gray-500">- Pengunjung dapat memberikan komentar</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Gallery Images --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Gallery Images</h2>

                    {{-- Current Gallery --}}
                    @if ($post->media->count() > 0)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Gambar Gallery Saat Ini:</p>
                            <div class="grid grid-cols-6 gap-2">
                                @foreach ($post->media as $media)
                                    <div class="relative group">
                                        <img src="{{ $media->url }}" alt="Gallery"
                                            class="h-20 w-20 object-cover rounded border border-gray-200">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Gambar Baru</label>
                        <input type="file" name="gallery_images[]" accept="image/*" multiple
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Pilih multiple gambar untuk menambah ke gallery</p>
                    </div>
                </div>

                {{-- Meta Information --}}
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Meta Information</h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Created</p>
                            <p class="font-medium text-gray-900">{{ $post->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Updated</p>
                            <p class="font-medium text-gray-900">{{ $post->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Views</p>
                            <p class="font-medium text-gray-900">{{ number_format($post->views_count) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Reading Time</p>
                            <p class="font-medium text-gray-900">{{ $post->reading_time }} menit</p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <a href="{{ route('admin.posts.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Post
                    </button>
                </div>
            </div>
        </form>

        {{-- Delete Form - SEPARATE FROM UPDATE FORM --}}
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-red-200 p-6">
            <h3 class="text-lg font-semibold text-red-900 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-4">
                Tindakan ini akan menghapus post secara permanen dan tidak dapat dibatalkan.
            </p>
            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                onsubmit="return confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus post ini?\n\nTindakan ini tidak dapat dibatalkan!')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Post Permanen
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
       {{-- TinyMCE Self-Hosted (No API Key Required) --}}
       <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
       
        <script>
            function postForm() {
                return {
                    status: '{{ old('status', $post->status) }}',
                    imagePreview: null,

                    previewImage(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.imagePreview = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }
            }

            // Initialize TinyMCE
            document.addEventListener('DOMContentLoaded', function() {
                tinymce.init({
                    selector: '#content',
                    height: 500,
                    menubar: true,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                        'bold italic underline strikethrough | forecolor backcolor | ' +
                        'alignleft aligncenter alignright alignjustify | ' +
                        'bullist numlist outdent indent | removeformat | ' +
                        'link image media table | code fullscreen preview | help',

                    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; line-height: 1.6; padding: 10px; }',

                    font_family_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,palatino; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times; Verdana=verdana,geneva',
                    font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',

                    resize: true,

                    setup: function(editor) {
                        editor.on('change', function() {
                            editor.save();
                        });
                    },

                    automatic_uploads: false,
                    file_picker_types: 'image',
                    paste_data_images: true,
                    link_assume_external_targets: true,
                    link_default_protocol: 'https',
                    branding: false,
                });
            });
        </script>
    @endpush
@endsection
