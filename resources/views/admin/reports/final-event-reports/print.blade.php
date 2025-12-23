<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $finalEventReport->title }} - Print</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            color: #333;
            padding: 40px;
            max-width: 210mm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0053C5;
        }

        .header h1 {
            font-size: 24px;
            color: #0053C5;
            margin-bottom: 10px;
        }

        .header .subtitle {
            font-size: 14px;
            color: #666;
        }

        .meta-info {
            margin: 20px 0;
            padding: 15px;
            background: #f5f5f5;
            border-left: 4px solid #0053C5;
        }

        .meta-info table {
            width: 100%;
        }

        .meta-info td {
            padding: 5px;
            font-size: 14px;
        }

        .meta-info td:first-child {
            font-weight: bold;
            width: 150px;
        }

        .section {
            margin: 30px 0;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #0053C5;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e0e0;
        }

        .section-content {
            font-size: 14px;
            text-align: justify;
            white-space: pre-wrap;
        }

        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }

        .stat-box {
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
        }

        .stat-box .label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-box .value {
            font-size: 20px;
            font-weight: bold;
            color: #0053C5;
        }

        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .financial-table th,
        .financial-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #e0e0e0;
        }

        .financial-table th {
            background: #0053C5;
            color: white;
            font-weight: bold;
        }

        .financial-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .financial-table .total-row {
            background: #e3f2fd;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            font-size: 12px;
            color: #666;
        }

        .signatures {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 50px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-box .title {
            font-size: 12px;
            margin-bottom: 60px;
        }

        .signature-box .name {
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .section {
                page-break-inside: avoid;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #0053C5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .print-button:hover {
            background: #004AB0;
        }
    </style>
</head>

<body>
    {{-- Print Button --}}
    <button onclick="window.print()" class="print-button no-print">🖨️ Print Laporan</button>

    {{-- Header --}}
    <div class="header">
        <h1>{{ $finalEventReport->title }}</h1>
        <div class="subtitle">
            {{ $finalEventReport->report_code }} | {{ $finalEventReport->report_date->format('d F Y') }}
        </div>
    </div>

    {{-- Meta Information --}}
    <div class="meta-info">
        <table>
            <tr>
                <td>Nama Acara</td>
                <td>: {{ $finalEventReport->event->title }}</td>
            </tr>
            <tr>
                <td>Tanggal Acara</td>
                <td>: {{ $finalEventReport->event->start_datetime->format('d M Y') }} -
                    {{ $finalEventReport->event->end_datetime->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>: {{ $finalEventReport->event->location ?? '-' }}</td>
            </tr>
            <tr>
                <td>Status Laporan</td>
                <td>: {{ $finalEventReport->status_label }}</td>
            </tr>
            <tr>
                <td>Dibuat Oleh</td>
                <td>: {{ $finalEventReport->createdBy->name }}</td>
            </tr>
        </table>
    </div>

    {{-- Executive Summary --}}
    @if ($finalEventReport->executive_summary)
        <div class="section">
            <div class="section-title">I. RINGKASAN EKSEKUTIF</div>
            <div class="section-content">{{ $finalEventReport->executive_summary }}</div>
        </div>
    @endif

    {{-- Event Overview --}}
    @if ($finalEventReport->event_overview)
        <div class="section">
            <div class="section-title">II. GAMBARAN UMUM ACARA</div>
            <div class="section-content">{{ $finalEventReport->event_overview }}</div>
        </div>
    @endif

    {{-- Statistics --}}
    @if ($finalEventReport->total_participants || $finalEventReport->attendance_rate)
        <div class="section">
            <div class="section-title">III. STATISTIK PESERTA</div>
            <div class="statistics-grid">
                @if ($finalEventReport->total_participants)
                    <div class="stat-box">
                        <div class="label">Total Peserta</div>
                        <div class="value">{{ number_format($finalEventReport->total_participants) }} orang</div>
                    </div>
                @endif

                @if ($finalEventReport->registered_participants)
                    <div class="stat-box">
                        <div class="label">Peserta Terdaftar</div>
                        <div class="value">{{ number_format($finalEventReport->registered_participants) }} orang</div>
                    </div>
                @endif

                @if ($finalEventReport->attended_participants)
                    <div class="stat-box">
                        <div class="label">Peserta Hadir</div>
                        <div class="value">{{ number_format($finalEventReport->attended_participants) }} orang</div>
                    </div>
                @endif

                @if ($finalEventReport->attendance_rate)
                    <div class="stat-box">
                        <div class="label">Tingkat Kehadiran</div>
                        <div class="value">{{ number_format($finalEventReport->attendance_rate, 1) }}%</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Objectives Achievement --}}
    @if ($finalEventReport->objectives_achievement)
        <div class="section">
            <div class="section-title">IV. PENCAPAIAN TUJUAN</div>
            <div class="section-content">{{ $finalEventReport->objectives_achievement }}</div>

            @if ($finalEventReport->overall_satisfaction)
                <div class="statistics-grid" style="margin-top: 20px;">
                    <div class="stat-box">
                        <div class="label">Kepuasan Keseluruhan</div>
                        <div class="value">{{ number_format($finalEventReport->overall_satisfaction, 1) }}/5.0</div>
                    </div>

                    @if ($finalEventReport->content_rating)
                        <div class="stat-box">
                            <div class="label">Rating Konten</div>
                            <div class="value">{{ number_format($finalEventReport->content_rating, 1) }}/5.0</div>
                        </div>
                    @endif

                    @if ($finalEventReport->organization_rating)
                        <div class="stat-box">
                            <div class="label">Rating Organisasi</div>
                            <div class="value">{{ number_format($finalEventReport->organization_rating, 1) }}/5.0
                            </div>
                        </div>
                    @endif

                    @if ($finalEventReport->venue_rating)
                        <div class="stat-box">
                            <div class="label">Rating Venue</div>
                            <div class="value">{{ number_format($finalEventReport->venue_rating, 1) }}/5.0</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- Implementation Process --}}
    @if ($finalEventReport->implementation_process)
        <div class="section">
            <div class="section-title">V. PROSES PELAKSANAAN</div>
            <div class="section-content">{{ $finalEventReport->implementation_process }}</div>
        </div>
    @endif

    {{-- Participant Analysis --}}
    @if ($finalEventReport->participant_analysis)
        <div class="section">
            <div class="section-title">VI. ANALISIS PESERTA</div>
            <div class="section-content">{{ $finalEventReport->participant_analysis }}</div>
        </div>
    @endif

    {{-- Financial Report --}}
    @if ($finalEventReport->financial_report || $finalEventReport->total_budget)
        <div class="section">
            <div class="section-title">VII. LAPORAN KEUANGAN</div>

            @if ($finalEventReport->financial_report)
                <div class="section-content">{{ $finalEventReport->financial_report }}</div>
            @endif

            @if ($finalEventReport->total_budget || $finalEventReport->total_income || $finalEventReport->total_expenses)
                <table class="financial-table">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th style="text-align: right;">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($finalEventReport->total_budget)
                            <tr>
                                <td>Total Anggaran</td>
                                <td style="text-align: right;">
                                    {{ number_format($finalEventReport->total_budget, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif

                        @if ($finalEventReport->total_income)
                            <tr>
                                <td>Total Pemasukan</td>
                                <td style="text-align: right;">
                                    {{ number_format($finalEventReport->total_income, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif

                        @if ($finalEventReport->total_expenses)
                            <tr>
                                <td>Total Pengeluaran</td>
                                <td style="text-align: right;">
                                    {{ number_format($finalEventReport->total_expenses, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif

                        @if ($finalEventReport->surplus_deficit)
                            <tr class="total-row">
                                <td><strong>{{ $finalEventReport->is_surplus ? 'Surplus' : 'Defisit' }}</strong></td>
                                <td style="text-align: right;">
                                    <strong>{{ number_format(abs($finalEventReport->surplus_deficit), 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endif
        </div>
    @endif

    {{-- Challenges & Solutions --}}
    @if ($finalEventReport->challenges_solutions)
        <div class="section">
            <div class="section-title">VIII. TANTANGAN & SOLUSI</div>
            <div class="section-content">{{ $finalEventReport->challenges_solutions }}</div>
        </div>
    @endif

    {{-- Lessons Learned --}}
    @if ($finalEventReport->lessons_learned)
        <div class="section">
            <div class="section-title">IX. PELAJARAN YANG DIPETIK</div>
            <div class="section-content">{{ $finalEventReport->lessons_learned }}</div>
        </div>
    @endif

    {{-- Recommendations --}}
    @if ($finalEventReport->recommendations)
        <div class="section">
            <div class="section-title">X. REKOMENDASI</div>
            <div class="section-content">{{ $finalEventReport->recommendations }}</div>
        </div>
    @endif

    {{-- Conclusion --}}
    @if ($finalEventReport->conclusion)
        <div class="section">
            <div class="section-title">XI. KESIMPULAN</div>
            <div class="section-content">{{ $finalEventReport->conclusion }}</div>
        </div>
    @endif

    {{-- Signatures --}}
    <div class="signatures">
        <div class="signature-box">
            <div class="title">Dibuat Oleh,</div>
            <div class="name">{{ $finalEventReport->createdBy->name }}</div>
            <div style="font-size: 11px; color: #666;">Pembuat Laporan</div>
        </div>

        @if ($finalEventReport->reviewedBy)
            <div class="signature-box">
                <div class="title">Ditinjau Oleh,</div>
                <div class="name">{{ $finalEventReport->reviewedBy->name }}</div>
                <div style="font-size: 11px; color: #666;">Reviewer</div>
            </div>
        @endif

        @if ($finalEventReport->approvedBy)
            <div class="signature-box">
                <div class="title">Disetujui Oleh,</div>
                <div class="name">{{ $finalEventReport->approvedBy->name }}</div>
                <div style="font-size: 11px; color: #666;">Approver</div>
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini dicetak pada {{ now()->format('d F Y, H:i') }} WIB</p>
        <p>© {{ date('Y') }} Panitia Ramadhan 1447H. All rights reserved.</p>
    </div>

</body>

</html>
