<!DOCTYPE html>
<html>
<head>
    <title>Job Card - {{ $item->code }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table td { padding: 5px; }
        .parts-table { width: 100%; border-collapse: collapse; }
        .parts-table th, .parts-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .footer { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>VEHICLE MAINTENANCE JOB CARD</h2>
        <h3>{{ $item->code }}</h3>
    </div>

    <table class="details-table">
        <tr>
            <td><strong>Service Title:</strong></td>
            <td>{{ $item->title ?? 'N/A' }}</td>
            <td><strong>Date:</strong></td>
            <td>{{ $item->date ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Vehicle:</strong></td>
            <td>{{ $item->vehicle->name ?? 'N/A' }}</td>
            <td><strong>Priority:</strong></td>
            <td>{{ ucfirst($item->priority) ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Requisition For:</strong></td>
            <td>{{ $item->employee->name ?? 'N/A' }}</td>
            <td><strong>Status:</strong></td>
            <td>{{ ucfirst($item->status) ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Maintenance Type:</strong></td>
            <td>{{ $item->maintenanceType->name ?? 'N/A' }}</td>
            <td><strong>Type:</strong></td>
            <td>{{ ucfirst($item->type) ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="parts-table">
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
            @foreach ($item->details as $d)
            <tr>
                <td>{{ $d->category->name ?? 'N/A' }}</td>
                <td>{{ $d->parts->name ?? 'N/A' }}</td>
                <td>{{ $d->qty ?? '0' }}</td>
                <td>{{ $d->price ?? '0' }}</td>
                <td>{{ $d->total ?? '0' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                <td>{{ $item->total }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p><strong>Remarks:</strong> {{ $item->remarks ?? 'N/A' }}</p>
    </div>
</body>
</html>
