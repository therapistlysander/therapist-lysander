@extends('admin.layouts.admin')
@section('title', 'Testimonials')
@section('page_title', 'Testimonials')

@section('content')
<div class="admin-page-header">
  <h1>Testimonials</h1>
  <a href="{{ route('admin.testimonials.create') }}" class="btn-admin btn-admin--primary">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Add Testimonial
  </a>
</div>

<div class="admin-table-wrap">
  @if($testimonials->isEmpty())
    <div class="admin-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
      <p>No testimonials yet. <a href="{{ route('admin.testimonials.create') }}" style="color:#5a9e97;">Add the first one.</a></p>
    </div>
  @else
    <table>
      <thead><tr><th>Client</th><th>Type</th><th>Headline</th><th>Featured</th><th>Active</th><th>Order</th><th></th></tr></thead>
      <tbody>
        @foreach($testimonials as $t)
        <tr>
          <td><strong>{{ $t->client_name }}</strong>@if($t->tag)<br><span style="font-size:11px;color:#9ca3af;">{{ $t->tag }}</span>@endif</td>
          <td><span class="badge" style="background:{{ $t->type === 'endorsement' ? '#8b5cf6' : '#6b7280' }};color:#fff;font-size:11px;padding:2px 8px;border-radius:4px;">{{ ucfirst($t->type ?? 'client') }}</span></td>
          <td style="color:#6b7280;font-size:13px;">{{ Str::limit($t->headline ?? $t->body, 60) }}</td>
          <td>@if($t->is_featured)<span class="badge badge--featured">Featured</span>@else<span style="color:#d1d5db;font-size:13px;">—</span>@endif</td>
          <td>@if($t->is_active)<span class="badge badge--confirmed">Active</span>@else<span class="badge badge--cancelled">Hidden</span>@endif</td>
          <td style="color:#9ca3af;font-size:13px;">{{ $t->sort_order }}</td>
          <td style="display:flex;gap:6px;">
            <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn-admin btn-admin--outline">Edit</a>
            <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Delete this testimonial?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-admin btn-admin--danger">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
