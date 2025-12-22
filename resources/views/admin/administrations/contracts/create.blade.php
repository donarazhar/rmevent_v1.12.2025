@extends('admin.layouts.app')

@section('title', 'Buat Kontrak Baru')

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.contracts.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Kontrak Baru</h1>
                <p class="text-gray-600 mt-1">Isi informasi kontrak dengan lengkap</p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.contracts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Kontrak <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror"
                            placeholder="Contoh: Kontrak Sponsorship Acara Ramadhan">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Kontrak <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('type') border-red-500 @enderror">
                            <option value="">Pilih Tipe</option>
                            <option value="sponsorship" {{ old('type') == 'sponsorship' ? 'selected' : '' }}>Sponsorship
                            </option>
                            <option value="vendor" {{ old('type') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                            <option value="venue" {{ old('type') == 'venue' ? 'selected' : '' }}>Venue</option>
                            <option value="partnership" {{ old('type') == 'partnership' ? 'selected' : '' }}>Partnership
                            </option>
                            <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Service</option>
                            <option value="employment" {{ old('type') == 'employment' ? 'selected' : '' }}>Employment
                            </option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending_signature" {{ old('status') == 'pending_signature' ? 'selected' : '' }}>
                                Menunggu Tanda Tangan</option>
                            <option value="signed" {{ old('status') == 'signed' ? 'selected' : '' }}>Ditandatangani
                            </option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Event --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event (Opsional)</label>
                        <select name="event_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">-- Tidak terkait event --</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sponsorship --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sponsorship (Opsional)</label>
                        <select name="sponsorship_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">-- Tidak terkait sponsorship --</option>
                            @foreach ($sponsorships as $sponsorship)
                                <option value="{{ $sponsorship->id }}"
                                    {{ old('sponsorship_id') == $sponsorship->id ? 'selected' : '' }}>
                                    {{ $sponsorship->company_name }} - {{ $sponsorship->tier }}
                                    ({{ $sponsorship->sponsor_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                            placeholder="Deskripsi singkat tentang kontrak ini">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Party A (Our Organization) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pihak Pertama (Kami)</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Party A Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Organisasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="party_a_name"
                            value="{{ old('party_a_name', 'Panitia Ramadhan 1447H') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Party A Representative --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perwakilan</label>
                        <input type="text" name="party_a_representative" value="{{ old('party_a_representative') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Party A Address --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="party_a_address" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('party_a_address') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Party B (External Party) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pihak Kedua (Partner/Vendor)</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Party B Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Perusahaan/Organisasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="party_b_name" value="{{ old('party_b_name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('party_b_name') border-red-500 @enderror">
                        @error('party_b_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Party B Representative --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perwakilan</label>
                        <input type="text" name="party_b_representative" value="{{ old('party_b_representative') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Party B Contact --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                        <input type="text" name="party_b_contact" value="{{ old('party_b_contact') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Party B Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="party_b_email" value="{{ old('party_b_email') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Party B Address --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="party_b_address" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('party_b_address') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Contract Value & Period --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Nilai & Periode Kontrak</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Contract Value --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Kontrak</label>
                        <input type="number" name="contract_value" value="{{ old('contract_value') }}" step="0.01"
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    {{-- Currency --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mata Uang</label>
                        <select name="currency"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="IDR" {{ old('currency', 'IDR') == 'IDR' ? 'selected' : '' }}>IDR - Rupiah
                            </option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar
                            </option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        </select>
                    </div>

                    {{-- Auto Renewal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Perpanjangan Otomatis</label>
                        <div class="flex items-center h-[42px]">
                            <input type="checkbox" name="auto_renewal" value="1"
                                {{ old('auto_renewal') ? 'checked' : '' }}
                                class="w-5 h-5 text-[#0053C5] border-gray-300 rounded focus:ring-[#0053C5]">
                            <label class="ml-2 text-sm text-gray-700">Ya, perpanjang otomatis</label>
                        </div>
                    </div>

                    {{-- Start Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Berakhir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PIC Internal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PIC Internal</label>
                        <select name="pic_internal"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">-- Pilih PIC --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('pic_internal') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Terms & Scope --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ketentuan & Lingkup Pekerjaan</h2>

                <div class="space-y-6">
                    {{-- Terms and Conditions --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Syarat & Ketentuan</label>
                        <textarea name="terms_and_conditions" rows="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                            placeholder="Tuliskan syarat dan ketentuan kontrak...">{{ old('terms_and_conditions') }}</textarea>
                    </div>

                    {{-- Scope of Work --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lingkup Pekerjaan</label>
                        <textarea name="scope_of_work" rows="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                            placeholder="Deskripsikan lingkup pekerjaan yang akan dilakukan...">{{ old('scope_of_work') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Files --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Kontrak</h2>

                <div class="space-y-6">
                    {{-- Contract File --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Kontrak (PDF/DOC)</label>
                        <input type="file" name="contract_file" accept=".pdf,.doc,.docx"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX. Max: 10MB</p>
                    </div>

                    {{-- Supporting Documents --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Pendukung (Multiple)</label>
                        <input type="file" name="supporting_documents[]" multiple
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX, JPG, PNG. Max per file: 5MB</p>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>
                <textarea name="notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                    placeholder="Catatan internal tentang kontrak ini...">{{ old('notes') }}</textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-4 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <a href="{{ route('admin.contracts.index') }}"
                    class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#0053C5] hover:bg-[#004AB0] text-white rounded-lg transition-colors">
                    Simpan Kontrak
                </button>
            </div>
        </form>
    </div>
@endsection
