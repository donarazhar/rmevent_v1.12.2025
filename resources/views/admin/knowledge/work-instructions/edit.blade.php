@extends('admin.layouts.app')

@section('title', 'Edit Work Instruction')

@section('content')
    <div x-data="{
        steps: {{ json_encode($workInstruction->steps ?? []) }},
        tools: {{ json_encode($workInstruction->tools_required ?? []) }},
        materials: {{ json_encode($workInstruction->materials_required ?? []) }},
        precautions: {{ json_encode($workInstruction->precautions ?? []) }},
        addStep() {
            this.steps.push({ title: '', description: '', image: '' });
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
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.work-instructions.index') }}"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Work Instruction</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $workInstruction->instruction_code }} -
                        {{ $workInstruction->title }}</p>
                </div>
            </div>
        </div>

        {{-- Warning for archived --}}
        @if ($workInstruction->status === 'archived')
            <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-orange-800">Peringatan</p>
                    <p class="text-xs text-orange-700 mt-1">
                        Work Instruction ini sudah diarsip dan tidak dapat diedit.
                    </p>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.work-instructions.update', $workInstruction) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Instruction Code (readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Instruction</label>
                        <input type="text" value="{{ $workInstruction->instruction_code }}" readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        <p class="mt-1 text-xs text-gray-500">Kode tidak dapat diubah</p>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="setup"
                                {{ old('category', $workInstruction->category) == 'setup' ? 'selected' : '' }}>Setup
                            </option>
                            <option value="execution"
                                {{ old('category', $workInstruction->category) == 'execution' ? 'selected' : '' }}>
                                Execution</option>
                            <option value="troubleshooting"
                                {{ old('category', $workInstruction->category) == 'troubleshooting' ? 'selected' : '' }}>
                                Troubleshooting</option>
                            <option value="maintenance"
                                {{ old('category', $workInstruction->category) == 'maintenance' ? 'selected' : '' }}>
                                Maintenance</option>
                            <option value="reporting"
                                {{ old('category', $workInstruction->category) == 'reporting' ? 'selected' : '' }}>
                                Reporting</option>
                            <option value="other"
                                {{ old('category', $workInstruction->category) == 'other' ? 'selected' : '' }}>Other
                            </option>
                        </select>
                    </div>

                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $workInstruction->title) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Singkat</label>
                        <textarea name="description" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('description', $workInstruction->description) }}</textarea>
                    </div>

                    {{-- SOP Reference --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referensi SOP</label>
                        <select name="sop_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih SOP (Opsional)</option>
                            @foreach ($sops as $sop)
                                <option value="{{ $sop->id }}"
                                    {{ old('sop_id', $workInstruction->sop_id) == $sop->id ? 'selected' : '' }}>
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="easy"
                                {{ old('difficulty_level', $workInstruction->difficulty_level) == 'easy' ? 'selected' : '' }}>
                                Easy (Mudah)</option>
                            <option value="medium"
                                {{ old('difficulty_level', $workInstruction->difficulty_level) == 'medium' ? 'selected' : '' }}>
                                Medium (Sedang)</option>
                            <option value="hard"
                                {{ old('difficulty_level', $workInstruction->difficulty_level) == 'hard' ? 'selected' : '' }}>
                                Hard (Sulit)</option>
                        </select>
                    </div>

                    {{-- Estimated Time --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi Waktu (menit)</label>
                        <input type="number" name="estimated_time"
                            value="{{ old('estimated_time', $workInstruction->estimated_time) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Version --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Version <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="version" value="{{ old('version', $workInstruction->version) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Effective Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Efektif <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="effective_date"
                            value="{{ old('effective_date', $workInstruction->effective_date?->format('Y-m-d')) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
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
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('content', $workInstruction->content) }}</textarea>
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
                            +
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
                            +
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Keamanan</label>
                        <textarea name="safety_notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('safety_notes', $workInstruction->safety_notes) }}</textarea>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Hal yang Perlu Diperhatikan</label>
                            <button type="button" @click="addPrecaution()"
                                class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#003d8f] transition-colors">
                                +
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
                    {{-- Current Attachments --}}
                    @if ($workInstruction->attachments && count($workInstruction->attachments) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Saat Ini</label>
                            <div class="space-y-2">
                                @foreach ($workInstruction->attachments as $attachment)
                                    <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                                        <span class="text-sm text-gray-700">{{ $attachment['name'] ?? 'File' }}</span>
                                        <a href="{{ Storage::url($attachment['path'] ?? '') }}" target="_blank"
                                            class="text-sm text-[#0053C5] hover:underline">Lihat</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- New Attachments --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tambah File Baru</label>
                        <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('notes', $workInstruction->notes) }}</textarea>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="draft"
                                {{ old('status', $workInstruction->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published"
                                {{ old('status', $workInstruction->status) == 'published' ? 'selected' : '' }}>Published
                            </option>
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
                    <span>Update Work Instruction</span>
                </button>
            </div>
        </form>
    </div>
@endsection
