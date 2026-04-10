<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Machine Usage Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #00c8ff;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #0080ff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #00c8ff;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status-online {
            color: #00e676;
            font-weight: bold;
        }
        .status-offline {
            color: #ff4444;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Machine Usage Report</h1>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Machine Code</th>
                <th>Machine Name</th>
                <th>Total Sessions</th>
                <th>Total Minutes</th>
                <th>Total Coins</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($machines as $machine)
            <tr>
                <td>{{ $machine->machine_code }}</td>
                <td>{{ $machine->machine_name }}</td>
                <td>{{ $machine->sessions_count ?? 0 }}</td>
                <td>{{ $machine->total_minutes ?? 0 }} min</td>
                <td>₱{{ number_format($machine->coin_transactions_sum_coins_inserted ?? 0, 2) }}</td>
                <td class="status-{{ strtolower($machine->status) }}">
                    {{ ucfirst($machine->status) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No machine data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>CentralizedPisoNet Management System - Machine Usage Report</p>
        <p>This is a computer-generated document. No signature required.</p>
    </div>
</body>
</html>
