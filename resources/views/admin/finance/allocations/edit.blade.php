@extends('admin.layouts.app')

@section('title', 'Edit Alokasi Budget')

@section('content')
    <div x-data="{
        budget: null,
        totalBudget: 0,
        allocatedBudget: 0,
        originalAllocated: {{ $budgetAllocation->allocated_amount }},
        availableBudget: 0,
        allocatedAmount: {{ old('allocated_amount', $budgetAllocation->allocated_amount) }},
        spentAmount: {{ $budgetAllocation->spent_amount }},
    
        updateBudgetInfo() {
            const select = document.getElementById('budget_id');
            const option = select.options[select.selectedIndex];
    
            if (option.value) {
                this.totalBudget = parseFloat(option.dataset.approved) || 0;
                this.allocatedBudget = parseFloat(option.dataset.allocated) || 0;
                const originalInBudget = parseFloat(option.dataset.originalAllocated) || 0;
                this.availableBudget = this.totalBudget - this.allocatedBudget + originalInBudget;
            }
        },
    
        validateAmount() {
            if (this.allocatedAmount < this.spentAmount) {
                return false;
            }
            if (this.allocatedAmount > this.availableBudget) {
                return false;
            }
            return true;
        },
    
        formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }
    }" x-init="updateBudgetInfo()" class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900">Edit Alokasi Budget</h1>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                        {{ $budgetAllocation->allocation_code }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi alokasi budget</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.budget-allocations.show', $budgetAllocation) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Detail
                </a>
                <a href="{{ route('admin.budget-allocations.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2">
                <form action="{{ route('admin.budget-allocations.update', $budgetAllocation) }}" method="POST"
                    @submit="if (!validateAmount()) { alert('Jumlah alokasi tidak valid!'); $event.preventDefault(); }">
                    @csrf
                    @method('PUT')

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
                                        data-original-allocated="{{ $budget->id == $budgetAllocation->budget_id ? $budgetAllocation->allocated_amount : 0 }}"
                                        {{ old('budget_id', $budgetAllocation->budget_id) == $budget->id ? 'selected' : '' }}>
                                        {{ $budget->budget_code }} - {{ $budget->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('budget_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Budget Info --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
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

                        {{-- Current Usage --}}
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Status Penggunaan Saat Ini</h4>
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Sudah Digunakan</p>
                                    <p class="text-sm font-bold text-red-600">Rp
                                        {{ number_format($budgetAllocation->spent_amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Sisa</p>
                                    <p class="text-sm font-bold text-green-600">Rp
                                        {{ number_format($budgetAllocation->remaining_amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Utilisasi</p>
                                    <p class="text-sm font-bold text-blue-600">
                                        {{ number_format($budgetAllocation->utilization_rate, 2) }}%</p>
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
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    required>
                                    <option value="">-- Pilih Event --</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id', $budgetAllocation->event_id) == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Structure --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Struktur/Divisi
                                </label>
                                <select name="structure_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                    <option value="">-- Pilih Struktur --</option>
                                    @foreach ($structures as $structure)
                                        <option value="{{ $structure->id }}"
                                            {{ old('structure_id', $budgetAllocation->structure_id) == $structure->id ? 'selected' : '' }}>
                                            {{ $structure->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Alokasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $budgetAllocation->title) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                required>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('description', $budgetAllocation->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Allocation Type --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipe Alokasi <span class="text-red-500">*</span>
                                </label>
                                <select name="allocation_type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="operational"
                                        {{ old('allocation_type', $budgetAllocation->allocation_type) == 'operational' ? 'selected' : '' }}>
                                        Operasional</option>
                                    <option value="program"
                                        {{ old('allocation_type', $budgetAllocation->allocation_type) == 'program' ? 'selected' : '' }}>
                                        Program</option>
                                    <option value="project"
                                        {{ old('allocation_type', $budgetAllocation->allocation_type) == 'project' ? 'selected' : '' }}>
                                        Project</option>
                                    <option value="reserve"
                                        {{ old('allocation_type', $budgetAllocation->allocation_type) == 'reserve' ? 'selected' : '' }}>
                                        Cadangan</option>
                                    <option value="contingency"
                                        {{ old('allocation_type', $budgetAllocation->allocation_type) == 'contingency' ? 'selected' : '' }}>
                                        Kontingensi</option>
                                </select>
                            </div>

                            {{-- Allocated Amount --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Alokasi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="number" name="allocated_amount" x-model="allocatedAmount"
                                        step="0.01" min="0"
                                        class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                        required>
                                </div>
                                <p x-show="allocatedAmount < spentAmount" class="text-red-500 text-xs mt-1">⚠️ Tidak boleh
                                    kurang dari yang sudah digunakan!</p>
                                <p x-show="allocatedAmount >= spentAmount && allocatedAmount <= availableBudget"
                                    class="text-green-600 text-xs mt-1">✓ Jumlah valid</p>
                                <p x-show="allocatedAmount > availableBudget" class="text-red-500 text-xs mt-1">⚠️
                                    Melebihi budget yang tersedia!</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Valid From --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Berlaku Dari <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="valid_from"
                                    value="{{ old('valid_from', $budgetAllocation->valid_from->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    required>
                            </div>

                            {{-- Valid Until --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Berlaku Sampai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="valid_until"
                                    value="{{ old('valid_until', $budgetAllocation->valid_until->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                                    required>
                            </div>
                        </div>

                        {{-- Allocated To --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dialokasikan Kepada</label>
                            <select name="allocated_to"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('allocated_to', $budgetAllocation->allocated_to) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">{{ old('notes', $budgetAllocation->notes) }}</textarea>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.budget-allocations.show', $budgetAllocation) }}"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#004AB0] transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update Alokasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status Info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Status Alokasi</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kode:</span>
                            <span class="font-medium">{{ $budgetAllocation->allocation_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span>
                                @if ($budgetAllocation->status === 'active')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                                @elseif($budgetAllocation->status === 'depleted')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Depleted</span>
                                @else
                                    <span
                                        class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">{{ ucfirst($budgetAllocation->status) }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dibuat:</span>
                            <span class="font-medium">{{ $budgetAllocation->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Oleh:</span>
                            <span class="font-medium">{{ $budgetAllocation->creator->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Warning Panel --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <p class="font-semibold mb-2">Peringatan:</p>
                            <ul class="space-y-1 list-disc list-inside text-xs">
                                <li>Jumlah alokasi tidak boleh kurang dari yang sudah terpakai</li>
                                <li>Pastikan tidak melebihi budget yang tersedia</li>
                                <li>Perubahan tanggal harus mempertimbangkan transaksi yang ada</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
