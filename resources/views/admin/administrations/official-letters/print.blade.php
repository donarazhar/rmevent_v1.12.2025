<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $officialLetter->letter_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.5;
            color: #000;
            padding: 2cm;
            font-size: 12pt;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .header p {
            font-size: 11pt;
            margin: 2px 0;
            line-height: 1.3;
        }

        .letter-info {
            margin-bottom: 20px;
        }

        .letter-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .letter-info td {
            padding: 2px 0;
            font-size: 12pt;
            vertical-align: top;
        }

        .letter-info td:first-child {
            width: 120px;
        }

        .letter-info td:nth-child(2) {
            width: 20px;
        }

        .recipient-section {
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .recipient-section p {
            font-size: 12pt;
            margin: 1px 0;
            line-height: 1.4;
        }

        .opening {
            margin-bottom: 15px;
            font-size: 12pt;
        }

        .content {
            text-align: left;
            font-size: 12pt;
            margin-bottom: 20px;
            line-height: 1.5;
            white-space: pre-wrap;
            text-indent: 0;
        }

        .closing {
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 12pt;
            line-height: 1.5;
        }

        .signature-section {
            margin-top: 30px;
            text-align: left;
            margin-left: 60%;
        }

        .signature-section p {
            font-size: 12pt;
            margin: 2px 0;
            line-height: 1.4;
        }

        .signature-space {
            height: 60px;
            margin: 10px 0;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .attachment-list {
            clear: both;
            margin-top: 40px;
            font-size: 12pt;
            page-break-inside: avoid;
        }

        .attachment-list h3 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .attachment-list ol {
            margin-left: 25px;
            line-height: 1.5;
        }

        .attachment-list li {
            margin-bottom: 3px;
        }

        .cc-list {
            clear: both;
            margin-top: 20px;
            font-size: 11pt;
            page-break-inside: avoid;
        }

        .cc-list p {
            margin-bottom: 5px;
        }

        .cc-list ol {
            margin-left: 25px;
            line-height: 1.4;
        }

        .cc-list li {
            margin-bottom: 2px;
        }

        @media print {
            body {
                padding: 0;
            }

            @page {
                margin: 2cm;
                size: A4;
            }

            .header {
                page-break-after: avoid;
            }

            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <h1>PANITIA RAMADHAN 1447 H</h1>
        <p>Masjid Agung Al Azhar Jakarta</p>
        <p>Jl. Sisingamangaraja, Kebayoran Baru, Jakarta Selatan 12110</p>
        <p>Email: info@masjidagungalazhar.com | Tel: (+62) 882-1211-4771</p>
    </div>

    {{-- Letter Info --}}
    <div class="letter-info">
        <table>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>{{ $officialLetter->letter_number }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>{{ $officialLetter->attachment_count ?? 0 }} berkas</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>{{ $officialLetter->subject }}</strong></td>
            </tr>
        </table>
    </div>

    {{-- Recipient --}}
    <div class="recipient-section">
        <p>Kepada Yth.</p>
        <p><strong>{{ $officialLetter->recipient_name }}</strong></p>
        @if ($officialLetter->recipient_organization)
            <p>{{ $officialLetter->recipient_organization }}</p>
        @endif
        @if ($officialLetter->recipient_address)
            <p>{{ $officialLetter->recipient_address }}</p>
        @endif
    </div>

    {{-- Content --}}
    <div class="content">{{ $officialLetter->content }}</div>

    {{-- Attachment List --}}
    @php
        $attachmentList = is_array($officialLetter->attachment_list)
            ? $officialLetter->attachment_list
            : (is_string($officialLetter->attachment_list) && !empty($officialLetter->attachment_list)
                ? json_decode($officialLetter->attachment_list, true)
                : []);
    @endphp
    @if (!empty($attachmentList) && is_array($attachmentList) && count($attachmentList) > 0)
        <div class="attachment-list">
            <h3>Lampiran:</h3>
            <ol>
                @foreach ($attachmentList as $attachment)
                    <li>{{ $attachment }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    {{-- CC List --}}
    @php
        $ccRecipients = is_array($officialLetter->cc_recipients)
            ? $officialLetter->cc_recipients
            : (is_string($officialLetter->cc_recipients) && !empty($officialLetter->cc_recipients)
                ? json_decode($officialLetter->cc_recipients, true)
                : []);
    @endphp
    @if (!empty($ccRecipients) && is_array($ccRecipients) && count($ccRecipients) > 0)
        <div class="cc-list">
            <p><strong>Tembusan:</strong></p>
            <ol>
                @foreach ($ccRecipients as $cc)
                    <li>
                        {{ $cc['name'] ?? '' }}
                        @if (!empty($cc['organization']))
                            ({{ $cc['organization'] }})
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    @endif

</body>

</html>
