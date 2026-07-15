@php
  $currentSort = request('sort', 'page_key');
  $currentDir = request('dir', 'asc');
@endphp
@extends('admin.layouts.admin')
@section('title', 'SEO Settings')
@section('page_title', 'SEO Settings')

@section('content')
<div class="admin-page-header">
  <h1>SEO Settings</h1>
</div>

<div class="admin-table-wrap">
  {{-- Toolbar --}}
  @include('admin.partials.table-filter-bar', [
    'searchPlaceholder' => 'Search page or title...',
    'filters' => [],
    'resetUrl' => route('admin.seo.index'),
    'total' => $seoSettings->total(),
  ])

  @if($seoSettings->isEmpty())
    <div class="table-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9.75m3 0H9.75m3 3.75H9.75m-3 3.75h6.375a2.25 2.25 0 002.25-2.25V7.875a2.25 2.25 0 00-2.25-2.25H8.25"/></svg>
      <p>{{ request('search') ? 'No matching SEO settings found.' : 'No SEO settings found.' }}</p>
    </div>
  @else
    <div class="admin-table-scroll">
    <table>
      <thead>
        <tr>
          @include('admin.partials.table-sort-header', ['column' => 'page_key', 'label' => 'Page', 'currentSort' => $currentSort, 'currentDir' => $currentDir])
          @include('admin.partials.table-sort-header', ['column' => 'title', 'label' => 'Title', 'currentSort' => $currentSort, 'currentDir' => $currentDir])
          <th>Meta Description</th>
          <th style="width:80px;"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($seoSettings as $seo)
        <tr>
          <td><code style="font-size:12px;background:#f3f4f6;padding:2px 6px;border-radius:4px;">{{ $seo->page_key }}</code></td>
          <td style="max-width:220px;font-size:13px;">{{ Str::limit($seo->title, 50) }}</td>
          <td style="max-width:300px;font-size:12px;color:#9ca3af;">{{ Str::limit($seo->meta_description, 70) }}</td>
          <td><a href="{{ route('admin.seo.edit', $seo->page_key) }}" class="btn-admin btn-admin--outline btn-admin--sm">Edit</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>
    @include('admin.partials.table-pagination', ['items' => $seoSettings])
  @endif
</div>
@endsection
