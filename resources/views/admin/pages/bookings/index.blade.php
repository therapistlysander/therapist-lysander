@extends('admin.layouts.admin')
@section('title', 'Bookings')
@section('page_title', 'Bookings')

@section('page_styles')
<style>
  /* Modern Dropdown */
  .modern-dropdown { position: relative; display: inline-block; }
  .modern-dropdown__trigger {
    display: flex; align-items: center; gap: 8px; padding: 8px 14px;
    background: white; border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 13px; color: #374151; cursor: pointer; min-width: 140px;
    transition: all 0.15s;
  }
  .modern-dropdown__trigger:hover { border-color: #5a9e97; }
  .modern-dropdown__trigger svg { width: 14px; height: 14px; color: #9ca3af; }
  .modern-dropdown__menu {
    display: none; position: absolute; top: calc(100% + 4px); left: 0;
    min-width: 100%; background: white; border: 1px solid #e5e7eb;
    border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    z-index: 100; overflow: hidden; padding: 4px;
  }
  .modern-dropdown__menu.open { display: block; }
  .modern-dropdown__item {
    display: flex; align-items: center; gap: 8px; width: 100%;
    padding: 8px 12px; font-size: 13px; color: #374151;
    border: none; background: none; cursor: pointer; border-radius: 6px;
    transition: background 0.1s; text-align: left;
  }
  .modern-dropdown__item:hover { background: #f3f4f6; }
  .modern-dropdown__item.active { background: #f0fdf9; color: #5a9e97; font-weight: 600; }
  .modern-dropdown__item svg { width: 14px; height: 14px; }

  /* Bulk Bar */
  .bulk-bar { display: none; align-items: center; gap: 12px; padding: 12px 20px; background: #fef3c7; border-bottom: 1px solid #fde68a; }
  .bulk-bar.visible { display: flex; }
  .bulk-bar__count { font-size: 13px; font-weight: 600; color: #92400e; }
  .bulk-bar__actions { display: flex; gap: 8px; margin-left: auto; }

  /* Table */
  .check-all { width: 16px; height: 16px; accent-color: #5a9e97; cursor: pointer; }
  .row-check { width: 16px; height: 16px; accent-color: #5a9e97; cursor: pointer; }
  tr.selected td { background: #f0fdf9 !important; }

  /* Filter Bar */
  .filter-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  .search-input {
    display: flex; align-items: center; gap: 8px; padding: 8px 14px;
    background: white; border: 1px solid #e5e7eb; border-radius: 8px;
    transition: border-color 0.15s;
  }
  .search-input:focus-within { border-color: #5a9e97; }
  .search-input input { border: none; outline: none; font-size: 13px; width: 180px; }
  .search-input svg { width: 14px; height: 14px; color: #9ca3af; }

  /* Responsive */
  @media (max-width: 768px) {
    .filter-bar { flex-direction: column; align-items: stretch; }
    .search-input { width: 100%; }
    .search-input input { width: 100%; }
    .modern-dropdown { width: 100%; }
    .modern-dropdown__trigger { width: 100%; }
    .modern-dropdown__menu { width: 100%; }
    .bulk-bar { flex-wrap: wrap; }
    .bulk-bar__actions { margin-left: 0; width: 100%; justify-content: flex-end; }
  }
</style>
@endsection

@section('content')

<div class="admin-table-wrap">
  {{-- Filter Bar --}}
  <div class="admin-table-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:16px 20px;">
    <form method="GET" class="filter-bar">
      <div class="search-input">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" placeholder="Search name or email..." value="{{ request('search') }}">
      </div>

      {{-- Status Dropdown --}}
      <div class="modern-dropdown" id="status-dropdown">
        <button type="button" class="modern-dropdown__trigger" onclick="toggleDropdown('status-dropdown')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
          <span id="status-label">{{ request('status') ? ucfirst(str_replace('_', ' ', request('status'))) : 'All Statuses' }}</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="modern-dropdown__menu">
          <button type="button" class="modern-dropdown__item {{ !request('status') ? 'active' : '' }}" onclick="selectDropdown('status-dropdown', '', 'All Statuses')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            All Statuses
          </button>
          @foreach(['pending','confirmed','cancelled','completed','no_show'] as $s)
          <button type="button" class="modern-dropdown__item {{ request('status') === $s ? 'active' : '' }}" onclick="selectDropdown('status-dropdown', '{{ $s }}', '{{ ucfirst(str_replace("_", " ", $s)) }}')">
            <span style="width:8px;height:8px;border-radius:50%;background:{{ ['pending'=>'#f59e0b','confirmed'=>'#10b981','cancelled'=>'#ef4444','completed'=>'#6366f1','no_show'=>'#9ca3af'][$s] }};"></span>
            {{ ucfirst(str_replace('_', ' ', $s)) }}
          </button>
          @endforeach
        </div>
        <input type="hidden" name="status" value="{{ request('status') }}">
      </div>

      {{-- Type Dropdown --}}
      <div class="modern-dropdown" id="type-dropdown">
        <button type="button" class="modern-dropdown__trigger" onclick="toggleDropdown('type-dropdown')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          <span id="type-label">{{ request('type') ? ucfirst(request('type')) : 'All Types' }}</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="modern-dropdown__menu">
          <button type="button" class="modern-dropdown__item {{ !request('type') ? 'active' : '' }}" onclick="selectDropdown('type-dropdown', '', 'All Types')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            All Types
          </button>
          <button type="button" class="modern-dropdown__item {{ request('type') === 'online' ? 'active' : '' }}" onclick="selectDropdown('type-dropdown', 'online', 'Online')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 19h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Online
          </button>
          <button type="button" class="modern-dropdown__item {{ request('type') === 'in-person' ? 'active' : '' }}" onclick="selectDropdown('type-dropdown', 'in-person', 'In-person')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            In-person
          </button>
        </div>
        <input type="hidden" name="type" value="{{ request('type') }}">
      </div>

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
    <div class="admin-table-scroll">
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
              <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" style="margin:0;" id="delete-form-{{ $booking->id }}">
                @csrf @method('DELETE')
                <button type="button" class="btn-admin btn-admin--danger" style="font-size:11px;padding:4px 10px;" onclick="showConfirmModal('Delete Booking?', 'Are you sure you want to delete this booking? This cannot be undone.', function() { document.getElementById('delete-form-{{ $booking->id }}').submit(); })">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>
    <div style="padding:16px 20px;">{{ $bookings->links() }}</div>
  @endif
</div>
@endsection

@section('page_scripts')
<script>
// Dropdown Functions
function toggleDropdown(id) {
  const dropdown = document.getElementById(id);
  const menu = dropdown.querySelector('.modern-dropdown__menu');
  const isOpen = menu.classList.contains('open');

  // Close all dropdowns
  document.querySelectorAll('.modern-dropdown__menu').forEach(m => m.classList.remove('open'));

  // Toggle current
  if (!isOpen) {
    menu.classList.add('open');
  }
}

function selectDropdown(id, value, label) {
  const dropdown = document.getElementById(id);
  const input = dropdown.querySelector('input[type="hidden"]');
  const labelEl = dropdown.querySelector('[id$="-label"]');

  input.value = value;
  labelEl.textContent = label;

  // Update active state
  dropdown.querySelectorAll('.modern-dropdown__item').forEach(item => {
    item.classList.remove('active');
  });
  event.target.closest('.modern-dropdown__item').classList.add('active');

  // Close dropdown
  dropdown.querySelector('.modern-dropdown__menu').classList.remove('open');

  // Submit form
  dropdown.closest('form').submit();
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
  if (!e.target.closest('.modern-dropdown')) {
    document.querySelectorAll('.modern-dropdown__menu').forEach(m => m.classList.remove('open'));
  }
});

// Table Functions
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

  showConfirmModal(
    'Delete ' + checked.length + ' Booking(s)?',
    'This action cannot be undone. All selected bookings will be permanently deleted.',
    function() {
      const form = document.getElementById('bulk-form');
      form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
      checked.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = cb.value;
        form.appendChild(input);
      });
      form.submit();
    }
  );
}
</script>
@endsection
