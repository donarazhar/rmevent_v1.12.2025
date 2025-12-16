@extends('admin.layouts.app')

@section('title', 'Tambah Income')

@section('content')
    <div x-data="{
        category: 'other',
        paymentMethod: 'bank_transfer',
        amount: 0,
        showRegistration: false,
        showSponsorship: false
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
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Income Baru</h1>
                    <p class="text-sm text-gray-600 mt-1">Catat pemasukan baru untuk event</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.incomes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Income Code --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Income <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="income_code" value="{{ old('income_code', $incomeCode) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('income_code') border-red-500 @enderror">
                        @error('income_code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
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
                            <option value="other" selected>Other</option>
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
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
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
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
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
                                    {{ old('budget_id') == $budget->id ? 'selected' : '' }}>
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
                    {{-- Registration (if registration_fee) --}}
                    <div x-show="showRegistration" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Registration</label>
                        <select name="registration_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Registration</option>
                            @foreach ($registrations as $registration)
                                <option value="{{ $registration->id }}"
                                    {{ old('registration_id') == $registration->id ? 'selected' : '' }}>
                                    {{ $registration->registration_code }} - {{ $registration->participant_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sponsorship (if sponsorship) --}}
                    <div x-show="showSponsorship" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sponsorship</label>
                        <select name="sponsorship_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Sponsorship</option>
                            @foreach ($sponsorships as $sponsorship)
                                <option value="{{ $sponsorship->id }}"
                                    {{ old('sponsorship_id') == $sponsorship->id ? 'selected' : '' }}>
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
                    {{-- Source Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Sumber/Donatur <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="source_name" value="{{ old('source_name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('source_name') border-red-500 @enderror">
                        @error('source_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Source Contact --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kontak</label>
                        <input type="text" name="source_contact" value="{{ old('source_contact') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('source_contact') border-red-500 @enderror">
                        @error('source_contact')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Source Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="source_email" value="{{ old('source_email') }}"
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
                    {{-- Amount --}}
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

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" x-model="paymentMethod" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('payment_method') border-red-500 @enderror">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer" selected>Bank Transfer</option>
                            <option value="e_wallet">E-Wallet</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="check">Check</option>
                            <option value="other">Other</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Reference --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referensi Pembayaran</label>
                        <input type="text" name="payment_reference" value="{{ old('payment_reference') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('payment_reference') border-red-500 @enderror"
                            placeholder="No. transfer, no. cek, dll">
                        @error('payment_reference')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bank Account --}}
                    <div x-show="paymentMethod === 'bank_transfer'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rekening Bank</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('bank_account') border-red-500 @enderror"
                            placeholder="BCA 1234567890">
                        @error('bank_account')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('payment_date') border-red-500 @enderror">
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Received Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Diterima</label>
                        <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('received_date') border-red-500 @enderror">
                        @error('received_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika sama dengan tanggal pembayaran</p>
                    </div>
                </div>
            </div>

            {{-- Receipt & Status --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bukti & Status</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Receipt File --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Bukti</label>
                        <input type="file" name="receipt_file" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, PNG. Max: 5MB</p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="pending" selected>Pending</option>
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
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
                    <span>Simpan Income</span>
                </button>
            </div>
        </form>
    </div>
@endsection
