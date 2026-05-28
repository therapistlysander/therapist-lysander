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
  @if($faqs->isEmpty())
    <div class="admin-empty">
      <p>No FAQs yet. <a href="{{ route('admin.faqs.create') }}" style="color:#5a9e97;">Add the first one.</a></p>
    </div>
  @else
    <table>
      <thead><tr><th>Question</th><th>Category</th><th>Order</th><th>Active</th><th></th></tr></thead>
      <tbody>
        @foreach($faqs as $faq)
        <tr>
          <td style="max-width:400px;">{{ Str::limit($faq->question, 80) }}</td>
          <td><span style="font-size:12px;background:#f3f4f6;padding:2px 8px;border-radius:999px;">{{ $faq->category }}</span></td>
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
  @endif
</div>
@endsection
