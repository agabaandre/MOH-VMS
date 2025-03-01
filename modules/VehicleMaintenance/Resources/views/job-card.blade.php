<!DOCTYPE html>
<html>
<head>
    <title>Job Card - {{ $item->code }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            position: relative;
        }
        .watermark {
            position: absolute;
            top: 35%; /* Changed from 50% to 35% */
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
            width: 50%;
            height: auto;
        }
        .header { 
            display: flex;
            align-items: flex-start; /* Changed from center to flex-start */
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            position: relative; /* Added */
        }
        .logo {
            width: 80px; /* Reduced from 100px */
            height: auto;
            margin-top: 5px; /* Added to align with text */
        }
        .header-text {
            flex-grow: 1;
            text-align: center;
            padding-left: 80px; /* Changed from padding-right */
            margin-right: 80px; /* Added to balance the layout */
            position: absolute; /* Added */
            left: 0; /* Added */
            right: 0; /* Added */
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
        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }
        .service-table th { 
            font-size: 12px;
            font-weight: bold;
        }
        .service-table th, .service-table td {
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
    <img src="{{ storage_path('app/public/MoH-Logo.png') }}" class="watermark">
    
    <div class="header">
        <img src="{{ storage_path('app/public/MoH-Logo.png') }}" class="logo">
        <div class="header-text">
            <div class="ministry-header">MINISTRY OF HEALTH</div>
            <div class="sub-header">TRANSPORT OFFICE</div>
            <div class="form-title">INHOUSE GARAGE JOB FORM</div>
        </div>
    </div>

    <table class="details-table">
        <tr>
            <td><strong>Advisor (receiver):</strong></td>
            <td>{{ $item->employee->name ?? 'N/A' }}</td>
            <td><strong>Transport Officer:</strong></td>
            <td>_____________________</td>
        </tr>
        <tr>
            <td><strong>Job Card Number:</strong></td>
            <td>{{ $item->code }}</td>
            <td><strong>Date Received:</strong></td>
            <td>{{ $item->date ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Department:</strong></td>
            <td>{{ $item->vehicle->department->name ?? 'N/A' }}</td>
            <td><strong>Type:</strong></td>
            <td>{{ $item->type ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Make and Model:</strong></td>
            <td>{{ $item->vehicle->name ?? 'N/A' }}</td>
            <td><strong>Priority:</strong></td>
            <td>{{ ucfirst($item->priority) ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Vehicle Registration:</strong></td>
            <td>{{ $item->vehicle->license_plate ?? 'N/A' }}</td>
            <td><strong>Status:</strong></td>
            <td>{{ ucfirst($item->status) ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Charge Bear By:</strong></td>
            <td>{{ $item->charge_bear_by ?? 'N/A' }}</td>
            <td><strong>Total Charge:</strong></td>
            <td>{{ $item->charge ?? 'N/A' }}</td>
        </tr>
    </table>

    <div><strong>SERVICE/REPAIR DESCRIPTION</strong></div>
    <div style="margin: 10px 0;">{{ $item->title ?? 'N/A' }}</div>
    <div style="margin: 10px 0;">{{ $item->remarks ?? 'N/A' }}</div>

    <div><strong>PARTS TO BE USED</strong></div>
    <table class="service-table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item->details as $d)
            <tr>
                <td>{{ $d->category->name ?? 'N/A' }}</td>
                <td>{{ $d->parts->name ?? 'N/A' }}</td>
                <td>{{ $d->qty ?? '0' }}</td>
                <td>{{ $d->price ?? '0' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        <div>
            <span>Signature of User:</span>
            <span class="signature-line"></span>
        </div>
        <div style="margin: 20px 0;">
            <span>Accepted by:</span>
            <span class="signature-line"></span>
        </div>
        <div>
            <span>Automotive/Mechanical Engineer:</span>
            <span class="signature-line"></span>
        </div>
    </div>
</body>
</html>
