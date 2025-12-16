@extends('admin.layouts.app')

@section('title', 'Edit Template')

@section('content')
    <div x-data="{
        tags: {{ json_encode($template->tags ?? []) }},
        tagInput: '',
        variables: {{ json_encode($template->variables ?? []) }},
        variableInput: '',
        addTag() {
            if (this.tagInput.trim()) {
                this.tags.push(this.tagInput.trim());
                this.tagInput = '';
            }
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },
        addVariable() {
            if (this.variableInput.trim()) {
                this.variables.push(this.variableInput.trim());
                this.variableInput = '';
            }
        },
        removeVariable(index) {
            this.variables.splice(index, 1);
        }
    }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.templates.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Template</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $template->template_code }} - {{ $template->name }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.templates.update', $template) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Template Code (readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Template</label>
                        <input type="text" value="{{ $template->template_code }}" readonly
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
                            <option value="document" {{ $template->category == 'document' ? 'selected' : '' }}>Document
                            </option>
                            <option value="form" {{ $template->category == 'form' ? 'selected' : '' }}>Form</option>
                            <option value="presentation" {{ $template->category == 'presentation' ? 'selected' : '' }}>
                                Presentation</option>
                            <option value="spreadsheet" {{ $template->category == 'spreadsheet' ? 'selected' : '' }}>
                                Spreadsheet</option>
                            <option value="email" {{ $template->category == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="report" {{ $template->category == 'report' ? 'selected' : '' }}>Report</option>
                            <option value="certificate" {{ $template->category == 'certificate' ? 'selected' : '' }}>
                                Certificate</option>
                            <option value="letter" {{ $template->category == 'letter' ? 'selected' : '' }}>Letter</option>
                            <option value="other" {{ $template->category == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    {{-- Name --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Template <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('description', $template->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- File Upload --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">File Template</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Current File --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Saat Ini</label>
                        <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ strtoupper($template->file_type) }}</p>
                                    <p class="text-xs text-gray-500">{{ $template->file_size_formatted }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.templates.download', $template) }}"
                                class="text-sm text-[#0053C5] hover:underline">
                                Download
                            </a>
                        </div>
                    </div>

                    {{-- Replace File --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ganti File (Opsional)</label>
                        <input type="file" name="file" accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: DOCX, XLSX, PPTX, PDF. Max: 20MB</p>
                    </div>

                    {{-- Current Preview --}}
                    @if ($template->preview_image)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preview Saat Ini</label>
                            <img src="{{ Storage::url($template->preview_image) }}" alt="Preview"
                                class="w-full h-32 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif

                    {{-- Replace Preview --}}
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2">{{ $template->preview_image ? 'Ganti' : 'Upload' }}
                            Preview Image</label>
                        <input type="file" name="preview_image" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Max: 5MB</p>
                    </div>

                    {{-- Preview Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Description</label>
                        <textarea name="preview_description" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('preview_description', $template->preview_description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Variables & Tags --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Variables --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Variables</h3>

                    <div class="flex gap-2 mb-3">
                        <input type="text" x-model="variableInput" @keyup.enter="addVariable()"
                            placeholder="Contoh: {nama}"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <button type="button" @click="addVariable()"
                            class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f]">
                            Tambah
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <template x-for="(variable, index) in variables" :key="index">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                <span x-text="variable"></span>
                                <button type="button" @click="removeVariable(index)" class="hover:text-blue-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>

                    <input type="hidden" name="variables" :value="variables.join(',')">
                </div>

                {{-- Tags --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>

                    <div class="flex gap-2 mb-3">
                        <input type="text" x-model="tagInput" @keyup.enter="addTag()"
                            placeholder="Contoh: surat, resmi"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <button type="button" @click="addTag()"
                            class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f]">
                            Tambah
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <template x-for="(tag, index) in tags" :key="index">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm">
                                <span x-text="tag"></span>
                                <button type="button" @click="removeTag(index)" class="hover:text-purple-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>

                    <input type="hidden" name="tags" :value="tags.join(',')">
                </div>
            </div>

            {{-- Usage Instructions --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Petunjuk Penggunaan</h3>
                <textarea name="usage_instructions" rows="5"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('usage_instructions', $template->usage_instructions) }}</textarea>
            </div>

            {{-- Status & Notes --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Catatan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="active" {{ $template->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $template->status == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Internal</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('notes', $template->notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.templates.index') }}"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Update Template</span>
                </button>
            </div>
        </form>
    </div>
@endsection
