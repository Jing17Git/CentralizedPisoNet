@extends('layouts.admin')

@section('title', 'Machine Usage Report')

@section('content')
<div class="glass-card">
  <div class="card-header">
    <div>
      <div class="card-title">Machine <span>Usage Report</span></div>
      <div class="card-sub">Comprehensive machine activity and performance</div>
    </div>
    <div style="display: flex; gap: 10px;">
      <button onclick="exportPDF()" style="padding: 8px 16px; background: var(--accent-red); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">📄 Export PDF</button>
      <button onclick="exportExcel()" style="padding: 8px 16px; background: var(--accent-grn); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">📊 Export Excel</button>
      <button onclick="window.print()" style="padding: 8px 16px; background: var(--neon2); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">🖨️ Print</button>
    </div>
  </div>
  
  <div style="overflow-x: auto; margin-top: 20px;">
    <table class="txn-table">
      <thead>
        <tr>
          <th>Machine ID</th>
          <th>Machine Name</th>
          <th>Total Sessions</th>
          <th>Total Minutes Used</th>
          <th>Total Coins Collected</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($machines as $machine)
        <tr>
          <td class="txn-machine">{{ $machine->machine_code }}</td>
          <td class="txn-user">{{ $machine->machine_name }}</td>
          <td>{{ $machine->sessions_count ?? 0 }}</td>
          <td class="txn-dur">{{ number_format($machine->total_minutes ?? 0) }} min</td>
          <td class="txn-amount">{{ number_format($machine->coin_transactions_sum_coins_inserted ?? 0) }}</td>
          <td>
            @if($machine->status == 'online')
              <span class="badge-paid">Online</span>
            @else
              <span class="badge-pend">Offline</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-mid);">No machine data found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('scripts')
<script>
function exportPDF() {
  window.location.href = '/admin/reports/machines/export/pdf';
}

function exportExcel() {
  window.location.href = '/admin/reports/machines/export/excel';
}
</script>
@endsection
