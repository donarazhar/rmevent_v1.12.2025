@extends('admin.layouts.app')

@section('title', 'Buat Post Baru')

@section('content')
    <div x-data="postForm()">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Buat Post Baru</h1>
                    <p class="mt-1 text-sm text-gray-600">Buat konten blog baru</p>
                </div>
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
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                    <div class="space-y-4">
                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Post <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Excerpt --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ringkasan
                            </label>
                            <textarea name="excerpt" rows="3" maxlength="500"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('excerpt') border-red-500 @enderror">{{ old('excerpt') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maksimal 500 karakter. Kosongkan untuk generate otomatis
                                dari konten.</p>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konten <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" class="w-full @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Featured Image --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Utama <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="featured_image" accept="image/*" required
                                @change="previewImage($event)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('featured_image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WEBP. Maksimal 2MB</p>
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- Image Preview --}}
                            <div x-show="imagePreview" class="mt-3">
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
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <select name="tags[]" multiple
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                        {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
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
                        {{-- Meta Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="255"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Kosongkan untuk menggunakan judul post</p>
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" maxlength="500"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maksimal 500 karakter. Kosongkan untuk menggunakan
                                ringkasan.</p>
                        </div>

                        {{-- Meta Keywords --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" maxlength="255"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma. Contoh: ramadhan, event, masjid</p>
                        </div>
                    </div>
                </div>

                {{-- Status & Publishing --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status & Publishing</h2>

                    <div class="space-y-4">
                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required x-model="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled
                                </option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived
                                </option>
                            </select>
                        </div>

                        {{-- Published At --}}
                        <div x-show="status === 'published'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publish</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Kosongkan untuk menggunakan waktu sekarang</p>
                        </div>

                        {{-- Scheduled At --}}
                        <div x-show="status === 'scheduled'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal Publish</label>
                            <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Post akan otomatis publish pada waktu yang ditentukan</p>
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Fitur Post</h2>

                    <div class="space-y-3">
                        {{-- Featured --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                {{ old('is_featured') ? 'checked' : '' }}
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                <span class="font-medium">Post Unggulan</span>
                                <span class="text-gray-500">- Tampilkan di slider atau section featured</span>
                            </label>
                        </div>

                        {{-- Sticky --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="is_sticky" id="is_sticky" value="1"
                                {{ old('is_sticky') ? 'checked' : '' }}
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="is_sticky" class="ml-2 block text-sm text-gray-700">
                                <span class="font-medium">Post Sticky</span>
                                <span class="text-gray-500">- Selalu tampil di atas daftar post</span>
                            </label>
                        </div>

                        {{-- Allow Comments --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="allow_comments" id="allow_comments" value="1"
                                {{ old('allow_comments', true) ? 'checked' : '' }}
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar</label>
                        <input type="file" name="gallery_images[]" accept="image/*" multiple
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Pilih multiple gambar untuk gallery. Format: JPG, PNG, WEBP.
                            Maksimal 2MB per gambar.</p>
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
                        Simpan Post
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        {{-- TinyMCE Self-Hosted (No API Key Required) --}}
        <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>

        <script>
            function postForm() {
                return {
                    status: '{{ old('status', 'draft') }}',
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
