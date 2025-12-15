@extends('admin.layouts.app')

@section('title', 'Buat RAB Baru')

@section('content')
    <div class="space-y-6" x-data="budgetForm()">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat RAB Baru</h1>
                <p class="text-sm text-gray-600 mt-1">Rencana Anggaran Biaya</p>
            </div>
            <a href="{{ route('admin.budgets.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.budgets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Basic Information --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Event --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Event <span class="text-red-500">*</span>
                            </label>
                            <select name="event_id" required
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('event_id') border-red-500 @enderror">
                                <option value="">Pilih Event</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}"
                                        {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fiscal Year --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tahun Anggaran <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="fiscal_year" value="{{ old('fiscal_year', date('Y')) }}"
                                min="2020" max="2100" required
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('fiscal_year') border-red-500 @enderror">
                            @error('fiscal_year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul RAB <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="Contoh: RAB Acara Ramadhan 1447H"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="description" rows="3" placeholder="Deskripsi singkat tentang RAB ini..."
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Valid From --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Berlaku Dari <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="valid_from" value="{{ old('valid_from') }}" required
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('valid_from') border-red-500 @enderror">
                            @error('valid_from')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Valid Until --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Berlaku Sampai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="valid_until" value="{{ old('valid_until') }}" required
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('valid_until') border-red-500 @enderror">
                            @error('valid_until')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea name="notes" rows="2" placeholder="Catatan tambahan..."
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Budget Items --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Item Anggaran</h3>
                    <button type="button" @click="addItem()"
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary text-white text-sm rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Item
                    </button>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex items-start justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700">Item #<span x-text="index + 1"></span>
                                    </h4>
                                    <button type="button" @click="removeItem(index)"
                                        class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Code --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Kode Item <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'items[' + index + '][code]'" x-model="item.code"
                                            required placeholder="Contoh: A.1.1"
                                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                    </div>

                                    {{-- Name --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Nama Item <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'items[' + index + '][name]'" x-model="item.name"
                                            required placeholder="Contoh: Konsumsi Panitia"
                                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                    </div>

                                    {{-- Category --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Kategori <span class="text-red-500">*</span>
                                        </label>
                                        <select :name="'items[' + index + '][category]'" x-model="item.category" required
                                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                            <option value="">Pilih Kategori</option>
                                            <option value="operational">Operasional</option>
                                            <option value="program">Program</option>
                                            <option value="equipment">Peralatan</option>
                                            <option value="services">Jasa</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                    </div>

                                    {{-- Priority --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Prioritas <span class="text-red-500">*</span>
                                        </label>
                                        <select :name="'items[' + index + '][priority]'" x-model="item.priority" required
                                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>

                                    {{-- Quantity --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Kuantitas <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity"
                                            @input="calculateSubtotal(index)" required min="0" step="0.01"
                                            placeholder="0"
                                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                    </div>

                                    {{-- Unit --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Satuan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'items[' + index + '][unit]'" x-model="item.unit"
                                            required placeholder="pcs/pax/set/dll"
                                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                    </div>

                                    {{-- Unit Price --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Harga Satuan (Rp) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" :name="'items[' + index + '][unit_price]'"
                                            x-model="item.unit_price" @input="calculateSubtotal(index)" required
                                            min="0" step="0.01" placeholder="0"
                                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                                    </div>

                                    {{-- Subtotal (Read-only) --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Subtotal (Rp)
                                        </label>
                                        <input type="text" :value="formatCurrency(item.subtotal)" readonly
                                            class="w-full text-sm rounded-lg border-gray-300 bg-gray-100 text-gray-700">
                                    </div>

                                    {{-- Description --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Deskripsi
                                        </label>
                                        <textarea :name="'items[' + index + '][description]'" x-model="item.description" rows="2"
                                            placeholder="Deskripsi item..."
                                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm"></textarea>
                                    </div>

                                    {{-- Is Mandatory --}}
                                    <div class="md:col-span-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" :name="'items[' + index + '][is_mandatory]'"
                                                x-model="item.is_mandatory" value="1"
                                                class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-700">Item Wajib (Mandatory)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="items.length === 0"
                            class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada item anggaran</p>
                            <button type="button" @click="addItem()"
                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-primary-dark transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Item Pertama
                            </button>
                        </div>
                    </div>

                    {{-- Total Budget --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Total Items:</p>
                                <p class="text-xs text-gray-500" x-text="items.length + ' item(s)'"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-700">Total Budget Planned:</p>
                                <p class="text-2xl font-bold text-primary" x-text="formatCurrency(totalBudget)"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Attachments --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Lampiran</h3>
                </div>
                <div class="p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Dokumen Pendukung
                    </label>
                    <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark cursor-pointer">
                    <p class="mt-2 text-xs text-gray-500">
                        Format: PDF, DOC, DOCX, XLS, XLSX. Maksimal 10MB per file.
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <a href="{{ route('admin.budgets.index') }}"
                    class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium shadow-sm">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium shadow-sm">
                    Simpan RAB
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function budgetForm() {
            return {
                items: [],

                init() {
                    // Add first item by default
                    this.addItem();
                },

                addItem() {
                    this.items.push({
                        code: '',
                        name: '',
                        description: '',
                        category: '',
                        quantity: 1,
                        unit: '',
                        unit_price: 0,
                        subtotal: 0,
                        priority: 'medium',
                        is_mandatory: false
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                calculateSubtotal(index) {
                    const item = this.items[index];
                    item.subtotal = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
                },

                get totalBudget() {
                    return this.items.reduce((total, item) => total + (parseFloat(item.subtotal) || 0), 0);
                },

                formatCurrency(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value || 0);
                }
            }
        }
    </script>
@endpush
