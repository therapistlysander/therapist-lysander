@extends('admin.layouts.admin')
@section('title', 'Bookings')
@section('page_title', 'Bookings')

@section('page_styles')
<style>
  .bulk-bar { display: none; align-items: center; gap: 12px; padding: 12px 20px; background: #fef3c7; border-bottom: 1px solid #fde68a; }
  .bulk-bar.visible { display: flex; }
  .bulk-bar__count { font-size: 13px; font-weight: 600; color: #92400e; }
  .bulk-bar__actions { display: flex; gap: 8px; margin-left: auto; }
  .check-all { width: 16px; height: 16px; accent-color: #5a9e97; cursor: pointer; }
  .row-check { width: 16px; height: 16px; accent-color: #5a9e97; cursor: pointer; }
  tr.selected td { background: #f0fdf9 !important; }
</style>
@endsection

@section('content')

{{-- Stats Row --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
  @foreach([
    ['label'=>'Total','value'=>$stats['total'],'color'=>'#5a7a76'],
    ['label'=>'Pending','value'=>$stats['pending'],'color'=>'#f59e0b'],
    ['label'=>'Confirmed','value'=>$stats['confirmed'],'color'=>'#10b981'],
    ['label'=>'Completed','value'=>$stats['completed'],'color'=>'#6366f1'],
  ] as $s)
  <div style="background:#fff;border:1px solid #e8ede9;border-radius:10px;padding:16px 20px;display:flex;flex-direction:column;gap:4px;">
    <span style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:#9ca3af;font-weight:600;">{{ $s['label'] }}</span>
    <span style="font-size:28px;font-weight:700;color:{{ $s['color'] }};line-height:1;">{{ $s['value'] }}</span>
  </div>
  @endforeach
</div>

<div class="admin-table-wrap">
  <div class="admin-table-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
      <input type="text" name="search" class="admin-input" style="width:200px;" placeholder="Name or email..." value="{{ request('search') }}">
      <select name="status" class="admin-select" style="width:140px;">
        <option value="">All statuses</option>
        @foreach(['pending','confirmed','cancelled','completed','no_show'] as $s)
          <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
        @endforeach
      </select>
      <select name="type" class="admin-select" style="width:130px;">
        <option value="">All types</option>
        <option value="online" {{ request('type')==='online' ? 'selected' : '' }}>Online</option>
        <option value="in-person" {{ request('type')==='in-person' ? 'selected' : '' }}>In-person</option>
      </select>
      <button type="submit" class="btn-admin btn-admin--primary">Filter</button>
      @if(request('search') || request('status') || request('type'))
        <a href="{{ route('admin.bookings.index') }}" class="btn-admin btn-admin--outline">Clear</a>
      @endif
    </form>
    <span style="font-size:12px;color:#9ca3af;">{{ $bookings->total() }} booking{{ $bookings->total() !== 1 ? 's' : '' }}</span>
  </div>

  {{-- Bulk action bar --}}
  <div class="bulk-bar" id="bulk-bar">
    <span class="bulk-bar__count" id="bulk-count">0 selected</span>
    <div class="bulk-bar__actions">
      <button type="button" class="btn-admin btn-admin--danger" onclick="bulkDelete()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
        Delete Selected
      </button>
      <button type="button" class="btn-admin btn-admin--outline" onclick="clearSelection()">Cancel</button>
    </div>
  </div>

  {{-- Bulk delete form (hidden) --}}
  <form id="bulk-form" method="POST" action="{{ route('admin.bookings.bulkDelete') }}" style="display:none;">
    @csrf
  </form>

  @if($bookings->isEmpty())
    <div class="admin-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/></svg>
      <p>No bookings found.</p>
    </div>
  @else
    <table>
      <thead>
        <tr>
          <th style="width:36px;"><input type="checkbox" class="check-all" onchange="toggleAll(this)"></th>
          <th>Client</th>
          <th>Type</th>
          <th>Format</th>
          <th>Date & Time</th>
          <th>Status</th>
          <th>Received</th>
          <th style="width:140px;"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($bookings as $booking)
        <tr id="row-{{ $booking->id }}">
          <td><input type="checkbox" class="row-check" value="{{ $booking->id }}" onchange="updateBulkBar()"></td>
          <td>
            <div style="font-weight:600;font-size:13px;">{{ $booking->full_name }}</div>
            <div style="font-size:11px;color:#9ca3af;">{{ $booking->email }}</div>
          </td>
          <td style="font-size:12px;">{{ $booking->session_type ? ucfirst($booking->session_type) : '—' }}</td>
          <td style="font-size:12px;">{{ match($booking->session_format) { 'intake' => 'Introduction Call', 'standard' => 'Standard Session', 'emdr' => 'EMDR Session', 'initial' => 'Initial Session', default => $booking->session_format ? ucfirst(str_replace('_',' ',$booking->session_format)) : '—' } }}</td>
          <td style="font-size:12px;">
            @if($booking->preferred_date)
              <span style="color:#1a2332;font-weight:500;">{{ $booking->preferred_date->format('d M Y') }}</span><br>
              <span style="color:#9ca3af;">{{ $booking->preferred_date->format('H:i') }}</span>
            @elseif($booking->scheduled_at)
              <span style="color:#1a2332;font-weight:500;">{{ $booking->scheduled_at->format('d M Y') }}</span><br>
              <span style="color:#9ca3af;">{{ $booking->scheduled_at->format('H:i') }}</span>
            @else
              <span style="color:#f59e0b;">Not set</span>
            @endif
          </td>
          <td>
            @php
              $colors = ['pending'=>'#f59e0b','confirmed'=>'#10b981','cancelled'=>'#ef4444','completed'=>'#6366f1','no_show'=>'#9ca3af'];
              $color = $colors[$booking->status] ?? '#9ca3af';
            @endphp
            <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:{{ $color }}18;color:{{ $color }};">
              {{ ucfirst(str_replace('_',' ',$booking->status)) }}
            </span>
          </td>
          <td style="font-size:11px;color:#9ca3af;">{{ $booking->created_at->format('d M Y') }}</td>
          <td>
            <div style="display:flex;gap:6px;align-items:center;">
              <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-admin btn-admin--outline" style="font-size:11px;padding:4px 10px;">View</a>
              <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" style="margin:0;" onsubmit="return confirm('Delete this booking?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-admin btn-admin--danger" style="font-size:11px;padding:4px 10px;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div style="padding:16px 20px;">{{ $bookings->links() }}</div>
  @endif
</div>
@endsection

@section('page_scripts')
<script>
function toggleAll(master) {
  document.querySelectorAll('.row-check').forEach(cb => {
    cb.checked = master.checked;
    const row = cb.closest('tr');
    row.classList.toggle('selected', master.checked);
  });
  updateBulkBar();
}

function updateBulkBar() {
  const checked = document.querySelectorAll('.row-check:checked');
  const bar = document.getElementById('bulk-bar');
  const count = document.getElementById('bulk-count');
  if (checked.length > 0) {
    bar.classList.add('visible');
    count.textContent = checked.length + ' selected';
  } else {
    bar.classList.remove('visible');
  }
  // Highlight selected rows
  document.querySelectorAll('.row-check').forEach(cb => {
    cb.closest('tr').classList.toggle('selected', cb.checked);
  });
}

function clearSelection() {
  document.querySelectorAll('.row-check').forEach(cb => {
    cb.checked = false;
    cb.closest('tr').classList.remove('selected');
  });
  document.querySelector('.check-all').checked = false;
  document.getElementById('bulk-bar').classList.remove('visible');
}

function bulkDelete() {
  const checked = document.querySelectorAll('.row-check:checked');
  if (checked.length === 0) return;
  if (!confirm('Delete ' + checked.length + ' booking(s)? This cannot be undone.')) return;

  const form = document.getElementById('bulk-form');
  // Remove old hidden inputs
  form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
  // Add selected IDs
  checked.forEach(cb => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'ids[]';
    input.value = cb.value;
    form.appendChild(input);
  });
  form.submit();
}
</script>
@endsection
