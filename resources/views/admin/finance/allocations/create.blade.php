@extends('admin.layouts.app')

@section('title', 'Buat Alokasi Budget')

@section('content')
    <div x-data="{
        budget: null,
        totalBudget: 0,
        allocatedBudget: 0,
        availableBudget: 0,
        allocatedAmount: 0,
    
        updateBudgetInfo() {
            const select = document.getElementById('budget_id');
            const option = select.options[select.selectedIndex];
    
            if (option.value) {
                this.totalBudget = parseFloat(option.dataset.approved) || 0;
                this.allocatedBudget = parseFloat(option.dataset.allocated) || 0;
                this.availableBudget = this.totalBudget - this.allocatedBudget;
            } else {
                this.totalBudget = 0;
                this.allocatedBudget = 0;
                this.availableBudget = 0;
            }
        },
    
        validateAmount() {
            if (this.allocatedAmount > this.availableBudget) {
                return false;
            }
            return true;
        },
    
        formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }
    }" class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Alokasi Budget</h1>
                <p class="text-sm text-gray-600 mt-1">Tambahkan alokasi budget baru untuk kegiatan atau divisi</p>
            </div>
            <a href="{{ route('admin.budget-allocations.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2">
                <form action="{{ route('admin.budget-allocations.store') }}" method="POST"
                    @submit="if (!validateAmount()) { alert('Jumlah alokasi melebihi budget yang tersedia!'); $event.preventDefault(); }">
                    @csrf

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                        {{-- Budget Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Budget <span class="text-red-500">*</span>
                            </label>
                            <select name="budget_id" id="budget_id" @change="updateBudgetInfo()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('budget_id') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Budget --</option>
                                @foreach ($budgets as $budget)
                                    <option value="{{ $budget->id }}" data-approved="{{ $budget->total_approved }}"
                                        data-allocated="{{ $budget->total_allocated }}"
                                        {{ old('budget_id') == $budget->id ? 'selected' : '' }}>
                                        {{ $budget->budget_code }} - {{ $budget->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('budget_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Budget Info --}}
                        <div x-show="availableBudget > 0" x-transition
                            class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Total Budget</p>
                                    <p class="text-sm font-bold text-gray-900">Rp <span
                                            x-text="formatRupiah(totalBudget)"></span></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Sudah Dialokasikan</p>
                                    <p class="text-sm font-bold text-gray-900">Rp <span
                                            x-text="formatRupiah(allocatedBudget)"></span></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Tersedia</p>
                                    <p class="text-sm font-bold text-green-600">Rp <span
                                            x-text="formatRupiah(availableBudget)"></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Event --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Event <span class="text-red-500">*</span>
                                </label>
                                <select name="event_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('event_id') border-red-500 @enderror"
                                    required>
                                    <option value="">-- Pilih Event --</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Structure --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Struktur/Divisi
                                </label>
                                <select name="structure_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('structure_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Struktur --</option>
                                    @foreach ($structures as $structure)
                                        <option value="{{ $structure->id }}"
                                            {{ old('structure_id') == $structure->id ? 'selected' : '' }}>
                                            {{ $structure->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('structure_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Alokasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                placeholder="Contoh: Budget Konsumsi Acara"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('title') border-red-500 @enderror"
                                required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" rows="3" placeholder="Deskripsi alokasi budget..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Allocation Type --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipe Alokasi <span class="text-red-500">*</span>
                                </label>
                                <select name="allocation_type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('allocation_type') border-red-500 @enderror"
                                    required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="operational"
                                        {{ old('allocation_type') == 'operational' ? 'selected' : '' }}>Operasional
                                    </option>
                                    <option value="program" {{ old('allocation_type') == 'program' ? 'selected' : '' }}>
                                        Program</option>
                                    <option value="project" {{ old('allocation_type') == 'project' ? 'selected' : '' }}>
                                        Project</option>
                                    <option value="reserve" {{ old('allocation_type') == 'reserve' ? 'selected' : '' }}>
                                        Cadangan</option>
                                    <option value="contingency"
                                        {{ old('allocation_type') == 'contingency' ? 'selected' : '' }}>Kontingensi
                                    </option>
                                </select>
                                @error('allocation_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Allocated Amount --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Alokasi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="number" name="allocated_amount" x-model="allocatedAmount"
                                        value="{{ old('allocated_amount') }}" step="0.01" min="0"
                                        class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('allocated_amount') border-red-500 @enderror"
                                        required>
                                </div>
                                <p x-show="allocatedAmount > 0 && allocatedAmount <= availableBudget"
                                    class="text-green-600 text-xs mt-1">✓ Jumlah valid</p>
                                <p x-show="allocatedAmount > availableBudget" class="text-red-500 text-xs mt-1">⚠️ Jumlah
                                    melebihi budget yang tersedia!</p>
                                @error('allocated_amount')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Valid From --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Berlaku Dari <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="valid_from" value="{{ old('valid_from', date('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('valid_from') border-red-500 @enderror"
                                    required>
                                @error('valid_from')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Valid Until --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Berlaku Sampai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="valid_until" value="{{ old('valid_until') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('valid_until') border-red-500 @enderror"
                                    required>
                                @error('valid_until')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Allocated To --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Dialokasikan Kepada
                            </label>
                            <select name="allocated_to"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('allocated_to') border-red-500 @enderror">
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('allocated_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('allocated_to')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan
                            </label>
                            <textarea name="notes" rows="3" placeholder="Catatan tambahan..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.budget-allocations.index') }}"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Alokasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Info Panel --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Tipe Alokasi</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="font-medium text-gray-900">Operasional</p>
                            <p class="text-gray-600 text-xs">Untuk kebutuhan operasional rutin</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Program</p>
                            <p class="text-gray-600 text-xs">Untuk program/kegiatan tertentu</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Project</p>
                            <p class="text-gray-600 text-xs">Untuk proyek khusus</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Cadangan</p>
                            <p class="text-gray-600 text-xs">Budget cadangan</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Kontingensi</p>
                            <p class="text-gray-600 text-xs">Budget untuk keadaan darurat</p>
                        </div>
                    </div>
                </div>

                {{-- Tips Panel --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <p class="font-semibold mb-2">Catatan Penting:</p>
                            <ul class="space-y-1 list-disc list-inside">
                                <li>Pastikan jumlah alokasi tidak melebihi budget yang tersedia</li>
                                <li>Tanggal berlaku harus dalam periode yang valid</li>
                                <li>Alokasi akan berstatus "active" setelah dibuat</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
