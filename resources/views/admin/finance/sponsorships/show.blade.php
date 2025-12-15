@extends('admin.layouts.app')

@section('title', 'Detail Sponsorship')

@section('content')
    <div x-data="{ showCancelModal: false, cancelReason: '' }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.sponsorships.index') }}"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $sponsorship->company_name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $sponsorship->sponsor_code }} •
                            {{ $sponsorship->event->name ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if ($sponsorship->status !== 'confirmed' && $sponsorship->status !== 'cancelled')
                        <form action="{{ route('admin.sponsorships.confirm', $sponsorship) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Konfirmasi sponsorship ini?')"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Konfirmasi</span>
                            </button>
                        </form>
                    @endif

                    @if ($sponsorship->status !== 'cancelled')
                        <button @click="showCancelModal = true"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Batalkan</span>
                        </button>
                    @endif

                    <a href="{{ route('admin.sponsorships.edit', $sponsorship) }}"
                        class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit</span>
                    </a>
                </div>
            </div>

            {{-- Status Badge --}}
            @php
                $statusColors = [
                    'prospecting' => 'bg-gray-100 text-gray-700',
                    'negotiating' => 'bg-yellow-100 text-yellow-700',
                    'committed' => 'bg-blue-100 text-blue-700',
                    'confirmed' => 'bg-green-100 text-green-700',
                    'delivered' => 'bg-emerald-100 text-emerald-700',
                    'completed' => 'bg-purple-100 text-purple-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                ];
            @endphp
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$sponsorship->status] ?? 'bg-gray-100 text-gray-700' }}">
                {{ ucfirst($sponsorship->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Financial Summary --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Finansial</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-600 mb-1">Total Komitmen</p>
                            <p class="text-2xl font-bold text-blue-900">Rp
                                {{ number_format($sponsorship->committed_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-green-600 mb-1">Diterima</p>
                            <p class="text-2xl font-bold text-green-900">Rp
                                {{ number_format($sponsorship->received_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <p class="text-sm text-orange-600 mb-1">Outstanding</p>
                            <p class="text-2xl font-bold text-orange-900">Rp
                                {{ number_format($sponsorship->outstanding_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Payment Progress --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Progress Pembayaran</span>
                            <span class="text-sm font-bold text-gray-900">{{ $sponsorship->payment_progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-500 h-3 rounded-full transition-all duration-300"
                                style="width: {{ $sponsorship->payment_progress }}%"></div>
                        </div>
                    </div>

                    @if ($sponsorship->type !== 'cash')
                        <div class="mt-6 p-4 bg-purple-50 rounded-lg border border-purple-100">
                            <h4 class="text-sm font-semibold text-purple-900 mb-2">In-Kind Contribution</h4>
                            @if ($sponsorship->in_kind_description)
                                <p class="text-sm text-purple-700 mb-2">{{ $sponsorship->in_kind_description }}</p>
                            @endif
                            @if ($sponsorship->in_kind_value)
                                <p class="text-sm font-medium text-purple-900">
                                    Nilai Estimasi: Rp {{ number_format($sponsorship->in_kind_value, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Sponsorship Details --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Sponsorship</h3>

                    <div class="grid grid-cols-2 gap-x-8 gap-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tier</p>
                            @php
                                $tierColors = [
                                    'platinum' => 'bg-slate-100 text-slate-700',
                                    'gold' => 'bg-yellow-100 text-yellow-700',
                                    'silver' => 'bg-gray-100 text-gray-700',
                                    'bronze' => 'bg-orange-100 text-orange-700',
                                ];
                            @endphp
                            <span
                                class="inline-block px-3 py-1 text-sm font-medium rounded-full {{ $tierColors[$sponsorship->tier] ?? 'bg-gray-100' }}">
                                {{ ucfirst($sponsorship->tier) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tipe</p>
                            @php
                                $typeColors = [
                                    'cash' => 'bg-green-100 text-green-700',
                                    'in_kind' => 'bg-purple-100 text-purple-700',
                                    'mixed' => 'bg-blue-100 text-blue-700',
                                ];
                            @endphp
                            <span
                                class="inline-block px-3 py-1 text-sm font-medium rounded-full {{ $typeColors[$sponsorship->type] ?? 'bg-gray-100' }}">
                                {{ ucfirst(str_replace('_', ' ', $sponsorship->type)) }}
                            </span>
                        </div>

                        @if ($sponsorship->proposal_sent_date)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Proposal Dikirim</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $sponsorship->proposal_sent_date->format('d M Y') }}</p>
                            </div>
                        @endif

                        @if ($sponsorship->commitment_date)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal Komitmen</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $sponsorship->commitment_date->format('d M Y') }}</p>
                            </div>
                        @endif

                        @if ($sponsorship->contract_date)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal Kontrak</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $sponsorship->contract_date->format('d M Y') }}</p>
                            </div>
                        @endif

                        @if ($sponsorship->fulfillment_date)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal Pemenuhan</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $sponsorship->fulfillment_date->format('d M Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment History --}}
                @if ($sponsorship->incomes->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Pembayaran</h3>

                        <div class="space-y-3">
                            @foreach ($sponsorship->incomes as $income)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $income->income_code }}</p>
                                            <p class="text-xs text-gray-500">{{ $income->date->format('d M Y') }} •
                                                {{ $income->createdBy->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-bold text-green-600">Rp
                                        {{ number_format($income->amount, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Notes --}}
                @if ($sponsorship->notes || $sponsorship->internal_notes)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h3>

                        @if ($sponsorship->notes)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Catatan Umum</h4>
                                <p class="text-sm text-gray-600">{{ $sponsorship->notes }}</p>
                            </div>
                        @endif

                        @if ($sponsorship->internal_notes)
                            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                                <h4 class="text-sm font-medium text-yellow-900 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Catatan Internal
                                </h4>
                                <p class="text-sm text-yellow-700 whitespace-pre-wrap">{{ $sponsorship->internal_notes }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Contact Information --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>

                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Contact Person</p>
                            <p class="text-sm font-medium text-gray-900">{{ $sponsorship->contact_person }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">Email</p>
                            <a href="mailto:{{ $sponsorship->email }}"
                                class="text-sm font-medium text-[#0053C5] hover:underline">
                                {{ $sponsorship->email }}
                            </a>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">Telepon</p>
                            <a href="tel:{{ $sponsorship->phone }}"
                                class="text-sm font-medium text-[#0053C5] hover:underline">
                                {{ $sponsorship->phone }}
                            </a>
                        </div>

                        @if ($sponsorship->website)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Website</p>
                                <a href="{{ $sponsorship->website }}" target="_blank" rel="noopener"
                                    class="text-sm font-medium text-[#0053C5] hover:underline">
                                    {{ $sponsorship->website }}
                                </a>
                            </div>
                        @endif

                        @if ($sponsorship->address)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Alamat</p>
                                <p class="text-sm text-gray-700">{{ $sponsorship->address }}</p>
                            </div>
                        @endif

                        @if ($sponsorship->picInternal)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">PIC Internal</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-[#0053C5] rounded-full flex items-center justify-center">
                                        <span
                                            class="text-xs font-medium text-white">{{ substr($sponsorship->picInternal->name, 0, 2) }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">{{ $sponsorship->picInternal->name }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Documents --}}
                @if ($sponsorship->proposal_document || $sponsorship->contract_document)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h3>

                        <div class="space-y-3">
                            @if ($sponsorship->proposal_document)
                                <a href="{{ Storage::url($sponsorship->proposal_document) }}" target="_blank"
                                    class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Proposal</p>
                                        <p class="text-xs text-gray-500">Klik untuk melihat</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endif

                            @if ($sponsorship->contract_document)
                                <a href="{{ Storage::url($sponsorship->contract_document) }}" target="_blank"
                                    class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Kontrak</p>
                                        <p class="text-xs text-gray-500">Klik untuk melihat</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Quick Actions --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>

                    <div class="space-y-2">
                        <form action="{{ route('admin.sponsorships.invoice', $sponsorship) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-left">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Generate Invoice</span>
                            </button>
                        </form>

                        <form action="{{ route('admin.sponsorships.receipt', $sponsorship) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-left">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Generate Receipt</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Metadata --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Dibuat</span>
                            <span
                                class="text-gray-900 font-medium">{{ $sponsorship->created_at->format('d M Y H:i') }}</span>
                        </div>
                        @if ($sponsorship->creator)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Dibuat oleh</span>
                                <span class="text-gray-900 font-medium">{{ $sponsorship->creator->name }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-500">Terakhir diubah</span>
                            <span
                                class="text-gray-900 font-medium">{{ $sponsorship->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cancel Modal --}}
        <div x-show="showCancelModal" x-cloak
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="showCancelModal = false">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Batalkan Sponsorship</h3>
                    <button @click="showCancelModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.sponsorships.cancel', $sponsorship) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan</label>
                        <textarea name="reason" x-model="cancelReason" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Jelaskan alasan pembatalan sponsorship ini..."></textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" @click="showCancelModal = false"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Batalkan Sponsorship
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
