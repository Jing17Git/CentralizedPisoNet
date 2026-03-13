@extends('layouts.admin')

@section('title', 'Earnings Report')

@section('content')
<div class="glass-card" style="margin-bottom: 20px;">
  <div class="card-header">
    <div>
      <div class="card-title">Earnings <span>Report</span></div>
      <div class="card-sub">Detailed earnings breakdown by machine and date</div>
    </div>
    <div style="display: flex; gap: 10px;">
      <button onclick="exportPDF()" style="padding: 8px 16px; background: var(--accent-red); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">📄 Export PDF</button>
      <button onclick="exportExcel()" style="padding: 8px 16px; background: var(--accent-grn); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">📊 Export Excel</button>
      <button onclick="window.print()" style="padding: 8px 16px; background: var(--neon2); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">🖨️ Print</button>
    </div>
  </div>
  
  <!-- Filters -->
  <form method="GET" style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
    <input type="date" name="date" value="{{ request('date') }}" style="padding: 8px 12px; background: var(--glass); border: 1px solid var(--border); border-radius: 8px; color: var(--text-hi); font-size: 12px;">
    
    <select name="machine_id" style="padding: 8px 12px; background: var(--glass); border: 1px solid var(--border); border-radius: 8px; color: var(--text-hi); font-size: 12px;">
      <option value="">All Machines</option>
      @foreach($machines as $machine)
        <option value="{{ $machine->id }}" {{ request('machine_id') == $machine->id ? 'selected' : '' }}>
          {{ $machine->machine_name }}
        </option>
      @endforeach
    </select>
    
    <select name="filter" style="padding: 8px 12px; background: var(--glass); border: 1px solid var(--border); border-radius: 8px; color: var(--text-hi); font-size: 12px;">
      <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today</option>
      <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This Week</option>
      <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
    </select>
    
    <button type="submit" style="padding: 8px 16px; background: linear-gradient(90deg, var(--neon2), var(--neon)); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">Apply Filters</button>
  </form>
  
  <!-- Table -->
  <div style="overflow-x: auto;">
    <table class="txn-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Machine ID</th>
          <th>Machine Name</th>
          <th>Coins Inserted</th>
          <th>Minutes Purchased</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
        @forelse($earnings as $earning)
        <tr>
          <td>{{ $earning->created_at->format('M d, Y H:i') }}</td>
          <td class="txn-machine">{{ $earning->machine->machine_code ?? 'N/A' }}</td>
          <td class="txn-user">{{ $earning->machine->machine_name ?? 'Unknown' }}</td>
          <td>{{ $earning->coins_inserted }}</td>
          <td class="txn-dur">{{ $earning->minutes_purchased }} min</td>
          <td class="txn-amount">₱{{ number_format($earning->amount, 2) }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-mid);">No earnings data found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  <!-- Pagination -->
  <div style="margin-top: 20px;">
    {{ $earnings->links() }}
  </div>
</div>
@endsection

@section('scripts')
<script>
function exportPDF() {
  window.location.href = '/admin/reports/earnings/export/pdf?' + new URLSearchParams(window.location.search);
}

function exportExcel() {
  window.location.href = '/admin/reports/earnings/export/excel?' + new URLSearchParams(window.location.search);
}
</script>
@endsection
