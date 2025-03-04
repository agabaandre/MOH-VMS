<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Order - {{ $item->code }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            position: relative;
        }
        @page {
            margin: 0.5cm 1cm;
        }
        .watermark {
            position: fixed;
            top: 35%;
            left: 20%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
            width: 60%;
            height: auto;
        }
        .header { 
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            position: relative;
        }
        .logo {
            width: 120px;
            height: auto;
            margin-top: 5px;
        }
        .header-text {
            flex-grow: 1;
            text-align: center;
            padding-left: 80px;
            margin-right: 80px;
            position: absolute;
            left: 0;
            right: 0;
        }
        .ministry-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .sub-header {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .form-title {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
        }
        .details-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
            font-size: 11px;
        }
        .details-table td { 
            padding: 5px; 
            border: 1px solid #ddd;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }
        .items-table th { 
            font-size: 12px;
            font-weight: bold;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            display: inline-block;
            margin: 0 20px;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('storage/MoH-Logo.png') }}" class="watermark">
    
    <div class="header">
        <img src="{{ public_path('storage/MoH-Logo.png') }}" class="logo">
        <div class="header-text">
            <div class="ministry-header">MINISTRY OF HEALTH</div>
            <div class="sub-header">TRANSPORT OFFICE</div>
            <div class="form-title">PURCHASE ORDER</div>
        </div>
    </div>

    <table class="details-table">
        <tr>
            <td><strong>Purchase Order No:</strong></td>
            <td>{{ $item->code }}</td>
            <td><strong>Date:</strong></td>
            <td>{{ $item->date }}</td>
        </tr>
        <tr>
            <td><strong>Vendor:</strong></td>
            <td>{{ $item->vendor->name ?? 'N/A' }}</td>
            <td><strong>Status:</strong></td>
            <td>{{ ucfirst($item->status) }}</td>
        </tr>
    </table>

    <div><strong>ITEMS</strong></div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item->details as $detail)
            <tr>
                <td>{{ $detail->category->name ?? 'N/A' }}</td>
                <td>{{ $detail->parts->name ?? 'N/A' }}</td>
                <td>{{ number_format($detail->qty, 2) }}</td>
                <td>{{ number_format($detail->price, 2) }}</td>
                <td>{{ number_format($detail->total, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                <td>{{ number_format($item->total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <div>
            <span>Prepared by:</span>
            <span class="signature-line"></span>
        </div>
        <div style="margin: 20px 0;">
            <span>Reviewed by:</span>
            <span class="signature-line"></span>
        </div>
        <div>
            <span>Approved by:</span>
            <span class="signature-line"></span>
        </div>
    </div>
</body>
</html>
