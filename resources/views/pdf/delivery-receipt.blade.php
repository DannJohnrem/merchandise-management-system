<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; padding: 40px; }

        /* ── Header ── */
        .header-row {
            display: table;
            width: 100%;
            margin: 24px 0 12px;
        }
        .header-logo {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
        }
        .header-logo img {
            width: 90px;
            height: auto;
        }
        .header-title {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }
        .header-title h1 {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 4px;
        }
        /* spacer cell to balance the logo */
        .header-spacer {
            display: table-cell;
            width: 120px;
        }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 11px;
        }
        th {
            text-align: center;
            font-weight: bold;
            background-color: #fff;
        }
        /* NO. column */
        thead tr th:first-child,
        tbody tr td:first-child {
            text-align: center;
            width: 4%;
        }
        /* MODEL column */
        thead tr th:nth-child(2),
        tbody tr td:nth-child(2) {
            width: 28%;
        }
        /* MOUSE/BAG column */
        thead tr th:last-child,
        tbody tr td:last-child {
            text-align: center;
            width: 10%;
        }
        .nothing-follows td {
            text-align: center;
            border-top: 1px solid #000;
        }
        .empty-row td {
            border: 1px solid #000;
            height: 20px;
        }

        /* ── Signature ── */
        .signature-section {
            margin-top: 40px;
            font-size: 10px;
        }
        .signature-section .label {
            font-weight: bold;
            margin-bottom: 30px;
        }
        .sig-fields {
            display: table;
            width: 60%;
        }
        .sig-name, .sig-date {
            display: table-cell;
            padding-right: 40px;
        }
        .sig-line {
            border-top: 1px solid #000;
            margin-bottom: 3px;
        }
        .sig-caption {
            font-size: 9px;
        }
    </style>
</head>
<body>

    {{-- Header: Logo left | Title center --}}
    <div class="header-row">
        <div class="header-logo">
            {{-- Replace src with your actual logo path --}}
            <img src="{{ public_path('images/logo.jpg') }}" alt="Logo">
        </div>
        <div class="header-title">
            <h1>DELIVERY RECEIPT</h1>
        </div>
        <div class="header-spacer"></div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            {{-- Row 1: merged headers --}}
            <tr>
                <th rowspan="2">NO.</th>
                <th rowspan="2">MODEL</th>
                <th style="border-bottom: none;">LAPTOP</th>
                <th style="border-bottom: none;">LAPTOP CHARGER</th>
                <th rowspan="2">MOUSE/BAG</th>
            </tr>
            {{-- Row 2: sub-headers --}}
            <tr>
                <th style="border-top: none;">SERIAL NUMBER</th>
                <th style="border-top: none;">SERIAL NUMBER</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                @php $inc = $inclusionsMap[$item->id]; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ trim(($item->brand ?? '') . ' ' . ($item->model ?? '')) ?: '—' }}</td>
                    <td>{{ $item->serial_number ?? '—' }}</td>
                    <td>{{ $item->charger_serial_number ?? '—' }}</td>
                    <td style="text-align:center;">{{ $inc->contains('bag') ? '1' : '—' }}</td>
                </tr>
            @endforeach

            <tr class="nothing-follows">
                <td colspan="5">Nothing follows</td>
            </tr>
            <tr class="empty-row">
                <td colspan="3"></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    {{-- Signature --}}
    <div class="signature-section">
        <div class="label">RECEIVED IN GOOD CONDITION BY:</div>
        <div class="sig-fields">
            <div class="sig-name">
                <div class="sig-line"></div>
                <div class="sig-caption">Signature over printed name</div>
            </div>
            <div class="sig-date">
                <div class="sig-line"></div>
                <div class="sig-caption">Date</div>
            </div>
        </div>
    </div>

</body>
</html>
