@extends('admin.layouts.admin')
@section('title', 'SEO Settings')
@section('page_title', 'SEO Settings')

@section('content')
<div class="admin-page-header">
  <h1>SEO Settings</h1>
</div>

<div class="admin-table-wrap">
  @include('admin.partials.table-filter-bar', [
    'resetUrl' => route('admin.seo.index'),
    'searchPlaceholder' => 'Search page key...',
  ])

  @if($seoSettings->isEmpty())
    <div class="dt-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>
      <p>No SEO settings found.</p>
    </div>
  @else
    <table>
      <thead>
        <tr>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'page_key', 'direction' => request('sort') === 'page_key' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => null]) }}" class="dt-sort {{ request('sort') === 'page_key' ? 'active' : '' }}">Page <span class="dt-sort__icon">{{ request('sort') === 'page_key' ? (request('direction') === 'asc' ? '▲' : '▼') : '▲▼' }}</span></a>
          </th>
          <th>Title</th>
          <th>Meta Description</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($seoSettings as $seo)
        <tr>
          <td><code style="font-size:12px;background:#f3f4f6;padding:2px 6px;border-radius:4px;">{{ $seo->page_key }}</code></td>
          <td style="max-width:220px;font-size:13px;">{{ Str::limit($seo->title, 50) }}</td>
          <td style="max-width:300px;font-size:12px;color:#9ca3af;">{{ Str::limit($seo->meta_description, 70) }}</td>
          <td><a href="{{ route('admin.seo.edit', $seo->page_key) }}" class="btn-admin btn-admin--outline">Edit</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>

    @include('admin.partials.table-pagination', ['items' => $seoSettings])
  @endif
</div>
@endsection
