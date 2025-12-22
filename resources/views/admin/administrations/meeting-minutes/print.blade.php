<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notulensi - {{ $meetingMinute->minute_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 2cm;
            size: A4;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background: #fff;
            padding: 20px;
        }

        .print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 999;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #000;
        }

        .header h1 {
            font-size: 18pt;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 16pt;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 10pt;
            margin: 2px 0;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        table td:first-child {
            width: 150px;
            font-weight: bold;
        }

        table td:nth-child(2) {
            width: 10px;
        }

        .participants {
            columns: 2;
            column-gap: 20px;
        }

        .participant-item {
            padding: 3px 0;
            break-inside: avoid;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10pt;
        }

        .signatures {
            margin-top: 40px;
        }

        .sig-row {
            display: table;
            width: 100%;
            margin-top: 60px;
        }

        .sig-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }

        .sig-box p {
            margin-bottom: 70px;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <button onclick="window.print()" class="print-btn">🖨️ Print</button>

    {{-- HEADER --}}
    <div class="header">
        <h1>Notulensi Rapat</h1>
        <h2>Ecosystem Digital Ramadhan 1447H</h2>
        <p>Jl. Contoh No. 123, Jakarta Selatan 12345</p>
        <p>Email: info@ramadhan1447h.com | Telp: (021) 1234567</p>
    </div>

    {{-- MEETING INFO --}}
    <table>
        <tr>
            <td>Kode Notulensi</td>
            <td>:</td>
            <td><strong>{{ $meetingMinute->minute_code }}</strong></td>
        </tr>
        <tr>
            <td>Judul Rapat</td>
            <td>:</td>
            <td><strong>{{ $meetingMinute->meeting_title }}</strong></td>
        </tr>
        <tr>
            <td>Tipe Rapat</td>
            <td>:</td>
            <td>{{ ucfirst($meetingMinute->meeting_type) }}</td>
        </tr>
        <tr>
            <td>Tanggal & Waktu</td>
            <td>:</td>
            <td>
                @if ($meetingMinute->meeting_date)
                    {{ $meetingMinute->meeting_date->format('l, d F Y | H:i') }} WIB
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td>Lokasi</td>
            <td>:</td>
            <td>{{ $meetingMinute->location ?? '-' }}</td>
        </tr>
        @if ($meetingMinute->duration_minutes)
            <tr>
                <td>Durasi</td>
                <td>:</td>
                <td>{{ $meetingMinute->duration_formatted }}</td>
            </tr>
        @endif
        @if ($meetingMinute->chairmanUser)
            <tr>
                <td>Ketua Rapat</td>
                <td>:</td>
                <td>{{ $meetingMinute->chairmanUser->name }}</td>
            </tr>
        @endif
        @if ($meetingMinute->secretaryUser)
            <tr>
                <td>Sekretaris</td>
                <td>:</td>
                <td>{{ $meetingMinute->secretaryUser->name }}</td>
            </tr>
        @endif
    </table>

    {{-- PARTICIPANTS --}}
    <div class="section">
        <h3 class="section-title">Peserta Hadir ({{ count($participants) }} orang)</h3>
        @if (count($participants) > 0)
            <div class="participants">
                @foreach ($participants as $index => $p)
                    <div class="participant-item">{{ $index + 1 }}. {{ $p->name }}</div>
                @endforeach
            </div>
        @else
            <p><em>Belum ada peserta hadir tercatat.</em></p>
        @endif
    </div>

    {{-- ABSENT --}}
    @if (count($absentMembers) > 0)
        <div class="section">
            <h3 class="section-title">Tidak Hadir ({{ count($absentMembers) }} orang)</h3>
            <div class="participants">
                @foreach ($absentMembers as $index => $a)
                    <div class="participant-item">{{ $index + 1 }}. {{ $a->name }}</div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- EXTERNAL PARTICIPANTS --}}
    @if ($meetingMinute->external_participants && count($meetingMinute->external_participants) > 0)
        <div class="section">
            <h3 class="section-title">Peserta Eksternal</h3>
            @foreach ($meetingMinute->external_participants as $index => $ext)
                <p>{{ $index + 1 }}. {{ $ext['name'] }}
                    @if (isset($ext['organization']))
                        - {{ $ext['organization'] }}
                    @endif
                    @if (isset($ext['email']))
                        ({{ $ext['email'] }})
                    @endif
                </p>
            @endforeach
        </div>
    @endif

    {{-- AGENDA --}}
    <div class="section">
        <h3 class="section-title">Agenda Rapat</h3>
        @if ($meetingMinute->agenda)
            <div>{!! nl2br(e($meetingMinute->agenda)) !!}</div>
        @else
            <p><em>Agenda belum diisi.</em></p>
        @endif
    </div>

    {{-- DISCUSSION --}}
    <div class="section">
        <h3 class="section-title">Ringkasan Diskusi</h3>
        @if ($meetingMinute->discussion_summary)
            <div>{!! nl2br(e($meetingMinute->discussion_summary)) !!}</div>
        @else
            <p><em>Ringkasan diskusi belum diisi.</em></p>
        @endif
    </div>

    {{-- DECISIONS --}}
    <div class="section">
        <h3 class="section-title">Keputusan Rapat</h3>
        @if ($meetingMinute->decisions)
            <div>{!! nl2br(e($meetingMinute->decisions)) !!}</div>
        @else
            <p><em>Keputusan rapat belum diisi.</em></p>
        @endif
    </div>

    {{-- ACTION ITEMS --}}
    @if ($meetingMinute->action_items_list && count($meetingMinute->action_items_list) > 0)
        <div class="section">
            <h3 class="section-title">Daftar Tindak Lanjut</h3>
            <table border="1" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background: #f0f0f0;">
                        <th style="padding: 8px; border: 1px solid #000; width: 5%;">No</th>
                        <th style="padding: 8px; border: 1px solid #000; width: 40%;">Task</th>
                        <th style="padding: 8px; border: 1px solid #000; width: 20%;">PIC</th>
                        <th style="padding: 8px; border: 1px solid #000; width: 15%;">Deadline</th>
                        <th style="padding: 8px; border: 1px solid #000; width: 20%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($meetingMinute->action_items_list as $index => $item)
                        <tr>
                            <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $index + 1 }}
                            </td>
                            <td style="padding: 8px; border: 1px solid #000;">{{ $item['task'] }}</td>
                            <td style="padding: 8px; border: 1px solid #000;">
                                @if (isset($item['assignee']))
                                    @php $assignee = \App\Models\User::find($item['assignee']); @endphp
                                    {{ $assignee ? $assignee->name : '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 8px; border: 1px solid #000;">
                                {{ isset($item['deadline']) ? \Carbon\Carbon::parse($item['deadline'])->format('d M Y') : '-' }}
                            </td>
                            <td style="padding: 8px; border: 1px solid #000;">
                                {{ ucfirst(str_replace('_', ' ', $item['status'])) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- NEXT MEETING --}}
    @if ($meetingMinute->next_meeting_date)
        <div class="section">
            <h3 class="section-title">Rapat Berikutnya</h3>
            <table>
                <tr>
                    <td>Tanggal & Waktu</td>
                    <td>:</td>
                    <td>{{ $meetingMinute->next_meeting_date->format('l, d F Y | H:i') }} WIB</td>
                </tr>
                @if ($meetingMinute->next_meeting_location)
                    <tr>
                        <td>Lokasi</td>
                        <td>:</td>
                        <td>{{ $meetingMinute->next_meeting_location }}</td>
                    </tr>
                @endif
            </table>
            @if ($meetingMinute->next_meeting_agenda)
                <p><strong>Agenda:</strong></p>
                <div>{!! nl2br(e($meetingMinute->next_meeting_agenda)) !!}</div>
            @endif
        </div>
    @endif

    {{-- SIGNATURES --}}
    @if ($meetingMinute->secretaryUser || $meetingMinute->chairmanUser)
        <div class="signatures">
            <div class="sig-row">
                @if ($meetingMinute->secretaryUser)
                    <div class="sig-box">
                        <p>Sekretaris/Notulis,</p>
                        <p class="sig-name">{{ $meetingMinute->secretaryUser->name }}</p>
                    </div>
                @endif

                @if ($meetingMinute->chairmanUser)
                    <div class="sig-box">
                        <p>Ketua Rapat,</p>
                        <p class="sig-name">{{ $meetingMinute->chairmanUser->name }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        <p>Notulensi ini dibuat pada
            {{ $meetingMinute->created_at ? $meetingMinute->created_at->format('d F Y') : '-' }}</p>
        @if ($meetingMinute->finalized_at && $meetingMinute->finalizedBy)
            <p>Difinalisasi pada {{ $meetingMinute->finalized_at->format('d F Y, H:i') }} oleh
                {{ $meetingMinute->finalizedBy->name }}</p>
        @endif
        <p style="margin-top: 10px; font-style: italic;">Ecosystem Digital Ramadhan 1447H - Dokumentasi Rapat</p>
    </div>
</body>

</html>
