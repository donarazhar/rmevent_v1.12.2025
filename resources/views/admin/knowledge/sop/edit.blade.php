@extends('admin.layouts.app')

@section('title', 'Edit SOP')

@push('styles')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
    <form method="POST" action="{{ route('admin.sops.update', $sop) }}" enctype="multipart/form-data" x-data="{
        procedures: {{ json_encode($sop->procedures ?? []) }},
        responsibilities: {{ json_encode($sop->responsibilities ?? []) }},
        relatedForms: {{ json_encode($sop->related_forms ?? []) }},
        relatedTemplates: {{ json_encode($sop->related_templates ?? []) }},
        addProcedure() {
            this.procedures.push({ step: this.procedures.length + 1, description: '', notes: '' });
        },
        removeProcedure(index) {
            this.procedures.splice(index, 1);
            this.procedures.forEach((p, i) => p.step = i + 1);
        },
        addResponsibility() {
            this.responsibilities.push({ role: '', tasks: '' });
        },
        removeResponsibility(index) {
            this.responsibilities.splice(index, 1);
        },
        addForm() {
            this.relatedForms.push({ name: '', reference: '' });
        },
        removeForm(index) {
            this.relatedForms.splice(index, 1);
        },
        addTemplate() {
            this.relatedTemplates.push({ name: '', reference: '' });
        },
        removeTemplate(index) {
            this.relatedTemplates.splice(index, 1);
        }
    }">
        @csrf
        @method('PUT')

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.sops.show', $sop) }}"
                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit SOP</h1>
                    <p class="text-gray-600 mt-1">{{ $sop->sop_code }} - v{{ $sop->version }}</p>
                </div>
            </div>
        </div>

        {{-- Warning for Published SOPs --}}
        @if ($sop->status === 'published')
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-yellow-800">This SOP is Published</h3>
                        <p class="text-sm text-yellow-700 mt-1">This SOP cannot be edited as it's already published. Please
                            create a new version instead.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                    <div class="space-y-4">
                        {{-- SOP Code (Readonly) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SOP Code</label>
                            <input type="text" value="{{ $sop->sop_code }}" readonly
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                        </div>

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $sop->title) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Select Category</option>
                                <option value="event_management"
                                    {{ old('category', $sop->category) == 'event_management' ? 'selected' : '' }}>Event
                                    Management</option>
                                <option value="finance"
                                    {{ old('category', $sop->category) == 'finance' ? 'selected' : '' }}>Finance</option>
                                <option value="registration"
                                    {{ old('category', $sop->category) == 'registration' ? 'selected' : '' }}>Registration
                                </option>
                                <option value="documentation"
                                    {{ old('category', $sop->category) == 'documentation' ? 'selected' : '' }}>
                                    Documentation</option>
                                <option value="emergency"
                                    {{ old('category', $sop->category) == 'emergency' ? 'selected' : '' }}>Emergency
                                </option>
                                <option value="general"
                                    {{ old('category', $sop->category) == 'general' ? 'selected' : '' }}>General</option>
                                <option value="other" {{ old('category', $sop->category) == 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Purpose --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purpose</label>
                            <textarea name="purpose" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('purpose', $sop->purpose) }}</textarea>
                        </div>

                        {{-- Scope --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Scope</label>
                            <textarea name="scope" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('scope', $sop->scope) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Content</h2>
                    <textarea id="content" name="content">{{ old('content', $sop->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Procedures --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Procedures (Step-by-Step)</h2>
                        <button type="button" @click="addProcedure"
                            class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#004AB0] transition-colors">
                            Add Step
                        </button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(procedure, index) in procedures" :key="index">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <span class="text-sm font-medium text-gray-700">Step <span
                                            x-text="procedure.step"></span></span>
                                    <button type="button" @click="removeProcedure(index)"
                                        class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" :name="'procedures[' + index + '][step]'" x-model="procedure.step">
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Description</label>
                                        <textarea :name="'procedures[' + index + '][description]'" rows="2" x-model="procedure.description"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Notes (Optional)</label>
                                        <textarea :name="'procedures[' + index + '][notes]'" rows="2" x-model="procedure.notes"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template x-if="procedures.length === 0">
                            <p class="text-center text-gray-500 py-4">No procedures added yet.</p>
                        </template>
                    </div>
                </div>

                {{-- Responsibilities --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Responsibilities</h2>
                        <button type="button" @click="addResponsibility"
                            class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#004AB0] transition-colors">
                            Add Role
                        </button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(responsibility, index) in responsibilities" :key="index">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <span class="text-sm font-medium text-gray-700">Role</span>
                                    <button type="button" @click="removeResponsibility(index)"
                                        class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Role/Position</label>
                                        <input type="text" :name="'responsibilities[' + index + '][role]'"
                                            x-model="responsibility.role"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Tasks</label>
                                        <textarea :name="'responsibilities[' + index + '][tasks]'" rows="2" x-model="responsibility.tasks"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template x-if="responsibilities.length === 0">
                            <p class="text-center text-gray-500 py-4">No responsibilities defined.</p>
                        </template>
                    </div>
                </div>

                {{-- Related Forms --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Related Forms</h2>
                        <button type="button" @click="addForm"
                            class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#004AB0] transition-colors">
                            Add Form
                        </button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(form, index) in relatedForms" :key="index">
                            <div class="flex gap-3">
                                <input type="text" :name="'related_forms[' + index + '][name]'" x-model="form.name"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="Form name">
                                <input type="text" :name="'related_forms[' + index + '][reference]'"
                                    x-model="form.reference"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="Reference code">
                                <button type="button" @click="removeForm(index)"
                                    class="px-3 py-2 text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <template x-if="relatedForms.length === 0">
                            <p class="text-center text-gray-500 py-4">No forms added.</p>
                        </template>
                    </div>
                </div>

                {{-- Related Templates --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Related Templates</h2>
                        <button type="button" @click="addTemplate"
                            class="px-3 py-1.5 bg-[#0053C5] text-white text-sm rounded-lg hover:bg-[#004AB0] transition-colors">
                            Add Template
                        </button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(template, index) in relatedTemplates" :key="index">
                            <div class="flex gap-3">
                                <input type="text" :name="'related_templates[' + index + '][name]'"
                                    x-model="template.name"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="Template name">
                                <input type="text" :name="'related_templates[' + index + '][reference]'"
                                    x-model="template.reference"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    placeholder="Reference code">
                                <button type="button" @click="removeTemplate(index)"
                                    class="px-3 py-2 text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <template x-if="relatedTemplates.length === 0">
                            <p class="text-center text-gray-500 py-4">No templates added.</p>
                        </template>
                    </div>
                </div>

                {{-- Current Attachments --}}
                @if ($sop->attachments && count($sop->attachments) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Attachments</h2>
                        <div class="space-y-2">
                            @foreach ($sop->attachments as $index => $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $attachment['name'] }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ number_format($attachment['size'] / 1024, 2) }} KB</p>
                                        </div>
                                    </div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="remove_attachments[]" value="{{ $index }}"
                                            class="rounded border-gray-300 text-[#0053C5] focus:ring-[#0053C5]">
                                        <span class="text-sm text-red-600">Remove</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Dates --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Dates</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Effective Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="effective_date"
                                value="{{ old('effective_date', $sop->effective_date ? $sop->effective_date->format('Y-m-d') : '') }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Review Date</label>
                            <input type="date" name="review_date"
                                value="{{ old('review_date', $sop->review_date ? $sop->review_date->format('Y-m-d') : '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" name="expiry_date"
                                value="{{ old('expiry_date', $sop->expiry_date ? $sop->expiry_date->format('Y-m-d') : '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        </div>
                    </div>
                </div>

                {{-- New Attachments --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Add New Attachments</h2>
                    <div>
                        <label
                            class="block w-full cursor-pointer border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#0053C5] transition-colors">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span class="text-sm text-gray-600">Click to upload files</span>
                            <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx"
                                class="hidden">
                        </label>
                        <p class="mt-2 text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX (Max: 5MB)</p>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                    <textarea name="notes" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('notes', $sop->notes) }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                    <div class="space-y-3">
                        <button type="submit" name="submit_action" value="draft"
                            class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Save Changes
                        </button>
                        @if ($sop->status === 'draft')
                            <button type="submit" name="submit_action" value="publish"
                                class="w-full px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors">
                                Submit for Review
                            </button>
                        @endif
                        <a href="{{ route('admin.sops.show', $sop) }}"
                            class="block w-full px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    {{-- TinyMCE Self-Hosted (No API Key Required) --}}
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>

    <script>
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Inter,sans-serif; font-size:14px }',
            branding: false
        });
    </script>
@endpush
