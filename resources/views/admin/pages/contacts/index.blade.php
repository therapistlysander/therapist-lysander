@extends('admin.layouts.admin')
@section('title', 'Contact Messages')
@section('page_title', 'Contact Messages')

@section('content')
<div class="admin-page-header">
  <h1>Contact Messages</h1>
</div>

<div class="admin-table-wrap">
  @include('admin.partials.table-filter-bar', [
    'resetUrl' => route('admin.contacts.index'),
    'searchPlaceholder' => 'Search name or email...',
    'statusOptions' => [
      '' => 'All Statuses',
      'new' => 'New',
      'read' => 'Read',
      'replied' => 'Replied',
      'resolved' => 'Resolved',
    ],
  ])

  @if($contacts->isEmpty())
    <div class="dt-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0"/></svg>
      <p>No messages found.</p>
    </div>
  @else
    <table>
      <thead>
        <tr>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'name' ? 'active' : '' }}">Name <span class="dt-sort__icon">{{ request('sort') === 'name' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th>Email</th>
          <th>Message</th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'status' ? 'active' : '' }}">Status <span class="dt-sort__icon">{{ request('sort') === 'status' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'created_at' ? 'active' : '' }}">Date <span class="dt-sort__icon">{{ request('sort') === 'created_at' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th></th>
        </tr>
      </thead>
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

    @include('admin.partials.table-pagination', ['items' => $contacts])
  @endif
</div>
@endsection
