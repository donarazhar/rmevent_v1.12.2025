{{-- Milestone Modal Manager --}}
<div x-data="milestoneModal()">
    {{-- Create/Edit Modal --}}
    <div x-show="showFormModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true"
        @keydown.escape.window="closeFormModal()">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div x-show="showFormModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                @click="closeFormModal()">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal panel --}}
            <div x-show="showFormModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">

                <form
                    :action="modalMode === 'create' ? '{{ route('admin.milestone.store') }}' :
                        `/admin/milestone/${milestoneId}`"
                    method="POST">
                    @csrf
                    <input type="hidden" name="_method" :value="modalMode === 'edit' ? 'PUT' : 'POST'">

                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-primary to-blue-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white"
                                x-text="modalMode === 'create' ? 'Tambah Milestone' : 'Edit Milestone'"></h3>
                            <button type="button" @click="closeFormModal()"
                                class="text-white hover:text-gray-200 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-4 max-h-[calc(100vh-250px)] overflow-y-auto">
                        <div class="space-y-5">
                            {{-- Event --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Event <span class="text-red-500">*</span>
                                </label>
                                <select name="event_id" x-model="formData.event_id" required
                                    class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                    <option value="">Pilih Event</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Timeline --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Timeline</label>
                                <select name="timeline_id" x-model="formData.timeline_id"
                                    class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                    <option value="">Pilih Timeline (Optional)</option>
                                    @foreach ($timelines as $timeline)
                                        <option value="{{ $timeline->id }}">{{ $timeline->name }}
                                            ({{ $timeline->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Nama Milestone --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Milestone <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" x-model="formData.name" required
                                    placeholder="Masukkan nama milestone"
                                    class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Kode --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
                                    <input type="text" name="code" x-model="formData.code"
                                        placeholder="Auto-generate jika kosong"
                                        class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                </div>

                                {{-- Target Date --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Target Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="target_date" x-model="formData.target_date" required
                                        class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                </div>

                                {{-- Actual Date (Edit Only) --}}
                                <div x-show="modalMode === 'edit'">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Actual Date</label>
                                    <input type="date" name="actual_date" x-model="formData.actual_date"
                                        class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                </div>

                                {{-- Progress (Edit Only) --}}
                                <div x-show="modalMode === 'edit'">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Progress (%)</label>
                                    <input type="number" name="progress_percentage"
                                        x-model="formData.progress_percentage" min="0" max="100"
                                        class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                </div>

                                {{-- Status --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" x-model="formData.status" required
                                        class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                        <option value="delayed">Delayed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>

                                {{-- Priority --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Priority <span class="text-red-500">*</span>
                                    </label>
                                    <select name="priority" x-model="formData.priority" required
                                        class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>

                                {{-- Penanggung Jawab --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Penanggung
                                        Jawab</label>
                                    <select name="responsible_person" x-model="formData.responsible_person"
                                        class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                        <option value="">Pilih PIC</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Struktur/Divisi --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Struktur/Divisi</label>
                                    <select name="structure_id" x-model="formData.structure_id"
                                        class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                        <option value="">Pilih Struktur</option>
                                        @foreach ($structures as $structure)
                                            <option value="{{ $structure->id }}">{{ $structure->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Order (Edit Only) --}}
                                <div x-show="modalMode === 'edit'">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                                    <input type="number" name="order" x-model="formData.order" min="0"
                                        class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" x-model="formData.description" rows="3"
                                    placeholder="Masukkan deskripsi milestone"
                                    class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm"></textarea>
                            </div>

                            {{-- Kriteria Kesuksesan --}}
                            <div x-data="{ criteria: formData.success_criteria || [''] }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kriteria Kesuksesan</label>
                                <div class="space-y-2">
                                    <template x-for="(criterion, index) in criteria" :key="index">
                                        <div class="flex items-center gap-2">
                                            <input type="text" :name="'success_criteria[' + index + ']'"
                                                x-model="criteria[index]" placeholder="Kriteria kesuksesan..."
                                                class="flex-1 text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                            <button type="button" @click="criteria.splice(index, 1)"
                                                x-show="criteria.length > 1"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="criteria.push('')"
                                    class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-primary hover:text-primary-dark transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Kriteria
                                </button>
                            </div>

                            {{-- Deliverables --}}
                            <div x-data="{ deliverables: formData.deliverables || [''] }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deliverables</label>
                                <div class="space-y-2">
                                    <template x-for="(deliverable, index) in deliverables" :key="index">
                                        <div class="flex items-center gap-2">
                                            <input type="text" :name="'deliverables[' + index + ']'"
                                                x-model="deliverables[index]" placeholder="Deliverable..."
                                                class="flex-1 text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                            <button type="button" @click="deliverables.splice(index, 1)"
                                                x-show="deliverables.length > 1"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="deliverables.push('')"
                                    class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-primary hover:text-primary-dark transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Deliverable
                                </button>
                            </div>

                            {{-- Completion Notes (Edit Only) --}}
                            <div x-show="modalMode === 'edit'">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                    Penyelesaian</label>
                                <textarea name="completion_notes" x-model="formData.completion_notes" rows="3"
                                    placeholder="Catatan penyelesaian milestone"
                                    class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" @click="closeFormModal()"
                            class="px-5 py-2.5 text-sm font-medium bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-sm">
                            <span x-text="modalMode === 'create' ? 'Simpan Milestone' : 'Update Milestone'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
        aria-modal="true" @keydown.escape.window="closeDeleteModal()">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                @click="closeDeleteModal()">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">

                <form :action="`/admin/milestone/${milestoneId}`" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <div class="mt-4 text-center">
                            <h3 class="text-lg font-semibold text-gray-900">Hapus Milestone</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus milestone <strong class="text-gray-900"
                                    x-text="deleteData.name"></strong>?
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex gap-3">
                        <button type="button" @click="closeDeleteModal()"
                            class="flex-1 px-4 py-2.5 text-sm font-medium bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Complete Modal --}}
    <div x-show="showCompleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
        aria-modal="true" @keydown.escape.window="closeCompleteModal()">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCompleteModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                @click="closeCompleteModal()">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showCompleteModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

                <form :action="`/admin/milestone/${milestoneId}/complete`" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">Selesaikan Milestone</h3>
                            <button type="button" @click="closeCompleteModal()"
                                class="text-white hover:text-gray-200 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-5">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-5">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-900"
                                        x-text="'Milestone: ' + completeData.name"></h4>
                                    <p class="text-sm text-blue-700 mt-1">Pastikan semua deliverable telah selesai
                                        sebelum menandai milestone sebagai complete.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                    Penyelesaian</label>
                                <textarea name="completion_notes" rows="4"
                                    placeholder="Tambahkan catatan tentang penyelesaian milestone ini..."
                                    class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent shadow-sm"></textarea>
                            </div>

                            <div x-data="{ files: [] }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Penyelesaian
                                    (Optional)</label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label :for="'completion_proof_' + milestoneId"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">
                                                <span>Upload files</span>
                                                <input :id="'completion_proof_' + milestoneId"
                                                    name="completion_proof[]" type="file" multiple
                                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                    @change="files = Array.from($event.target.files)" class="sr-only">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, PDF, DOC up to 5MB</p>
                                    </div>
                                </div>

                                <div x-show="files.length > 0" class="mt-4 space-y-2">
                                    <p class="text-sm font-medium text-gray-700">Files dipilih:</p>
                                    <template x-for="file in files" :key="file.name">
                                        <div
                                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <span class="text-sm text-gray-600 truncate" x-text="file.name"></span>
                                            <span class="text-xs text-gray-500 ml-2"
                                                x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" @click="closeCompleteModal()"
                            class="px-5 py-2.5 text-sm font-medium bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Tandai Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Verify Modal --}}
    <div x-show="showVerifyModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
        aria-modal="true" @keydown.escape.window="closeVerifyModal()">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showVerifyModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                @click="closeVerifyModal()">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showVerifyModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">

                <form :action="`/admin/milestone/${milestoneId}/verify`" method="POST">
                    @csrf

                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">Verifikasi Milestone</h3>
                            <button type="button" @click="closeVerifyModal()"
                                class="text-white hover:text-gray-200 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-5 max-h-[calc(100vh-300px)] overflow-y-auto">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-5">
                            <div class="flex">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-green-900"
                                        x-text="'Milestone: ' + verifyData.name"></h4>
                                    <p class="text-sm text-green-700 mt-1">Status: Completed</p>
                                    <p class="text-sm text-green-700" x-show="verifyData.completed_by"
                                        x-text="'Diselesaikan oleh: ' + verifyData.completed_by"></p>
                                    <p class="text-sm text-green-700" x-show="verifyData.completed_at"
                                        x-text="'Tanggal: ' + verifyData.completed_at"></p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div x-show="verifyData.completion_notes">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                    Penyelesaian</label>
                                <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 border border-gray-200"
                                    x-text="verifyData.completion_notes"></div>
                            </div>

                            <div x-show="verifyData.completion_proof && verifyData.completion_proof.length > 0">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Penyelesaian</label>
                                <div class="space-y-2">
                                    <template x-for="proof in verifyData.completion_proof" :key="proof.name">
                                        <a :href="proof.url" target="_blank"
                                            class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-sm text-gray-700" x-text="proof.name"></span>
                                        </a>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Verifikasi
                                    (Optional)</label>
                                <textarea name="verification_notes" rows="4" placeholder="Tambahkan catatan verifikasi..."
                                    class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" @click="closeVerifyModal()"
                            class="px-5 py-2.5 text-sm font-medium bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Alpine.js Component Script - Tetap sama seperti sebelumnya --}}
@push('scripts')
    <script>
        function milestoneModal() {
            return {
                showFormModal: false,
                showDeleteModal: false,
                showCompleteModal: false,
                showVerifyModal: false,
                modalMode: 'create',
                milestoneId: null,
                formData: {},
                deleteData: {},
                completeData: {},
                verifyData: {},

                init() {
                    window.addEventListener('open-modal', (e) => {
                        const detail = e.detail;

                        if (detail.startsWith('create-milestone')) {
                            this.openCreateModal();
                        } else if (detail.startsWith('edit-milestone-')) {
                            const id = detail.replace('edit-milestone-', '');
                            this.openEditModal(id);
                        } else if (detail.startsWith('delete-milestone-')) {
                            const id = detail.replace('delete-milestone-', '');
                            this.openDeleteModal(id);
                        } else if (detail.startsWith('complete-milestone-')) {
                            const id = detail.replace('complete-milestone-', '');
                            this.openCompleteModal(id);
                        } else if (detail.startsWith('verify-milestone-')) {
                            const id = detail.replace('verify-milestone-', '');
                            this.openVerifyModal(id);
                        }
                    });
                },

                openCreateModal() {
                    this.modalMode = 'create';
                    this.formData = {
                        event_id: '',
                        timeline_id: '',
                        name: '',
                        code: '',
                        target_date: '',
                        status: 'pending',
                        priority: 'medium',
                        responsible_person: '',
                        structure_id: '',
                        description: '',
                        success_criteria: [''],
                        deliverables: ['']
                    };
                    this.showFormModal = true;
                },

                openEditModal(id) {
                    this.modalMode = 'edit';
                    this.milestoneId = id;

                    fetch(`/admin/milestone/${id}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            this.formData = data;
                            this.showFormModal = true;
                        })
                        .catch(error => console.error('Error:', error));
                },

                openDeleteModal(id) {
                    this.milestoneId = id;

                    const row = document.querySelector(`[data-milestone-id="${id}"]`);
                    this.deleteData = {
                        name: row ? row.dataset.milestoneName : 'milestone ini'
                    };

                    this.showDeleteModal = true;
                },

                openCompleteModal(id) {
                    this.milestoneId = id;

                    const row = document.querySelector(`[data-milestone-id="${id}"]`);
                    this.completeData = {
                        name: row ? row.dataset.milestoneName : ''
                    };

                    this.showCompleteModal = true;
                },

                openVerifyModal(id) {
                    this.milestoneId = id;

                    fetch(`/admin/milestone/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            this.verifyData = data;
                            this.showVerifyModal = true;
                        })
                        .catch(error => console.error('Error:', error));
                },

                closeFormModal() {
                    this.showFormModal = false;
                    this.formData = {};
                    this.milestoneId = null;
                },

                closeDeleteModal() {
                    this.showDeleteModal = false;
                    this.deleteData = {};
                    this.milestoneId = null;
                },

                closeCompleteModal() {
                    this.showCompleteModal = false;
                    this.completeData = {};
                    this.milestoneId = null;
                },

                closeVerifyModal() {
                    this.showVerifyModal = false;
                    this.verifyData = {};
                    this.milestoneId = null;
                }
            }
        }
    </script>
@endpush
