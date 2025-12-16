@extends('admin.layouts.app')

@section('title', 'Edit Income')

@section('content')
    <div x-data="{
        category: '{{ old('category', $income->category) }}',
        paymentMethod: '{{ old('payment_method', $income->payment_method) }}',
        amount: {{ old('amount', $income->amount) }},
        showRegistration: {{ old('category', $income->category) === 'registration_fee' ? 'true' : 'false' }},
        showSponsorship: {{ old('category', $income->category) === 'sponsorship' ? 'true' : 'false' }}
    }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.incomes.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Income</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $income->income_code }} - {{ $income->title }}</p>
                </div>
            </div>
        </div>

        @if ($income->status === 'verified')
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-yellow-800">Peringatan</p>
                    <p class="text-xs text-yellow-700 mt-1">
                        Income ini sudah diverifikasi. Perubahan dapat mempengaruhi laporan keuangan.
                    </p>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.incomes.update', $income) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Income Code (readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Income</label>
                        <input type="text" value="{{ $income->income_code }}" readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        <p class="mt-1 text-xs text-gray-500">Kode tidak dapat diubah</p>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" x-model="category" required
                            @change="showRegistration = (category === 'registration_fee'); showSponsorship = (category === 'sponsorship')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('category') border-red-500 @enderror">
                            <option value="registration_fee">Registration Fee</option>
                            <option value="sponsorship">Sponsorship</option>
                            <option value="donation">Donation</option>
                            <option value="infaq">Infaq</option>
                            <option value="merchandise">Merchandise</option>
                            <option value="grant">Grant</option>
                            <option value="other">Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $income->title) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $income->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Event --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                        <select name="event_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('event_id') border-red-500 @enderror">
                            <option value="">Pilih Event (Opsional)</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ old('event_id', $income->event_id) == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Budget --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Budget</label>
                        <select name="budget_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('budget_id') border-red-500 @enderror">
                            <option value="">Pilih Budget (Opsional)</option>
                            @foreach ($budgets as $budget)
                                <option value="{{ $budget->id }}"
                                    {{ old('budget_id', $income->budget_id) == $budget->id ? 'selected' : '' }}>
                                    {{ $budget->budget_code }} - {{ $budget->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('budget_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Related Records --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Relasi Data</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Registration --}}
                    <div x-show="showRegistration" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Registration</label>
                        <select name="registration_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Registration</option>
                            @foreach ($registrations as $registration)
                                <option value="{{ $registration->id }}"
                                    {{ old('registration_id', $income->registration_id) == $registration->id ? 'selected' : '' }}>
                                    {{ $registration->registration_code }} - {{ $registration->participant_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sponsorship --}}
                    <div x-show="showSponsorship" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sponsorship</label>
                        <select name="sponsorship_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Sponsorship</option>
                            @foreach ($sponsorships as $sponsorship)
                                <option value="{{ $sponsorship->id }}"
                                    {{ old('sponsorship_id', $income->sponsorship_id) == $sponsorship->id ? 'selected' : '' }}>
                                    {{ $sponsorship->sponsor_code }} - {{ $sponsorship->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Source Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sumber</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Sumber/Donatur <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="source_name" value="{{ old('source_name', $income->source_name) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('source_name') border-red-500 @enderror">
                        @error('source_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kontak</label>
                        <input type="text" name="source_contact"
                            value="{{ old('source_contact', $income->source_contact) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('source_contact') border-red-500 @enderror">
                        @error('source_contact')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="source_email"
                            value="{{ old('source_email', $income->source_email) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('source_email') border-red-500 @enderror">
                        @error('source_email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pembayaran</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" x-model="amount" min="0" step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('amount') border-red-500 @enderror">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm font-medium text-[#0053C5]">
                            Rp <span x-text="parseFloat(amount || 0).toLocaleString('id-ID')">0</span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" x-model="paymentMethod" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('payment_method') border-red-500 @enderror">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="e_wallet">E-Wallet</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="check">Check</option>
                            <option value="other">Other</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referensi Pembayaran</label>
                        <input type="text" name="payment_reference"
                            value="{{ old('payment_reference', $income->payment_reference) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('payment_reference') border-red-500 @enderror">
                        @error('payment_reference')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="paymentMethod === 'bank_transfer'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rekening Bank</label>
                        <input type="text" name="bank_account"
                            value="{{ old('bank_account', $income->bank_account) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('bank_account') border-red-500 @enderror">
                        @error('bank_account')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="payment_date"
                            value="{{ old('payment_date', $income->payment_date->format('Y-m-d')) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('payment_date') border-red-500 @enderror">
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Diterima</label>
                        <input type="date" name="received_date"
                            value="{{ old('received_date', $income->received_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('received_date') border-red-500 @enderror">
                        @error('received_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Receipt & Status --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bukti & Status</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Bukti</label>
                        @if ($income->receipt_file)
                            <div class="mb-2 p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-700">File saat ini</span>
                                </div>
                                <a href="{{ Storage::url($income->receipt_file) }}" target="_blank"
                                    class="text-sm text-[#0053C5] hover:underline">Lihat</a>
                            </div>
                        @endif
                        <input type="file" name="receipt_file" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, PNG. Max: 5MB. Upload untuk mengganti.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status', $income->status) == 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="verified" {{ old('status', $income->status) == 'verified' ? 'selected' : '' }}>
                                Verified</option>
                            <option value="rejected" {{ old('status', $income->status) == 'rejected' ? 'selected' : '' }}>
                                Rejected</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $income->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.incomes.index') }}"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Update Income</span>
                </button>
            </div>
        </form>
    </div>
@endsection
