@extends('admin.layouts.app')

@section('title', 'Detail Proposal')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.proposals.index') }}"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $proposal->proposal_code }}</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $proposal->title }}</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Print Button --}}
                <a href="{{ route('admin.proposals.print', $proposal) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </a>

                @if ($proposal->document_file)
                    <a href="{{ route('admin.proposals.download', $proposal) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download
                    </a>
                @endif

                @if ($proposal->isDraft())
                    <a href="{{ route('admin.proposals.edit', $proposal) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>

                    <button @click="$refs.submitModal.showModal()"
                        class="inline-flex items-center px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Ajukan Proposal
                    </button>
                @endif

                @if (in_array($proposal->status, ['submitted', 'under_review']))
                    <button @click="$refs.approveModal.showModal()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Setujui
                    </button>

                    <button @click="$refs.reviseModal.showModal()"
                        class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Minta Revisi
                    </button>

                    <button @click="$refs.rejectModal.showModal()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tolak
                    </button>
                @endif
            </div>
        </div>

        {{-- Status & Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Status</p>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $proposal->status == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $proposal->status == 'submitted' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $proposal->status == 'under_review' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $proposal->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $proposal->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $proposal->status == 'revision_needed' ? 'bg-orange-100 text-orange-800' : '' }}">
                    {{ $proposal->status_label }}
                </span>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Tipe</p>
                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($proposal->type) }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Dana Diminta</p>
                <p class="text-lg font-semibold text-gray-900">
                    @if ($proposal->requested_amount)
                        Rp {{ number_format($proposal->requested_amount, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </p>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Dana Disetujui</p>
                <p class="text-lg font-semibold text-gray-900">
                    @if ($proposal->approved_amount)
                        Rp {{ number_format($proposal->approved_amount, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </p>
                @if ($proposal->approval_percentage)
                    <p class="text-xs text-green-600 mt-1">{{ $proposal->approval_percentage }}% dari permintaan</p>
                @endif
            </div>
        </div>

        {{-- Overdue Warning --}}
        @if ($proposal->is_overdue)
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800">Proposal Overdue</p>
                        <p class="text-sm text-red-700 mt-1">
                            Batas waktu respon: {{ $proposal->response_deadline->format('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Kode Proposal</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $proposal->proposal_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tipe</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ ucfirst($proposal->type) }}</p>
                            </div>
                            @if ($proposal->event)
                                <div>
                                    <p class="text-sm text-gray-600">Event</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $proposal->event->title }}</p>
                                </div>
                            @endif
                            @if ($proposal->structure)
                                <div>
                                    <p class="text-sm text-gray-600">Struktur Kepanitiaan</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $proposal->structure->name }}</p>
                                </div>
                            @endif
                        </div>

                        @if ($proposal->description)
                            <div>
                                <p class="text-sm text-gray-600">Deskripsi</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $proposal->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Recipient Information --}}
                @if ($proposal->submitted_to || $proposal->recipient_contact || $proposal->recipient_email)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penerima</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if ($proposal->submitted_to)
                                <div>
                                    <p class="text-sm text-gray-600">Diajukan Kepada</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $proposal->submitted_to }}</p>
                                </div>
                            @endif
                            @if ($proposal->recipient_contact)
                                <div>
                                    <p class="text-sm text-gray-600">Kontak Person</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $proposal->recipient_contact }}
                                    </p>
                                </div>
                            @endif
                            @if ($proposal->recipient_email)
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">
                                        <a href="mailto:{{ $proposal->recipient_email }}" class="text-blue-600 hover:underline">
                                            {{ $proposal->recipient_email }}
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Proposal Content --}}
                @if ($proposal->executive_summary || $proposal->background || $proposal->objectives || $proposal->methodology || $proposal->timeline || $proposal->expected_outcomes)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Konten Proposal</h3>
                        <div class="space-y-6">
                            @if ($proposal->executive_summary)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Ringkasan Eksekutif</h4>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">
                                        {{ $proposal->executive_summary }}
                                    </div>
                                </div>
                            @endif

                            @if ($proposal->background)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Latar Belakang</h4>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $proposal->background }}
                                    </div>
                                </div>
                            @endif

                            @if ($proposal->objectives)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Tujuan</h4>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $proposal->objectives }}
                                    </div>
                                </div>
                            @endif

                            @if ($proposal->methodology)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Metodologi</h4>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $proposal->methodology }}
                                    </div>
                                </div>
                            @endif

                            @if ($proposal->timeline)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Timeline</h4>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $proposal->timeline }}</div>
                                </div>
                            @endif

                            @if ($proposal->expected_outcomes)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Hasil yang Diharapkan</h4>
                                    <div class="text-sm text-gray-700 whitespace-pre-line">
                                        {{ $proposal->expected_outcomes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Budget Information --}}
                @if ($proposal->budget_overview)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Budget</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $proposal->budget_overview }}</div>
                    </div>
                @endif

                {{-- Documents --}}
                @if ($proposal->document_file || ($proposal->supporting_documents && count($proposal->supporting_documents) > 0))
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h3>
                        <div class="space-y-4">
                            @if ($proposal->document_file)
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">Dokumen Proposal</p>
                                    <a href="{{ $proposal->document_url }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium">Lihat Dokumen</span>
                                    </a>
                                </div>
                            @endif

                            @if ($proposal->supporting_documents && count($proposal->supporting_documents) > 0)
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">Dokumen Pendukung</p>
                                    <div class="space-y-2">
                                        @foreach ($proposal->supporting_documents as $doc)
                                            <a href="{{ Storage::url($doc) }}" target="_blank"
                                                class="flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <span class="text-sm text-gray-900">{{ basename($doc) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Notes --}}
                @if ($proposal->notes)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan Internal</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $proposal->notes }}</div>
                    </div>
                @endif
            </div>

            {{-- Right Column - Metadata & Workflow --}}
            <div class="space-y-6">
                {{-- Timeline --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Dibuat</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                {{ $proposal->created_at->format('d F Y, H:i') }}
                            </p>
                            @if ($proposal->createdBy)
                                <p class="text-xs text-gray-500 mt-1">oleh {{ $proposal->createdBy->name }}</p>
                            @endif
                        </div>

                        @if ($proposal->submission_date)
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $proposal->submission_date->format('d F Y') }}
                                </p>
                                @if ($proposal->submittedBy)
                                    <p class="text-xs text-gray-500 mt-1">oleh {{ $proposal->submittedBy->name }}</p>
                                @endif
                            </div>
                        @endif

                        @if ($proposal->response_deadline)
                            <div>
                                <p class="text-sm text-gray-600">Batas Waktu Respon</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $proposal->response_deadline->format('d F Y') }}
                                </p>
                            </div>
                        @endif

                        @if ($proposal->reviewed_at)
                            <div>
                                <p class="text-sm text-gray-600">Ditinjau</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $proposal->reviewed_at->format('d F Y, H:i') }}
                                </p>
                                @if ($proposal->reviewedBy)
                                    <p class="text-xs text-gray-500 mt-1">oleh {{ $proposal->reviewedBy->name }}</p>
                                @endif
                            </div>
                        @endif

                        @if ($proposal->approved_at)
                            <div>
                                <p class="text-sm text-gray-600">Disetujui</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $proposal->approved_at->format('d F Y, H:i') }}
                                </p>
                                @if ($proposal->approvedBy)
                                    <p class="text-xs text-gray-500 mt-1">oleh {{ $proposal->approvedBy->name }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Review Feedback --}}
                @if ($proposal->review_feedback)
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-orange-900 mb-4">Feedback Revisi</h3>
                        <div class="text-sm text-orange-800 whitespace-pre-line">{{ $proposal->review_feedback }}</div>
                    </div>
                @endif

                {{-- Approval Notes --}}
                @if ($proposal->approval_notes)
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-green-900 mb-4">Catatan Persetujuan</h3>
                        <div class="text-sm text-green-800 whitespace-pre-line">{{ $proposal->approval_notes }}</div>
                    </div>
                @endif

                {{-- Rejection Reason --}}
                @if ($proposal->rejection_reason)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-red-900 mb-4">Alasan Penolakan</h3>
                        <div class="text-sm text-red-800 whitespace-pre-line">{{ $proposal->rejection_reason }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Submit Modal --}}
    <dialog x-ref="submitModal" class="rounded-xl p-0 backdrop:bg-black/50 w-full max-w-md">
        <div class="bg-white rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ajukan Proposal</h3>
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin mengajukan proposal ini? Setelah diajukan, Anda tidak dapat mengedit proposal lagi.
            </p>
            <form action="{{ route('admin.proposals.submit', $proposal) }}" method="POST">
                @csrf
                <div class="flex justify-end gap-3">
                    <button type="button" @click="$refs.submitModal.close()"
                        class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#0053C5] hover:bg-[#004AB0] text-white rounded-lg transition-colors">
                        Ya, Ajukan
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- Approve Modal --}}
    <dialog x-ref="approveModal" class="rounded-xl p-0 backdrop:bg-black/50 w-full max-w-md">
        <div class="bg-white rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Setujui Proposal</h3>
            <form action="{{ route('admin.proposals.approve', $proposal) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dana yang Disetujui (Rp)</label>
                    <input type="number" name="approved_amount" min="0" step="0.01"
                        value="{{ $proposal->requested_amount }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Persetujuan</label>
                    <textarea name="approval_notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="$refs.approveModal.close()"
                        class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        Setujui Proposal
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- Revise Modal --}}
    <dialog x-ref="reviseModal" class="rounded-xl p-0 backdrop:bg-black/50 w-full max-w-md">
        <div class="bg-white rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Minta Revisi</h3>
            <form action="{{ route('admin.proposals.revise', $proposal) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Feedback Revisi <span
                            class="text-red-500">*</span></label>
                    <textarea name="review_feedback" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                        placeholder="Jelaskan apa yang perlu direvisi..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="$refs.reviseModal.close()"
                        class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors">
                        Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- Reject Modal --}}
    <dialog x-ref="rejectModal" class="rounded-xl p-0 backdrop:bg-black/50 w-full max-w-md">
        <div class="bg-white rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tolak Proposal</h3>
            <form action="{{ route('admin.proposals.reject', $proposal) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span
                            class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0053C5] focus:border-transparent"
                        placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="$refs.rejectModal.close()"
                        class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Tolak Proposal
                    </button>
                </div>
            </form>
        </div>
    </dialog>
@endsection