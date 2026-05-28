@extends('admin.layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="admin-stats">
  <div class="admin-stat">
    <div class="admin-stat__num">{{ $stats['bookings_total'] }}</div>
    <div class="admin-stat__label">Total Bookings</div>
    @if($stats['bookings_new'] > 0)
      <span class="admin-stat__badge admin-stat__badge--new">{{ $stats['bookings_new'] }} new</span>
    @endif
  </div>
  <div class="admin-stat">
    <div class="admin-stat__num">{{ $stats['contacts_total'] }}</div>
    <div class="admin-stat__label">Contact Messages</div>
    @if($stats['contacts_new'] > 0)
      <span class="admin-stat__badge admin-stat__badge--new">{{ $stats['contacts_new'] }} unread</span>
    @endif
  </div>
  <div class="admin-stat">
    <div class="admin-stat__num">{{ $stats['testimonials'] }}</div>
    <div class="admin-stat__label">Testimonials</div>
  </div>
  <div class="admin-stat">
    <div class="admin-stat__num">{{ $stats['faqs'] }}</div>
    <div class="admin-stat__label">FAQ Items</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;flex-wrap:wrap;">

  <!-- Recent bookings -->
  <div class="admin-table-wrap">
    <div class="admin-table-header">
      <h2>Recent Bookings</h2>
      <a href="{{ route('admin.bookings.index') }}" class="btn-admin btn-admin--outline">View all</a>
    </div>
    @if($recentBookings->isEmpty())
      <div class="admin-empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/></svg>
        <p>No bookings yet</p>
      </div>
    @else
      <table>
        <thead><tr><th>Name</th><th>Format</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
          @foreach($recentBookings as $booking)
          <tr>
            <td><a href="{{ route('admin.bookings.show', $booking) }}" style="color:#5a9e97;text-decoration:none;">{{ $booking->full_name }}</a></td>
            <td>{{ $booking->session_format ?? '—' }}</td>
            <td><span class="badge badge--{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
            <td style="color:#9ca3af;font-size:12px;">{{ $booking->created_at->diffForHumans() }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  <!-- Recent contacts -->
  <div class="admin-table-wrap">
    <div class="admin-table-header">
      <h2>Recent Messages</h2>
      <a href="{{ route('admin.contacts.index') }}" class="btn-admin btn-admin--outline">View all</a>
    </div>
    @if($recentContacts->isEmpty())
      <div class="admin-empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
        <p>No messages yet</p>
      </div>
    @else
      <table>
        <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
          @foreach($recentContacts as $contact)
          <tr>
            <td><a href="{{ route('admin.contacts.show', $contact) }}" style="color:#5a9e97;text-decoration:none;">{{ $contact->name }}</a></td>
            <td style="font-size:12px;color:#9ca3af;">{{ $contact->email }}</td>
            <td><span class="badge badge--{{ $contact->status }}">{{ ucfirst($contact->status) }}</span></td>
            <td style="color:#9ca3af;font-size:12px;">{{ $contact->created_at->diffForHumans() }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

</div>
@endsection
