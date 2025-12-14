{{-- resources/views/admin/timeline/partials/modal-form.blade.php --}}

<div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

        {{-- Modal panel --}}
        <div x-show="showModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">

            <form
                :action="modalMode === 'create' ? '{{ route('admin.timeline.store') }}' : `/admin/timeline/${timelineId}`"
                method="POST">
                @csrf
                <input type="hidden" name="_method" :value="modalMode === 'edit' ? 'PUT' : 'POST'">

                {{-- Modal Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900"
                            x-text="modalMode === 'create' ? 'Tambah Timeline Baru' : 'Edit Timeline'"></h3>
                        <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Event --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Event <span class="text-red-500">*</span>
                            </label>
                            <select name="event_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih Event</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Parent Timeline --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Parent Timeline (Opsional)
                            </label>
                            <select name="parent_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Tidak Ada (Root Level)</option>
                                @foreach ($timelines as $tl)
                                    <option value="{{ $tl->id }}">
                                        {{ str_repeat('─', $tl->level) }} {{ $tl->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Structure --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Struktur Kepanitiaan
                            </label>
                            <select name="structure_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih Struktur</option>
                                @foreach ($structures as $structure)
                                    <option value="{{ $structure->id }}">{{ $structure->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Name --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Timeline <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Contoh: Persiapan Venue">
                        </div>

                        {{-- Code --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Timeline
                            </label>
                            <input type="text" name="code"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Otomatis jika kosong">
                        </div>

                        {{-- Order --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Urutan
                            </label>
                            <input type="number" name="order" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Otomatis jika kosong">
                        </div>

                        {{-- Description --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Deskripsi detail timeline..."></textarea>
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        {{-- End Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="end_date" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        {{-- Actual Start Date (Edit Only) --}}
                        <div x-show="modalMode === 'edit'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai Aktual
                            </label>
                            <input type="date" name="actual_start_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        {{-- Actual End Date (Edit Only) --}}
                        <div x-show="modalMode === 'edit'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Selesai Aktual
                            </label>
                            <input type="date" name="actual_end_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        {{-- Assigned To --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                PIC (Person In Charge)
                            </label>
                            <select name="assigned_to"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Pilih PIC</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="not_started">Belum Dimulai</option>
                                <option value="in_progress">Sedang Berjalan</option>
                                <option value="completed">Selesai</option>
                                <option value="delayed">Terlambat</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>

                        {{-- Priority --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Prioritas <span class="text-red-500">*</span>
                            </label>
                            <select name="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="low">Rendah</option>
                                <option value="medium" selected>Sedang</option>
                                <option value="high">Tinggi</option>
                                <option value="urgent">Mendesak</option>
                            </select>
                        </div>

                        {{-- Progress Percentage (Edit Only) --}}
                        <div x-show="modalMode === 'edit'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Progress (%)
                            </label>
                            <input type="number" name="progress_percentage" min="0" max="100"
                                value="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        {{-- Estimated Budget --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Estimasi Anggaran (Rp)
                            </label>
                            <input type="number" name="estimated_budget" min="0" step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="0">
                        </div>

                        {{-- Actual Budget (Edit Only) --}}
                        <div x-show="modalMode === 'edit'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Anggaran Aktual (Rp)
                            </label>
                            <input type="number" name="actual_budget" min="0" step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="0">
                        </div>

                        {{-- Estimated Hours --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Estimasi Jam Kerja
                            </label>
                            <input type="number" name="estimated_hours" min="0" step="0.5"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="0">
                        </div>

                        {{-- Actual Hours (Edit Only) --}}
                        <div x-show="modalMode === 'edit'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Kerja Aktual
                            </label>
                            <input type="number" name="actual_hours" min="0" step="0.5"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="0">
                        </div>

                        {{-- Team Members --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tim Anggota
                            </label>
                            <select name="team_members[]" multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                style="height: 120px;">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Tahan Ctrl/Cmd untuk memilih lebih dari satu</p>
                        </div>

                        {{-- Notes --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan
                            </label>
                            <textarea name="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Catatan tambahan..."></textarea>
                        </div>

                        {{-- Completion Notes (Edit Only) --}}
                        <div class="col-span-2" x-show="modalMode === 'edit'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Penyelesaian
                            </label>
                            <textarea name="completion_notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Catatan saat timeline selesai..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" @click="closeModal()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        <span x-text="modalMode === 'create' ? 'Tambah Timeline' : 'Update Timeline'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
