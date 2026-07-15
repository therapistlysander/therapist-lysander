@php
  $currentSort = request('sort', 'created_at');
  $currentDir = request('dir', 'desc');
@endphp
@extends('admin.layouts.admin')
@section('title', 'Contact Messages')
@section('page_title', 'Contact Messages')

@section('content')
<div class="admin-page-header">
  <h1>Contact Messages</h1>
</div>

<div class="admin-table-wrap">
  {{-- Toolbar --}}
  @include('admin.partials.table-filter-bar', [
    'searchPlaceholder' => 'Search name, email or message...',
    'filters' => [
      [
        'id' => 'status',
        'label' => 'All Statuses',
        'options' => [
          ['value' => '', 'label' => 'All Statuses'],
          ['value' => 'new', 'label' => 'New'],
          ['value' => 'read', 'label' => 'Read'],
          ['value' => 'replied', 'label' => 'Replied'],
          ['value' => 'resolved', 'label' => 'Resolved'],
        ],
        'colors' => ['new' => '#3b82f6', 'read' => '#f59e0b', 'replied' => '#10b981', 'resolved' => '#6b7280'],
      ],
    ],
    'resetUrl' => route('admin.contacts.index'),
    'total' => $contacts->total(),
  ])

  {{-- Bulk bar --}}
  @include('admin.partials.table-bulk-bar', [
    'bulkActionUrl' => route('admin.contacts.bulkAction'),
    'bulkActions' => [
      ['label' => 'Delete Selected', 'action' => 'delete', 'class' => 'btn-admin--danger'],
      ['label' => 'Mark as Read', 'action' => 'mark_read', 'class' => 'btn-admin--outline'],
      ['label' => 'Mark as Replied', 'action' => 'mark_replied', 'class' => 'btn-admin--outline'],
      ['label' => 'Mark as Resolved', 'action' => 'mark_resolved', 'class' => 'btn-admin--outline'],
    ],
  ])

  @if($contacts->isEmpty())
    <div class="table-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0"/></svg>
      <p>{{ request('search') || request('status') ? 'No matching messages found.' : 'No messages found.' }}</p>
    </div>
  @else
    <div class="admin-table-scroll">
    <table>
      <thead>
        <tr>
          <th style="width:36px;"></th>
          @include('admin.partials.table-sort-header', ['column' => 'name', 'label' => 'Name', 'currentSort' => $currentSort, 'currentDir' => $currentDir])
          <th>Email</th>
          <th>Message</th>
          @include('admin.partials.table-sort-header', ['column' => 'status', 'label' => 'Status', 'currentSort' => $currentSort, 'currentDir' => $currentDir])
          @include('admin.partials.table-sort-header', ['column' => 'created_at', 'label' => 'Date', 'currentSort' => $currentSort, 'currentDir' => $currentDir])
          <th style="width:80px;"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($contacts as $contact)
        <tr>
          <td><input type="checkbox" class="row-check" value="{{ $contact->id }}" onchange="updateTableBulkBar()"></td>
          <td><strong>{{ $contact->name }}</strong></td>
          <td style="font-size:12px;color:#9ca3af;">{{ $contact->email }}</td>
          <td style="max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#6b7280;font-size:13px;">{{ Str::limit($contact->message, 80) }}</td>
          <td><span class="badge badge--{{ $contact->status }}">{{ ucfirst($contact->status) }}</span></td>
          <td style="font-size:12px;color:#9ca3af;">{{ $contact->created_at->format('d M Y') }}</td>
          <td><a href="{{ route('admin.contacts.show', $contact) }}" class="btn-admin btn-admin--outline btn-admin--sm">View</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>
    @include('admin.partials.table-pagination', ['items' => $contacts])
  @endif
</div>
@endsection
