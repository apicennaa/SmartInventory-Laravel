<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Outgoing Goods Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #7c3aed;
        }
        .header h1 {
            font-size: 22px;
            color: #7c3aed;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .filters {
            background: #f8f5ff;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .filters strong {
            color: #7c3aed;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #7c3aed;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e5e5;
        }
        tr:nth-child(even) {
            background: #faf8ff;
        }
        tr:hover {
            background: #f0ebff;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-category {
            background: #e5e5e5;
            color: #555;
        }
        .badge-quantity {
            background: #fed7aa;
            color: #9a3412;
        }
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e5e5;
            font-size: 10px;
            color: #888;
        }
        .summary {
            background: #7c3aed;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 0 10px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
        }
        .summary-label {
            font-size: 10px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Outgoing Goods Report</h1>
        <p>Generated on {{ date('d F Y, H:i') }}</p>
    </div>

    @if(array_filter($filters))
    <div class="filters">
        <strong>Active Filters:</strong>
        @if($filters['search']) | Search: "{{ $filters['search'] }}" @endif
        @if($filters['category']) | Category: {{ $filters['category'] }} @endif
        @if($filters['store']) | Store: {{ $filters['store'] }} @endif
        @if($filters['start_date']) | From: {{ $filters['start_date'] }} @endif
        @if($filters['end_date']) | To: {{ $filters['end_date'] }} @endif
    </div>
    @endif

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value">{{ number_format($outgoingGoods->count()) }}</div>
                <div class="summary-label">Total Records</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ number_format($outgoingGoods->sum('outgoing')) }}</div>
                <div class="summary-label">Total Quantity</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $outgoingGoods->pluck('store')->unique()->count() }}</div>
                <div class="summary-label">Unique Stores</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 25%">Product</th>
                <th style="width: 15%">Category</th>
                <th style="width: 10%" class="text-right">Quantity</th>
                <th style="width: 25%">Store</th>
                <th style="width: 15%">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($outgoingGoods as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item->product }}</strong></td>
                <td><span class="badge badge-category">{{ $item->category }}</span></td>
                <td class="text-right"><span class="badge badge-quantity">{{ number_format($item->outgoing) }}</span></td>
                <td>{{ $item->store }}</td>
                <td>{{ $item->date->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 30px; color: #888;">
                    No outgoing goods data found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Smart Inventory System - Total {{ $outgoingGoods->count() }} records exported</p>
    </div>
</body>
</html>
