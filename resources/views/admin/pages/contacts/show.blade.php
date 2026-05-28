@extends('admin.layouts.admin')
@section('title', 'Message from ' . $contact->name)
@section('page_title', 'Contact Message')

@section('content')
<div class="admin-page-header">
  <h1>Message from {{ $contact->name }}</h1>
  <div style="display:flex;gap:8px;">
    <a href="{{ route('admin.contacts.index') }}" class="btn-admin btn-admin--outline">&larr; Back</a>
    <form method="POST" action="{{ route('admin.contacts.status', $contact) }}" style="display:flex;gap:8px;">
      @csrf @method('PATCH')
      <select name="status" class="admin-select" style="width:140px;">
        @foreach(['new','read','replied','resolved'] as $s)
          <option value="{{ $s }}" {{ $contact->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn-admin btn-admin--primary">Update</button>
    </form>
    <a href="mailto:{{ $contact->email }}?subject=Re: Your message" class="btn-admin btn-admin--primary">Reply by email</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
  <div>
    <div class="admin-detail" style="margin-bottom:20px;">
      <h3 style="font-size:14px;font-weight:600;margin:0 0 16px;">Message</h3>
      <div style="font-size:14px;line-height:1.7;color:#374151;white-space:pre-line;">{{ $contact->message }}</div>
    </div>

    <!-- Notes -->
    <div class="admin-table-wrap">
      <div class="admin-table-header">
        <h2>Internal Notes</h2>
      </div>
      @if($contact->notes->isEmpty())
        <div class="admin-empty"><p>No notes yet.</p></div>
      @else
        <div style="padding:16px 20px;">
          @foreach($contact->notes as $note)
          <div style="padding:12px;background:#f9fafb;border-radius:8px;margin-bottom:10px;">
            <p style="font-size:13px;color:#374151;margin:0 0 6px;white-space:pre-line;">{{ $note->note }}</p>
            <p style="font-size:11px;color:#9ca3af;margin:0;">{{ $note->author->name ?? 'Admin' }} &middot; {{ $note->created_at->format('d M Y H:i') }}</p>
          </div>
          @endforeach
        </div>
      @endif
      <div style="padding:16px 20px;border-top:1px solid #e5e7eb;">
        <form method="POST" action="{{ route('admin.contacts.notes.store', $contact) }}">
          @csrf
          <textarea name="note" class="admin-input" rows="3" placeholder="Add an internal note…" required></textarea>
          <button type="submit" class="btn-admin btn-admin--primary" style="margin-top:8px;">Add Note</button>
        </form>
      </div>
    </div>
  </div>

  <div class="admin-detail">
    <h3 style="font-size:14px;font-weight:600;margin:0 0 16px;">Details</h3>
    <div class="admin-detail__row"><span class="admin-detail__label">Name</span><span class="admin-detail__value">{{ $contact->name }}</span></div>
    <div class="admin-detail__row"><span class="admin-detail__label">Email</span><span class="admin-detail__value"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></span></div>
    <div class="admin-detail__row"><span class="admin-detail__label">Status</span><span class="admin-detail__value"><span class="badge badge--{{ $contact->status }}">{{ ucfirst($contact->status) }}</span></span></div>
    <div class="admin-detail__row"><span class="admin-detail__label">Source</span><span class="admin-detail__value">{{ $contact->source ?? 'web_form' }}</span></div>
    <div class="admin-detail__row"><span class="admin-detail__label">Received</span><span class="admin-detail__value">{{ $contact->created_at->format('d M Y H:i') }}</span></div>
    @if($contact->ip_address)
    <div class="admin-detail__row"><span class="admin-detail__label">IP</span><span class="admin-detail__value" style="font-size:12px;">{{ $contact->ip_address }}</span></div>
    @endif
  </div>
</div>
@endsection
