@extends('admin.layouts.admin')
@section('title', 'SEO Settings')
@section('page_title', 'SEO Settings')

@section('content')
<div class="admin-page-header">
  <h1>SEO Settings</h1>
</div>

<div class="admin-table-wrap">
  @if($seoSettings->isEmpty())
    <div class="admin-empty"><p>No SEO settings found.</p></div>
  @else
    <table>
      <thead><tr><th>Page</th><th>Title</th><th>Meta Description</th><th></th></tr></thead>
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
  @endif
</div>
@endsection
