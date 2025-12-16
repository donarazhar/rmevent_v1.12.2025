@extends('admin.layouts.app')

@section('title', 'Ajukan Expense')

@section('content')
    <div x-data="{
        category: 'operational',
        requestedAmount: 0,
        taxAmount: 0,
        taxType: '',
        hasTaxInvoice: false,
        totalAmount: 0,
        paymentMethod: 'bank_transfer',
        calculateTotal() {
            this.totalAmount = parseFloat(this.requestedAmount || 0) + parseFloat(this.taxAmount || 0);
        }
    }" x-init="$watch('requestedAmount', () => calculateTotal());
    $watch('taxAmount', () => calculateTotal())">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.expenses.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Ajukan Expense Baru</h1>
                    <p class="text-sm text-gray-600 mt-1">Buat pengajuan pengeluaran untuk event</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Expense Code --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Expense <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="expense_code" value="{{ old('expense_code', $expenseCode) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('expense_code') border-red-500 @enderror">
                        @error('expense_code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" x-model="category" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('category') border-red-500 @enderror">
                            <option value="operational">Operational</option>
                            <option value="event_execution">Event Execution</option>
                            <option value="equipment">Equipment</option>
                            <option value="logistics">Logistics</option>
                            <option value="marketing">Marketing</option>
                            <option value="transportation">Transportation</option>
                            <option value="accommodation">Accommodation</option>
                            <option value="meals">Meals</option>
                            <option value="honorarium">Honorarium</option>
                            <option value="utilities">Utilities</option>
                            <option value="other">Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Expense <span class="text-red-500">*</span>
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

                    {{-- Budget Item --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Budget Item</label>
                        <select name="budget_item_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Budget Item (Opsional)</option>
                            @foreach ($budgetItems as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('budget_item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_code }} - {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Budget Allocation --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Budget Allocation</label>
                        <select name="budget_allocation_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Allocation (Opsional)</option>
                            @foreach ($budgetAllocations as $allocation)
                                <option value="{{ $allocation->id }}"
                                    {{ old('budget_allocation_id') == $allocation->id ? 'selected' : '' }}>
                                    {{ $allocation->allocation_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Structure --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Committee Structure</label>
                        <select name="structure_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Structure (Opsional)</option>
                            @foreach ($structures as $structure)
                                <option value="{{ $structure->id }}"
                                    {{ old('structure_id') == $structure->id ? 'selected' : '' }}>
                                    {{ $structure->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Vendor Information --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Vendor/Penerima</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Vendor Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Vendor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="vendor_name" value="{{ old('vendor_name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('vendor_name') border-red-500 @enderror">
                        @error('vendor_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Vendor Contact --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kontak Vendor</label>
                        <input type="text" name="vendor_contact" value="{{ old('vendor_contact') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('vendor_contact') border-red-500 @enderror">
                        @error('vendor_contact')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Vendor Address --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Vendor</label>
                        <textarea name="vendor_address" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('vendor_address') border-red-500 @enderror">{{ old('vendor_address') }}</textarea>
                        @error('vendor_address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Vendor Tax ID --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NPWP Vendor</label>
                        <input type="text" name="vendor_tax_id" value="{{ old('vendor_tax_id') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('vendor_tax_id') border-red-500 @enderror"
                            placeholder="00.000.000.0-000.000">
                        @error('vendor_tax_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Amount & Tax --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Nilai & Pajak</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Requested Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai yang Diajukan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="requested_amount" x-model="requestedAmount" min="0"
                            step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('requested_amount') border-red-500 @enderror">
                        @error('requested_amount')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm font-medium text-[#0053C5]">
                            Rp <span x-text="parseFloat(requestedAmount || 0).toLocaleString('id-ID')">0</span>
                        </p>
                    </div>

                    {{-- Tax Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Pajak</label>
                        <input type="number" name="tax_amount" x-model="taxAmount" min="0" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('tax_amount') border-red-500 @enderror">
                        @error('tax_amount')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm font-medium text-orange-600">
                            Rp <span x-text="parseFloat(taxAmount || 0).toLocaleString('id-ID')">0</span>
                        </p>
                    </div>

                    {{-- Tax Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pajak</label>
                        <select name="tax_type" x-model="taxType"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                            <option value="">Pilih Jenis Pajak</option>
                            <option value="PPh21">PPh21 (Pajak Penghasilan Pasal 21)</option>
                            <option value="PPh23">PPh23 (Pajak Penghasilan Pasal 23)</option>
                            <option value="PPh4(2)">PPh4(2) (Pajak Penghasilan Final)</option>
                            <option value="PPN">PPN (Pajak Pertambahan Nilai)</option>
                            <option value="Other">Lainnya</option>
                        </select>
                    </div>

                    {{-- Has Tax Invoice --}}
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="has_tax_invoice" x-model="hasTaxInvoice" value="1"
                                class="w-4 h-4 text-[#0053C5] rounded focus:ring-[#0053C5]">
                            <span class="text-sm font-medium text-gray-700">Ada Faktur Pajak</span>
                        </label>
                    </div>

                    {{-- Total Amount Display --}}
                    <div
                        class="md:col-span-2 p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-700 mb-1">Total Nilai (termasuk pajak)</p>
                        <p class="text-3xl font-bold text-blue-900">
                            Rp <span x-text="totalAmount.toLocaleString('id-ID')">0</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Dates --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tanggal</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Request Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Request <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="request_date" value="{{ old('request_date', date('Y-m-d')) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('request_date') border-red-500 @enderror">
                        @error('request_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Needed By Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deadline Kebutuhan</label>
                        <input type="date" name="needed_by_date" value="{{ old('needed_by_date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('needed_by_date') border-red-500 @enderror">
                        @error('needed_by_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kapan budget ini dibutuhkan?</p>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" x-model="paymentMethod" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('payment_method') border-red-500 @enderror">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer" selected>Bank Transfer</option>
                            <option value="check">Check</option>
                            <option value="petty_cash">Petty Cash</option>
                            <option value="e_wallet">E-Wallet</option>
                            <option value="other">Other</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bank Account (if bank_transfer) --}}
                    <div x-show="paymentMethod === 'bank_transfer'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rekening Bank</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('bank_account') border-red-500 @enderror"
                            placeholder="BCA 1234567890 a.n. Vendor">
                        @error('bank_account')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Documents & Notes --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen & Catatan</h3>

                <div class="grid grid-cols-1 gap-6">
                    {{-- Invoice File --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Invoice/Quotation</label>
                        <input type="file" name="invoice_file" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, PNG. Max: 5MB</p>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="draft">Draft (Simpan untuk dilanjutkan nanti)</option>
                            <option value="submitted">Submit (Langsung ajukan untuk review)</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.expenses.index') }}"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Expense</span>
                </button>
            </div>
        </form>
    </div>
@endsection
