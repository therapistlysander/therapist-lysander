@extends('admin.layouts.admin')

@section('title', 'Google Calendar Settings')
@section('page_title', 'Google Calendar Settings')

@section('page_styles')
<style>
  .gcal-layout { display: flex; flex-direction: column; gap: 24px; }

  .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
  .card__header { padding: 20px 24px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
  .card__header h2 { font-size: 15px; font-weight: 600; margin: 0; color: #1a2332; display: flex; align-items: center; gap: 10px; }
  .card__header p { font-size: 12px; color: #9ca3af; margin: 3px 0 0; }
  .card__body { padding: 24px; }
  .card__icon { width: 20px; height: 20px; color: #5a9e97; }

  /* Stats Cards */
  .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; }
  .stat-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; text-align: center; transition: all 0.2s; }
  .stat-card:hover { border-color: #5a9e97; box-shadow: 0 4px 12px rgba(90,158,151,0.08); }
  .stat-card__icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
  .stat-card__icon--sync { background: #e0f2fe; color: #0284c7; }
  .stat-card__icon--upcoming { background: #d1fae5; color: #059669; }
  .stat-card__icon--week { background: #fef3c7; color: #d97706; }
  .stat-card__icon--status { background: #ede9fe; color: #7c3aed; }
  .stat-card__num { font-size: 28px; font-weight: 700; color: #1a2332; line-height: 1; }
  .stat-card__label { font-size: 12px; color: #6b7280; margin-top: 6px; text-transform: uppercase; letter-spacing: 0.04em; }

  /* Status Badge */
  .status-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 999px; font-size: 13px; font-weight: 500; }
  .status-badge--connected { background: #d1fae5; color: #065f46; }
  .status-badge--disconnected { background: #f3f4f6; color: #6b7280; }
  .status-badge--error { background: #fee2e2; color: #991b1b; }
  .status-dot { width: 8px; height: 8px; border-radius: 50%; animation: pulse 2s infinite; }
  .status-dot--green { background: #10b981; }
  .status-dot--gray { background: #9ca3af; animation: none; }
  .status-dot--red { background: #ef4444; }
  @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

  /* Info Grid */
  .info-grid { display: grid; grid-template-columns: 140px 1fr; gap: 12px 16px; align-items: center; }
  .info-label { font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em; }
  .info-value { font-size: 14px; color: #1a2332; }
  .info-value--mono { font-family: 'SF Mono', Monaco, monospace; font-size: 12px; color: #6b7280; }

  /* Buttons */
  .btn-admin { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 500; border: none; cursor: pointer; transition: all 0.15s; text-decoration: none; }
  .btn-admin--primary { background: #5a9e97; color: white; }
  .btn-admin--primary:hover { background: #4a8e87; }
  .btn-admin--danger { background: #fee2e2; color: #991b1b; }
  .btn-admin--danger:hover { background: #fecaca; }
  .btn-admin--secondary { background: #f3f4f6; color: #374151; }
  .btn-admin--secondary:hover { background: #e5e7eb; }
  .btn-admin--google { background: #fff; color: #3c4043; border: 1px solid #dadce0; }
  .btn-admin--google:hover { background: #f8f9fa; border-color: #c6c8ca; }
  .btn-admin--google svg { color: #4285f4; }

  .btn-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }

  /* Form Fields */
  .field-group { margin-bottom: 16px; }
  .field-group label { display: block; font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
  .field-group select { width: 100%; padding: 9px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #1a2332; background: white; }
  .field-group select:focus { border-color: #5a9e97; outline: none; box-shadow: 0 0 0 3px rgba(90,158,151,0.1); }

  /* Form Dropdown (modern styled select for forms) */
  .form-dropdown { position: relative; }
  .form-dropdown__trigger {
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    width: 100%; padding: 9px 12px;
    background: white; border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 14px; color: #1a2332; cursor: pointer;
    transition: border-color 0.15s, box-shadow 0.15s;
  }
  .form-dropdown__trigger:hover { border-color: #5a9e97; }
  .form-dropdown__trigger:focus { outline: none; border-color: #5a9e97; box-shadow: 0 0 0 3px rgba(90,158,151,0.1); }
  .form-dropdown__trigger svg { width: 14px; height: 14px; color: #9ca3af; flex-shrink: 0; }
  .form-dropdown__menu {
    display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: white; border: 1px solid #e5e7eb;
    border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    z-index: 100; overflow: hidden; padding: 4px; max-height: 240px; overflow-y: auto;
  }
  .form-dropdown__menu.open { display: block; }
  .form-dropdown__item {
    display: block; width: 100%;
    padding: 8px 12px; font-size: 14px; color: #1a2332;
    border: none; background: none; cursor: pointer; border-radius: 6px;
    transition: background 0.1s; text-align: left;
  }
  .form-dropdown__item:hover { background: #f3f4f6; }
  .form-dropdown__item.active { background: #f0fdf9; color: #5a9e97; font-weight: 600; }

  /* Alerts */
  .alert { padding: 14px 18px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
  .alert--success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
  .alert--error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
  .alert--warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
  .alert--info { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }

  /* How It Works */
  .how-it-works { font-size: 13px; color: #6b7280; line-height: 1.7; }
  .how-it-works ul { margin: 8px 0 0; padding-left: 20px; }
  .how-it-works li { margin-bottom: 6px; }
  .how-it-works li strong { color: #374151; }

  /* Error Box */
  .error-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 14px 18px; margin-top: 12px; }
  .error-box__title { font-size: 12px; font-weight: 600; color: #991b1b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px; }
  .error-box__text { font-size: 13px; color: #7f1d1d; word-break: break-word; }

  /* Synced Events List */
  .synced-list { display: flex; flex-direction: column; }
  .synced-item { display: flex; align-items: center; gap: 16px; padding: 14px 0; border-bottom: 1px solid #f3f4f6; }
  .synced-item:last-child { border-bottom: none; }
  .synced-item__icon { width: 36px; height: 36px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .synced-item__content { flex: 1; min-width: 0; }
  .synced-item__title { font-size: 14px; font-weight: 500; color: #1a2332; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .synced-item__meta { font-size: 12px; color: #9ca3af; margin-top: 2px; }
  .synced-item__time { font-size: 13px; color: #6b7280; font-weight: 500; white-space: nowrap; }
  .synced-item__status { padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 500; }
  .synced-item__status--upcoming { background: #d1fae5; color: #065f46; }
  .synced-item__status--past { background: #f3f4f6; color: #6b7280; }

  .empty-state { text-align: center; padding: 40px 20px; color: #9ca3af; }
  .empty-state__icon { width: 48px; height: 48px; margin: 0 auto 12px; color: #d1d5db; }
  .empty-state__text { font-size: 14px; }
</style>
@endsection

@section('content')
<div class="gcal-layout">

  {{-- Stats Overview (only if connected) --}}
  @if($token && $token->is_active)
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-card__icon stat-card__icon--sync">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-4.14-3.36-7.5-7.5-7.5S4.5 7.86 4.5 12M12 4.5v15m7.5-7.5h-15"/></svg>
      </div>
      <div class="stat-card__num">{{ $stats['total_synced'] }}</div>
      <div class="stat-card__label">Events Synced</div>
    </div>
    <div class="stat-card">
      <div class="stat-card__icon stat-card__icon--upcoming">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/></svg>
      </div>
      <div class="stat-card__num">{{ $stats['upcoming'] }}</div>
      <div class="stat-card__label">Upcoming</div>
    </div>
    <div class="stat-card">
      <div class="stat-card__icon stat-card__icon--week">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      </div>
      <div class="stat-card__num">{{ $stats['this_week'] }}</div>
      <div class="stat-card__label">This Week</div>
    </div>
    <div class="stat-card">
      <div class="stat-card__icon stat-card__icon--status">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-card__num" style="font-size: 16px; padding-top: 4px;">
        <span class="status-badge status-badge--connected" style="padding: 4px 12px; font-size: 12px;">
          <span class="status-dot status-dot--green"></span>
          Active
        </span>
      </div>
      <div class="stat-card__label">Connection</div>
    </div>
  </div>
  @endif

  {{-- Connection Status --}}
  <div class="card">
    <div class="card__header">
      <div>
        <h2>
          <svg class="card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
          Connection Status
        </h2>
        <p>Connect your Google account to sync bookings with Google Calendar.</p>
      </div>
      @if($token && $token->is_active)
      <div class="btn-row" style="margin-top: 0;">
        <form method="POST" action="{{ route('admin.google-calendar.test-sync') }}">
          @csrf
          <button type="submit" class="btn-admin btn-admin--secondary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Test Connection
          </button>
        </form>
        <a href="https://calendar.google.com/calendar/u/0/r" target="_blank" class="btn-admin btn-admin--google">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          Open Google Calendar
        </a>
        <form method="POST" action="{{ route('admin.google-calendar.disconnect') }}" id="disconnect-form" style="display:none;">
          @csrf
        </form>
        <button type="button" class="btn-admin btn-admin--danger" onclick="showConfirmModal('Disconnect Google Calendar?', 'Existing calendar events will not be removed automatically. You can reconnect at any time.', function() { document.getElementById('disconnect-form').submit(); })">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
          Disconnect
        </button>
      </div>
      @endif
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
          <span class="info-value info-value--mono">{{ $token->calendar_id }}</span>

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
        <div class="btn-row" style="margin-top: 20px;">
          <a href="{{ route('admin.google-calendar.connect') }}" class="btn-admin btn-admin--google">
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
      <h2>
        <svg class="card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Calendar Selection
      </h2>
      <p>Choose which Google Calendar to sync bookings with.</p>
    </div>
    <div class="card__body">
      <form method="POST" action="{{ route('admin.google-calendar.settings') }}">
        @csrf
        @method('PATCH')
        <div class="field-group">
          <label>Calendar</label>
          <div class="form-dropdown" id="calendar-dropdown">
            <button type="button" class="form-dropdown__trigger" onclick="toggleFormDropdown('calendar-dropdown')">
              <span id="calendar-label">{{ $calendars->firstWhere('id', $token->calendar_id)['summary'] ?? 'Select Calendar' }}</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="form-dropdown__menu">
              @foreach($calendars as $cal)
              <button type="button" class="form-dropdown__item {{ $token->calendar_id === $cal['id'] ? 'active' : '' }}" onclick="selectFormDropdown('calendar-dropdown', '{{ $cal['id'] }}', '{{ addslashes($cal['summary']) }}')">
                {{ $cal['summary'] }}
              </button>
              @endforeach
            </div>
            <input type="hidden" name="calendar_id" value="{{ $token->calendar_id }}">
          </div>
        </div>
        <button type="submit" class="btn-admin btn-admin--primary">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Save Calendar
        </button>
      </form>
    </div>
  </div>
  @endif

  {{-- Synced Events (only if connected and has events) --}}
  @if($token && $token->is_active && $syncedBookings->count() > 0)
  <div class="card">
    <div class="card__header">
      <h2>
        <svg class="card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        Recently Synced Events
      </h2>
      <span style="font-size: 12px; color: #9ca3af;">Last {{ $syncedBookings->count() }} events</span>
    </div>
    <div class="card__body" style="padding: 12px 24px;">
      <div class="synced-list">
        @foreach($syncedBookings as $booking)
        <div class="synced-item">
          <div class="synced-item__icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
          <div class="synced-item__content">
            <p class="synced-item__title">{{ $booking->full_name }}</p>
            <div class="synced-item__meta">
              {{ ucfirst($booking->session_format) }} • {{ ucfirst($booking->session_type) }}
            </div>
          </div>
          <div class="synced-item__time">
            {{ $booking->scheduled_at ? \Carbon\Carbon::parse($booking->scheduled_at)->format('M j, H:i') : '—' }}
          </div>
          <span class="synced-item__status {{ $booking->scheduled_at && $booking->scheduled_at->isFuture() ? 'synced-item__status--upcoming' : 'synced-item__status--past' }}">
            {{ $booking->scheduled_at && $booking->scheduled_at->isFuture() ? 'Upcoming' : 'Past' }}
          </span>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @elseif($token && $token->is_active)
  <div class="card">
    <div class="card__body">
      <div class="empty-state">
        <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <p class="empty-state__text">No events synced yet. Bookings will appear here once confirmed.</p>
      </div>
    </div>
  </div>
  @endif

  {{-- How It Works --}}
  <div class="card">
    <div class="card__header">
      <h2>
        <svg class="card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        How It Works
      </h2>
    </div>
    <div class="card__body">
      <div class="how-it-works">
        <p>When connected, Google Calendar integration works automatically:</p>
        <ul>
          <li><strong>Booking confirmed</strong> — A Google Calendar event is created with the client details, session info, and meeting link.</li>
          <li><strong>Booking rescheduled</strong> — The existing calendar event is updated with the new time.</li>
          <li><strong>Booking cancelled</strong> — The calendar event is automatically removed.</li>
          <li><strong>Availability checking</strong> — Google Calendar busy times are checked alongside website bookings to prevent double-bookings.</li>
          <li><strong>Timezone-aware</strong> — Events are created in the configured timezone (Europe/Amsterdam) with correct start and end times.</li>
          <li><strong>Calendar invitations</strong> — Clients receive email invitations with the appointment details in their local timezone.</li>
        </ul>
        <p style="margin-top: 12px; color: #9ca3af; font-size: 12px;">
          All calendar operations run in the background. If Google Calendar is temporarily unavailable, bookings will still work normally.
        </p>
      </div>
    </div>
  </div>

</div>

@endsection

@section('page_scripts')
<script>
// Form Dropdown Functions (for form fields - no auto-submit)
function toggleFormDropdown(id) {
  const dropdown = document.getElementById(id);
  const menu = dropdown.querySelector('.form-dropdown__menu');
  const isOpen = menu.classList.contains('open');

  // Close all dropdowns
  document.querySelectorAll('.form-dropdown__menu').forEach(m => m.classList.remove('open'));

  // Toggle current
  if (!isOpen) {
    menu.classList.add('open');
  }
}

function selectFormDropdown(id, value, label) {
  const dropdown = document.getElementById(id);
  const input = dropdown.querySelector('input[type="hidden"]');
  const labelEl = dropdown.querySelector('[id$="-label"]');

  input.value = value;
  labelEl.textContent = label;

  // Update active state
  dropdown.querySelectorAll('.form-dropdown__item').forEach(item => {
    item.classList.remove('active');
  });
  event.target.closest('.form-dropdown__item').classList.add('active');

  // Close dropdown
  dropdown.querySelector('.form-dropdown__menu').classList.remove('open');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
  if (!e.target.closest('.form-dropdown')) {
    document.querySelectorAll('.form-dropdown__menu').forEach(m => m.classList.remove('open'));
  }
});
</script>
@endsection
