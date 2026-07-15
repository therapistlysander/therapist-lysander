@extends('admin.layouts.admin')
@section('title', 'Bookings')
@section('page_title', 'Bookings')

@section('content')

<div class="admin-table-wrap">
  @include('admin.partials.table-filter-bar', [
    'resetUrl' => route('admin.bookings.index'),
    'searchPlaceholder' => 'Search name or email...',
    'statusOptions' => [
      '' => 'All Statuses',
      'pending' => 'Pending',
      'confirmed' => 'Confirmed',
      'cancelled' => 'Cancelled',
      'completed' => 'Completed',
      'no_show' => 'No Show',
    ],
    'extraFilters' => [
      'type' => [
        '' => 'All Types',
        'online' => 'Online',
        'in-person' => 'In-person',
      ],
    ],
  ])

  @include('admin.partials.table-bulk-bar', [
    'bulkActionRoute' => route('admin.bookings.bulkDelete'),
    'bulkActions' => ['delete' => 'Delete Selected'],
  ])

  @if($bookings->isEmpty())
    <div class="dt-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/></svg>
      <p>No bookings found.</p>
    </div>
  @else
    <div class="admin-table-scroll">
    <table>
      <thead>
        <tr>
          <th style="width:36px;"><input type="checkbox" class="dt-check-all"></th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'first_name', 'direction' => request('sort') === 'first_name' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'first_name' ? 'active' : '' }}">Client <span class="dt-sort__icon">{{ request('sort') === 'first_name' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'session_type', 'direction' => request('sort') === 'session_type' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'session_type' ? 'active' : '' }}">Type <span class="dt-sort__icon">{{ request('sort') === 'session_type' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th>Format</th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'scheduled_at', 'direction' => request('sort') === 'scheduled_at' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'scheduled_at' ? 'active' : '' }}">Date & Time <span class="dt-sort__icon">{{ request('sort') === 'scheduled_at' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'status' ? 'active' : '' }}">Status <span class="dt-sort__icon">{{ request('sort') === 'status' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'created_at' ? 'active' : '' }}">Received <span class="dt-sort__icon">{{ request('sort') === 'created_at' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th style="width:140px;"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($bookings as $booking)
        <tr>
          <td><input type="checkbox" class="dt-row-check" value="{{ $booking->id }}"></td>
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

    @include('admin.partials.table-pagination', ['items' => $bookings])
  @endif
</div>
@endsection
