@extends('admin.layouts.app')

@section('title', 'Detail Kontrak - ' . $contract->contract_code)

@section('content')
    <div class="space-y-6" x-data="{
        showSignModal: false,
        showRenewModal: false,
        showTerminateModal: false,
        signParty: 'party_a'
    }">
        {{-- Page Header --}}
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="flex items-start gap-4">
                <a href="{{ route('admin.contracts.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $contract->contract_code }}</h1>
                        @php
                            $statusConfig = [
                                'draft' => ['color' => 'gray', 'label' => 'Draft'],
                                'pending_signature' => ['color' => 'yellow', 'label' => 'Menunggu TTD'],
                                'signed' => ['color' => 'blue', 'label' => 'Ditandatangani'],
                                'active' => ['color' => 'green', 'label' => 'Aktif'],
                                'completed' => ['color' => 'indigo', 'label' => 'Selesai'],
                                'terminated' => ['color' => 'red', 'label' => 'Diakhiri'],
                                'expired' => ['color' => 'red', 'label' => 'Kadaluarsa'],
                            ];
                            $status = $statusConfig[$contract->status] ?? [
                                'color' => 'gray',
                                'label' => $contract->status,
                            ];
                        @endphp
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800">
                            {{ $status['label'] }}
                        </span>
                    </div>
                    <p class="text-xl text-gray-900 font-semibold">{{ $contract->title }}</p>
                    <p class="text-gray-600 mt-1">{{ $contract->description }}</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-2">
                @if ($contract->contract_file)
                    <a href="{{ route('admin.contracts.download', $contract) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Kontrak
                    </a>
                @endif

                @if (!$contract->isCompleted() && !$contract->isTerminated() && !$contract->isExpiredContract())
                    <button @click="showSignModal = true"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Tanda Tangan
                    </button>
                @endif

                @if ($contract->isActiveContract() || $contract->isSigned())
                    <button @click="showRenewModal = true"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Perpanjang
                    </button>

                    <button @click="showTerminateModal = true"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Akhiri Kontrak
                    </button>
                @endif

                <a href="{{ route('admin.contracts.edit', $contract) }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        {{-- Warning for Expiring Soon --}}
        @if ($contract->is_expiring_soon && $contract->isActiveContract())
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-orange-900">Kontrak Akan Segera Berakhir</h3>
                        <p class="text-sm text-orange-800 mt-1">
                            Kontrak ini akan berakhir dalam <strong>{{ $contract->days_remaining }} hari</strong> pada
                            tanggal
                            <strong>{{ $contract->end_date->format('d M Y') }}</strong>.
                            Segera lakukan perpanjangan jika diperlukan.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Contract Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontrak</h2>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tipe Kontrak</p>
                            <p class="font-medium text-gray-900 capitalize">{{ $contract->type }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nilai Kontrak</p>
                            <p class="font-medium text-gray-900">{{ $contract->contract_value_formatted }}</p>
                        </div>

                        @if ($contract->event)
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600 mb-1">Terkait Event</p>
                                <p class="font-medium text-gray-900">{{ $contract->event->name }}</p>
                            </div>
                        @endif

                        @if ($contract->sponsorship)
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600 mb-1">Terkait Sponsorship</p>
                                <p class="font-medium text-gray-900">
                                    {{ $contract->sponsorship->company_name }} -
                                    {{ ucfirst($contract->sponsorship->tier) }}
                                    ({{ $contract->sponsorship->sponsor_code }})
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Parties Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Pihak yang Terlibat</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Party A --}}
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-semibold text-blue-900 mb-3">Pihak Pertama (Kami)</h3>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm text-blue-700">Nama Organisasi</p>
                                    <p class="font-medium text-blue-900">{{ $contract->party_a_name }}</p>
                                </div>
                                @if ($contract->party_a_representative)
                                    <div>
                                        <p class="text-sm text-blue-700">Perwakilan</p>
                                        <p class="font-medium text-blue-900">{{ $contract->party_a_representative }}</p>
                                    </div>
                                @endif
                                @if ($contract->party_a_address)
                                    <div>
                                        <p class="text-sm text-blue-700">Alamat</p>
                                        <p class="text-sm text-blue-900">{{ $contract->party_a_address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Party B --}}
                        <div class="p-4 bg-green-50 rounded-lg">
                            <h3 class="font-semibold text-green-900 mb-3">Pihak Kedua (Partner)</h3>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm text-green-700">Nama Perusahaan</p>
                                    <p class="font-medium text-green-900">{{ $contract->party_b_name }}</p>
                                </div>
                                @if ($contract->party_b_representative)
                                    <div>
                                        <p class="text-sm text-green-700">Perwakilan</p>
                                        <p class="font-medium text-green-900">{{ $contract->party_b_representative }}</p>
                                    </div>
                                @endif
                                @if ($contract->party_b_contact)
                                    <div>
                                        <p class="text-sm text-green-700">Kontak</p>
                                        <p class="text-sm text-green-900">{{ $contract->party_b_contact }}</p>
                                    </div>
                                @endif
                                @if ($contract->party_b_email)
                                    <div>
                                        <p class="text-sm text-green-700">Email</p>
                                        <p class="text-sm text-green-900">{{ $contract->party_b_email }}</p>
                                    </div>
                                @endif
                                @if ($contract->party_b_address)
                                    <div>
                                        <p class="text-sm text-green-700">Alamat</p>
                                        <p class="text-sm text-green-900">{{ $contract->party_b_address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Terms & Conditions --}}
                @if ($contract->terms_and_conditions)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Syarat & Ketentuan</h2>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($contract->terms_and_conditions)) !!}
                        </div>
                    </div>
                @endif

                {{-- Scope of Work --}}
                @if ($contract->scope_of_work)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Lingkup Pekerjaan</h2>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($contract->scope_of_work)) !!}
                        </div>
                    </div>
                @endif

                {{-- Supporting Documents --}}
                @if ($contract->supporting_documents && count($contract->supporting_documents) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Pendukung</h2>
                        <div class="space-y-2">
                            @foreach ($contract->supporting_documents as $doc)
                                <a href="{{ Storage::url($doc) }}" target="_blank"
                                    class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="flex-1 text-sm font-medium text-gray-900">{{ basename($doc) }}</span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Period & Duration --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Periode Kontrak</h2>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Mulai</p>
                            <p class="font-medium text-gray-900">{{ $contract->start_date->format('d M Y') }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Berakhir</p>
                            <p class="font-medium text-gray-900">{{ $contract->end_date->format('d M Y') }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Durasi</p>
                            <p class="font-medium text-gray-900">{{ $contract->duration_days }} hari</p>
                        </div>

                        @if ($contract->isActiveContract())
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Sisa Waktu</p>
                                <p class="font-medium text-gray-900">{{ $contract->days_remaining }} hari lagi</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Perpanjangan Otomatis</p>
                            <p class="font-medium text-gray-900">{{ $contract->auto_renewal ? 'Ya' : 'Tidak' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Signatures Status --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Tanda Tangan</h2>

                    <div class="space-y-4">
                        {{-- Party A Signature --}}
                        <div class="p-4 {{ $contract->signed_at_party_a ? 'bg-green-50' : 'bg-gray-50' }} rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-medium text-gray-900">Pihak Pertama</p>
                                @if ($contract->signed_at_party_a)
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            @if ($contract->signed_at_party_a)
                                <p class="text-sm text-gray-700">
                                    Ditandatangani pada {{ $contract->signed_at_party_a->format('d M Y H:i') }}
                                </p>
                                @if ($contract->signedByPartyA)
                                    <p class="text-xs text-gray-600 mt-1">oleh {{ $contract->signedByPartyA->name }}</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-600">Belum ditandatangani</p>
                            @endif
                        </div>

                        {{-- Party B Signature --}}
                        <div class="p-4 {{ $contract->signed_at_party_b ? 'bg-green-50' : 'bg-gray-50' }} rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-medium text-gray-900">Pihak Kedua</p>
                                @if ($contract->signed_at_party_b)
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            @if ($contract->signed_at_party_b)
                                <p class="text-sm text-gray-700">
                                    Ditandatangani pada {{ $contract->signed_at_party_b->format('d M Y') }}
                                </p>
                            @else
                                <p class="text-sm text-gray-600">Belum ditandatangani</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Management Info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Manajemen</h2>

                    <div class="space-y-4">
                        @if ($contract->picInternal)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">PIC Internal</p>
                                <p class="font-medium text-gray-900">{{ $contract->picInternal->name }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Dibuat Oleh</p>
                            <p class="font-medium text-gray-900">{{ $contract->createdBy->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Dibuat</p>
                            <p class="font-medium text-gray-900">{{ $contract->created_at->format('d M Y H:i') }}</p>
                        </div>

                        @if ($contract->notes)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Catatan</p>
                                <p class="text-sm text-gray-900">{{ $contract->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Termination Info --}}
                @if ($contract->isTerminated())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <h2 class="text-lg font-semibold text-red-900 mb-4">Informasi Pengakhiran</h2>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-red-700 mb-1">Tanggal Diakhiri</p>
                                <p class="font-medium text-red-900">
                                    {{ $contract->termination_date ? $contract->termination_date->format('d M Y') : '-' }}
                                </p>
                            </div>

                            @if ($contract->termination_reason)
                                <div>
                                    <p class="text-sm text-red-700 mb-1">Alasan</p>
                                    <p class="text-sm text-red-900">{{ $contract->termination_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sign Modal --}}
        <div x-show="showSignModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="showSignModal = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showSignModal = false"></div>

                <div class="relative bg-white rounded-xl shadow-2xl max-w-lg w-full p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tanda Tangan Kontrak</h3>

                    <form action="{{ route('admin.contracts.sign', $contract) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pihak</label>
                                <select name="party" x-model="signParty"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                    <option value="party_a">Pihak Pertama (Kami)</option>
                                    <option value="party_b">Pihak Kedua (Partner)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Tanda Tangan</label>
                                <input type="date" name="signed_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Tanda Tangan
                                    (Opsional)</label>
                                <input type="file" name="signature_file" accept="image/*,.pdf"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF. Max: 2MB</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-6">
                            <button type="button" @click="showSignModal = false"
                                class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                Simpan Tanda Tangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Renew Modal --}}
        <div x-show="showRenewModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="showRenewModal = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showRenewModal = false"></div>

                <div class="relative bg-white rounded-xl shadow-2xl max-w-lg w-full p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Perpanjang Kontrak</h3>

                    <form action="{{ route('admin.contracts.renew', $contract) }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Baru</label>
                                <input type="date" name="start_date"
                                    value="{{ $contract->end_date->addDay()->format('Y-m-d') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir Baru</label>
                                <input type="date" name="end_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Kontrak Baru
                                    (Opsional)</label>
                                <input type="number" name="contract_value" value="{{ $contract->contract_value }}"
                                    step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-6">
                            <button type="button" @click="showRenewModal = false"
                                class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                                Perpanjang Kontrak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Terminate Modal --}}
        <div x-show="showTerminateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="showTerminateModal = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showTerminateModal = false"></div>

                <div class="relative bg-white rounded-xl shadow-2xl max-w-lg w-full p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Akhiri Kontrak</h3>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-red-800">
                            Tindakan ini akan mengakhiri kontrak sebelum masa berlakunya habis.
                            Pastikan Anda telah mendokumentasikan alasan pengakhiran dengan baik.
                        </p>
                    </div>

                    <form action="{{ route('admin.contracts.terminate', $contract) }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengakhiran</label>
                                <input type="date" name="termination_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pengakhiran</label>
                                <textarea name="termination_reason" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5]"
                                    placeholder="Jelaskan alasan mengakhiri kontrak..."></textarea>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-6">
                            <button type="button" @click="showTerminateModal = false"
                                class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                Akhiri Kontrak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
