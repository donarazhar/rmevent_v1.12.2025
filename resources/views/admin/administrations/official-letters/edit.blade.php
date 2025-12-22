@extends('admin.layouts.app')

@section('title', 'Edit Surat Resmi')

@section('content')
    <div x-data="{
        direction: '{{ $officialLetter->direction }}',
        letterType: '{{ $officialLetter->letter_type }}',
        hasDueDate: {{ $officialLetter->due_date ? 'true' : 'false' }},
        ccRecipients: {{ json_encode($officialLetter->cc_recipients ?? []) }},
        attachmentList: {{ json_encode($officialLetter->attachment_list ?? []) }},
        addCcRecipient() {
            this.ccRecipients.push({
                name: '',
                email: '',
                organization: ''
            });
        },
        removeCcRecipient(index) {
            this.ccRecipients.splice(index, 1);
        },
        addAttachment() {
            const name = prompt('Nama lampiran:');
            if (name) {
                this.attachmentList.push(name);
            }
        },
        removeAttachment(index) {
            this.attachmentList.splice(index, 1);
        }
    }">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-3 text-sm text-gray-600 mb-4">
                <a href="{{ route('admin.official-letters.index') }}" class="hover:text-[#0053C5]">
                    Manajemen Surat
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('admin.official-letters.show', $officialLetter) }}" class="hover:text-[#0053C5]">
                    Detail Surat
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium">Edit Surat</span>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Surat Resmi</h1>
                    <p class="text-gray-600 mt-1">{{ $officialLetter->letter_number }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.official-letters.update', $officialLetter) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="direction" x-model="direction">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Basic Information --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                        <div class="space-y-4">
                            {{-- Direction (Read Only) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Arah Surat *</label>
                                <div class="flex gap-4">
                                    <label class="flex items-center cursor-not-allowed opacity-60">
                                        <input type="radio" x-model="direction" value="outgoing" disabled
                                            class="w-4 h-4 text-[#0053C5] border-gray-300 focus:ring-[#0053C5]">
                                        <span class="ml-2 text-sm text-gray-700">📤 Surat Keluar</span>
                                    </label>
                                    <label class="flex items-center cursor-not-allowed opacity-60">
                                        <input type="radio" x-model="direction" value="incoming" disabled
                                            class="w-4 h-4 text-[#0053C5] border-gray-300 focus:ring-[#0053C5]">
                                        <span class="ml-2 text-sm text-gray-700">📥 Surat Masuk</span>
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Arah surat tidak dapat diubah</p>
                            </div>

                            {{-- Letter Type --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat *</label>
                                <select name="letter_type" x-model="letterType" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <option value="invitation">Undangan</option>
                                    <option value="announcement">Pengumuman</option>
                                    <option value="notification">Pemberitahuan</option>
                                    <option value="request">Permohonan</option>
                                    <option value="response">Balasan</option>
                                    <option value="thank_you">Ucapan Terima Kasih</option>
                                    <option value="cooperation">Kerjasama</option>
                                    <option value="recommendation">Rekomendasi</option>
                                    <option value="other">Lainnya</option>
                                </select>
                                @error('letter_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Subject --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subjek Surat *</label>
                                <input type="text" name="subject" value="{{ old('subject', $officialLetter->subject) }}"
                                    required placeholder="Contoh: Undangan Rapat Koordinasi Panitia"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Content --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Isi Surat *</label>
                                <textarea name="content" rows="12" required placeholder="Tuliskan isi surat lengkap di sini..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('content', $officialLetter->content) }}</textarea>
                                @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Event (Optional) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Terkait Event (Opsional)</label>
                                <select name="event_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <option value="">- Tidak terkait event -</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id', $officialLetter->event_id) == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Sender Information (for incoming) --}}
                    <div x-show="direction === 'incoming'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengirim</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim *</label>
                                <input type="text" name="sender_name"
                                    value="{{ old('sender_name', $officialLetter->sender_name) }}"
                                    placeholder="Nama instansi/organisasi/perorangan"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                @error('sender_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Organisasi Pengirim</label>
                                <input type="text" name="sender_organization"
                                    value="{{ old('sender_organization', $officialLetter->sender_organization) }}"
                                    placeholder="Nama organisasi/instansi"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    {{-- Recipient Information --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            <span x-show="direction === 'outgoing'">Informasi Penerima</span>
                            <span x-show="direction === 'incoming'">Informasi Penerima (Kami)</span>
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima *</label>
                                <input type="text" name="recipient_name"
                                    value="{{ old('recipient_name', $officialLetter->recipient_name) }}" required
                                    placeholder="Nama penerima surat"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                @error('recipient_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Organisasi Penerima</label>
                                <input type="text" name="recipient_organization"
                                    value="{{ old('recipient_organization', $officialLetter->recipient_organization) }}"
                                    placeholder="Nama organisasi/instansi penerima"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Penerima</label>
                                <textarea name="recipient_address" rows="3" placeholder="Alamat lengkap penerima"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('recipient_address', $officialLetter->recipient_address) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Penerima</label>
                                <input type="email" name="recipient_email"
                                    value="{{ old('recipient_email', $officialLetter->recipient_email) }}"
                                    placeholder="email@example.com"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    {{-- CC Recipients --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Tembusan (CC)</h2>
                            <button type="button" @click="addCcRecipient()"
                                class="text-sm text-[#0053C5] hover:text-[#004AB0] font-medium">
                                + Tambah Tembusan
                            </button>
                        </div>

                        <div x-show="ccRecipients.length === 0" class="text-sm text-gray-500 text-center py-4">
                            Belum ada tembusan. Klik "Tambah Tembusan" untuk menambahkan.
                        </div>

                        <div class="space-y-4">
                            <template x-for="(cc, index) in ccRecipients" :key="index">
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="text-sm font-medium text-gray-700">Tembusan <span
                                                x-text="index + 1"></span></h3>
                                        <button type="button" @click="removeCcRecipient(index)"
                                            class="text-red-600 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <input type="text" :name="'cc_recipients[' + index + '][name]'"
                                                x-model="cc.name" placeholder="Nama" required
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                        </div>
                                        <div>
                                            <input type="email" :name="'cc_recipients[' + index + '][email]'"
                                                x-model="cc.email" placeholder="Email" required
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                        </div>
                                        <div>
                                            <input type="text" :name="'cc_recipients[' + index + '][organization]'"
                                                x-model="cc.organization" placeholder="Organisasi"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Attachments & Files --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Lampiran & File</h2>

                        <div class="space-y-4">
                            {{-- Current Main Letter File --}}
                            @if ($officialLetter->letter_file)
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-sm text-blue-900">File surat saat ini</span>
                                        </div>
                                        <a href="{{ Storage::url($officialLetter->letter_file) }}" target="_blank"
                                            class="text-sm text-blue-600 hover:text-blue-700">Lihat</a>
                                    </div>
                                </div>
                            @endif

                            {{-- Main Letter File --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $officialLetter->letter_file ? 'Ganti File Surat Utama' : 'File Surat Utama' }}
                                </label>
                                <input type="file" name="letter_file" accept=".pdf,.doc,.docx"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX (Max: 5MB)</p>
                            </div>

                            {{-- Current Supporting Files --}}
                            @if ($officialLetter->supporting_files && count($officialLetter->supporting_files) > 0)
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">File Pendukung Saat Ini</p>
                                    <div class="space-y-2">
                                        @foreach ($officialLetter->supporting_files as $file)
                                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                                <span class="text-sm text-gray-700">{{ basename($file) }}</span>
                                                <a href="{{ Storage::url($file) }}" target="_blank"
                                                    class="text-sm text-blue-600 hover:text-blue-700">Lihat</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Supporting Files --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tambah File Pendukung</label>
                                <input type="file" name="supporting_files[]" multiple
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX, JPG, PNG, XLS, XLSX (Max: 5MB
                                    per file)</p>
                            </div>

                            {{-- Attachment List (names only) --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Daftar Lampiran</label>
                                    <button type="button" @click="addAttachment()"
                                        class="text-sm text-[#0053C5] hover:text-[#004AB0] font-medium">
                                        + Tambah Nama Lampiran
                                    </button>
                                </div>
                                <div x-show="attachmentList.length === 0"
                                    class="text-sm text-gray-500 text-center py-3 border border-dashed border-gray-300 rounded-lg">
                                    Belum ada lampiran
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(attachment, index) in attachmentList" :key="index">
                                        <div class="flex items-center gap-2">
                                            <input type="text" :name="'attachment_list[' + index + ']'"
                                                :value="attachment" readonly
                                                class="flex-1 px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg">
                                            <input type="hidden" :name="'attachment_list[' + index + ']'"
                                                :value="attachment">
                                            <button type="button" @click="removeAttachment(index)"
                                                class="text-red-600 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Umum</label>
                                <textarea name="notes" rows="3" placeholder="Catatan yang dapat dilihat oleh semua pihak terkait"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('notes', $officialLetter->notes) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Internal</label>
                                <textarea name="internal_notes" rows="3"
                                    placeholder="Catatan internal yang hanya dapat dilihat oleh tim internal"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('internal_notes', $officialLetter->internal_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Dates & Priority --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tanggal & Prioritas</h2>

                        <div class="space-y-4">
                            {{-- Letter Date --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat *</label>
                                <input type="date" name="letter_date"
                                    value="{{ old('letter_date', $officialLetter->letter_date->format('Y-m-d')) }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            </div>

                            {{-- Due Date Toggle --}}
                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input typeLanjutkan14.36="checkbox" x-model="hasDueDate"
                                        class="w-4 h-4 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Perlu Tenggat Waktu?</span>
                                </label>
                            </div> {{-- Due Date --}}
                            <div x-show="hasDueDate">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tenggat Waktu</label>
                                <input type="date" name="due_date"
                                    value="{{ old('due_date', $officialLetter->due_date ? $officialLetter->due_date->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Untuk surat yang memerlukan tindak lanjut/balasan</p>
                            </div> {{-- Priority --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prioritas *</label>
                                <select name="priority" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <option value="low"
                                        {{ old('priority', $officialLetter->priority) == 'low' ? 'selected' : '' }}>
                                        Rendah</option>
                                    <option value="normal"
                                        {{ old('priority', $officialLetter->priority) == 'normal' ? 'selected' : '' }}>
                                        Normal
                                    </option>
                                    <option value="high"
                                        {{ old('priority', $officialLetter->priority) == 'high' ? 'selected' : '' }}>
                                        Tinggi</option>
                                    <option value="urgent"
                                        {{ old('priority', $officialLetter->priority) == 'urgent' ? 'selected' : '' }}>
                                        Mendesak
                                    </option>
                                </select>
                            </div> {{-- Classification --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Klasifikasi *</label>
                                <select name="classification" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <option value="public"
                                        {{ old('classification', $officialLetter->classification) == 'public' ? 'selected' : '' }}>
                                        Publik</option>
                                    <option value="internal"
                                        {{ old('classification', $officialLetter->classification) == 'internal' ? 'selected' : '' }}>
                                        Internal</option>
                                    <option value="confidential"
                                        {{ old('classification', $officialLetter->classification) == 'confidential' ? 'selected' : '' }}>
                                        Rahasia</option>
                                    <option value="secret"
                                        {{ old('classification', $officialLetter->classification) == 'secret' ? 'selected' : '' }}>
                                        Sangat Rahasia</option>
                                </select>
                            </div>
                        </div>
                    </div> {{-- Reference --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Referensi</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat Rujukan</label>
                            <input type="text" name="reference_number"
                                value="{{ old('reference_number', $officialLetter->reference_number) }}"
                                placeholder="Nomor surat yang dirujuk"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Isi jika surat ini merupakan balasan/tindak lanjut dari
                                surat lain</p>
                        </div>
                    </div> {{-- Signatory (for outgoing only) --}}
                    <div x-show="direction === 'outgoing'"
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Penandatangan</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Penandatangan</label>
                                <select name="signatory"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <option value="">- Pilih User -</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('signatory', $officialLetter->signatory) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penandatangan *</label>
                                <input type="text" name="signatory_name"
                                    value="{{ old('signatory_name', $officialLetter->signatory_name) }}"
                                    placeholder="Dr. H. Abdullah Rahman, M.A."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan Penandatangan *</label>
                                <input type="text" name="signatory_position"
                                    value="{{ old('signatory_position', $officialLetter->signatory_position) }}"
                                    placeholder="Ketua Panitia Ramadhan 1447H"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            </div>
                        </div>
                    </div> {{-- Action Buttons --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit"
                                class="w-full px-6 py-3 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors font-medium">
                                💾 Update Surat
                            </button>
                            <a href="{{ route('admin.official-letters.show', $officialLetter) }}"
                                class="block w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center font-medium">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
