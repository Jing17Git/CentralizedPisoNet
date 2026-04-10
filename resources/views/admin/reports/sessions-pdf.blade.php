<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Session History Report</title>
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
            font-size: 11px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status-active {
            color: #00e676;
            font-weight: bold;
        }
        .status-ended {
            color: #666;
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
        <h1>Session History Report</h1>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Session ID</th>
                <th>Machine</th>
                <th>User</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Minutes Used</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $session)
            <tr>
                <td>{{ $session->session_id }}</td>
                <td>PC-{{ str_pad($session->pc_unit_number, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $session->user_session_name ?? 'Guest' }}</td>
                <td>{{ \Carbon\Carbon::parse($session->start_time)->format('Y-m-d H:i') }}</td>
                <td>{{ $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ $session->minutes_used ?? 0 }} min</td>
                <td class="status-{{ strtolower($session->status) }}">
                    {{ ucfirst($session->status) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No session data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>CentralizedPisoNet Management System - Session History Report</p>
        <p>This is a computer-generated document. No signature required.</p>
    </div>
</body>
</html>
