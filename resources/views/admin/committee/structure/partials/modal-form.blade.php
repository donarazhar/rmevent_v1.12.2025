{{-- resources/views/admin/committee/structure/partials/modal-form.blade.php --}}

<div x-show="showModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     @keydown.escape.window="closeModal()">
    
    {{-- Backdrop --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
             @click="closeModal()">
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        {{-- Modal Content --}}
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block w-full max-w-3xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900" x-text="modalMode === 'add' ? 'Tambah Struktur Organisasi' : 'Edit Struktur Organisasi'"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form @submit.prevent="submitForm()" class="space-y-5">
                {{-- Event Selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Event <span class="text-red-500">*</span>
                    </label>
                    <select x-model="formData.event_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Pilih Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Parent Structure --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Parent Structure (Opsional)
                    </label>
                    <select x-model="formData.parent_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Top Level --</option>
                        <template x-for="structure in availableParents" :key="structure.id">
                            <option :value="structure.id" x-text="structure.name"></option>
                        </template>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika ini adalah struktur tingkat tertinggi</p>
                </div>

                {{-- Basic Info Row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Struktur <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="formData.name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                               placeholder="e.g., Divisi Acara">
                    </div>

                    {{-- Code --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode (Opsional)
                        </label>
                        <input type="text" x-model="formData.code"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                               placeholder="e.g., DIV-ACARA">
                        <p class="mt-1 text-xs text-gray-500">Auto-generate jika kosong</p>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea x-model="formData.description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                              placeholder="Deskripsi singkat tentang struktur ini..."></textarea>
                </div>

                {{-- Level & Status Row --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Level --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Level <span class="text-red-500">*</span>
                        </label>
                        <input type="number" x-model="formData.level" required min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>

                    {{-- Order --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan
                        </label>
                        <input type="number" x-model="formData.order" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select x-model="formData.status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>

                {{-- Leadership --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Leader --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ketua
                        </label>
                        <select x-model="formData.leader_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="">-- Pilih Ketua --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Vice Leader --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Wakil Ketua
                        </label>
                        <select x-model="formData.vice_leader_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="">-- Pilih Wakil Ketua --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" x-model="formData.email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                               placeholder="divisi@ramadhanmubarak.org">
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Telepon
                        </label>
                        <input type="tel" x-model="formData.phone"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                               placeholder="+62 812-3456-7890">
                    </div>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="formData.responsibilities.push('')"
                                class="text-sm text-primary hover:text-primary-dark font-medium">
                            + Tambah Tanggung Jawab
                        </button>
                    </div>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="formData.authorities.push('')"
                                class="text-sm text-primary hover:text-primary-dark font-medium">
                            + Tambah Wewenang
                        </button>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" @click="closeModal()"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="loading"
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <span x-show="loading">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>