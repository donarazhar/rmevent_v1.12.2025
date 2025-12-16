{{-- resources/views/admin/users/create.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div>
        {{-- Breadcrumb --}}
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Dashboard</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="hover:text-primary">Users</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">Tambah User</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah User Baru</h1>
                    <p class="text-sm text-gray-600 mt-1">Buat akun pengguna baru</p>
                </div>
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data"
                x-data="userForm()">
                @csrf

                <div class="p-6 space-y-6">
                    {{-- Avatar Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Avatar (Opsional)
                        </label>
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img id="avatar-preview"
                                    src="https://ui-avatars.com/api/?name=New+User&color=0053C5&background=E3F2FD"
                                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                            </div>
                            <div>
                                <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden"
                                    onchange="previewAvatar(event)">
                                <label for="avatar"
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer inline-flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Pilih Foto</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF. Max 2MB</p>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Ahmad Fauzi">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email & Phone Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror"
                                placeholder="user@ramadhanmubarak.org">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('phone') border-red-500 @enderror"
                                placeholder="+62 812-3456-7890">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Password Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('password') border-red-500 @enderror"
                                placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                placeholder="Ulangi password">
                        </div>
                    </div>

                    {{-- Role & Status Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Role --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role" x-model="selectedRole" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('role') border-red-500 @enderror">
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $value => $label)
                                    <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('status') border-red-500 @enderror">
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('status', 'active') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Conditional Fields Based on Role --}}
                    <div x-show="showPositionFields()" x-transition class="space-y-6">
                        {{-- Info Alert --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-blue-900 mb-1">Informasi Tambahan</h3>
                                    <p class="text-sm text-blue-800">
                                        Isi field di bawah sesuai dengan role yang dipilih.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Position (for Pelaksana) --}}
                        <div x-show="selectedRole === 'pelaksana'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jabatan (Pelaksana)
                            </label>
                            <select name="position"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('position') border-red-500 @enderror">
                                <option value="">Pilih Jabatan</option>
                                <option value="Ketua" {{ old('position') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                                <option value="Sekretaris" {{ old('position') == 'Sekretaris' ? 'selected' : '' }}>
                                    Sekretaris</option>
                                <option value="Bendahara" {{ old('position') == 'Bendahara' ? 'selected' : '' }}>Bendahara
                                </option>
                            </select>
                            @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Unit (for Koordinator) --}}
                        <div x-show="selectedRole === 'koordinator'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Unit Koordinasi
                            </label>
                            <input type="text" name="unit" value="{{ old('unit') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('unit') border-red-500 @enderror"
                                placeholder="Contoh: TK Islam Al Azhar 1">
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Seksi & Is Coordinator (for Panitia) --}}
                        <div x-show="selectedRole === 'panitia'" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Seksi Kegiatan
                                </label>
                                <input type="text" name="seksi" value="{{ old('seksi') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('seksi') border-red-500 @enderror"
                                    placeholder="Contoh: Perlengkapan dan Kebersihan">
                                @error('seksi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_coordinator" id="is_coordinator" value="1"
                                    {{ old('is_coordinator') ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <label for="is_coordinator" class="ml-2 text-sm text-gray-700">
                                    Koordinator Seksi
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bio (Opsional)
                        </label>
                        <textarea name="bio" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('bio') border-red-500 @enderror"
                            placeholder="Tulis bio singkat...">{{ old('bio') }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Address Section (Optional) --}}
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Alamat (Opsional)</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Lengkap
                                </label>
                                <textarea name="address" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('address') border-red-500 @enderror"
                                    placeholder="Jl. Contoh No. 123">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Kota/Kabupaten
                                    </label>
                                    <input type="text" name="city" value="{{ old('city') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('city') border-red-500 @enderror"
                                        placeholder="Jakarta Selatan">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Provinsi
                                    </label>
                                    <input type="text" name="province" value="{{ old('province') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('province') border-red-500 @enderror"
                                        placeholder="DKI Jakarta">
                                    @error('province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Kode Pos
                                    </label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary @error('postal_code') border-red-500 @enderror"
                                        placeholder="12110">
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center rounded-b-xl">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function userForm() {
            return {
                selectedRole: '{{ old('role') }}',

                showPositionFields() {
                    return ['pelaksana', 'koordinator', 'panitia'].includes(this.selectedRole);
                }
            }
        }
    </script>
@endpush
