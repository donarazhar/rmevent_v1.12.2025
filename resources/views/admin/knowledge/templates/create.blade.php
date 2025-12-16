@extends('admin.layouts.app')

@section('title', 'Upload Template')

@section('content')
    <div x-data="{
        tags: [],
        tagInput: '',
        variables: [],
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
                    <h1 class="text-2xl font-bold text-gray-900">Upload Template</h1>
                    <p class="text-sm text-gray-600 mt-1">Tambahkan template baru ke library</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Template Code --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Template <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="template_code" value="{{ old('template_code', $templateCode) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('template_code') border-red-500 @enderror">
                        @error('template_code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="document">Document</option>
                            <option value="form">Form</option>
                            <option value="presentation">Presentation</option>
                            <option value="spreadsheet">Spreadsheet</option>
                            <option value="email">Email</option>
                            <option value="report">Report</option>
                            <option value="certificate">Certificate</option>
                            <option value="letter">Letter</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    {{-- Name --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Template <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- File Upload --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">File Template</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Template File --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload File <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="file" required accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: DOCX, XLSX, PPTX, PDF. Max: 20MB</p>
                    </div>

                    {{-- Preview Image --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Image</label>
                        <input type="file" name="preview_image" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Max: 5MB</p>
                    </div>

                    {{-- Preview Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Description</label>
                        <textarea name="preview_description" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('preview_description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Variables & Tags --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Variables --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Variables</h3>
                    <p class="text-sm text-gray-600 mb-4">Variables yang dapat diganti di template (contoh: {nama},
                        {tanggal})</p>

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
                    <p class="text-sm text-gray-600 mb-4">Tags untuk memudahkan pencarian template</p>

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
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                    placeholder="Tulis petunjuk cara menggunakan template ini...">{{ old('usage_instructions') }}</textarea>
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
                            <option value="active">Active (Tersedia untuk digunakan)</option>
                            <option value="inactive">Inactive (Tidak aktif)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Internal</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('notes') }}</textarea>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <span>Upload Template</span>
                </button>
            </div>
        </form>
    </div>
@endsection
