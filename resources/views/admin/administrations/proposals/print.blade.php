<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print - {{ $proposal->proposal_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                margin: 1cm;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>

<body class="bg-white">
    {{-- Print Button --}}
    <div class="no-print fixed top-4 right-4 z-50">
        <button onclick="window.print()"
            class="px-6 py-3 bg-[#0053C5] hover:bg-[#004AB0] text-white font-medium rounded-lg shadow-lg transition-colors">
            🖨️ Print Proposal
        </button>
    </div>

    {{-- Print Content --}}
    <div class="max-w-5xl mx-auto p-8">
        {{-- Header --}}
        <div class="text-center mb-8 pb-6 border-b-2 border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">PROPOSAL</h1>
            <p class="text-xl font-semibold text-gray-700">{{ $proposal->title }}</p>
            <p class="text-sm text-gray-600 mt-2">{{ $proposal->proposal_code }}</p>
        </div>

        {{-- Basic Information --}}
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Informasi Dasar</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Tipe Proposal</p>
                    <p class="text-sm font-medium text-gray-900">{{ ucfirst($proposal->type) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="text-sm font-medium text-gray-900">{{ $proposal->status_label }}</p>
                </div>
                @if ($proposal->event)
                    <div>
                        <p class="text-sm text-gray-600">Event</p>
                        <p class="text-sm font-medium text-gray-900">{{ $proposal->event->title }}</p>
                    </div>
                @endif
                @if ($proposal->structure)
                    <div>
                        <p class="text-sm text-gray-600">Struktur Kepanitiaan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $proposal->structure->name }}</p>
                    </div>
                @endif
                @if ($proposal->submission_date)
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $proposal->submission_date->format('d F Y') }}
                        </p>
                    </div>
                @endif
                @if ($proposal->response_deadline)
                    <div>
                        <p class="text-sm text-gray-600">Batas Waktu Respon</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $proposal->response_deadline->format('d F Y') }}</p>
                    </div>
                @endif
            </div>

            @if ($proposal->description)
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Deskripsi</p>
                    <p class="text-sm text-gray-900 mt-1">{{ $proposal->description }}</p>
                </div>
            @endif
        </div>

        {{-- Recipient Information --}}
        @if ($proposal->submitted_to || $proposal->recipient_contact || $proposal->recipient_email)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Informasi Penerima</h2>
                <div class="grid grid-cols-3 gap-4">
                    @if ($proposal->submitted_to)
                        <div>
                            <p class="text-sm text-gray-600">Diajukan Kepada</p>
                            <p class="text-sm font-medium text-gray-900">{{ $proposal->submitted_to }}</p>
                        </div>
                    @endif
                    @if ($proposal->recipient_contact)
                        <div>
                            <p class="text-sm text-gray-600">Kontak Person</p>
                            <p class="text-sm font-medium text-gray-900">{{ $proposal->recipient_contact }}</p>
                        </div>
                    @endif
                    @if ($proposal->recipient_email)
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-sm font-medium text-gray-900">{{ $proposal->recipient_email }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Financial Information --}}
        @if ($proposal->requested_amount || $proposal->approved_amount)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Informasi Keuangan</h2>
                <div class="grid grid-cols-2 gap-4">
                    @if ($proposal->requested_amount)
                        <div>
                            <p class="text-sm text-gray-600">Dana yang Diminta</p>
                            <p class="text-base font-bold text-gray-900">Rp
                                {{ number_format($proposal->requested_amount, 0, ',', '.') }}</p>
                        </div>
                    @endif
                    @if ($proposal->approved_amount)
                        <div>
                            <p class="text-sm text-gray-600">Dana yang Disetujui</p>
                            <p class="text-base font-bold text-green-600">Rp
                                {{ number_format($proposal->approved_amount, 0, ',', '.') }}</p>
                            @if ($proposal->approval_percentage)
                                <p class="text-xs text-gray-600 mt-1">{{ $proposal->approval_percentage }}% dari
                                    permintaan</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Executive Summary --}}
        @if ($proposal->executive_summary)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Ringkasan Eksekutif</h2>
                <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">
                    {{ $proposal->executive_summary }}
                </div>
            </div>
        @endif

        {{-- Background --}}
        @if ($proposal->background)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Latar Belakang</h2>
                <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">{{ $proposal->background }}
                </div>
            </div>
        @endif

        {{-- Objectives --}}
        @if ($proposal->objectives)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Tujuan</h2>
                <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">{{ $proposal->objectives }}
                </div>
            </div>
        @endif

        {{-- Methodology --}}
        @if ($proposal->methodology)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Metodologi</h2>
                <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">{{ $proposal->methodology }}
                </div>
            </div>
        @endif

        {{-- Timeline --}}
        @if ($proposal->timeline)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Timeline</h2>
                <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">{{ $proposal->timeline }}</div>
            </div>
        @endif

        {{-- Budget Overview --}}
        @if ($proposal->budget_overview)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Rincian Budget</h2>
                <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">{{ $proposal->budget_overview }}
                </div>
            </div>
        @endif

        {{-- Expected Outcomes --}}
        @if ($proposal->expected_outcomes)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Hasil yang Diharapkan
                </h2>
                <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">
                    {{ $proposal->expected_outcomes }}</div>
            </div>
        @endif

        {{-- Page Break Before Footer --}}
        <div class="page-break"></div>

        {{-- Approval Information --}}
        @if ($proposal->isApproved())
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-300">Informasi Persetujuan
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    @if ($proposal->approvedBy)
                        <div>
                            <p class="text-sm text-gray-600">Disetujui Oleh</p>
                            <p class="text-sm font-medium text-gray-900">{{ $proposal->approvedBy->name }}</p>
                        </div>
                    @endif
                    @if ($proposal->approved_at)
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Persetujuan</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $proposal->approved_at->format('d F Y, H:i') }}</p>
                        </div>
                    @endif
                </div>
                @if ($proposal->approval_notes)
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm font-medium text-green-900 mb-2">Catatan Persetujuan:</p>
                        <div class="text-sm text-green-800 whitespace-pre-line">{{ $proposal->approval_notes }}</div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Footer --}}
        <div class="mt-12 pt-6 border-t-2 border-gray-300">
            <div class="flex justify-between items-end">
                <div class="text-sm text-gray-600">
                    <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
                    <p class="mt-1">Panitia Ramadhan 1447H</p>
                </div>

                @if ($proposal->createdBy)
                    <div class="text-right">
                        <p class="text-sm text-gray-600 mb-8">Dibuat oleh:</p>
                        <div class="border-t border-gray-400 pt-2 min-w-[200px]">
                            <p class="text-sm font-medium text-gray-900">{{ $proposal->createdBy->name }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
