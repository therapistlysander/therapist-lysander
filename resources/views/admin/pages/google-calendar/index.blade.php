@extends('admin.layouts.admin')

@section('title', 'Google Calendar')
@section('page_title', 'Google Calendar')

@section('page_styles')
<style>
  .gcal-layout { display: flex; flex-direction: column; gap: 24px; max-width: 720px; }

  .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; }
  .card__header { padding: 20px 24px; border-bottom: 1px solid #f3f4f6; }
  .card__header h2 { font-size: 15px; font-weight: 600; margin: 0; color: #1a2332; }
  .card__header p { font-size: 12px; color: #9ca3af; margin: 3px 0 0; }
  .card__body { padding: 24px; }

  .status-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 999px; font-size: 13px; font-weight: 500; }
  .status-badge--connected { background: #d1fae5; color: #065f46; }
  .status-badge--disconnected { background: #f3f4f6; color: #6b7280; }
  .status-badge--error { background: #fee2e2; color: #991b1b; }
  .status-dot { width: 8px; height: 8px; border-radius: 50%; }
  .status-dot--green { background: #10b981; }
  .status-dot--gray { background: #9ca3af; }
  .status-dot--red { background: #ef4444; }

  .info-grid { display: grid; grid-template-columns: 140px 1fr; gap: 12px 16px; align-items: center; }
  .info-label { font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em; }
  .info-value { font-size: 14px; color: #1a2332; }

  .btn-admin { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 500; border: none; cursor: pointer; transition: all 0.15s; text-decoration: none; }
  .btn-admin--primary { background: #5a9e97; color: white; }
  .btn-admin--primary:hover { background: #4a8e87; }
  .btn-admin--danger { background: #fee2e2; color: #991b1b; }
  .btn-admin--danger:hover { background: #fecaca; }
  .btn-admin--secondary { background: #f3f4f6; color: #374151; }
  .btn-admin--secondary:hover { background: #e5e7eb; }

  .btn-row { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 20px; }

  .field-group { margin-bottom: 16px; }
  .field-group label { display: block; font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
  .field-group select { width: 100%; padding: 9px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #1a2332; background: white; }
  .field-group select:focus { border-color: #5a9e97; outline: none; box-shadow: 0 0 0 3px rgba(90,158,151,0.1); }

  .alert { padding: 14px 18px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
  .alert--success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
  .alert--error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
  .alert--warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
  .alert--info { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }

  .how-it-works { font-size: 13px; color: #6b7280; line-height: 1.7; }
  .how-it-works ul { margin: 8px 0 0; padding-left: 20px; }
  .how-it-works li { margin-bottom: 6px; }

  .error-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 14px 18px; margin-top: 12px; }
  .error-box__title { font-size: 12px; font-weight: 600; color: #991b1b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px; }
  .error-box__text { font-size: 13px; color: #7f1d1d; word-break: break-word; }
</style>
@endsection

@section('content')
<div class="gcal-layout">

  {{-- Connection Status --}}
  <div class="card">
    <div class="card__header">
      <h2>Connection Status</h2>
      <p>Connect your Google account to sync bookings with Google Calendar.</p>
    </div>
    <div class="card__body">
      @if($token && $token->is_active)
        <div class="status-badge status-badge--connected">
          <span class="status-dot status-dot--green"></span>
          Connected
        </div>

        <div class="info-grid" style="margin-top: 20px;">
          <span class="info-label">Google Account</span>
          <span class="info-value">{{ $token->google_email ?? 'Unknown' }}</span>

          <span class="info-label">Calendar ID</span>
          <span class="info-value" style="font-family: monospace; font-size: 12px;">{{ $token->calendar_id }}</span>

          <span class="info-label">Connected Since</span>
          <span class="info-value">{{ $token->connected_at?->format('j M Y, H:i') ?? 'N/A' }}</span>

          <span class="info-label">Last Synced</span>
          <span class="info-value">{{ $token->last_synced_at?->diffForHumans() ?? 'Never' }}</span>
        </div>

        @if($token->last_error)
        <div class="error-box">
          <div class="error-box__title">Last Error</div>
          <div class="error-box__text">{{ $token->last_error }}</div>
        </div>
        @endif

        @if($connectionError)
        <div class="alert alert--warning" style="margin-top: 16px;">
          <strong>Warning:</strong> Could not fetch calendar list. {{ $connectionError }}
        </div>
        @endif

        <div class="btn-row">
          <form method="POST" action="{{ route('admin.google-calendar.test-sync') }}">
            @csrf
            <button type="submit" class="btn-admin btn-admin--secondary">Test Connection</button>
          </form>
          <form method="POST" action="{{ route('admin.google-calendar.disconnect') }}" onsubmit="return confirm('Are you sure you want to disconnect Google Calendar? Existing calendar events will not be removed automatically.')">
            @csrf
            <button type="submit" class="btn-admin btn-admin--danger">Disconnect</button>
          </form>
        </div>
      @else
        <div class="status-badge status-badge--disconnected">
          <span class="status-dot status-dot--gray"></span>
          Not Connected
        </div>

        @if($token && !$token->is_active && $token->last_error)
        <div class="alert alert--warning" style="margin-top: 16px;">
          <strong>Connection expired:</strong> {{ $token->last_error }}<br>
          Please reconnect your Google account below.
        </div>
        @endif

        @if(!config('google-calendar.client_id'))
        <div class="alert alert--error" style="margin-top: 16px;">
          <strong>Configuration missing:</strong> Google OAuth credentials are not set. Please add <code>GOOGLE_CLIENT_ID</code> and <code>GOOGLE_CLIENT_SECRET</code> to your <code>.env</code> file.
        </div>
        @else
        <div class="btn-row">
          <a href="{{ route('admin.google-calendar.connect') }}" class="btn-admin btn-admin--primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
            Connect Google Calendar
          </a>
        </div>
        @endif
      @endif
    </div>
  </div>

  {{-- Calendar Selection (only if connected) --}}
  @if($token && $token->is_active && !empty($calendars))
  <div class="card">
    <div class="card__header">
      <h2>Calendar Selection</h2>
      <p>Choose which Google Calendar to sync bookings with.</p>
    </div>
    <div class="card__body">
      <form method="POST" action="{{ route('admin.google-calendar.settings') }}">
        @csrf
        @method('PATCH')
        <div class="field-group">
          <label for="calendar_id">Calendar</label>
          <select name="calendar_id" id="calendar_id">
            @foreach($calendars as $cal)
            <option value="{{ $cal['id'] }}" {{ $token->calendar_id === $cal['id'] ? 'selected' : '' }}>
              {{ $cal['summary'] }} ({{ $cal['id'] }})
            </option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn-admin btn-admin--primary">Save Calendar</button>
      </form>
    </div>
  </div>
  @endif

  {{-- How It Works --}}
  <div class="card">
    <div class="card__header">
      <h2>How It Works</h2>
    </div>
    <div class="card__body">
      <div class="how-it-works">
        <p>When connected, Google Calendar integration works automatically:</p>
        <ul>
          <li><strong>Booking confirmed</strong> &mdash; A Google Calendar event is created with the client details, session info, and meeting link.</li>
          <li><strong>Booking rescheduled</strong> &mdash; The existing calendar event is updated with the new time.</li>
          <li><strong>Booking cancelled</strong> &mdash; The calendar event is automatically removed.</li>
          <li><strong>Availability checking</strong> &mdash; Google Calendar busy times are checked alongside website bookings to prevent double-bookings.</li>
          <li><strong>Timezone-aware</strong> &mdash; Events are created in the configured timezone (Europe/Amsterdam) with correct start and end times.</li>
        </ul>
        <p style="margin-top: 12px; color: #9ca3af; font-size: 12px;">
          All calendar operations run in the background. If Google Calendar is temporarily unavailable, bookings will still work normally.
        </p>
      </div>
    </div>
  </div>

</div>
@endsection
