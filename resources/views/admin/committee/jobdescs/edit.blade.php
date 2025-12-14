{{-- resources/views/admin/jobdescs/edit.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Edit Job Description')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Job Description</h1>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi job description</p>
            </div>
            <a href="{{ route('admin.jobdescs.show', $jobdesc) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.jobdescs.update', $jobdesc) }}" method="POST" class="space-y-6"
            x-data="jobDescForm()">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Structure --}}
                    <div>
                        <label for="structure_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Divisi/Struktur <span class="text-red-500">*</span>
                        </label>
                        <select name="structure_id" id="structure_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('structure_id') border-red-500 @enderror">
                            <option value="">Pilih Divisi</option>
                            @foreach ($structures as $structure)
                                <option value="{{ $structure->id }}"
                                    {{ old('structure_id', $jobdesc->structure_id) == $structure->id ? 'selected' : '' }}>
                                    {{ str_repeat('— ', $structure->level) }}{{ $structure->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('structure_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Event --}}
                    <div>
                        <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Event (Opsional)
                        </label>
                        <select name="event_id" id="event_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('event_id') border-red-500 @enderror">
                            <option value="">Pilih Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ old('event_id', $jobdesc->event_id) == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Job Description <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $jobdesc->title) }}"
                            required placeholder="Contoh: Koordinator Acara Utama"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Code --}}
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Job Description <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code', $jobdesc->code) }}"
                            required placeholder="Contoh: JD-ACR-001"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('code') border-red-500 @enderror">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" required
                        placeholder="Jelaskan deskripsi pekerjaan secara detail..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $jobdesc->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Responsibilities --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Tanggung Jawab <span class="text-red-500">*</span>
                    </h2>
                    <button type="button" @click="addResponsibility"
                        class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-sm rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah</button>
                </div>
                <div class="space-y-3">
                    <template x-for="(item, index) in responsibilities" :key="index">
                        <div class="flex items-start space-x-3">
                            <input type="text" :name="'responsibilities[' + index + ']'" x-model="item.value"
                                placeholder="Contoh: Mengoordinasikan seluruh kegiatan acara" required
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button type="button" @click="removeResponsibility(index)"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                @error('responsibilities')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Requirements --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Persyaratan</h2>
                    <button type="button" @click="addRequirement"
                        class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-sm rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in requirements" :key="index">
                        <div class="flex items-start space-x-3">
                            <input type="text" :name="'requirements[' + index + ']'" x-model="item.value"
                                placeholder="Contoh: Minimal pengalaman 1 tahun di bidang event"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button type="button" @click="removeRequirement(index)"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Skills Required --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Keterampilan yang Dibutuhkan</h2>
                    <button type="button" @click="addSkill"
                        class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-sm rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in skills" :key="index">
                        <div class="flex items-start space-x-3">
                            <input type="text" :name="'skills_required[' + index + ']'" x-model="item.value"
                                placeholder="Contoh: Komunikasi, Leadership, Time Management"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button type="button" @click="removeSkill(index)"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Work Details --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pekerjaan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Estimated Hours --}}
                    <div>
                        <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            Estimasi Jam Kerja
                        </label>
                        <input type="number" name="estimated_hours" id="estimated_hours" min="1"
                            value="{{ old('estimated_hours', $jobdesc->estimated_hours) }}" placeholder="Contoh: 40"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('estimated_hours') border-red-500 @enderror">
                        @error('estimated_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Workload Level --}}
                    <div>
                        <label for="workload_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Tingkat Beban Kerja <span class="text-red-500">*</span>
                        </label>
                        <select name="workload_level" id="workload_level" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('workload_level') border-red-500 @enderror">
                            <option value="">Pilih Tingkat Beban</option>
                            <option value="ringan"
                                {{ old('workload_level', $jobdesc->workload_level) == 'ringan' ? 'selected' : '' }}>Ringan
                            </option>
                            <option value="sedang"
                                {{ old('workload_level', $jobdesc->workload_level) == 'sedang' ? 'selected' : '' }}>Sedang
                            </option>
                            <option value="berat"
                                {{ old('workload_level', $jobdesc->workload_level) == 'berat' ? 'selected' : '' }}>Berat
                            </option>
                        </select>
                        @error('workload_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Priority --}}
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Prioritas <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('priority') border-red-500 @enderror">
                            <option value="">Pilih Prioritas</option>
                            <option value="rendah"
                                {{ old('priority', $jobdesc->priority) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang"
                                {{ old('priority', $jobdesc->priority) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi"
                                {{ old('priority', $jobdesc->priority) == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="kritis"
                                {{ old('priority', $jobdesc->priority) == 'kritis' ? 'selected' : '' }}>Kritis</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Required Members --}}
                    <div>
                        <label for="required_members" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Anggota yang Dibutuhkan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="required_members" id="required_members" min="1"
                            value="{{ old('required_members', $jobdesc->required_members) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('required_members') border-red-500 @enderror">
                        @error('required_members')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Start Date --}}
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai
                        </label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ old('start_date', $jobdesc->start_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Selesai
                        </label>
                        <input type="date" name="end_date" id="end_date"
                            value="{{ old('end_date', $jobdesc->end_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="draft" {{ old('status', $jobdesc->status) == 'draft' ? 'selected' : '' }}>
                                Draft</option>
                            <option value="active" {{ old('status', $jobdesc->status) == 'active' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="completed"
                                {{ old('status', $jobdesc->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="archived"
                                {{ old('status', $jobdesc->status) == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.jobdescs.show', $jobdesc) }}"
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    Perbarui Job Description
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function jobDescForm() {
                return {
                    responsibilities: @json(collect(old('responsibilities', $jobdesc->responsibilities ?? []))->map(fn($val) => ['value' => $val])->toArray()),
                    requirements: @json(collect(old('requirements', $jobdesc->requirements ?? []))->map(fn($val) => ['value' => $val])->toArray()),
                    skills: @json(collect(old('skills_required', $jobdesc->skills_required ?? []))->map(fn($val) => ['value' => $val])->toArray()),

                    addResponsibility() {
                        this.responsibilities.push({
                            value: ''
                        });
                    },
                    removeResponsibility(index) {
                        if (this.responsibilities.length > 1) {
                            this.responsibilities.splice(index, 1);
                        }
                    },

                    addRequirement() {
                        this.requirements.push({
                            value: ''
                        });
                    },
                    removeRequirement(index) {
                        this.requirements.splice(index, 1);
                    },

                    addSkill() {
                        this.skills.push({
                            value: ''
                        });
                    },
                    removeSkill(index) {
                        this.skills.splice(index, 1);
                    }
                }
            }
        </script>
    @endpush
@endsection
