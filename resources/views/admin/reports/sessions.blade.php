@extends('layouts.admin')

@section('title', 'Session History Report')

@section('content')
<div class="glass-card">
  <div class="card-header">
    <div>
      <div class="card-title">Session <span>History Report</span></div>
      <div class="card-sub">Complete session logs and activity</div>
    </div>
    <div style="display: flex; gap: 10px;">
      <button onclick="exportPDF()" style="padding: 8px 16px; background: var(--accent-red); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">📄 Export PDF</button>
      <button onclick="exportExcel()" style="padding: 8px 16px; background: var(--accent-grn); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">📊 Export Excel</button>
      <button onclick="window.print()" style="padding: 8px 16px; background: var(--neon2); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">🖨️ Print</button>
    </div>
  </div>
  
  <!-- Filters -->
  <form method="GET" style="display: flex; gap: 12px; margin: 20px 0; flex-wrap: wrap;">
    <input type="date" name="date" value="{{ request('date') }}" style="padding: 8px 12px; background: var(--glass); border: 1px solid var(--border); border-radius: 8px; color: var(--text-hi); font-size: 12px;">
    
    <select name="machine_id" style="padding: 8px 12px; background: var(--glass); border: 1px solid var(--border); border-radius: 8px; color: var(--text-hi); font-size: 12px;">
      <option value="">All Machines</option>
      @foreach($machines as $machine)
        <option value="{{ $machine->id }}" {{ request('machine_id') == $machine->id ? 'selected' : '' }}>
          {{ $machine->machine_name }}
        </option>
      @endforeach
    </select>
    
    <button type="submit" style="padding: 8px 16px; background: linear-gradient(90deg, var(--neon2), var(--neon)); border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 12px; font-weight: 600;">Apply Filters</button>
  </form>
  
  <div style="overflow-x: auto;">
    <table class="txn-table">
      <thead>
        <tr>
          <th>Session ID</th>
          <th>Machine ID</th>
          <th>User Name</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Minutes Used</th>
          <th>Remaining Time</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($sessions as $session)
        <tr>
          <td class="txn-machine">{{ $session->session_id }}</td>
          <td class="txn-machine">{{ $session->pc_unit_number }}</td>
          <td class="txn-user">{{ $session->user_session_name }}</td>
          <td>{{ $session->start_time->format('M d, Y H:i') }}</td>
          <td>{{ $session->end_time ? $session->end_time->format('M d, Y H:i') : 'Active' }}</td>
          <td class="txn-dur">{{ $session->minutes_used }} min</td>
          <td class="txn-dur">{{ $session->remaining_time }} min</td>
          <td>
            @if($session->status == 'Active')
              <span class="badge-paid">Active</span>
            @else
              <span class="badge-pend">Ended</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-mid);">No session data found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  <!-- Pagination -->
  <div style="margin-top: 20px;">
    {{ $sessions->links() }}
  </div>
</div>
@endsection

@section('scripts')
<script>
function exportPDF() {
  window.location.href = '/admin/reports/sessions/export/pdf?' + new URLSearchParams(window.location.search);
}

function exportExcel() {
  window.location.href = '/admin/reports/sessions/export/excel?' + new URLSearchParams(window.location.search);
}
</script>
@endsection
