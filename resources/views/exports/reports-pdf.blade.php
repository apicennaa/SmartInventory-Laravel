<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Report</title>
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
        .badge-incoming {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-outgoing {
            background: #fed7aa;
            color: #9a3412;
        }
        .badge-stock {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-stock-low {
            background: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e5e5;
            font-size: 10px;
            color: #888;
        }
        .summary {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 0 15px;
            border-right: 1px solid rgba(255,255,255,0.3);
        }
        .summary-item:last-child {
            border-right: none;
        }
        .summary-value {
            font-size: 20px;
            font-weight: bold;
        }
        .summary-label {
            font-size: 10px;
            opacity: 0.9;
            margin-top: 3px;
        }
        .category-badge {
            background: #e5e5e5;
            color: #555;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Inventory Report</h1>
        <p>Generated on {{ date('d F Y, H:i') }}</p>
    </div>

    @if(array_filter($filters))
    <div class="filters">
        <strong>Active Filters:</strong>
        @if($filters['search']) | Search: "{{ $filters['search'] }}" @endif
        @if($filters['category']) | Category: {{ $filters['category'] }} @endif
        @if($filters['type']) | Type: {{ ucfirst($filters['type']) }} @endif
        @if($filters['entry_date']) | From: {{ $filters['entry_date'] }} @endif
        @if($filters['exit_date']) | To: {{ $filters['exit_date'] }} @endif
    </div>
    @endif

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value">{{ number_format($reports->count()) }}</div>
                <div class="summary-label">Total Products</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ number_format($totals['incoming']) }}</div>
                <div class="summary-label">Total Incoming</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ number_format($totals['outgoing']) }}</div>
                <div class="summary-label">Total Outgoing</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ number_format($totals['stock']) }}</div>
                <div class="summary-label">Current Stock</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 30%">Product</th>
                <th style="width: 15%">Category</th>
                <th style="width: 12%" class="text-right">Incoming</th>
                <th style="width: 12%" class="text-right">Outgoing</th>
                <th style="width: 12%" class="text-right">Stock</th>
                <th style="width: 14%">Last Sale</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $report)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $report['product'] }}</strong></td>
                <td>
                    @if($report['category'])
                        <span class="category-badge">{{ $report['category'] }}</span>
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
                <td class="text-right">
                    <span class="badge badge-incoming">{{ number_format($report['incoming']) }}</span>
                </td>
                <td class="text-right">
                    <span class="badge badge-outgoing">{{ number_format($report['outgoing']) }}</span>
                </td>
                <td class="text-right">
                    <span class="badge {{ $report['stock'] > 0 ? 'badge-stock' : 'badge-stock-low' }}">
                        {{ number_format($report['stock']) }}
                    </span>
                </td>
                <td>
                    @if($report['sales_date'])
                        {{ \Carbon\Carbon::parse($report['sales_date'])->format('d M Y') }}
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 30px; color: #888;">
                    No inventory data found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Smart Inventory System &bull; {{ $reports->count() }} products in report</p>
    </div>
</body>
</html>
