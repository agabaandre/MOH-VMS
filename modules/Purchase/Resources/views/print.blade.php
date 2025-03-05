<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Order - {{ $item->code }}</title>
    <style>
        @page { margin: 1.5cm 2cm; }
        body { 
            font-family: Arial, sans-serif;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .document-title {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .org-header {
            margin-bottom: 40px;
        }
        .org-logo {
            float: left;
            width: 80px;
        }
        .org-details {
            margin-left: 100px;
        }
        .org-name {
            font-size: 16px;
            font-weight: bold;
        }
        .org-address {
            font-size: 12px;
            color: #666;
        }
        .po-details {
            clear: both;
            padding-top: 20px;
            margin-bottom: 30px;
        }
        .po-box {
            border: 1px solid #ddd;
            padding: 10px;
            width: 45%;
            float: right;
        }
        .po-box table {
            width: 100%;
            font-size: 12px;
        }
        .po-box td {
            padding: 3px 0;
        }
        .po-box td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .vendor-details {
            width: 45%;
            float: left;
        }
        .vendor-details h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 5px;
        }
        .vendor-details p {
            font-size: 12px;
            margin: 0 0 3px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            clear: both;
        }
        .items-table th {
            background: #f8f9fa;
            border-bottom: 2px solid #ddd;
            padding: 8px;
            font-size: 12px;
            text-align: left;
        }
        .items-table td {
            padding: 8px;
            font-size: 12px;
            border-bottom: 1px solid #eee;
        }
        .items-table .amount-col {
            text-align: right;
        }
        .total-section {
            float: right;
            width: 35%;
        }
        .total-table {
            width: 100%;
            font-size: 12px;
            margin-bottom: 30px;
        }
        .total-table td {
            padding: 5px;
        }
        .total-table .total-row {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #ddd;
        }
        .total-table td:last-child {
            text-align: right;
        }
        .signature-section {
            clear: both;
            padding-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-block {
            width: 30%;
            text-align: center;
        }
        .signature-box {
            height: 50px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .signature-box img {
            max-height: 40px;
            max-width: 120px;
        }
        .signature-name {
            font-size: 11px;
            margin-top: 5px;
        }
        .signature-title {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="document-title">PURCHASE ORDER</div>
    
    <div class="org-header">
        <img src="{{ public_path('storage/MoH-Logo.png') }}" class="org-logo">
        <div class="org-details">
            <div class="org-name">MINISTRY OF HEALTH</div>
            <div class="org-address">
                Transport Office<br>
                Commonwealth Drive, Bandar Seri Begawan<br>
                Brunei Darussalam
            </div>
        </div>
    </div>

    <div class="po-details">
        <div class="vendor-details">
            <h3>VENDOR</h3>
            <p><strong>{{ $item->vendor->name ?? 'N/A' }}</strong></p>
            <p>{{ $item->vendor->address ?? '' }}</p>
            <p>{{ $item->vendor->phone ?? '' }}</p>
        </div>

        <div class="po-box">
            <table>
                <tr>
                    <td>PO Number:</td>
                    <td>{{ $item->code }}</td>
                </tr>
                <tr>
                    <td>Date Issued:</td>
                    <td>{{ $item->date }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>{{ ucfirst($item->status) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="20%">Category</th>
                <th width="35%">Item Description</th>
                <th width="10%">Qty</th>
                <th width="15%">Unit Price</th>
                <th width="15%">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->category->name ?? 'N/A' }}</td>
                <td>{{ $detail->parts->name ?? 'N/A' }}</td>
                <td>{{ number_format($detail->qty, 2) }}</td>
                <td class="amount-col">{{ number_format($detail->price, 2) }}</td>
                <td class="amount-col">{{ number_format($detail->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table class="total-table">
            <tr>
                <td>Subtotal:</td>
                <td>{{ number_format($item->total, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total:</td>
                <td>UGX {{ number_format($item->total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="signature-section">
        <div class="signature-block">
            <div class="signature-box">
                @if($user->signature_path)
                    <img src="{{ storage_path('app/public/' . $user->signature_path) }}" alt="Signature">
                @endif
            </div>
            <div class="signature-name">{{ $user->name }}</div>
            <div class="signature-title">Prepared by</div>
        </div>
        <div class="signature-block">
            <div class="signature-box"></div>
            <div class="signature-name">&nbsp;</div>
            <div class="signature-title">Reviewed by</div>
        </div>
        <div class="signature-block">
            <div class="signature-box"></div>
            <div class="signature-name">&nbsp;</div>
            <div class="signature-title">Approved by</div>
        </div>
    </div>
</body>
</html>