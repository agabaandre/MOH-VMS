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
            <td>{{ $item->title }}</td>
            <td><strong>Date:</strong></td>
            <td>{{ $item->date }}</td>
        </tr>
        <tr>
            <td><strong>Vehicle:</strong></td>
            <td>{{ $item->vehicle->name }}</td>
            <td><strong>Priority:</strong></td>
            <td>{{ ucfirst($item->priority) }}</td>
        </tr>
        <tr>
            <td><strong>Requisition For:</strong></td>
            <td>{{ $item->employee->name }}</td>
            <td><strong>Status:</strong></td>
            <td>{{ ucfirst($item->status) }}</td>
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
                <td>{{ $d->category->name }}</td>
                <td>{{ $d->parts->name }}</td>
                <td>{{ $d->qty }}</td>
                <td>{{ $d->price }}</td>
                <td>{{ $d->total }}</td>
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
