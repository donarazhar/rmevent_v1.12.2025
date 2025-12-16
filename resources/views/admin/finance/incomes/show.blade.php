@extends('admin.layouts.app')

@section('title', 'Detail Income')

@section('content')
    <div x-data="{
        showVerifyModal: false,
        verificationNotes: '',
        showRejectModal: false,
        rejectionReason: ''
    }">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.incomes.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $income->title }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $income->income_code }} •
                            {{ $income->event->name ?? 'No Event' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if ($income->status === 'pending')
                        <button @click="showVerifyModal = true"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Verifikasi</span>
                        </button>
                    @endif

                    @if ($income->status === 'verified' && !$income->receipt_number)
                        <form action="{{ route('admin.incomes.receipt', $income) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Generate Receipt</span>
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.incomes.edit', $income) }}"
                        class="px-4 py-2 bg-[#0053C5] text-white rounded-lg hover:bg-[#003d8f] transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit</span>
                    </a>

                    <form action="{{ route('admin.incomes.destroy', $income) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus income ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Status Badge --}}
            <div class="flex items-center gap-3">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'verified' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                    ];
                    $categoryColors = [
                        'registration_fee' => 'bg-blue-100 text-blue-700',
                        'sponsorship' => 'bg-purple-100 text-purple-700',
                        'donation' => 'bg-green-100 text-green-700',
                        'infaq' => 'bg-emerald-100 text-emerald-700',
                        'merchandise' => 'bg-pink-100 text-pink-700',
                        'grant' => 'bg-indigo-100 text-indigo-700',
                        'other' => 'bg-gray-100 text-gray-700',
                    ];
                @endphp
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$income->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst($income->status) }}
                </span>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $categoryColors[$income->category] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst(str_replace('_', ' ', $income->category)) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Financial Summary --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Keuangan</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <div
                            class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-100">
                            <p class="text-sm text-green-600 mb-2">Total Amount</p>
                            <p class="text-4xl font-bold text-green-900">Rp
                                {{ number_format($income->amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-green-600 mt-2">{{ $income->formatted_amount }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <p class="text-xs text-blue-600 mb-1">Payment Method</p>
                                <p class="text-sm font-semibold text-blue-900">
                                    {{ ucfirst(str_replace('_', ' ', $income->payment_method)) }}</p>
                            </div>
                            <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                                <p class="text-xs text-purple-600 mb-1">Payment Date</p>
                                <p class="text-sm font-semibold text-purple-900">
                                    {{ $income->payment_date ? $income->payment_date->format('d M Y') : '-' }}</p>
                            </div>
                        </div>

                        @if ($income->payment_reference)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 mb-1">Payment Reference</p>
                                <p class="text-sm font-mono font-semibold text-gray-900">{{ $income->payment_reference }}
                                </p>
                            </div>
                        @endif

                        @if ($income->bank_account && $income->payment_method === 'bank_transfer')
                            <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                                <p class="text-xs text-indigo-600 mb-1">Bank Account</p>
                                <p class="text-sm font-semibold text-indigo-900">{{ $income->bank_account }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if ($income->description)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $income->description }}</p>
                    </div>
                @endif

                {{-- Source Information --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sumber/Donatur</h3>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 bg-[#0053C5]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#0053C5]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Nama</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $income->source_name }}</p>
                            </div>
                        </div>

                        @if ($income->source_contact)
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 mb-1">Kontak</p>
                                    <a href="tel:{{ $income->source_contact }}"
                                        class="text-sm font-semibold text-[#0053C5] hover:underline">{{ $income->source_contact }}</a>
                                </div>
                            </div>
                        @endif

                        @if ($income->source_email)
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 mb-1">Email</p>
                                    <a href="mailto:{{ $income->source_email }}"
                                        class="text-sm font-semibold text-[#0053C5] hover:underline">{{ $income->source_email }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Related Data --}}
                @if ($income->registration || $income->sponsorship)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Terkait</h3>

                        <div class="space-y-3">
                            @if ($income->registration)
                                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs text-blue-600 mb-1">Registration</p>
                                            <p class="text-sm font-semibold text-blue-900">
                                                {{ $income->registration->registration_code }}</p>
                                            <p class="text-xs text-blue-700 mt-1">
                                                {{ $income->registration->participant_name ?? 'N/A' }}</p>
                                        </div>
                                        <a href="{{ route('admin.registrations.show', $income->registration) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if ($income->sponsorship)
                                <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs text-purple-600 mb-1">Sponsorship</p>
                                            <p class="text-sm font-semibold text-purple-900">
                                                {{ $income->sponsorship->sponsor_code }}</p>
                                            <p class="text-xs text-purple-700 mt-1">
                                                {{ $income->sponsorship->company_name }}</p>
                                        </div>
                                        <a href="{{ route('admin.sponsorships.show', $income->sponsorship) }}"
                                            class="text-purple-600 hover:text-purple-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Verification Details --}}
                @if ($income->status !== 'pending')
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Verifikasi</h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Status</p>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$income->status] ?? 'bg-gray-100' }}">
                                        {{ ucfirst($income->status) }}
                                    </span>
                                </div>
                                @if ($income->verifier)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Verified By</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $income->verifier->name }}</p>
                                    </div>
                                @endif
                            </div>

                            @if ($income->verified_at)
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Verified At</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $income->verified_at->format('d M Y H:i') }}</p>
                                </div>
                            @endif

                            @if ($income->verification_notes)
                                <div
                                    class="p-4 {{ $income->status === 'rejected' ? 'bg-red-50 border-red-100' : 'bg-green-50 border-green-100' }} rounded-lg border">
                                    <p
                                        class="text-xs {{ $income->status === 'rejected' ? 'text-red-600' : 'text-green-600' }} mb-2 font-medium">
                                        Catatan Verifikasi:</p>
                                    <p
                                        class="text-sm {{ $income->status === 'rejected' ? 'text-red-800' : 'text-green-800' }}">
                                        {{ $income->verification_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Notes --}}
                @if ($income->notes)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $income->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Receipt Information --}}
                @if ($income->receipt_number || $income->receipt_file)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bukti Penerimaan</h3>

                        <div class="space-y-3">
                            @if ($income->receipt_number)
                                <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                                    <p class="text-xs text-green-600 mb-1">Receipt Number</p>
                                    <p class="text-sm font-mono font-bold text-green-900">{{ $income->receipt_number }}
                                    </p>
                                </div>
                            @endif

                            @if ($income->receipt_file)
                                <a href="{{ Storage::url($income->receipt_file) }}" target="_blank"
                                    class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-100 transition-colors">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-blue-900">Receipt File</p>
                                        <p class="text-xs text-blue-600">Klik untuk melihat</p>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endif

                            @if ($income->received_date)
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-xs text-gray-600 mb-1">Received Date</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $income->received_date->format('d M Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Event & Budget Info --}}
                @if ($income->event || $income->budget)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Event & Budget</h3>

                        <div class="space-y-3">
                            @if ($income->event)
                                <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <p class="text-xs text-blue-600 mb-1">Event</p>
                                    <p class="text-sm font-semibold text-blue-900">{{ $income->event->title }}</p>
                                    @if ($income->event->start_date)
                                        <p class="text-xs text-blue-600 mt-1">
                                            {{ $income->event->start_date->format('d M Y') }}</p>
                                    @endif
                                </div>
                            @endif

                            @if ($income->budget)
                                <div class="p-3 bg-purple-50 rounded-lg border border-purple-100">
                                    <p class="text-xs text-purple-600 mb-1">Budget</p>
                                    <p class="text-sm font-semibold text-purple-900">{{ $income->budget->budget_code }}
                                    </p>
                                    <p class="text-xs text-purple-600 mt-1">{{ $income->budget->title }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Metadata --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-500">Dibuat</span>
                            <span
                                class="text-gray-900 font-medium text-right">{{ $income->created_at ? $income->created_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                        @if ($income->recorder)
                            <div class="flex justify-between items-start">
                                <span class="text-gray-500">Dicatat oleh</span>
                                <span class="text-gray-900 font-medium text-right">{{ $income->recorder->name }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-start">
                            <span class="text-gray-500">Terakhir diubah</span>
                            <span
                                class="text-gray-900 font-medium text-right">{{ $income->updated_at ? $income->updated_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                        @if ($income->updated_at && $income->created_at && $income->updated_at != $income->created_at)
                            <div class="flex justify-between items-start">
                                <span class="text-gray-500">Update terakhir</span>
                                <span
                                    class="text-gray-900 font-medium text-right">{{ $income->updated_at->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="bg-gradient-to-br from-[#0053C5] to-[#003d8f] rounded-xl shadow-sm p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">Quick Info</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-blue-100">Income Code</span>
                            <span class="font-mono font-bold">{{ $income->income_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-100">Category</span>
                            <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $income->category)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-100">Payment Method</span>
                            <span
                                class="font-semibold">{{ ucfirst(str_replace('_', ' ', $income->payment_method)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-100">Status</span>
                            <span class="font-semibold">{{ ucfirst($income->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Verify Modal --}}
        <div x-show="showVerifyModal" x-cloak
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="showVerifyModal = false">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Verifikasi Income</h3>
                    <button @click="showVerifyModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-900">Konfirmasi Verifikasi</p>
                            <p class="text-xs text-green-700 mt-1">
                                Income sebesar <strong>Rp {{ number_format($income->amount, 0, ',', '.') }}</strong> akan
                                diverifikasi.
                                @if ($income->budget)
                                    Budget akan diupdate otomatis.
                                @endif
                                @if ($income->sponsorship)
                                    Sponsorship payment akan diupdate.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.incomes.verify', $income) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Verifikasi (Opsional)</label>
                        <textarea name="verification_notes" x-model="verificationNotes" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Tambahkan catatan verifikasi jika diperlukan..."></textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" @click="showVerifyModal = false"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Verifikasi Income
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
