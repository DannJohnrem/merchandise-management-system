<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10.5px;
            color: #1e1e1e;
            margin: 0;
            padding: 0;
        }

        .page-border {
            border: 1.5px solid #1e1e1e;
            margin: 24px 30px;
            padding: 0;
        }

        .content {
            padding: 26px 34px 30px;
        }

        /* Header */
        .header {
            border-bottom: 2px solid #1e1e1e;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header h1 {
            font-size: 19px;
            margin: 0;
            letter-spacing: 0.6px;
            font-weight: bold;
        }

        /* Date */
        .date-row {
            margin-bottom: 20px;
            font-size: 10.5px;
        }

        .date-row .label {
            font-weight: bold;
            display: inline-block;
            width: 55px;
        }

        /* Section headings */
        .section-heading {
            font-size: 12.5px;
            font-weight: bold;
            letter-spacing: 0.3px;
            margin-bottom: 12px;
            padding-bottom: 4px;
            border-bottom: 1px solid #ccc;
        }

        /* Pull out main table */
        .pull-out-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .pull-out-table td {
            vertical-align: top;
            padding: 0;
        }

        .col-left {
            width: 38%;
        }

        .col-right {
            width: 62%;
            padding-left: 20px;
        }

        .check-row {
            margin-bottom: 8px;
        }

        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1.2px solid #1e1e1e;
            margin-right: 8px;
            vertical-align: middle;
        }

        .checkbox.checked {
            background: #1e1e1e;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 0 0 9px;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .info-label {
            font-weight: bold;
            width: 95px;
            padding-right: 6px;
        }

        .info-value {
            border-bottom: 1px solid #1e1e1e;
            padding-bottom: 2px;
            white-space: normal;
            width: 100%;
        }

        /* Reasons */
        .reasons-block {
            margin-bottom: 22px;
        }

        /* Condition */
        .condition-box {
            border: 1px solid #999;
            border-radius: 2px;
            min-height: 60px;
            padding: 8px 10px;
            white-space: pre-line;
            margin-bottom: 24px;
            background: #fafafa;
        }

        .condition-box.empty {
            color: #999;
            font-style: italic;
        }

        /* Replacement */
        .replacement-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .replacement-table td {
            padding: 3px 0;
        }

        /* Signature blocks */
        .sig-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .sig-grid td {
            width: 50%;
            vertical-align: top;
            padding: 0 14px 22px 0;
        }

        .sig-title {
            font-weight: bold;
            font-size: 10px;
            letter-spacing: 0.2px;
            display: block;
            margin-bottom: 26px;
            color: #444;
        }

        .sig-line {
            border-bottom: 1px solid #1e1e1e;
            min-height: 13px;
            padding-bottom: 2px;
            font-size: 11px;
        }

        .sig-company {
            font-size: 9.5px;
            color: #555;
            margin-top: 3px;
        }

        .footer-note {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8.5px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="page-border">
        <div class="content">

            <div class="header">
                <h1>PULL OUT / REPLACEMENT FORM</h1>
            </div>

            <div class="date-row">
                <span class="label">DATE:</span> {{ $form->form_date?->format('F j, Y') }}
            </div>

            <div class="section-heading">PULL OUT</div>

            <table class="pull-out-table">
                <tr>
                    <td class="col-left">
                        <div class="check-row">
                            <span class="checkbox {{ $form->type === 'laptop' ? 'checked' : '' }}"></span> Laptop
                        </div>
                        <div class="check-row">
                            <span class="checkbox {{ $form->type === 'printer' ? 'checked' : '' }}"></span> Printer
                        </div>
                        <div class="check-row">
                            <span class="checkbox {{ $form->type === 'others' ? 'checked' : '' }}"></span> Others
                            {{ $form->type === 'others' ? '('.$form->type_other.')' : '' }}
                        </div>
                    </td>
                    <td class="col-right">
                        <table class="info-table">
                            <tr>
                                <td class="info-label">Brand</td>
                                <td class="info-value">{{ $form->brand ?: '&nbsp;' }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Serial No.</td>
                                <td class="info-value">{{ $form->serial_no ?: '&nbsp;' }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Inclusion</td>
                                <td class="info-value">{{ $form->inclusion ?: '&nbsp;' }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Employee</td>
                                <td class="info-value">{{ $form->employee_name ?: '&nbsp;' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="reasons-block">
                <div class="section-heading">REASON FOR PULL OUT</div>
                @php
                    $reasonLabels = [
                        'assessment' => 'For assessment of issues/damage',
                        'return' => 'For return',
                        'overissuance' => 'Overissuance',
                        'incompatible_specs' => 'Incompatible specs',
                        'others' => 'Others',
                    ];
                    $selectedReasons = $form->reasons ?? [];
                @endphp
                <table class="pull-out-table">
                    <tr>
                        <td class="col-left">
                            @foreach (array_slice($reasonLabels, 0, 3, true) as $key => $label)
                                <div class="check-row">
                                    <span class="checkbox {{ in_array($key, $selectedReasons) ? 'checked' : '' }}"></span>
                                    {{ $label }}
                                </div>
                            @endforeach
                        </td>
                        <td class="col-right">
                            @foreach (array_slice($reasonLabels, 3, 2, true) as $key => $label)
                                <div class="check-row">
                                    <span class="checkbox {{ in_array($key, $selectedReasons) ? 'checked' : '' }}"></span>
                                    {{ $label }}
                                    @if ($key === 'others' && in_array('others', $selectedReasons) && $form->reason_other)
                                        — {{ $form->reason_other }}
                                    @endif
                                </div>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section-heading">CONDITION OF UNIT DETAILS</div>
            <div class="condition-box {{ $form->condition_details ? '' : 'empty' }}">
                {{ $form->condition_details ?: 'No condition details provided.' }}
            </div>

            @if ($form->has_replacement)
                <div class="section-heading">ISSUANCE OF REPLACEMENT</div>
                <table class="replacement-table">
                    <tr>
                        <td class="info-label" style="width:80px; font-weight:bold;">Brand</td>
                        <td>{{ $form->replacement_brand }}</td>
                    </tr>
                    <tr>
                        <td class="info-label" style="width:80px; font-weight:bold;">Serial No.</td>
                        <td>{{ $form->replacement_serial_no }}</td>
                    </tr>
                </table>

                <table class="sig-grid">
                    <tr>
                        <td>
                            <span class="sig-title">REPLACEMENT ISSUED BY</span>
                            <div class="sig-line">{{ $form->issued_by_name }}</div>
                            <div class="sig-company">{{ $form->issued_by_company }}</div>
                        </td>
                        <td>
                            <span class="sig-title">REPLACEMENT RECEIVED BY</span>
                            <div class="sig-line">{{ $form->received_by_name }}</div>
                            <div class="sig-company">{{ $form->received_by_company }}</div>
                        </td>
                    </tr>
                </table>
            @endif

            <table class="sig-grid">
                <tr>
                    <td>
                        <span class="sig-title">RETURNED BY</span>
                        <div class="sig-line">{{ $form->returned_by_name }}</div>
                        <div class="sig-company">{{ $form->returned_by_company }}</div>
                    </td>
                    <td>
                        <span class="sig-title">RETURN UNIT RECEIVED BY</span>
                        <div class="sig-line">{{ $form->return_received_by_name }}</div>
                        <div class="sig-company">{{ $form->return_received_by_company }}</div>
                    </td>
                </tr>
            </table>

            <div class="footer-note">
                Generated on {{ now()->format('F j, Y g:i A') }} — ACJ Summit Ventures Corp.
            </div>

        </div>
    </div>

</body>
</html>
