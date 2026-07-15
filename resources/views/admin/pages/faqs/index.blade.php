@extends('admin.layouts.admin')
@section('title', 'FAQs')
@section('page_title', 'FAQs')

@section('content')
<div class="admin-page-header">
  <h1>FAQs</h1>
  <a href="{{ route('admin.faqs.create') }}" class="btn-admin btn-admin--primary">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Add FAQ
  </a>
</div>

<div class="admin-table-wrap">
  @include('admin.partials.table-filter-bar', [
    'resetUrl' => route('admin.faqs.index'),
    'searchPlaceholder' => 'Search question or category...',
    'statusOptions' => ['' => 'All Statuses', '1' => 'Active', '0' => 'Hidden'],
    'extraFilters' => [
      'category' => array_merge(['' => 'All Categories'], $categories),
    ],
  ])

  @include('admin.partials.table-bulk-bar', [
    'bulkActionRoute' => route('admin.faqs.bulkAction'),
    'bulkActions' => ['activate' => 'Activate', 'deactivate' => 'Deactivate', 'delete' => 'Delete'],
  ])

  @if($faqs->isEmpty())
    <div class="dt-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
      <p>No FAQs found.</p>
    </div>
  @else
    <table>
      <thead>
        <tr>
          <th style="width:40px;"><input type="checkbox" class="dt-check-all"></th>
          <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'category', 'direction' => request('sort') === 'category' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'category' ? 'active' : '' }}">Category <span class="dt-sort__icon">{{ request('sort') === 'category' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a></th>
          <th>Question</th>
          <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'sort_order', 'direction' => request('sort') === 'sort_order' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'sort_order' ? 'active' : '' }}">Order <span class="dt-sort__icon">{{ request('sort') === 'sort_order' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a></th>
          <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'is_active', 'direction' => request('sort') === 'is_active' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'is_active' ? 'active' : '' }}">Status <span class="dt-sort__icon">{{ request('sort') === 'is_active' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($faqs as $faq)
        <tr>
          <td><input type="checkbox" class="dt-row-check" value="{{ $faq->id }}"></td>
          <td><span style="font-size:12px;background:#f3f4f6;padding:2px 8px;border-radius:999px;">{{ $categories[$faq->category] ?? $faq->category }}</span></td>
          <td style="max-width:400px;">{{ Str::limit($faq->question, 80) }}</td>
          <td style="color:#9ca3af;font-size:13px;">{{ $faq->sort_order }}</td>
          <td>@if($faq->is_active)<span class="badge badge--confirmed">Active</span>@else<span class="badge badge--cancelled">Hidden</span>@endif</td>
          <td style="display:flex;gap:6px;">
            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn-admin btn-admin--outline">Edit</a>
            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Delete this FAQ?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-admin btn-admin--danger">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    @include('admin.partials.table-pagination', ['items' => $faqs])
  @endif
</div>
@endsection
