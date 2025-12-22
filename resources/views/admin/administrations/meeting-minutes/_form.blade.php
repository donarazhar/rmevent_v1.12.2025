<div class="space-y-6" x-data="{
    actionItems: {{ isset($meetingMinute) && $meetingMinute->action_items_list ? json_encode($meetingMinute->action_items_list) : '[]' }},
    externalParticipants: {{ isset($meetingMinute) && $meetingMinute->external_participants ? json_encode($meetingMinute->external_participants) : '[]' }},
    addActionItem() {
        this.actionItems.push({
            task: '',
            assignee: '',
            deadline: '',
            status: 'pending',
            notes: ''
        });
    },
    removeActionItem(index) {
        this.actionItems.splice(index, 1);
    },
    addExternalParticipant() {
        this.externalParticipants.push({
            name: '',
            organization: '',
            email: '',
            phone: ''
        });
    },
    removeExternalParticipant(index) {
        this.externalParticipants.splice(index, 1);
    }
}">
    {{-- Basic Information --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Meeting Title --}}
            <div class="md:col-span-2">
                <label for="meeting_title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Rapat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="meeting_title" id="meeting_title"
                    value="{{ old('meeting_title', $meetingMinute->meeting_title ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('meeting_title') border-red-500 @enderror">
                @error('meeting_title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Meeting Type --}}
            <div>
                <label for="meeting_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipe Rapat <span class="text-red-500">*</span>
                </label>
                <select name="meeting_type" id="meeting_type" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('meeting_type') border-red-500 @enderror">
                    <option value="">Pilih Tipe Rapat</option>
                    <option value="coordination"
                        {{ old('meeting_type', $meetingMinute->meeting_type ?? '') == 'coordination' ? 'selected' : '' }}>
                        Koordinasi</option>
                    <option value="planning"
                        {{ old('meeting_type', $meetingMinute->meeting_type ?? '') == 'planning' ? 'selected' : '' }}>
                        Perencanaan</option>
                    <option value="evaluation"
                        {{ old('meeting_type', $meetingMinute->meeting_type ?? '') == 'evaluation' ? 'selected' : '' }}>
                        Evaluasi</option>
                    <option value="emergency"
                        {{ old('meeting_type', $meetingMinute->meeting_type ?? '') == 'emergency' ? 'selected' : '' }}>
                        Darurat</option>
                    <option value="general"
                        {{ old('meeting_type', $meetingMinute->meeting_type ?? '') == 'general' ? 'selected' : '' }}>
                        Umum
                    </option>
                    <option value="other"
                        {{ old('meeting_type', $meetingMinute->meeting_type ?? '') == 'other' ? 'selected' : '' }}>
                        Lainnya</option>
                </select>
                @error('meeting_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Meeting Date --}}
            <div>
                <label for="meeting_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal & Waktu Rapat <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="meeting_date" id="meeting_date"
                    value="{{ old('meeting_date', isset($meetingMinute) && $meetingMinute->meeting_date ? $meetingMinute->meeting_date->format('Y-m-d\TH:i') : '') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('meeting_date') border-red-500 @enderror">
                @error('meeting_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Event --}}
            <div>
                <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Event (Opsional)
                </label>
                <select name="event_id" id="event_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('event_id') border-red-500 @enderror">
                    <option value="">Tidak Terkait Event</option>
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}"
                            {{ old('event_id', $meetingMinute->event_id ?? '') == $event->id ? 'selected' : '' }}>
                            {{ $event->title }}
                        </option>
                    @endforeach
                </select>
                @error('event_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Structure --}}
            <div>
                <label for="structure_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Struktur Kepanitiaan (Opsional)
                </label>
                <select name="structure_id" id="structure_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('structure_id') border-red-500 @enderror">
                    <option value="">Tidak Terkait Struktur</option>
                    @foreach ($structures as $structure)
                        <option value="{{ $structure->id }}"
                            {{ old('structure_id', $meetingMinute->structure_id ?? '') == $structure->id ? 'selected' : '' }}>
                            {{ $structure->name }}
                        </option>
                    @endforeach
                </select>
                @error('structure_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Location --}}
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                    Lokasi Rapat
                </label>
                <input type="text" name="location" id="location"
                    value="{{ old('location', $meetingMinute->location ?? '') }}"
                    placeholder="Ruang Rapat / Masjid / dll"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('location') border-red-500 @enderror">
                @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Meeting Link --}}
            <div>
                <label for="meeting_link" class="block text-sm font-medium text-gray-700 mb-2">
                    Link Meeting Online (Zoom/GMeet)
                </label>
                <input type="url" name="meeting_link" id="meeting_link"
                    value="{{ old('meeting_link', $meetingMinute->meeting_link ?? '') }}"
                    placeholder="https://meet.google.com/..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('meeting_link') border-red-500 @enderror">
                @error('meeting_link')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Duration --}}
            <div>
                <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                    Durasi Rapat (Menit)
                </label>
                <input type="number" name="duration_minutes" id="duration_minutes" min="1"
                    value="{{ old('duration_minutes', $meetingMinute->duration_minutes ?? '') }}" placeholder="120"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('duration_minutes') border-red-500 @enderror">
                @error('duration_minutes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Meeting Roles --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Peran Rapat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Chairman --}}
            <div>
                <label for="chairman" class="block text-sm font-medium text-gray-700 mb-2">
                    Ketua Rapat
                </label>
                <select name="chairman" id="chairman"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('chairman') border-red-500 @enderror">
                    <option value="">Pilih Ketua Rapat</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('chairman', $meetingMinute->chairman ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('chairman')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Secretary --}}
            <div>
                <label for="secretary" class="block text-sm font-medium text-gray-700 mb-2">
                    Sekretaris/Notulis
                </label>
                <select name="secretary" id="secretary"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('secretary') border-red-500 @enderror">
                    <option value="">Pilih Sekretaris</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('secretary', $meetingMinute->secretary ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('secretary')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Participants --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Peserta Rapat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Participants Present --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Peserta Hadir
                    <span class="text-xs text-gray-500 font-normal">(Pilih anggota panitia yang hadir)</span>
                </label>
                <div class="border border-gray-300 rounded-lg p-3 max-h-64 overflow-y-auto">
                    @forelse ($users as $user)
                        <label class="flex items-center gap-2 py-2 hover:bg-gray-50 px-2 rounded cursor-pointer">
                            <input type="checkbox" name="participants[]" value="{{ $user->id }}"
                                {{ in_array($user->id, old('participants', isset($meetingMinute) && $meetingMinute->participants ? $meetingMinute->participants : [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-[#0053C5] focus:ring-[#0053C5]">
                            <div class="flex-1">
                                <span class="text-sm text-gray-700">{{ $user->name }}</span>
                                @if ($user->position || $user->seksi)
                                    <span class="text-xs text-gray-500 block">
                                        {{ $user->position ?? '' }}
                                        {{ $user->seksi ? '- ' . $user->seksi : '' }}
                                    </span>
                                @endif
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Tidak ada anggota panitia tersedia</p>
                    @endforelse
                </div>
                @error('participants')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Absent Members --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Peserta Tidak Hadir
                    <span class="text-xs text-gray-500 font-normal">(Pilih anggota panitia yang tidak hadir)</span>
                </label>
                <div class="border border-gray-300 rounded-lg p-3 max-h-64 overflow-y-auto">
                    @forelse ($users as $user)
                        <label class="flex items-center gap-2 py-2 hover:bg-gray-50 px-2 rounded cursor-pointer">
                            <input type="checkbox" name="absent_members[]" value="{{ $user->id }}"
                                {{ in_array($user->id, old('absent_members', isset($meetingMinute) && $meetingMinute->absent_members ? $meetingMinute->absent_members : [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                            <div class="flex-1">
                                <span class="text-sm text-gray-700">{{ $user->name }}</span>
                                @if ($user->position || $user->seksi)
                                    <span class="text-xs text-gray-500 block">
                                        {{ $user->position ?? '' }}
                                        {{ $user->seksi ? '- ' . $user->seksi : '' }}
                                    </span>
                                @endif
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Tidak ada anggota panitia tersedia</p>
                    @endforelse
                </div>
                @error('absent_members')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- External Participants --}}
        <div class="mt-6">
            <div class="flex items-center justify-between mb-3">
                <label class="block text-sm font-medium text-gray-700">
                    Peserta Eksternal (Non-User)
                </label>
                <button type="button" @click="addExternalParticipant()"
                    class="text-sm text-[#0053C5] hover:text-[#004AB0] font-medium">
                    + Tambah Peserta Eksternal
                </button>
            </div>
            <div class="space-y-3">
                <template x-for="(participant, index) in externalParticipants" :key="index">
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <input type="text" :name="`external_participants[${index}][name]`"
                                x-model="participant.name" placeholder="Nama Lengkap"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <input type="text" :name="`external_participants[${index}][organization]`"
                                x-model="participant.organization" placeholder="Organisasi"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <input type="email" :name="`external_participants[${index}][email]`"
                                x-model="participant.email" placeholder="Email"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <div class="flex gap-2">
                                <input type="text" :name="`external_participants[${index}][phone]`"
                                    x-model="participant.phone" placeholder="No. Telepon"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <button type="button" @click="removeExternalParticipant(index)"
                                    class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <p class="text-xs text-gray-500" x-show="externalParticipants.length === 0">
                    Belum ada peserta eksternal. Klik tombol di atas untuk menambah.
                </p>
            </div>
        </div>
    </div>

    {{-- Meeting Content --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Konten Rapat</h3>
        <div class="space-y-4">
            {{-- Agenda --}}
            <div>
                <label for="agenda" class="block text-sm font-medium text-gray-700 mb-2">
                    Agenda Rapat
                </label>
                <textarea name="agenda" id="agenda" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('agenda') border-red-500 @enderror"
                    placeholder="1. Pembukaan&#10;2. Pembahasan proposal&#10;3. Evaluasi kegiatan&#10;4. Penutup">{{ old('agenda', $meetingMinute->agenda ?? '') }}</textarea>
                @error('agenda')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Discussion Summary --}}
            <div>
                <label for="discussion_summary" class="block text-sm font-medium text-gray-700 mb-2">
                    Ringkasan Diskusi
                </label>
                <textarea name="discussion_summary" id="discussion_summary" rows="6"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('discussion_summary') border-red-500 @enderror"
                    placeholder="Ringkasan pembahasan dan diskusi yang terjadi...">{{ old('discussion_summary', $meetingMinute->discussion_summary ?? '') }}</textarea>
                @error('discussion_summary')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Decisions --}}
            <div>
                <label for="decisions" class="block text-sm font-medium text-gray-700 mb-2">
                    Keputusan Rapat
                </label>
                <textarea name="decisions" id="decisions" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('decisions') border-red-500 @enderror"
                    placeholder="1. Menyetujui proposal X&#10;2. Menunda kegiatan Y&#10;3. Membentuk tim Z">{{ old('decisions', $meetingMinute->decisions ?? '') }}</textarea>
                @error('decisions')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Action Items (Text) --}}
            <div>
                <label for="action_items" class="block text-sm font-medium text-gray-700 mb-2">
                    Tindak Lanjut (Text)
                </label>
                <textarea name="action_items" id="action_items" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('action_items') border-red-500 @enderror"
                    placeholder="Ringkasan tindak lanjut yang perlu dilakukan...">{{ old('action_items', $meetingMinute->action_items ?? '') }}</textarea>
                @error('action_items')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Action Items List (Structured) --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Tindak Lanjut (Structured)</h3>
            <button type="button" @click="addActionItem()"
                class="px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white text-sm font-medium rounded-lg transition-colors">
                + Tambah Action Item
            </button>
        </div>

        <div class="space-y-3">
            <template x-for="(item, index) in actionItems" :key="index">
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="md:col-span-2">
                            <input type="text" :name="`action_items_list[${index}][task]`" x-model="item.task"
                                placeholder="Task / Tugas yang harus dilakukan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        </div>
                        <div>
                            <select :name="`action_items_list[${index}][assignee]`" x-model="item.assignee"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">Pilih Penanggung Jawab</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="date" :name="`action_items_list[${index}][deadline]`"
                                x-model="item.deadline" placeholder="Deadline"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        </div>
                        <div>
                            <select :name="`action_items_list[${index}][status]`" x-model="item.status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" :name="`action_items_list[${index}][notes]`" x-model="item.notes"
                                placeholder="Catatan tambahan"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <button type="button" @click="removeActionItem(index)"
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
            <p class="text-sm text-gray-500" x-show="actionItems.length === 0">
                Belum ada action item. Klik tombol "Tambah Action Item" untuk menambahkan.
            </p>
        </div>
    </div>

    {{-- Next Meeting --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rapat Berikutnya</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="next_meeting_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Rapat Berikutnya
                </label>
                <input type="datetime-local" name="next_meeting_date" id="next_meeting_date"
                    value="{{ old('next_meeting_date', isset($meetingMinute) && $meetingMinute->next_meeting_date ? $meetingMinute->next_meeting_date->format('Y-m-d\TH:i') : '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('next_meeting_date') border-red-500 @enderror">
                @error('next_meeting_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="next_meeting_location" class="block text-sm font-medium text-gray-700 mb-2">
                    Lokasi Rapat Berikutnya
                </label>
                <input type="text" name="next_meeting_location" id="next_meeting_location"
                    value="{{ old('next_meeting_location', $meetingMinute->next_meeting_location ?? '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('next_meeting_location') border-red-500 @enderror">
                @error('next_meeting_location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="next_meeting_agenda" class="block text-sm font-medium text-gray-700 mb-2">
                    Agenda Rapat Berikutnya
                </label>
                <textarea name="next_meeting_agenda" id="next_meeting_agenda" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('next_meeting_agenda') border-red-500 @enderror">{{ old('next_meeting_agenda', $meetingMinute->next_meeting_agenda ?? '') }}</textarea>
                @error('next_meeting_agenda')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Documents --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h3>
        <div class="space-y-4">
            {{-- Document File --}}
            <div>
                <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">
                    Dokumen Notulensi (PDF/DOC/DOCX)
                </label>
                @if (isset($meetingMinute) && $meetingMinute->document_file)
                    <div class="mb-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            File saat ini: <a href="{{ $meetingMinute->document_url }}" target="_blank"
                                class="font-medium underline">Lihat Dokumen</a>
                        </p>
                    </div>
                @endif
                <input type="file" name="document_file" id="document_file" accept=".pdf,.doc,.docx"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('document_file') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX. Maksimal 10MB.</p>
                @error('document_file')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Attachments --}}
            <div>
                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                    Lampiran Tambahan (Opsional)
                </label>
                @if (isset($meetingMinute) && $meetingMinute->attachments && count($meetingMinute->attachments) > 0)
                    <div class="mb-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-medium text-blue-800 mb-2">Lampiran Saat Ini:</p>
                        <ul class="space-y-1">
                            @foreach ($meetingMinute->attachments as $attachment)
                                <li class="text-sm text-blue-700">
                                    <a href="{{ Storage::url($attachment) }}" target="_blank" class="underline">
                                        {{ basename($attachment) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <input type="file" name="attachments[]" id="attachments" multiple
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('attachments.*') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, JPG, PNG, XLS, XLSX. Maksimal 5MB per
                    file.</p>
                @error('attachments.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan Tambahan</h3>
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                Catatan Internal
            </label>
            <textarea name="notes" id="notes" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $meetingMinute->notes ?? '') }}</textarea>
            @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Submit Buttons --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.meeting-minutes.index') }}"
            class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
            Batal
        </a>
        <button type="submit"
            class="px-6 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white font-medium rounded-lg transition-colors">
            {{ isset($meetingMinute) ? 'Update Notulensi' : 'Simpan Notulensi' }}
        </button>
    </div>
</div>
