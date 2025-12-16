@extends('admin.layouts.app')

@section('title', 'Buat Work Instruction')

@section('content')
    <div x-data="{
        steps: [],
        tools: [],
        materials: [],
        precautions: [],
        addStep() {
            this.steps.push({
                title: '',
                description: '',
                image: ''
            });
        },
        removeStep(index) {
            this.steps.splice(index, 1);
        },
        addTool() {
            this.tools.push('');
        },
        removeTool(index) {
            this.tools.splice(index, 1);
        },
        addMaterial() {
            this.materials.push('');
        },
        removeMaterial(index) {
            this.materials.splice(index, 1);
        },
        addPrecaution() {
            this.precautions.push('');
        },
        removePrecaution(index) {
            this.precautions.splice(index, 1);
        }
    }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.work-instructions.index') }}"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Buat Work Instruction</h1>
                    <p class="text-sm text-gray-600 mt-1">Buat dokumentasi prosedur kerja baru</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.work-instructions.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Instruction Code --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Instruction <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="instruction_code"
                            value="{{ old('instruction_code', $instructionCode) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('instruction_code') border-red-500 @enderror">
                        @error('instruction_code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('category') border-red-500 @enderror">
                            <option value="setup">Setup</option>
                            <option value="execution" selected>Execution</option>
                            <option value="troubleshooting">Troubleshooting</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="reporting">Reporting</option>
                            <option value="other">Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Singkat</label>
                        <textarea name="description" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SOP Reference --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referensi SOP</label>
                        <select name="sop_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih SOP (Opsional)</option>
                            @foreach ($sops as $sop)
                                <option value="{{ $sop->id }}" {{ old('sop_id') == $sop->id ? 'selected' : '' }}>
                                    {{ $sop->sop_code }} - {{ $sop->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Difficulty Level --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tingkat Kesulitan <span class="text-red-500">*</span>
                        </label>
                        <select name="difficulty_level" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('difficulty_level') border-red-500 @enderror">
                            <option value="easy">Easy (Mudah)</option>
                            <option value="medium" selected>Medium (Sedang)</option>
                            <option value="hard">Hard (Sulit)</option>
                        </select>
                        @error('difficulty_level')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Estimated Time --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi Waktu (menit)</label>
                        <input type="number" name="estimated_time" value="{{ old('estimated_time') }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('estimated_time') border-red-500 @enderror">
                        @error('estimated_time')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Version --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Version <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="version" value="{{ old('version', '1.0') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('version') border-red-500 @enderror">
                        @error('version')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Effective Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Efektif <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="effective_date" value="{{ old('effective_date', date('Y-m-d')) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('effective_date') border-red-500 @enderror">
                        @error('effective_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Konten Utama</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Konten <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" rows="10" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('content') border-red-500 @enderror"
                        placeholder="Tulis konten lengkap work instruction di sini...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Gunakan Markdown untuk formatting</p>
                </div>
            </div>

            {{-- Step-by-Step Instructions --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Langkah-Langkah (Step by Step)</h3>
                    <button type="button" @click="addStep()"
                        class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Step
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(step, index) in steps" :key="index">
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">Step <span
                                        x-text="index + 1"></span></span>
                                <button type="button" @click="removeStep(index)"
                                    class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="space-y-3">
                                <input type="text" x-model="steps[index].title" :name="'steps[' + index + '][title]'"
                                    placeholder="Judul step"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <textarea x-model="steps[index].description" :name="'steps[' + index + '][description]'" rows="2"
                                    placeholder="Deskripsi step"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"></textarea>
                                <input type="text" x-model="steps[index].image" :name="'steps[' + index + '][image]'"
                                    placeholder="URL gambar (opsional)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            </div>
                        </div>
                    </template>

                    <p x-show="steps.length === 0" class="text-sm text-gray-500 text-center py-4">
                        Belum ada step. Klik "Tambah Step" untuk menambahkan.
                    </p>
                </div>

                <input type="hidden" name="steps" :value="JSON.stringify(steps)">
            </div>

            {{-- Tools & Materials --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tools Required --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tools/Peralatan</h3>
                        <button type="button" @click="addTool()"
                            class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#003d8f] transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(tool, index) in tools" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="tools[index]" :name="'tools[]'"
                                    placeholder="Nama tool/peralatan"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <button type="button" @click="removeTool(index)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <input type="hidden" name="tools_required" :value="JSON.stringify(tools)">
                </div>

                {{-- Materials Required --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Materials/Bahan</h3>
                        <button type="button" @click="addMaterial()"
                            class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#003d8f] transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(material, index) in materials" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="materials[index]" :name="'materials[]'"
                                    placeholder="Nama material/bahan"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <button type="button" @click="removeMaterial(index)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <input type="hidden" name="materials_required" :value="JSON.stringify(materials)">
                </div>
            </div>

            {{-- Safety & Precautions --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Keamanan & Pencegahan</h3>

                <div class="grid grid-cols-1 gap-6">
                    {{-- Safety Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Keamanan</label>
                        <textarea name="safety_notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                            placeholder="Catatan penting terkait keamanan...">{{ old('safety_notes') }}</textarea>
                    </div>

                    {{-- Precautions --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Hal yang Perlu Diperhatikan</label>
                            <button type="button" @click="addPrecaution()"
                                class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#003d8f] transition-colors">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(precaution, index) in precautions" :key="index">
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="precautions[index]" :name="'precautions[]'"
                                        placeholder="Hal yang perlu diperhatikan"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <button type="button" @click="removePrecaution(index)"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <input type="hidden" name="precautions" :value="JSON.stringify(precautions)">
                    </div>
                </div>
            </div>

            {{-- Attachments & Notes --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Lampiran & Catatan</h3>

                <div class="grid grid-cols-1 gap-6">
                    {{-- Attachments --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Lampiran</label>
                        <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, Images, Video. Max: 10MB per file</p>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="draft">Draft (Simpan untuk dilanjutkan nanti)</option>
                            <option value="published">Published (Langsung publish)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.work-instructions.index') }}"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Work Instruction</span>
                </button>
            </div>
        </form>
    </div>
@endsection
