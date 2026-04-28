<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; padding: 36px; color: #000; }

        /* ── Header ── */
        .header-wrap {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        .header-logo-cell {
            display: table-cell;
            width: 80px;
            vertical-align: top;
        }
        .header-logo-cell img {
            width: 65px;
            height: auto;
        }
        .header-company-cell {
            display: table-cell;
            vertical-align: top;
            padding-left: 8px;
        }
        .company-name {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .company-address {
            font-size: 9px;
            line-height: 1.6;
            color: #222;
        }

        /* ── DR Number ── */
        .dr-number-row {
            text-align: right;
            font-size: 11px;
            font-weight: bold;
            margin: 10px 0 14px;
        }

        /* ── Shipped / Billed ── */
        .shipped-wrap {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }
        .shipped-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .shipped-header {
            background-color: #33CCCC;
            color: #000;
            font-weight: bold;
            font-size: 10px;
            padding: 5px 8px;
            display: table;
            width: 100%;
        }
        .shipped-header-label {
            display: table-cell;
            white-space: nowrap;
            padding-right: 8px;
        }
        .shipped-header-value {
            display: table-cell;
            font-weight: bold;
            width: 100%;
        }
        .shipped-body {
            padding: 5px 8px 8px;
            font-size: 9px;
            line-height: 1.7;
        }
        .shipped-body .company {
            font-weight: bold;
            font-size: 10px;
        }

        /* ── Items Table ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .items-table th {
            background-color: #33CCCC;
            color: #000;
            font-weight: bold;
            padding: 6px 8px;
            font-size: 10px;
            text-align: left;
        }
        .items-table th.center { text-align: center; }
        .items-table td {
            padding: 5px 8px;
            font-size: 10px;
        }
        .items-table td.center { text-align: center; }
        .items-table tr:nth-child(even) td { background-color: #d9d9d9; }
        .items-table tr:nth-child(odd) td  { background-color: #ffffff; }

        /* ── Totals ── */
        .totals-row {
            font-size: 10px;
            margin-bottom: 12px;
        }
        .totals-row span { font-weight: bold; }

        /* ── Checklist ── */
        .checklist { font-size: 10px; margin-bottom: 30px; }
        .checklist-item {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        .checklist-box {
            display: table-cell;
            width: 14px;
            vertical-align: middle;
        }
        .box {
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            display: inline-block;
        }
        .checklist-text {
            display: table-cell;
            vertical-align: middle;
            padding-left: 6px;
        }

        /* ── Signatures ── */
        .sig-wrap {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .sig-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .sig-label {
            font-size: 10px;
            margin-bottom: 30px;
        }
        .sig-line {
            border-top: 1px solid #000;
            width: 75%;
            margin-bottom: 4px;
        }
        .sig-name {
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    {{-- ── TOP HEADER ── --}}
    <div class="header-wrap">
        <div class="header-logo-cell">
            <img src="{{ public_path('images/logo.jpg') }}" alt="Logo">
        </div>
        <div class="header-company-cell">
            <div class="company-name">ACJ SUMMIT VENTURES CORP</div>
            <div class="company-address">
                Unit 305 Spark Place Building<br>
                P.Tuazon cor 10th Ave Brgy Socorro<br>
                Cubao, QC
            </div>
        </div>
    </div>

    {{-- ── DR NUMBER ── --}}
    <div class="dr-number-row">
        DELIVERY RECEIPT: {{ $drNumber }}
    </div>

    {{-- ── SHIPPED TO / BILLED TO ── --}}
    <div class="shipped-wrap">
        {{-- SHIPPED TO --}}
        <div class="shipped-col" style="padding-right: 6px;">
            <div class="shipped-header">
                <span class="shipped-header-label">SHIPPED TO:</span>
                <span class="shipped-header-value">BTSMC MANAGING SOLUTIONS, INC</span>
            </div>
            <div class="shipped-body">
                Unit 330-3 &amp; 311-4 Spark Place Building<br>
                P.Tuazon cor 10th Ave Brgy Socorro<br>
                Cubao, QC
            </div>
        </div>

        {{-- BILLED TO --}}
        <div class="shipped-col" style="padding-left: 6px;">
            <div class="shipped-header">
                <span class="shipped-header-label">BILLED TO:</span>
                <span class="shipped-header-value">BTSMC MANAGING SOLUTIONS, INC</span>
            </div>
            <div class="shipped-body">
                Unit 330-3 &amp; 311-4 Spark Place Building<br>
                P.Tuazon cor 10th Ave Brgy Socorro<br>
                Cubao, QC
            </div>
        </div>
    </div>

    {{-- ── ITEMS TABLE ── --}}
    @php $lineItem = 1; $totalQty = 0; @endphp

    <table class="items-table">
        <thead>
            <tr>
                <th style="width:10%;" class="center">LINE ITEM</th>
                <th>ITEM DESCRIPTION</th>
                <th style="width:12%;" class="center">QUANTITY</th>
                <th style="width:22%;">SERIAL NUMBER</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php $inc = $inclusionsMap[$item->id]; @endphp

                {{-- Laptop row --}}
                <tr>
                    <td class="center">{{ $lineItem++ }}</td>
                    <td>{{ trim(($item->brand ?? '') . ' ' . ($item->model ?? '')) ?: '—' }}</td>
                    <td class="center">1</td>
                    <td>{{ $item->serial_number ?? 'n/a' }}</td>
                </tr>
                @php $totalQty++; @endphp

                {{-- Charger row --}}
                @if ($inc['has_charger'])
                    <tr>
                        <td class="center">{{ $lineItem++ }}</td>
                        <td>{{ ($item->brand ?? 'Laptop') }} Charger</td>
                        <td class="center">1</td>
                        <td>{{ $item->charger_serial_number ?? 'xxxx' }}</td>
                    </tr>
                    @php $totalQty++; @endphp
                @endif

                {{-- Bag row --}}
                @if ($inc['has_bag'])
                    <tr>
                        <td class="center">{{ $lineItem++ }}</td>
                        <td>{{ ($item->brand ?? 'Laptop') }} Laptop Bag</td>
                        <td class="center">1</td>
                        <td>n/a</td>
                    </tr>
                    @php $totalQty++; @endphp
                @endif

                {{-- Mouse row --}}
                @if ($inc['has_mouse'])
                    <tr>
                        <td class="center">{{ $lineItem++ }}</td>
                        <td>{{ ($item->brand ?? 'Laptop') }} Mouse</td>
                        <td class="center">1</td>
                        <td>n/a</td>
                    </tr>
                    @php $totalQty++; @endphp
                @endif

            @endforeach
        </tbody>
    </table>

    {{-- ── TOTAL QUANTITY ── --}}
    <div class="totals-row">
        TOTAL QUANTITY: <span>{{ $totalQty }}</span>
    </div>

    {{-- ── CHECKLIST ── --}}
    <div class="checklist">
        <div class="checklist-item">
            <div class="checklist-box"><span class="box"></span></div>
            <div class="checklist-text">All items received in good condition</div>
        </div>
        <div class="checklist-item">
            <div class="checklist-box"><span class="box"></span></div>
            <div class="checklist-text">All items subject to inspection</div>
        </div>
    </div>

    {{-- ── SIGNATURES ── --}}
    <div class="sig-wrap">
        <div class="sig-col">
            <div class="sig-label">RELEASED BY:</div>
            <div class="sig-line"></div>
            <div class="sig-name">ACJ SUMMIT VENTURES CORP / DATE</div>
        </div>
        <div class="sig-col">
            <div class="sig-label">RECEIVED BY:</div>
            <div class="sig-line"></div>
            <div class="sig-name">BTSMC MANAGING SOLUTIONS, INC / DATE</div>
        </div>
    </div>

</body>
</html>
