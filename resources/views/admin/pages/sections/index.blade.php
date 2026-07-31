@extends('admin.layouts.admin')
@section('title', $pageLabel . ' — Sections')
@section('page_title', 'Pages')

@section('content')
<div class="admin-page-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
  <div>
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
      <a href="{{ route('admin.pages.index') }}" style="font-size:12px;color:#5a9e97;text-decoration:none;display:flex;align-items:center;gap:4px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        All Pages
      </a>
    </div>
    <h1 style="margin:0;">{{ $pageLabel }}</h1>
    <p style="font-size:13px;color:#9ca3af;margin:4px 0 0;">{{ $sections->total() }} {{ Str::plural('section', $sections->total()) }} &middot; Edit content shown on this page.</p>
  </div>
  <a href="{{ url($pageRoute) }}" target="_blank" class="btn-admin btn-admin--outline" style="font-size:12px;padding:6px 14px;white-space:nowrap;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
    View Page
  </a>
</div>

<div class="admin-table-wrap">
  @include('admin.partials.table-filter-bar', [
    'resetUrl' => route('admin.sections.index', $page),
    'searchPlaceholder' => 'Search sections...',
    'statusOptions' => ['' => 'All Statuses', '1' => 'Active', '0' => 'Hidden'],
  ])

  @include('admin.partials.table-bulk-bar', [
    'bulkActionRoute' => route('admin.sections.bulkAction'),
    'bulkActions' => ['activate' => 'Activate', 'deactivate' => 'Deactivate'],
  ])

  @if($sections->isEmpty())
    <div class="dt-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
      <p>No sections found.</p>
    </div>
  @else
    <table>
      <thead>
        <tr>
          <th style="width:40px;"><input type="checkbox" class="dt-check-all"></th>
          <th style="width:50px;text-align:center;">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'sort_order', 'direction' => request('sort') === 'sort_order' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'sort_order' ? 'active' : '' }}"># <span class="dt-sort__icon">{{ request('sort') === 'sort_order' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th style="width:200px;">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'section_key', 'direction' => request('sort') === 'section_key' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'section_key' ? 'active' : '' }}">Section <span class="dt-sort__icon">{{ request('sort') === 'section_key' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th>Title / Heading</th>
          <th style="width:80px;text-align:center;">Image</th>
          <th style="width:80px;text-align:center;">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'is_active', 'direction' => request('sort') === 'is_active' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'is_active' ? 'active' : '' }}">Status <span class="dt-sort__icon">{{ request('sort') === 'is_active' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th style="width:80px;"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($sections as $section)
        <tr>
          <td><input type="checkbox" class="dt-row-check" value="{{ $section->id }}"></td>
          <td style="text-align:center;color:#9ca3af;font-size:13px;">{{ $section->sort_order }}</td>
          <td>
            <div style="font-size:13px;font-weight:500;color:#1a2332;">{{ $section->label ?? $section->section_key }}</div>
            <code style="font-size:10px;background:#f3f4f6;padding:1px 5px;border-radius:3px;color:#6b7280;">{{ $section->section_key }}</code>
          </td>
          <td style="font-size:13px;color:#374151;">
            {{ Str::limit($section->content['title'] ?? $section->content['heading'] ?? '—', 55) }}
          </td>
          <td style="text-align:center;">
            @if(!empty($section->content['image']))
              <img src="{{ $section->content['image'] }}" alt=""
                style="height:36px;width:48px;object-fit:cover;border-radius:4px;border:1px solid #e5e7eb;">
            @else
              <span style="font-size:11px;color:#d1d5db;">—</span>
            @endif
          </td>
          <td style="text-align:center;">
            @if($section->is_active)
              <span class="badge badge--confirmed">Active</span>
            @else
              <span class="badge badge--cancelled">Hidden</span>
            @endif
          </td>
          <td>
            <a href="{{ route('admin.sections.edit', $section) }}" class="btn-admin btn-admin--outline" style="font-size:12px;padding:5px 10px;">Edit</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    @include('admin.partials.table-pagination', ['items' => $sections])
  @endif
</div>
@endsection
