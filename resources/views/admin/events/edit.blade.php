@extends('admin.layouts.app')

@section('title', 'Edit Event: ' . $event->title)

@section('content')
    <div x-data="eventForm()">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Event</h1>
                    <p class="mt-1 text-sm text-gray-600">Update informasi event</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.events.show', $event) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Detail
                    </a>
                    <a href="{{ route('admin.events.index') }}"
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

        {{-- Info Box --}}
        <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-medium">Event ini memiliki {{ $event->registrations->count() }} registrasi</p>
                    <p class="text-sm mt-1">Berhati-hatilah saat mengubah tanggal, lokasi, atau kapasitas event.</p>
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
        <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                    <div class="space-y-4">
                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Event <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Slug saat ini: <span
                                    class="font-mono">{{ $event->slug }}</span></p>
                        </div>

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
                                        {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Singkat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" rows="3" required maxlength="500"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $event->description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maksimal 500 karakter</p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Full Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Lengkap
                            </label>
                            <textarea id="full_description" name="full_description"
                                class="w-full @error('full_description') border-red-500 @enderror">{{ old('full_description', $event->full_description) }}</textarea>
                            @error('full_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Featured Image --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Utama
                            </label>

                            {{-- Current Image --}}
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                                <img src="{{ $event->featured_image_url }}" alt="{{ $event->title }}"
                                    class="h-32 rounded-lg object-cover" id="currentImage">
                            </div>

                            {{-- Upload New --}}
                            <input type="file" name="featured_image" accept="image/*" @change="previewImage($event)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('featured_image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah gambar. Format: JPG,
                                PNG, WEBP. Maksimal 2MB</p>
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- New Image Preview --}}
                            <div x-show="imagePreview" class="mt-3">
                                <p class="text-sm text-gray-600 mb-2">Preview gambar baru:</p>
                                <img :src="imagePreview" class="h-32 rounded-lg object-cover">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Location & Date Time --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Lokasi & Waktu</h2>

                    <div class="space-y-4">
                        {{-- Location --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Lokasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location" value="{{ old('location', $event->location) }}"
                                required placeholder="Contoh: Masjid Istiqlal Jakarta"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('location') border-red-500 @enderror">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Google Maps URL --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                URL Google Maps
                            </label>
                            <input type="url" name="location_maps_url"
                                value="{{ old('location_maps_url', $event->location_maps_url) }}"
                                placeholder="https://maps.google.com/..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('location_maps_url') border-red-500 @enderror">
                            @error('location_maps_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date Time --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal & Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="start_datetime"
                                    value="{{ old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i')) }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('start_datetime') border-red-500 @enderror">
                                @error('start_datetime')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal & Waktu Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="end_datetime"
                                    value="{{ old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i')) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('end_datetime') border-red-500 @enderror">
                                @error('end_datetime')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Timezone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Zona Waktu
                            </label>
                            <select name="timezone"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="Asia/Jakarta"
                                    {{ old('timezone', $event->timezone) == 'Asia/Jakarta' ? 'selected' : '' }}>WIB
                                    (Asia/Jakarta)</option>
                                <option value="Asia/Makassar"
                                    {{ old('timezone', $event->timezone) == 'Asia/Makassar' ? 'selected' : '' }}>WITA
                                    (Asia/Makassar)</option>
                                <option value="Asia/Jayapura"
                                    {{ old('timezone', $event->timezone) == 'Asia/Jayapura' ? 'selected' : '' }}>WIT
                                    (Asia/Jayapura)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Registration Settings --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Pendaftaran</h2>

                    <div class="space-y-4">
                        {{-- Registration Open --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="is_registration_open" id="is_registration_open" value="1"
                                {{ old('is_registration_open', $event->is_registration_open) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="is_registration_open" class="ml-2 block text-sm text-gray-700">
                                Buka Pendaftaran
                            </label>
                        </div>

                        {{-- Registration Period --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pendaftaran Dibuka
                                </label>
                                <input type="datetime-local" name="registration_start"
                                    value="{{ old('registration_start', $event->registration_start ? $event->registration_start->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pendaftaran Ditutup
                                </label>
                                <input type="datetime-local" name="registration_end"
                                    value="{{ old('registration_end', $event->registration_end ? $event->registration_end->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        {{-- Max Participants --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Maksimal Peserta
                            </label>
                            <input type="number" name="max_participants"
                                value="{{ old('max_participants', $event->max_participants) }}"
                                placeholder="Kosongkan untuk unlimited"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('max_participants') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">
                                Peserta saat ini: {{ $event->current_participants }}
                                @if ($event->max_participants)
                                    / {{ $event->max_participants }}
                                @endif
                            </p>
                            @error('max_participants')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Harga Event</h2>

                    <div class="space-y-4">
                        {{-- Is Free --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="is_free" id="is_free" value="1" x-model="isFree"
                                {{ old('is_free', $event->is_free) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="is_free" class="ml-2 block text-sm text-gray-700">
                                Event Gratis
                            </label>
                        </div>

                        {{-- Price --}}
                        <div x-show="!isFree">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Harga Pendaftaran <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-gray-500">Rp</span>
                                <input type="number" name="price" value="{{ old('price', $event->price) }}"
                                    min="0"
                                    class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('price') border-red-500 @enderror">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Narahubung</label>
                            <input type="text" name="contact_person"
                                value="{{ old('contact_person', $event->contact_person) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                            <input type="text" name="contact_phone"
                                value="{{ old('contact_phone', $event->contact_phone) }}" placeholder="08xx-xxxx-xxxx"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Kontak</label>
                            <input type="email" name="contact_email"
                                value="{{ old('contact_email', $event->contact_email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('contact_email') border-red-500 @enderror">
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Additional Info --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tambahan</h2>

                    <div class="space-y-4">
                        {{-- Requirements --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Persyaratan Peserta
                            </label>
                            <textarea name="requirements" rows="4" placeholder="Contoh: Membawa KTP, Membawa alat tulis, dll"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('requirements', $event->requirements) }}</textarea>
                        </div>

                        {{-- Tags --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <select name="tags[]" multiple
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                        {{ in_array($tag->id, old('tags', $event->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                Selected:
                                @forelse($event->tags as $tag)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                        {{ $tag->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-400">Tidak ada tags</span>
                                @endforelse
                            </p>
                            <p class="mt-1 text-sm text-gray-500">Tahan Ctrl/Cmd untuk pilih multiple tags</p>
                        </div>

                        {{-- Gallery Images --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gallery Images
                            </label>

                            {{-- Current Gallery --}}
                            @if ($event->media && $event->media->count() > 0)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 mb-2">Gallery saat ini ({{ $event->media->count() }}
                                        gambar):</p>
                                    <div class="grid grid-cols-6 gap-2">
                                        @foreach ($event->media as $media)
                                            <div class="relative group">
                                                <img src="{{ $media->url }}" alt="Gallery"
                                                    class="w-full h-20 object-cover rounded">
                                                <div
                                                    class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                                                    <span class="text-white text-xs">{{ $media->file_size_human }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Untuk menghapus gambar, gunakan Media Library</p>
                                </div>
                            @endif

                            {{-- Upload New --}}
                            <input type="file" name="gallery_images[]" accept="image/*" multiple
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Upload gambar baru untuk menambah ke gallery</p>
                        </div>
                    </div>
                </div>

                {{-- Status & SEO --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status & SEO</h2>

                    <div class="space-y-4">
                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Event <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>
                                    Draft</option>
                                <option value="published"
                                    {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="cancelled"
                                    {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed"
                                    {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Status saat ini: <span
                                    class="font-medium">{{ ucfirst($event->status) }}</span></p>
                        </div>

                        {{-- Featured --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                Event Unggulan (Featured)
                            </label>
                        </div>

                        {{-- Meta Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title (SEO)</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $event->meta_title) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Kosongkan untuk menggunakan judul event</p>
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description (SEO)</label>
                            <textarea name="meta_description" rows="3" maxlength="500"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('meta_description', $event->meta_description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Kosongkan untuk menggunakan deskripsi singkat</p>
                        </div>
                    </div>
                </div>

                {{-- Meta Information (Read-only) --}}
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Meta</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Dibuat</p>
                            <p class="font-medium text-gray-900">{{ $event->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Terakhir Diupdate</p>
                            <p class="font-medium text-gray-900">{{ $event->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Views</p>
                            <p class="font-medium text-gray-900">{{ number_format($event->views_count ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <a href="{{ route('admin.events.show', $event) }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Event
                    </button>
                </div>
            </div>
        </form>

        {{-- Delete Form - SEPARATE FROM UPDATE FORM --}}
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-red-200 p-6">
            <h3 class="text-lg font-semibold text-red-900 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-4">
                Tindakan ini akan menghapus event secara permanen dan tidak dapat dibatalkan.
                @if ($event->registrations->count() > 0)
                    Event ini memiliki <strong>{{ $event->registrations->count() }} registrasi</strong> yang akan ikut
                    terhapus.
                @endif
            </p>
            <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                onsubmit="return confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus event ini?\n\nEvent memiliki {{ $event->registrations->count() }} registrasi yang akan ikut terhapus.\n\nTindakan ini tidak dapat dibatalkan!')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Event Permanen
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
       {{-- TinyMCE Self-Hosted (No API Key Required) --}}
       <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
       
        <script>
            function eventForm() {
                return {
                    isFree: {{ old('is_free', $event->is_free) ? 'true' : 'false' }},
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

            // Initialize TinyMCE after DOM loaded
            document.addEventListener('DOMContentLoaded', function() {
                tinymce.init({
                    selector: '#full_description',
                    height: 400,
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

                    // Styling
                    font_family_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,palatino; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times; Verdana=verdana,geneva',
                    font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',

                    // Hindari auto-resize
                    resize: true,

                    // Setup untuk integrasi dengan form
                    setup: function(editor) {
                        // Sync content before form submit
                        editor.on('change', function() {
                            editor.save();
                        });
                    },

                    // Hindari prompts
                    automatic_uploads: false,
                    file_picker_types: 'image',

                    // Paste configuration
                    paste_data_images: true,

                    // Link configuration
                    link_assume_external_targets: true,
                    link_default_protocol: 'https',

                    // Accessibility
                    branding: false,
                });
            });
        </script>
    @endpush
@endsection
