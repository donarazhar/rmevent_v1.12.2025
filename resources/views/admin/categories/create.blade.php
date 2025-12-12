@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div>
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Kategori</h1>
                    <p class="mt-1 text-sm text-gray-600">Buat kategori baru</p>
                </div>
                <a href="{{ route('admin.categories.index') }}"
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
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                    <div class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3" maxlength="500"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maksimal 500 karakter</p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('type') border-red-500 @enderror">
                                <option value="">Pilih Tipe</option>
                                @foreach ($types as $value => $label)
                                    <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Parent Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Parent Kategori</label>
                            <select name="parent_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Tidak Ada (Parent Category)</option>
                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id }}"
                                        {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }} ({{ ucfirst($parent->type) }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Kosongkan jika ini adalah kategori utama</p>
                        </div>
                    </div>
                </div>

                {{-- Styling --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tampilan</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Icon --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                            <input type="text" name="icon" value="{{ old('icon') }}"
                                placeholder="fa fa-folder, bi bi-tag, dll"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Contoh: fa fa-folder, bi bi-tag</p>
                        </div>

                        {{-- Color --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                            <div class="flex gap-2">
                                <input type="color" name="color" value="{{ old('color', '#3b82f6') }}"
                                    class="h-10 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" name="color_hex" value="{{ old('color', '#3b82f6') }}"
                                    placeholder="#3b82f6"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Pilih warna untuk kategori ini</p>
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan</h2>

                    <div class="space-y-4">
                        {{-- Order --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Semakin kecil nomor, semakin tinggi urutannya</p>
                        </div>

                        {{-- Is Active --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                <span class="font-medium">Aktif</span>
                                <span class="text-gray-500">- Kategori dapat digunakan</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <a href="{{ route('admin.categories.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Sync color picker with hex input
            const colorPicker = document.querySelector('input[type="color"]');
            const colorHex = document.querySelector('input[name="color_hex"]');

            if (colorPicker && colorHex) {
                colorPicker.addEventListener('input', function() {
                    colorHex.value = this.value;
                });

                colorHex.addEventListener('input', function() {
                    if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                        colorPicker.value = this.value;
                    }
                });
            }
        </script>
    @endpush
@endsection
