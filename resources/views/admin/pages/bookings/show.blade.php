@extends('admin.layouts.admin')
@section('title', 'Booking #' . $booking->id)
@section('page_title', 'Booking Detail')

@section('content')

{{-- Page Header --}}
<div class="admin-page-header" style="margin-bottom:24px;">
  <div>
    <h1 style="font-size:20px;font-weight:700;color:#1a2332;margin:0 0 4px;">{{ $booking->full_name }}</h1>
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
      <span style="font-size:12px;color:#9ca3af;">Booking #{{ $booking->id }} &middot; Received {{ $booking->created_at->format('d M Y H:i') }}</span>
      @php
        $colors = ['pending'=>'#f59e0b','confirmed'=>'#10b981','cancelled'=>'#ef4444','completed'=>'#6366f1','no_show'=>'#9ca3af'];
        $color = $colors[$booking->status] ?? '#9ca3af';
      @endphp
      <span style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:600;background:{{ $color }}18;color:{{ $color }};">
        {{ ucfirst(str_replace('_',' ',$booking->status)) }}
      </span>
    </div>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;">
    <a href="{{ route('admin.bookings.index') }}" class="btn-admin btn-admin--outline">&larr; All Bookings</a>

    {{-- Quick approve --}}
    @if(!in_array($booking->status, ['confirmed','completed','cancelled']))
    <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}" style="display:inline;" id="approve-form">
      @csrf
      <button type="button" class="btn-admin btn-admin--primary" style="background:#10b981;border-color:#10b981;" onclick="showConfirmModal('Approve Booking?', 'This will confirm the booking and notify the client.', function() { document.getElementById('approve-form').submit(); }, 'warning')">
        ✓ Approve
      </button>
    </form>
    @endif

    {{-- Delete --}}
    <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" style="display:inline;" id="delete-form">
      @csrf @method('DELETE')
      <button type="button" class="btn-admin btn-admin--outline" style="color:#ef4444;border-color:#ef4444;" onclick="showConfirmModal('Delete Booking?', 'Permanently delete this booking? This cannot be undone.', function() { document.getElementById('delete-form').submit(); })">
        Delete
      </button>
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

  {{-- ── Left Column ── --}}
  <div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Client Details --}}
    <div class="admin-detail">
      <h3 style="font-size:13px;font-weight:700;color:#1a2332;margin:0 0 16px;text-transform:uppercase;letter-spacing:.08em;">Client Details</h3>
      <div class="admin-detail__row">
        <span class="admin-detail__label">Name</span>
        <span class="admin-detail__value">{{ $booking->full_name }}</span>
      </div>
      <div class="admin-detail__row">
        <span class="admin-detail__label">Email</span>
        <span class="admin-detail__value">
          <a href="mailto:{{ $booking->email }}" style="color:#5a7a76;">{{ $booking->email }}</a>
        </span>
      </div>
      @if($booking->phone)
      <div class="admin-detail__row">
        <span class="admin-detail__label">Phone</span>
        <span class="admin-detail__value"><a href="tel:{{ $booking->phone }}" style="color:#5a7a76;">{{ $booking->phone }}</a></span>
      </div>
      @endif
      <div class="admin-detail__row">
        <span class="admin-detail__label">Session type</span>
        <span class="admin-detail__value">{{ $booking->session_type ? ucfirst($booking->session_type) : '—' }}</span>
      </div>
      <div class="admin-detail__row">
        <span class="admin-detail__label">Format</span>
        <span class="admin-detail__value">{{ match($booking->session_format) { 'intake' => 'Introduction Call', 'standard' => 'Standard Session', 'emdr' => 'EMDR Session', 'initial' => 'Initial Session', default => $booking->session_format ? ucfirst(str_replace('_',' ',$booking->session_format)) : '—' } }}</span>
      </div>
      <div class="admin-detail__row">
        <span class="admin-detail__label">Language</span>
        <span class="admin-detail__value">{{ $booking->preferred_language ? strtoupper($booking->preferred_language) : '—' }}</span>
      </div>
      @if($booking->preferred_date)
      <div class="admin-detail__row">
        <span class="admin-detail__label">Preferred date</span>
        <span class="admin-detail__value">{{ $booking->preferred_date->format('d M Y H:i') }}</span>
      </div>
      @endif
      @if($booking->reason)
      <div class="admin-detail__row" style="align-items:flex-start;">
        <span class="admin-detail__label">Reason</span>
        <span class="admin-detail__value" style="white-space:pre-line;">{{ $booking->reason }}</span>
      </div>
      @endif
    </div>

    {{-- Status Management --}}
    <div class="admin-detail">
      <h3 style="font-size:13px;font-weight:700;color:#1a2332;margin:0 0 16px;text-transform:uppercase;letter-spacing:.08em;">Update Status</h3>
      <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        @csrf @method('PATCH')
        <select name="status" class="admin-select" style="flex:1;min-width:160px;">
          @foreach(['pending','confirmed','cancelled','completed','no_show'] as $s)
            <option value="{{ $s }}" {{ $booking->status === $s ? 'selected' : '' }}>
              {{ ucfirst(str_replace('_',' ',$s)) }}
            </option>
          @endforeach
        </select>
        <button type="submit" class="btn-admin btn-admin--primary">Update</button>
      </form>

      {{-- Reject with reason --}}
      @if($booking->status !== 'cancelled')
      <details style="margin-top:14px;">
        <summary style="font-size:12px;color:#ef4444;cursor:pointer;font-weight:600;">Reject booking…</summary>
        <form method="POST" action="{{ route('admin.bookings.reject', $booking) }}" style="margin-top:12px;display:flex;flex-direction:column;gap:8px;" id="reject-form">
          @csrf
          <textarea name="rejection_reason" class="admin-input" rows="3" placeholder="Optional reason for rejection…" style="resize:vertical;width:100%;">{{ $booking->rejection_reason }}</textarea>
          <button type="button" class="btn-admin btn-admin--outline" style="color:#ef4444;border-color:#ef4444;" onclick="showConfirmModal('Reject Booking?', 'This will reject the booking and notify the client.', function() { document.getElementById('reject-form').submit(); }, 'warning')">Confirm Rejection</button>
        </form>
      </details>
      @elseif($booking->rejection_reason)
      <div style="margin-top:12px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px;font-size:12px;color:#991b1b;">
        <strong>Rejection reason:</strong> {{ $booking->rejection_reason }}
      </div>
      @endif
    </div>

    {{-- Admin Notes --}}
    <div class="admin-detail">
      <h3 style="font-size:13px;font-weight:700;color:#1a2332;margin:0 0 16px;text-transform:uppercase;letter-spacing:.08em;">Admin Notes</h3>
      <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" style="display:flex;flex-direction:column;gap:8px;">
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="{{ $booking->status }}">
        <textarea name="admin_notes" class="admin-input" rows="5" placeholder="Private notes about this client or session…" style="resize:vertical;width:100%;">{{ $booking->admin_notes }}</textarea>
        <button type="submit" class="btn-admin btn-admin--primary">Save Notes</button>
      </form>
    </div>

  </div>

  {{-- ── Right Column ── --}}
  <div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Schedule Session --}}
    <div class="admin-detail">
      <h3 style="font-size:13px;font-weight:700;color:#1a2332;margin:0 0 4px;text-transform:uppercase;letter-spacing:.08em;">Schedule Session</h3>
      @if($booking->scheduled_at)
      <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px;margin-bottom:16px;font-size:13px;">
        <div style="font-weight:600;color:#166534;">
          ✓ Scheduled: {{ $booking->scheduled_at->format('l, d M Y') }} at {{ $booking->scheduled_at->format('H:i') }}
        </div>
        @if($booking->meeting_platform)
        <div style="color:#15803d;margin-top:2px;">Platform: {{ ucfirst(str_replace('_',' ',$booking->meeting_platform)) }}</div>
        @endif
      </div>
      @endif
      <form method="POST" action="{{ route('admin.bookings.schedule', $booking) }}">
        @csrf @method('PATCH')
        <div style="margin-bottom:10px;">
          <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px;">Date &amp; Time *</label>
          <input type="text" name="scheduled_at" id="scheduled_at" class="admin-input"
            value="{{ $booking->scheduled_at ? $booking->scheduled_at->format('Y-m-d H:i') : '' }}"
            required autocomplete="off" style="width:100%;">
        </div>
        <div style="margin-bottom:10px;background:#f8fffe;border:1px solid #d1fae5;border-radius:8px;padding:10px 12px;font-size:12px;color:#4b5563;">
          @if($defaultMeetingLink)
            <div style="font-weight:600;color:#166534;margin-bottom:2px;">Online meeting room ({{ ucfirst(str_replace('_',' ', $defaultMeetingPlatform ?: 'other')) }})</div>
            <a href="{{ $defaultMeetingLink }}" target="_blank" rel="noopener" style="color:#5a7a76;font-weight:600;word-break:break-all;">{{ $defaultMeetingLink }}</a>
            <div style="color:#9ca3af;margin-top:4px;">Applied automatically to every online session. Manage it in <a href="{{ route('admin.settings.index') }}" style="color:#5a7a76;text-decoration:underline;">Site Settings &rarr; Booking &amp; Sessions</a>.</div>
          @else
            No default meeting link set yet. Add one in <a href="{{ route('admin.settings.index') }}" style="color:#5a7a76;text-decoration:underline;">Site Settings &rarr; Booking &amp; Sessions</a> and it will be applied to every online session automatically.
          @endif
        </div>
        <button type="submit" class="btn-admin btn-admin--primary" style="width:100%;">
          {{ $booking->scheduled_at ? 'Update Schedule' : 'Confirm & Schedule' }}
        </button>
      </form>
    </div>

    {{-- Meeting Link (read-only; managed centrally in Site Settings) --}}
    @if($booking->scheduled_at)
    <div class="admin-detail">
      <h3 style="font-size:13px;font-weight:700;color:#1a2332;margin:0 0 12px;text-transform:uppercase;letter-spacing:.08em;">Meeting Link</h3>
      @if($booking->meeting_link)
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;background:#f8fffe;border:1px solid #d1fae5;border-radius:8px;padding:10px 12px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
        <a href="{{ $booking->meeting_link }}" target="_blank" rel="noopener"
           style="font-size:12px;color:#5a7a76;font-weight:600;word-break:break-all;">
          {{ $booking->meeting_link }}
        </a>
      </div>
      @else
      <p style="font-size:12px;color:#9ca3af;margin:0 0 10px;">No meeting link on this booking yet. It is applied automatically from the default when the booking is confirmed.</p>
      @endif
      <p style="font-size:11px;color:#9ca3af;margin:0;">The online meeting room is managed centrally in <a href="{{ route('admin.settings.index') }}" style="color:#5a7a76;text-decoration:underline;">Site Settings &rarr; Booking &amp; Sessions</a> and used for every online session.</p>
    </div>
    @endif

    {{-- Pre-Call Questionnaire Responses --}}
    @if($booking->preIntakeResponse)
    @php $pi = $booking->preIntakeResponse; @endphp
    <div class="admin-detail">
      <h3 style="font-size:13px;font-weight:700;color:#1a2332;margin:0 0 16px;text-transform:uppercase;letter-spacing:.08em;">Pre-Call Questionnaire</h3>

      @if($pi->brings_to_therapy)
      <div class="admin-detail__row" style="align-items:flex-start;">
        <span class="admin-detail__label">What brings them</span>
        <span class="admin-detail__value" style="white-space:pre-line;">{{ $pi->brings_to_therapy }}</span>
      </div>
      @endif

      @if($pi->support_areas)
      <div class="admin-detail__row">
        <span class="admin-detail__label">Support areas</span>
        <span class="admin-detail__value">
          @php $areas = is_array($pi->support_areas) ? $pi->support_areas : json_decode($pi->support_areas, true) ?? [$pi->support_areas]; @endphp
          {{ implode(', ', $areas) }}
        </span>
      </div>
      @endif

      @if($pi->previous_therapy)
      <div class="admin-detail__row">
        <span class="admin-detail__label">Previous therapy</span>
        <span class="admin-detail__value">{{ $pi->previous_therapy }}</span>
      </div>
      @endif

      @if($pi->communication_style)
      <div class="admin-detail__row">
        <span class="admin-detail__label">Comm. style</span>
        <span class="admin-detail__value">{{ ucfirst(str_replace('-',' ',$pi->communication_style)) }}</span>
      </div>
      @endif

      @if($pi->duration_expectation)
      <div class="admin-detail__row">
        <span class="admin-detail__label">Duration expect.</span>
        <span class="admin-detail__value">{{ ucfirst(str_replace('-',' ',$pi->duration_expectation)) }}</span>
      </div>
      @endif

      @if($pi->additional_notes)
      <div class="admin-detail__row" style="align-items:flex-start;">
        <span class="admin-detail__label">Additional notes</span>
        <span class="admin-detail__value" style="white-space:pre-line;">{{ $pi->additional_notes }}</span>
      </div>
      @endif

      @if($pi->crisis_risk)
      <div class="admin-detail__row" style="background:#fef2f2;border-radius:6px;padding:10px 12px;">
        <span class="admin-detail__label" style="color:#dc2626;">⚠ Crisis risk</span>
        <span class="admin-detail__value" style="color:#dc2626;font-weight:700;">Flagged</span>
      </div>
      @if($pi->crisis_details)
      <div style="background:#fef2f2;border-radius:6px;padding:10px 12px;margin-top:4px;font-size:12px;color:#991b1b;">
        {{ $pi->crisis_details }}
      </div>
      @endif
      @endif
    </div>
    @else
    <div class="admin-detail">
      <h3 style="font-size:13px;font-weight:700;color:#1a2332;margin:0 0 12px;text-transform:uppercase;letter-spacing:.08em;">Pre-Call Questionnaire</h3>
      <div class="admin-empty" style="padding:24px 0;">
        <p style="margin:0;font-size:13px;">No pre-call questionnaire responses submitted yet.</p>
      </div>
    </div>
    @endif

  </div>{{-- end right column --}}
</div>
@endsection

@section('page_styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (window.flatpickr) {
      flatpickr('#scheduled_at', {
        enableTime: true,
        time_24hr: true,
        dateFormat: 'Y-m-d H:i',   // value submitted to the server
        altInput: true,
        altFormat: 'd/m/Y H:i',    // European format shown to the user
        minuteIncrement: 15,
        allowInput: true,
      });
    }
  });
</script>
@endsection
