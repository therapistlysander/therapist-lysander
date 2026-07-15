@php
  $categoryLabels = [
    'therapy_emdr' => __('ui.faq.cat_therapy_emdr') ?? 'Therapy & EMDR',
    'starting_therapy' => __('ui.faq.cat_starting_therapy') ?? 'Introduction & Intake',
    'practical' => __('ui.faq.cat_practical') ?? 'Practical Information',
    'sessions_progress' => __('ui.faq.cat_sessions_progress') ?? 'Therapy Process',
  ];

  $currentSort = request('sort', 'sort_order');
  $currentDir = request('dir', 'asc');
@endphp
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
  {{-- Toolbar --}}
  @include('admin.partials.table-filter-bar', [
    'searchPlaceholder' => 'Search questions...',
    'filters' => [
      [
        'id' => 'category',
        'label' => 'All Categories',
        'options' => array_merge([['value' => '', 'label' => 'All Categories']], collect($categoryLabels)->map(fn($label, $key) => ['value' => $key, 'label' => $label])->values()->toArray()),
      ],
      [
        'id' => 'is_active',
        'label' => 'All Status',
        'options' => [
          ['value' => '', 'label' => 'All Status'],
          ['value' => '1', 'label' => 'Active'],
          ['value' => '0', 'label' => 'Hidden'],
        ],
        'colors' => ['1' => '#10b981', '0' => '#9ca3af'],
      ],
    ],
    'resetUrl' => route('admin.faqs.index'),
    'total' => $faqs->total(),
  ])

  {{-- Bulk bar --}}
  @include('admin.partials.table-bulk-bar', [
    'bulkActionUrl' => route('admin.faqs.bulkDestroy'),
  ])

  @if($faqs->isEmpty())
    <div class="table-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
      <p>{{ request('search') || request('category') || request('is_active') !== null ? 'No matching FAQs found.' : 'No FAQs yet.' }} @if(!request('search') && !request('category') && request('is_active') === null)<a href="{{ route('admin.faqs.create') }}" style="color:#5a9e97;">Add the first one.</a>@endif</p>
    </div>
  @else
    <div class="admin-table-scroll">
    <table>
      <thead>
        <tr>
          <th style="width:36px;"></th>
          @include('admin.partials.table-sort-header', ['column' => 'question', 'label' => 'Question', 'currentSort' => $currentSort, 'currentDir' => $currentDir])
          @include('admin.partials.table-sort-header', ['column' => 'category', 'label' => 'Category', 'currentSort' => $currentSort, 'currentDir' => $currentDir])
          @include('admin.partials.table-sort-header', ['column' => 'sort_order', 'label' => 'Order', 'currentSort' => $currentSort, 'currentDir' => $currentDir])
          @include('admin.partials.table-sort-header', ['column' => 'is_active', 'label' => 'Status', 'currentSort' => $currentSort, 'currentDir' => $currentDir])
          <th style="width:140px;"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($faqs as $faq)
        <tr>
          <td><input type="checkbox" class="row-check" value="{{ $faq->id }}" onchange="updateTableBulkBar()"></td>
          <td style="max-width:400px;">{{ Str::limit($faq->question, 80) }}</td>
          <td><span style="font-size:12px;background:#f3f4f6;padding:2px 8px;border-radius:999px;">{{ $categoryLabels[$faq->category] ?? $faq->category }}</span></td>
          <td style="color:#9ca3af;font-size:13px;">{{ $faq->sort_order }}</td>
          <td>@if($faq->is_active)<span class="badge badge--confirmed">Active</span>@else<span class="badge badge--cancelled">Hidden</span>@endif</td>
          <td style="display:flex;gap:6px;">
            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn-admin btn-admin--outline btn-admin--sm">Edit</a>
            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Delete this FAQ?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-admin btn-admin--danger btn-admin--sm">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>
    @include('admin.partials.table-pagination', ['items' => $faqs])
  @endif
</div>
@endsection
