@extends('admin.layouts.admin')
@section('title', 'Contact Messages')
@section('page_title', 'Contact Messages')

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
</style>
@endsection

@section('content')
<div class="admin-page-header">
  <h1>Contact Messages</h1>
</div>

<div class="admin-table-wrap">
  <div class="admin-table-header">
    <form method="GET" class="filter-bar">
      <div class="search-input">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" placeholder="Search name or email…" value="{{ request('search') }}">
      </div>

      {{-- Status Dropdown --}}
      <div class="modern-dropdown" id="status-dropdown">
        <button type="button" class="modern-dropdown__trigger" onclick="toggleDropdown('status-dropdown')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
          <span id="status-label">{{ request('status') ? ucfirst(request('status')) : 'All Statuses' }}</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="modern-dropdown__menu">
          <button type="button" class="modern-dropdown__item {{ !request('status') ? 'active' : '' }}" onclick="selectDropdown('status-dropdown', '', 'All Statuses')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            All Statuses
          </button>
          @foreach(['new','read','replied','resolved'] as $s)
          <button type="button" class="modern-dropdown__item {{ request('status') === $s ? 'active' : '' }}" onclick="selectDropdown('status-dropdown', '{{ $s }}', '{{ ucfirst($s) }}')">
            <span style="width:8px;height:8px;border-radius:50%;background:{{ ['new'=>'#3b82f6','read'=>'#f59e0b','replied'=>'#10b981','resolved'=>'#6b7280'][$s] }};"></span>
            {{ ucfirst($s) }}
          </button>
          @endforeach
        </div>
        <input type="hidden" name="status" value="{{ request('status') }}">
      </div>

      <button type="submit" class="btn-admin btn-admin--primary">Filter</button>
      @if(request('search') || request('status'))
        <a href="{{ route('admin.contacts.index') }}" class="btn-admin btn-admin--outline">Clear</a>
      @endif
    </form>
  </div>

  @if($contacts->isEmpty())
    <div class="admin-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0"/></svg>
      <p>No messages found.</p>
    </div>
  @else
    <table>
      <thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        @foreach($contacts as $contact)
        <tr>
          <td><strong>{{ $contact->name }}</strong></td>
          <td style="font-size:12px;color:#9ca3af;">{{ $contact->email }}</td>
          <td style="max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#6b7280;font-size:13px;">{{ Str::limit($contact->message, 80) }}</td>
          <td><span class="badge badge--{{ $contact->status }}">{{ ucfirst($contact->status) }}</span></td>
          <td style="font-size:12px;color:#9ca3af;">{{ $contact->created_at->format('d M Y') }}</td>
          <td><a href="{{ route('admin.contacts.show', $contact) }}" class="btn-admin btn-admin--outline">View</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div style="padding:16px 20px;">{{ $contacts->links() }}</div>
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
</script>
@endsection
