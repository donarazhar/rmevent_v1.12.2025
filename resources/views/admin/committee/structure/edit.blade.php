{{-- resources/views/admin/committee/structure/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Struktur Organisasi')

@section('content')
    <div x-data="editStructureForm()" x-init="init()">
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
                    <a href="{{ route('admin.committee.structure.index') }}" class="hover:text-primary">Struktur
                        Organisasi</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">Edit Struktur</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Struktur Organisasi</h1>
                    <p class="text-sm text-gray-600 mt-1">Perbarui informasi struktur organisasi</p>
                </div>
                <a href="{{ route('admin.committee.structure.index') }}"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        {{-- Main Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form @submit.prevent="submitForm()">
                {{-- Form Header --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Struktur</h2>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Event Selection --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Event <span class="text-red-500">*</span>
                        </label>
                        <select x-model="formData.event_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="">Pilih Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ old('event_id', $structure->event_id) == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Parent Structure --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Parent Structure (Opsional)
                        </label>
                        <select x-model="formData.parent_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="">-- Top Level --</option>
                            @foreach ($availableParents as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id', $structure->parent_id) == $parent->id ? 'selected' : '' }}
                                    @if ($parent->id == $structure->id) disabled @endif>
                                    {{ $parent->full_name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika ini adalah struktur tingkat tertinggi</p>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Basic Info Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Struktur <span class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="formData.name" required
                                value="{{ old('name', $structure->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                placeholder="e.g., Divisi Acara">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Code --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode
                            </label>
                            <input type="text" x-model="formData.code" value="{{ old('code', $structure->code) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                placeholder="e.g., DIV-ACARA">
                            <p class="mt-1 text-xs text-gray-500">Kode unik untuk struktur ini</p>
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea x-model="formData.description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                            placeholder="Deskripsi singkat tentang struktur ini...">{{ old('description', $structure->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Level, Order & Status Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Level --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Level <span class="text-red-500">*</span>
                            </label>
                            <input type="number" x-model="formData.level" required min="1"
                                value="{{ old('level', $structure->level) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            <p class="mt-1 text-xs text-gray-500">Tingkat hierarki (1 = tertinggi)</p>
                            @error('level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Order --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Urutan
                            </label>
                            <input type="number" x-model="formData.order" min="0"
                                value="{{ old('order', $structure->order) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            <p class="mt-1 text-xs text-gray-500">Urutan tampilan</p>
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select x-model="formData.status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="active"
                                    {{ old('status', $structure->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive"
                                    {{ old('status', $structure->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                                <option value="archived"
                                    {{ old('status', $structure->status) == 'archived' ? 'selected' : '' }}>Archived
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Kepemimpinan</h3>
                    </div>

                    {{-- Leadership --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Leader --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ketua
                            </label>
                            <select x-model="formData.leader_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="">-- Pilih Ketua --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('leader_id', $structure->leader_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('leader_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Vice Leader --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Wakil Ketua
                            </label>
                            <select x-model="formData.vice_leader_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="">-- Pilih Wakil Ketua --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('vice_leader_id', $structure->vice_leader_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('vice_leader_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                    </div>

                    {{-- Contact Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" x-model="formData.email" value="{{ old('email', $structure->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                placeholder="divisi@ramadhanmubarak.org">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon
                            </label>
                            <input type="tel" x-model="formData.phone" value="{{ old('phone', $structure->phone) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                placeholder="+62 812-3456-7890">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Tanggung Jawab & Wewenang</h3>
                    </div>

                    {{-- Responsibilities --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggung Jawab
                        </label>
                        <div class="space-y-2">
                            <template x-for="(item, index) in formData.responsibilities" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" x-model="formData.responsibilities[index]"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Tanggung jawab...">
                                    <button type="button" @click="formData.responsibilities.splice(index, 1)"
                                        class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="formData.responsibilities.push('')"
                                class="text-sm text-primary hover:text-primary-dark font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Tanggung Jawab</span>
                            </button>
                        </div>
                        @error('responsibilities')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Authorities --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Wewenang
                        </label>
                        <div class="space-y-2">
                            <template x-for="(item, index) in formData.authorities" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" x-model="formData.authorities[index]"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                        placeholder="Wewenang...">
                                    <button type="button" @click="formData.authorities.splice(index, 1)"
                                        class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="formData.authorities.push('')"
                                class="text-sm text-primary hover:text-primary-dark font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Wewenang</span>
                            </button>
                        </div>
                        @error('authorities')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center rounded-b-xl">
                    <a href="{{ route('admin.committee.structure.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        Batal
                    </a>
                    <div class="flex gap-3">
                        <button type="button" @click="resetForm()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            Reset
                        </button>
                        <button type="submit" :disabled="loading"
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                            <span x-show="loading">
                                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span x-text="loading ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Info Card: Members --}}
        @if ($structure->members->count() > 0)
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-blue-900 mb-1">Informasi Anggota</h3>
                        <p class="text-sm text-blue-800">
                            Struktur ini memiliki <strong>{{ $structure->members->count() }} anggota</strong>.
                            Perubahan ketua atau wakil ketua akan mempengaruhi keanggotaan mereka.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Warning Card: Children --}}
        @if ($structure->children->count() > 0)
            <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-yellow-900 mb-1">Peringatan</h3>
                        <p class="text-sm text-yellow-800">
                            Struktur ini memiliki <strong>{{ $structure->children->count() }} sub-struktur</strong>.
                            Perubahan level atau parent dapat mempengaruhi hierarki seluruh sub-struktur.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function editStructureForm() {
            return {
                loading: false,

                formData: {
                    event_id: '{{ old('event_id', $structure->event_id) }}',
                    parent_id: '{{ old('parent_id', $structure->parent_id) }}',
                    name: '{{ old('name', $structure->name) }}',
                    code: '{{ old('code', $structure->code) }}',
                    description: `{{ old('description', $structure->description) }}`,
                    level: {{ old('level', $structure->level) }},
                    order: {{ old('order', $structure->order) ?? 'null' }},
                    leader_id: '{{ old('leader_id', $structure->leader_id) }}',
                    vice_leader_id: '{{ old('vice_leader_id', $structure->vice_leader_id) }}',
                    email: '{{ old('email', $structure->email) }}',
                    phone: '{{ old('phone', $structure->phone) }}',
                    status: '{{ old('status', $structure->status) }}',
                    responsibilities: {!! json_encode(old('responsibilities', $structure->responsibilities ?? [])) !!},
                    authorities: {!! json_encode(old('authorities', $structure->authorities ?? [])) !!}
                },

                init() {
                    // Ensure arrays are initialized
                    if (!Array.isArray(this.formData.responsibilities)) {
                        this.formData.responsibilities = [];
                    }
                    if (!Array.isArray(this.formData.authorities)) {
                        this.formData.authorities = [];
                    }
                },

                async submitForm() {
                    if (this.loading) return;

                    this.loading = true;

                    // Clean empty items from arrays
                    this.formData.responsibilities = this.formData.responsibilities.filter(r => r && r.trim());
                    this.formData.authorities = this.formData.authorities.filter(a => a && a.trim());

                    try {
                        const response = await fetch(
                        '{{ route('admin.committee.structure.update', $structure->id) }}', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showNotification('success', data.message);
                            setTimeout(() => {
                                window.location.href = '{{ route('admin.committee.structure.index') }}';
                            }, 1000);
                        } else {
                            this.showNotification('error', data.message || 'Terjadi kesalahan');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.showNotification('error', 'Gagal menyimpan data');
                    } finally {
                        this.loading = false;
                    }
                },

                resetForm() {
                    if (confirm('Reset semua perubahan? Data yang belum disimpan akan hilang.')) {
                        window.location.reload();
                    }
                },

                showNotification(type, message) {
                    if (type === 'success') {
                        alert('✓ ' + message);
                    } else {
                        alert('✗ ' + message);
                    }
                }
            }
        }
    </script>
@endpush
