@extends('admin.layouts.app')

@section('title', 'Edit Sponsorship')

@section('content')
    <div x-data="{
        type: '{{ old('type', $sponsorship->type) }}',
        tier: '{{ old('tier', $sponsorship->tier) }}',
        status: '{{ old('status', $sponsorship->status) }}',
        committedAmount: {{ old('committed_amount', $sponsorship->committed_amount) }},
        inKindValue: {{ old('in_kind_value', $sponsorship->in_kind_value ?? 0) }}
    }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.sponsorships.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Sponsorship</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $sponsorship->company_name }} -
                        {{ $sponsorship->sponsor_code }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.sponsorships.update', $sponsorship) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Event --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Event <span class="text-red-500">*</span>
                        </label>
                        <select name="event_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('event_id') border-red-500 @enderror">
                            <option value="">Pilih Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ old('event_id', $sponsorship->event_id) == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sponsor Code (readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Sponsor</label>
                        <input type="text" value="{{ $sponsorship->sponsor_code }}" readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        <p class="mt-1 text-xs text-gray-500">Kode tidak dapat diubah</p>
                    </div>

                    {{-- Company Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Perusahaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_name"
                            value="{{ old('company_name', $sponsorship->company_name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('company_name') border-red-500 @enderror">
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contact Person --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Person <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_person"
                            value="{{ old('contact_person', $sponsorship->contact_person) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('contact_person') border-red-500 @enderror">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $sponsorship->email) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $sponsorship->phone) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Website --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" name="website" value="{{ old('website', $sponsorship->website) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('website') border-red-500 @enderror"
                            placeholder="https://example.com">
                        @error('website')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PIC Internal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PIC Internal</label>
                        <select name="pic_internal"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('pic_internal') border-red-500 @enderror">
                            <option value="">Pilih PIC</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('pic_internal', $sponsorship->pic_internal) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('pic_internal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="address" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $sponsorship->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Sponsorship Details --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Sponsorship</h3>

                {{-- Warning if received amount exists --}}
                @if ($sponsorship->received_amount > 0)
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Peringatan Perubahan Komitmen</p>
                            <p class="text-xs text-yellow-700 mt-1">
                                Sudah ada pembayaran sebesar Rp
                                {{ number_format($sponsorship->received_amount, 0, ',', '.') }}.
                                Hati-hati saat mengubah nilai komitmen.
                            </p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tier --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tier <span class="text-red-500">*</span>
                        </label>
                        <select name="tier" x-model="tier" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('tier') border-red-500 @enderror">
                            <option value="platinum">Platinum</option>
                            <option value="gold">Gold</option>
                            <option value="silver">Silver</option>
                            <option value="bronze">Bronze</option>
                        </select>
                        @error('tier')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Sponsorship <span class="text-red-500">*</span>
                        </label>
                        <select name="type" x-model="type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('type') border-red-500 @enderror">
                            <option value="cash">Cash</option>
                            <option value="in_kind">In-Kind</option>
                            <option value="mixed">Mixed (Cash + In-Kind)</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Committed Amount --}}
                    <div x-show="type === 'cash' || type === 'mixed'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai Komitmen (Cash) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="committed_amount" x-model="committedAmount" min="0"
                            step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('committed_amount') border-red-500 @enderror">
                        @error('committed_amount')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Diterima: Rp {{ number_format($sponsorship->received_amount, 0, ',', '.') }} |
                            Outstanding: Rp {{ number_format($sponsorship->outstanding_amount, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" x-model="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="prospecting">Prospecting</option>
                            <option value="negotiating">Negotiating</option>
                            <option value="committed">Committed</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="delivered">Delivered</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- In-Kind Fields --}}
                    <div x-show="type === 'in_kind' || type === 'mixed'" class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi In-Kind
                        </label>
                        <textarea name="in_kind_description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('in_kind_description') border-red-500 @enderror"
                            placeholder="Contoh: 100 kaos, 50 mug, banner 3x4 meter">{{ old('in_kind_description', $sponsorship->in_kind_description) }}</textarea>
                        @error('in_kind_description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="type === 'in_kind' || type === 'mixed'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai In-Kind (Estimasi)
                        </label>
                        <input type="number" name="in_kind_value" x-model="inKindValue" min="0" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('in_kind_value') border-red-500 @enderror">
                        @error('in_kind_value')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Total Value Display --}}
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm font-medium text-blue-900">
                        Total Nilai Sponsorship:
                        <span class="text-lg font-bold">
                            Rp <span
                                x-text="(parseFloat(committedAmount || 0) + parseFloat(inKindValue || 0)).toLocaleString('id-ID')">0</span>
                        </span>
                    </p>
                </div>
            </div>

            {{-- Dates --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tanggal Penting</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Proposal Dikirim</label>
                        <input type="date" name="proposal_sent_date"
                            value="{{ old('proposal_sent_date', $sponsorship->proposal_sent_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Komitmen</label>
                        <input type="date" name="commitment_date"
                            value="{{ old('commitment_date', $sponsorship->commitment_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kontrak</label>
                        <input type="date" name="contract_date"
                            value="{{ old('contract_date', $sponsorship->contract_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pemenuhan</label>
                        <input type="date" name="fulfillment_date"
                            value="{{ old('fulfillment_date', $sponsorship->fulfillment_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- Documents --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proposal Dokumen</label>
                        @if ($sponsorship->proposal_document)
                            <div class="mb-2 p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-700">Dokumen saat ini</span>
                                </div>
                                <a href="{{ Storage::url($sponsorship->proposal_document) }}" target="_blank"
                                    class="text-sm text-[#0053C5] hover:underline">Lihat</a>
                            </div>
                        @endif
                        <input type="file" name="proposal_document" accept=".pdf,.doc,.docx"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX. Max: 5MB. Upload untuk mengganti.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kontrak Dokumen</label>
                        @if ($sponsorship->contract_document)
                            <div class="mb-2 p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-700">Dokumen saat ini</span>
                                </div>
                                <a href="{{ Storage::url($sponsorship->contract_document) }}" target="_blank"
                                    class="text-sm text-[#0053C5] hover:underline">Lihat</a>
                            </div>
                        @endif
                        <input type="file" name="contract_document" accept=".pdf,.doc,.docx"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX. Max: 5MB. Upload untuk mengganti.</p>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h3>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Umum</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('notes', $sponsorship->notes) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Internal</label>
                        <textarea name="internal_notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('internal_notes', $sponsorship->internal_notes) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Catatan ini tidak akan terlihat oleh sponsor</p>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.sponsorships.index') }}"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Update Sponsorship</span>
                </button>
            </div>
        </form>
    </div>
@endsection
