@extends('layouts.app')

@section('title', __('ui.page_title.booking'))
@section('meta_description', 'Book a free 30-minute introductory call with Lysander Verschuur — psychologist and trauma specialist. No commitment required. Online sessions available worldwide.')
@section('canonical', 'https://www.therapistlysander.com/booking/')

@section('page_styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
  body { background: var(--color-bg); }
  .booking-page { min-height: 60vh; display: flex; flex-direction: column; }

  /* Step Indicators */
  .step-indicators { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: var(--space-8); }
  .step-indicator { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: var(--size-sm); font-weight: 500; border: 2px solid var(--color-border); color: var(--color-text-light); transition: all 0.3s ease; flex-shrink: 0; }
  .step-indicator--active { border-color: var(--color-teal); background: var(--color-teal); color: white; }
  .step-indicator--done { border-color: var(--color-teal); background: var(--color-teal); color: white; }
  .step-indicator__line { width: 40px; height: 2px; background: var(--color-border); transition: background 0.3s ease; }
  .step-indicator__line--done { background: var(--color-teal); }

  .booking-container { max-width: 720px; margin: 0 auto; padding: var(--space-8) var(--space-4); width: 100%; flex: 1; }

  .progress-bar-wrap { margin-bottom: var(--space-8); }
  .progress-bar-labels { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-3); }
  .progress-bar-label { font-size: var(--size-xs); font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: var(--color-text-muted); }
  .progress-bar-pct { font-size: var(--size-xs); font-weight: 500; color: var(--color-teal); }
  .progress-bar-track { height: 4px; background: var(--color-border); border-radius: 999px; overflow: hidden; }
  .progress-bar-fill { height: 100%; background: linear-gradient(90deg, var(--color-teal), var(--color-accent)); border-radius: 999px; transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1); }

  .step-content { animation: fadeInUp 0.4s ease; }
  @keyframes fadeInUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
  .step-heading { font-family: var(--font-heading); font-size: clamp(1.5rem, 5vw, var(--size-3xl)); margin-bottom: var(--space-3); line-height: 1.15; }
  .step-subheading { font-size: var(--size-base); color: var(--color-text-muted); line-height: 1.6; margin-bottom: var(--space-8); }
  .step-section { margin-bottom: var(--space-8); }
  .step-section__label { font-size: var(--size-base); font-weight: 500; color: var(--color-text); margin-bottom: var(--space-4); display: block; line-height: 1.4; }
  .step-section__label span { color: var(--color-text-light); font-weight: 400; margin-left: 3px; }
  .step-section__helper { font-size: var(--size-xs); color: var(--color-text-light); margin-top: var(--space-2); }

  .option-cards { display: flex; flex-direction: column; gap: var(--space-4); }
  .option-cards--2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-4); }
  .option-card { display: flex; align-items: flex-start; gap: var(--space-4); border: 1.5px solid var(--color-border); border-radius: 12px; padding: var(--space-6); cursor: pointer; transition: all 0.2s ease; background: var(--color-white); text-align: left; -webkit-tap-highlight-color: transparent; }
  .option-card:hover { border-color: rgba(90, 122, 118, 0.35); background: var(--color-bg); }
  .option-card:active { transform: scale(0.98); }
  .option-card.selected { border-color: var(--color-teal); background: rgba(232, 239, 238, 0.45); box-shadow: 0 2px 12px rgba(90, 122, 118, 0.08); }
  .option-card--disabled { opacity: 0.45; cursor: not-allowed; pointer-events: none; }
  .option-card--disabled .option-card__indicator { border-color: var(--color-border); }
  .option-card--disabled .option-card__label { color: var(--color-text-light); }
  .option-card--disabled .option-card__desc { color: var(--color-text-light); }
  .option-card__indicator { margin-top: 3px; width: 22px; height: 22px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; border: 2px solid var(--color-text-light); transition: all 0.2s ease; }
  .option-card--radio .option-card__indicator { border-radius: 50% !important; }
  .option-card--checkbox .option-card__indicator { border-radius: 6px !important; }
  .option-card.selected .option-card__indicator { border-color: var(--color-teal); background: var(--color-teal); }
  .option-card__indicator-dot { width: 9px; height: 9px; border-radius: 50%; background: white; opacity: 0; transition: opacity 0.2s ease; }
  .option-card.selected .option-card__indicator-dot { opacity: 1; }
  .option-card__indicator-check { opacity: 0; transition: opacity 0.2s ease; }
  .option-card.selected .option-card__indicator-check { opacity: 1; }
  .option-card__content { display: flex; flex-direction: column; gap: 4px; flex: 1; min-width: 0; }
  .option-card__label { font-size: var(--size-base); font-weight: 500; color: var(--color-text); line-height: 1.4; }
  .option-card__desc { font-size: var(--size-sm); color: var(--color-text-muted); line-height: 1.55; }

  .time-slots { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--space-2); }
  .time-slot { padding: var(--space-3) var(--space-2); text-align: center; font-size: var(--size-sm); border: 1.5px solid var(--color-border); border-radius: var(--radius-md); color: var(--color-text-muted); cursor: pointer; transition: all var(--transition); background: var(--color-white); -webkit-tap-highlight-color: transparent; }
  .time-slot:hover { border-color: var(--color-accent); color: var(--color-text); }
  .time-slot:active { transform: scale(0.95); }
  .time-slot.selected { background: var(--color-teal); border-color: var(--color-teal); color: white; }
  .time-slot.unavailable { opacity: 0.3; cursor: not-allowed; pointer-events: none; }

  .flatpickr-calendar.inline { width: 100% !important; box-shadow: none !important; border: 1px solid var(--color-border) !important; border-radius: var(--radius-md) !important; margin-bottom: var(--space-6) !important; }
  .flatpickr-calendar.inline .flatpickr-innerContainer { padding: var(--space-2); }

  .summary-card { background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-md); overflow: hidden; }
  .summary-row { display: flex; justify-content: space-between; align-items: center; padding: var(--space-3) var(--space-4); border-bottom: 1px solid var(--color-border); gap: var(--space-3); }
  .summary-row:last-child { border-bottom: none; }
  .summary-label { font-size: var(--size-sm); color: var(--color-text-muted); flex-shrink: 0; }
  .summary-value { font-size: var(--size-sm); font-weight: 500; color: var(--color-text); text-align: right; word-break: break-word; }

  .step-nav { display: flex; align-items: center; justify-content: space-between; margin-top: var(--space-8); padding-top: var(--space-6); border-top: 1px solid var(--color-border); gap: var(--space-3); }
  .step-nav--end { justify-content: flex-end; }
  .btn--ghost { background: transparent; color: var(--color-text-muted); border: none; padding: var(--space-3) var(--space-4); font-size: var(--size-sm); display: inline-flex; align-items: center; gap: var(--space-2); transition: color var(--transition); cursor: pointer; -webkit-tap-highlight-color: transparent; }
  .btn--ghost:hover { color: var(--color-text); }

  .success-state { text-align: center; padding: var(--space-10) 0; animation: fadeInUp 0.5s ease; }
  .success-state p { word-wrap: break-word; overflow-wrap: break-word; hyphens: auto; }
  .success-icon { width: 64px; height: 64px; background: var(--color-teal-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-6); }
  .success-icon svg { width: 30px; height: 30px; }

  /* ===== Mobile Responsive ===== */
  @media (max-width: 480px) {
    .step-indicator { width: 28px; height: 28px; font-size: var(--size-xs); }
    .step-indicator__line { width: 20px; }

    .booking-container { padding: var(--space-6) var(--space-4); }
    .step-heading { font-size: 1.5rem; }
    .step-subheading { font-size: var(--size-sm); margin-bottom: var(--space-6); }
    .step-section { margin-bottom: var(--space-6); }
    .step-section__label { font-size: var(--size-sm); }

    .option-cards--2 { grid-template-columns: minmax(0, 1fr); }
    .option-card { padding: var(--space-4); gap: var(--space-3); }
    .option-card__label { font-size: var(--size-sm); }
    .option-card__desc { font-size: var(--size-xs); }

    .time-slots { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .time-slot { padding: var(--space-3) var(--space-1); font-size: var(--size-xs); border-radius: var(--radius); }

    .summary-row { padding: var(--space-3); flex-direction: column; align-items: flex-start; gap: var(--space-1); }
    .summary-value { text-align: left; }

    .step-nav { margin-top: var(--space-6); padding-top: var(--space-4); }
    .step-nav .btn { font-size: var(--size-xs); padding: var(--space-3) var(--space-6); }
  }

  /* Tablet refinements */
  @media (min-width: 481px) and (max-width: 768px) {
    .booking-container { padding: var(--space-10) var(--space-6); }
    .time-slots { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  }

  /* Desktop */
  @media (min-width: 769px) {
    .booking-container { padding: var(--space-16) var(--space-8); }
    .step-subheading { font-size: var(--size-md); margin-bottom: var(--space-10); }
    .step-section { margin-bottom: var(--space-10); }
    .option-card { padding: var(--space-6) var(--space-8); }
  }

  /* Touch-friendly: increase tap targets */
  @media (hover: none) and (pointer: coarse) {
    .option-card { min-height: 56px; }
    .time-slot { min-height: 44px; display: flex; align-items: center; justify-content: center; }
    .btn--ghost { min-height: 44px; }
  }
</style>
@endsection

@section('content')
<div class="booking-page">
  <div class="booking-container">

    <!-- Step Indicators -->
    <div class="step-indicators" id="step-indicators">
      <div class="step-indicator step-indicator--active" id="ind-1">1</div>
      <div class="step-indicator__line" id="line-1"></div>
      <div class="step-indicator" id="ind-2">2</div>
      <div class="step-indicator__line" id="line-2"></div>
      <div class="step-indicator" id="ind-3">3</div>
    </div>

    <!-- Progress bar -->
    <div class="progress-bar-wrap">
      <div class="progress-bar-labels">
        <span class="progress-bar-label" id="progress-label">{{ __('ui.booking.step_of', ['step' => 1, 'total' => 3]) }}</span>
        <span class="progress-bar-pct" id="progress-pct">0%</span>
      </div>
      <div class="progress-bar-track">
        <div class="progress-bar-fill" id="progress-fill" style="width:0%;"></div>
      </div>
    </div>

    @php $bookingHero = $sections['booking_hero'] ?? null; @endphp
    <!-- STEP 1: Details -->
    <div id="step-1" class="step-content">
      <h1 class="step-heading">{{ $bookingHero?->content['heading'] ?? __('ui.booking.hero_heading') }}</h1>
      <p class="step-subheading">{{ $bookingHero?->content['subheading'] ?? __('ui.booking.hero_subheading') }}</p>

      <div class="step-section">
        <label class="step-section__label">{{ __('ui.booking.youre_booking') }}</label>
        <div style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-4) var(--space-6);background:var(--color-teal-light);border:1.5px solid var(--color-accent-light);border-radius:12px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="var(--color-teal)" stroke-width="2" width="22" height="22" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
          <div>
            <span style="font-size:var(--size-base);font-weight:500;color:var(--color-text);display:block;">{!! __('ui.booking.free_call_label') !!}</span>
            <span style="font-size:var(--size-sm);color:var(--color-text-muted);">{!! __('ui.booking.free_call_desc') !!}</span>
          </div>
        </div>
      </div>

      <div class="step-section">
        <label class="step-section__label">{{ __('ui.booking.your_details') }}</label>
        <div class="form-group">
          <label class="form-label" for="b-name">{{ __('ui.booking.full_name') }}</label>
          <input type="text" class="form-input" id="b-name" placeholder="{{ __('ui.booking.name_placeholder') }}" autocomplete="name">
        </div>
        <div class="form-group" style="margin-top:var(--space-4);">
          <label class="form-label" for="b-email">{{ __('ui.contact.email_address') }}</label>
          <input type="email" class="form-input" id="b-email" placeholder="{{ __('ui.booking.email_placeholder') }}" autocomplete="email">
        </div>
      </div>

      <div class="step-nav step-nav--end">
        <button class="btn btn--primary" onclick="goStep(2)">
          {{ __('ui.common.continue') }}
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </button>
      </div>
    </div>

    <!-- STEP 2: Simple Questionnaire -->
    <div id="step-2" class="step-content" style="display:none;">
      <h1 class="step-heading">{{ __('ui.booking.what_brings_you') }}</h1>
      <p class="step-subheading">{{ __('ui.booking.what_brings_desc') }}</p>

      <div class="step-section">
        <div class="form-group">
          <textarea class="form-textarea" id="pi-goals" placeholder="{{ __('ui.booking.goals_placeholder') }}" style="min-height:160px;"></textarea>
        </div>
        <p class="step-section__helper">{{ __('ui.booking.optional_helper') }}</p>
      </div>

      <div class="step-nav">
        <button class="btn--ghost" onclick="goStep(1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
          {{ __('ui.common.back') }}
        </button>
        <button class="btn btn--primary" onclick="goStep(3)">
          {{ __('ui.common.continue') }}
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </button>
      </div>
    </div>

    <!-- STEP 3: Schedule & Confirm -->
    <div id="step-3" class="step-content" style="display:none;">
      <h1 class="step-heading">{{ __('ui.booking.choose_time') }}</h1>
      <p class="step-subheading">{{ __('ui.booking.choose_time_desc') }}</p>

      <div class="step-section">
        <label class="step-section__label">{{ __('ui.booking.select_date') }}</label>
        <div id="inline-calendar"></div>
      </div>

      <div class="step-section" id="slots-wrap" style="display:none;">
        <label class="step-section__label">{{ __('ui.booking.available_times') }}</label>
        <p id="timezone-label" style="font-size:var(--size-sm);color:var(--color-text-light);margin-bottom:var(--space-3);"></p>
        <div class="time-slots" id="time-slots"></div>
      </div>

      <div id="summary-section" style="display:none;">
        <div class="step-section" style="margin-top:var(--space-8);">
          <label class="step-section__label">{{ __('ui.booking.review_booking') }}</label>
          <div class="summary-card" id="summary-card">
            <div class="summary-row"><span class="summary-label">{{ __('ui.booking.name_summary') }}</span><span class="summary-value" id="sum-name">&mdash;</span></div>
            <div class="summary-row"><span class="summary-label">{!! __('ui.booking.datetime_summary') !!}</span><span class="summary-value" id="sum-datetime">&mdash;</span></div>
            <div class="summary-row"><span class="summary-label">{{ __('ui.contact.email_address') }}</span><span class="summary-value" id="sum-email">&mdash;</span></div>
          </div>
        </div>

      </div>

      <div class="step-nav">
        <button class="btn--ghost" onclick="goStep(2)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
          {{ __('ui.common.back') }}
        </button>
        <button class="btn btn--primary" id="btn-submit" style="opacity:0.5;pointer-events:none;" onclick="submitBooking()">
          {{ __('ui.booking.send_request') }}
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
        </button>
      </div>
      <p style="font-size:var(--size-xs);color:var(--color-text-light);margin-top:var(--space-4);text-align:center;">{{ __('ui.booking.booking_disclaimer') }}</p>
    </div>

  </div>
</div>
@endsection

@section('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
window.__translations = {
  booking: {
    stepOf: '{{ __('ui.booking.step_of', ['step' => ':step', 'total' => ':total']) }}',
    complete: '{{ __('ui.booking.complete') }}',
    sending: '{{ __('ui.booking.sending') }}',
    sendRequest: '{{ __('ui.booking.send_request') }}',
    successTitle: '{{ __('ui.booking.success_title') }}',
    successDesc: '{{ __('ui.booking.success_desc') }}',
    alsoWhatsapp: '{{ __('ui.booking.also_whatsapp') }}',
    backToHome: '{{ __('ui.booking.back_to_home') }}',
    successToast: '{{ __('ui.booking.success_toast') }}',
    noSlots: '{{ __('ui.booking.no_slots') }}',
    timezoneNotice: '{{ __('ui.booking.timezone_notice', ['tz' => ':tz']) }}',
    loadError: '{{ __('ui.booking.load_error') }}',
    loading: '{{ __('ui.booking.loading') }}',
    toastNameEmail: '{{ __('ui.booking.toast_name_email') }}',
    toastValidEmail: '{{ __('ui.booking.toast_valid_email') }}',
    toastSelectTime: '{{ __('ui.booking.toast_select_time') }}',
    toastError: '{{ __('ui.booking.toast_error') }}',
    waTypeOnline: '{{ __('ui.booking.wa_type_online') }}',
    waTypeInperson: '{{ __('ui.booking.wa_type_inperson') }}',
  }
};
const __t = window.__translations.booking;
function showToast(message, type) {
  const existing = document.getElementById('toast-notification');
  if (existing) existing.remove();
  const toast = document.createElement('div');
  toast.id = 'toast-notification';
  const bg = type === 'error' ? '#dc2626' : type === 'success' ? '#16a34a' : '#5a7a76';
  toast.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:' + bg + ';color:#fff;padding:12px 24px;border-radius:8px;font-size:14px;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,0.15);transition:opacity 0.3s;';
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 4000);
}

const state = {
  type: 'online', format: 'intake', name: '', email: '',
  date: '', time: '', localTime: '',
  piGoals: '',
};

// Detect visitor's timezone
const visitorTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
const serverTimezone = 'Europe/Amsterdam';

/**
 * Convert a slot time from Amsterdam timezone to visitor's local timezone.
 * @param {string} slotTime - Time in H:i format (e.g. "10:00")
 * @param {string} dateStr - Date in YYYY-MM-DD format
 * @returns {string} - Converted time in H:i format (visitor's local time)
 */
function convertSlotToLocal(slotTime, dateStr) {
  // Create a date string interpreted as Amsterdam timezone
  // We use the trick of creating a date and adjusting for timezone difference
  const [hours, minutes] = slotTime.split(':').map(Number);

  // Create date in Amsterdam timezone using Intl
  const amsterdamDate = new Date(dateStr + 'T' + slotTime + ':00');

  // Use Intl to format this date in Amsterdam timezone to get the correct UTC offset
  const amsterdamFormatter = new Intl.DateTimeFormat('en-CA', {
    timeZone: serverTimezone,
    year: 'numeric', month: '2-digit', day: '2-digit',
    hour: '2-digit', minute: '2-digit', second: '2-digit',
    hour12: false
  });

  // Get what 10:00 Amsterdam looks like as a UTC timestamp
  // We create a date, format it in Amsterdam, then parse back
  const testDate = new Date(dateStr + 'T12:00:00Z'); // noon UTC as reference
  const amsterdamParts = new Intl.DateTimeFormat('en-US', {
    timeZone: serverTimezone,
    year: 'numeric', month: 'numeric', day: 'numeric',
    hour: 'numeric', minute: 'numeric', second: 'numeric',
    hour12: false
  }).formatToParts(testDate);

  const getPart = (type) => amsterdamParts.find(p => p.type === type)?.value;
  const utcYear = parseInt(getPart('year'));
  const utcMonth = parseInt(getPart('month')) - 1;
  const utcDay = parseInt(getPart('day'));
  const utcHour = parseInt(getPart('hour'));
  const utcMinute = parseInt(getPart('minute'));

  // Now we know: 12:00 UTC = utcHour:utcMinute Amsterdam time
  // So Amsterdam offset = (utcHour - 12) hours
  const amsterdamOffsetHours = utcHour - 12;
  const amsterdamOffsetMinutes = utcMinute;

  // Create the slot time as if it were UTC, then adjust by Amsterdam offset
  const slotDate = new Date(Date.UTC(
    parseInt(dateStr.split('-')[0]),
    parseInt(dateStr.split('-')[1]) - 1,
    parseInt(dateStr.split('-')[2]),
    hours - amsterdamOffsetHours,
    minutes - amsterdamOffsetMinutes
  ));

  // Format in visitor's timezone
  const visitorFormatter = new Intl.DateTimeFormat('en-GB', {
    timeZone: visitorTimezone,
    hour: '2-digit', minute: '2-digit',
    hour12: false
  });

  return visitorFormatter.format(slotDate);
}

let currentStep = 1;
let inlinePicker;
let scheduleData = { inactive_days: [0, 4, 5, 6], fully_blocked_dates: [] };

// Load schedule data on page load (working days + blocked dates)
const schedulePromise = fetch('/api/availability/schedule')
  .then(r => r.json())
  .then(data => { scheduleData = data; })
  .catch(() => {});

// Get current locale from URL path (e.g. /en/booking → 'en')
const currentLocale = window.location.pathname.split('/')[1] || 'en';
const bookingSubmitUrl = '/' + currentLocale + '/booking';

// ── Localized date & time formatting ──────────────────────────────
// English: "21 July 2026" / "21 July 2026 at 2:00 PM" (12-hour clock, "at").
// Dutch:   "21 juli 2026" / "21 juli 2026 om 14:00" (24-hour clock, "om").
function formatBookingDate(dateStr) {
  if (!dateStr) return '';
  const p = dateStr.split('-').map(Number);
  const dt = new Date(p[0], p[1] - 1, p[2]);
  const loc = currentLocale === 'nl' ? 'nl-NL' : 'en-GB';
  return dt.toLocaleDateString(loc, { day: 'numeric', month: 'long', year: 'numeric' });
}
function formatBookingTime(timeStr) {
  if (!timeStr) return '';
  const t = timeStr.split(':').map(Number);
  const dt = new Date(2000, 0, 1, t[0], t[1] || 0);
  if (currentLocale === 'nl') {
    return dt.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit', hour12: false });
  }
  return dt.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
}
function formatBookingDateTime(dateStr, timeStr) {
  const datePart = formatBookingDate(dateStr);
  const timePart = formatBookingTime(timeStr);
  if (!datePart) return '';
  if (!timePart) return datePart;
  const connector = currentLocale === 'nl' ? 'om' : 'at';
  // Use non-breaking space to keep date+time together on one line
  return datePart + '\u00A0' + connector + '\u00A0' + timePart;
}

// Format is always 'intake' (free intro call only)

// Session type is always 'online' — no selection needed



function updateStepIndicators(step) {
  [1, 2, 3].forEach(s => {
    const ind = document.getElementById('ind-' + s);
    ind.classList.remove('step-indicator--active', 'step-indicator--done');
    if (s < step) {
      ind.classList.add('step-indicator--done');
      ind.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
    } else if (s === step) {
      ind.classList.add('step-indicator--active');
      ind.textContent = s;
    } else {
      ind.textContent = s;
    }
  });
  [1, 2].forEach(l => {
    const line = document.getElementById('line-' + l);
    if (l < step) {
      line.classList.add('step-indicator__line--done');
    } else {
      line.classList.remove('step-indicator__line--done');
    }
  });
}

function goStep(step) {
  if (step === 2) {
    const name = document.getElementById('b-name').value.trim();
    const email = document.getElementById('b-email').value.trim();
    if (!name || !email) { showToast(__t.toastNameEmail,'info'); return; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showToast(__t.toastValidEmail,'info'); return; }
    state.name = name; state.email = email;
  }
  if (step === 3) {
    state.piGoals = document.getElementById('pi-goals').value.trim();
    setTimeout(initInlinePicker, 50);
  }

  currentStep = step;
  const totalSteps = 3;
  const fillPct = Math.round(((step - 1) / (totalSteps - 1)) * 100);
  document.getElementById('progress-fill').style.width = fillPct + '%';
  document.getElementById('progress-label').textContent = __t.stepOf.replace(':step', step).replace(':total', totalSteps);
  document.getElementById('progress-pct').textContent = fillPct + '%';

  updateStepIndicators(step);

  [1, 2, 3].forEach(s => {
    const el = document.getElementById('step-' + s);
    if (el) el.style.display = s === step ? 'block' : 'none';
  });
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function initInlinePicker() {
  if (inlinePicker) { inlinePicker.destroy(); }

  // Wait for schedule data before initializing calendar
  schedulePromise.then(() => {
    // Convert inactive_days (0=Mon..6=Sun) to JS day-of-week (0=Sun..6=Sat)
    const disabledJsDays = scheduleData.inactive_days.map(d => (d + 1) % 7);
    const blockedDates = scheduleData.fully_blocked_dates || [];

    inlinePicker = flatpickr('#inline-calendar', {
      inline: true, minDate: 'today', maxDate: new Date().fp_incr(60),
      disable: [
        function(date) {
          if (disabledJsDays.includes(date.getDay())) return true;
          const y = date.getFullYear();
          const m = String(date.getMonth()+1).padStart(2,'0');
          const dd = String(date.getDate()).padStart(2,'0');
          if (blockedDates.includes(y+'-'+m+'-'+dd)) return true;
          return false;
        }
      ],
      locale: { firstDayOfWeek: 1 },
      onChange(selectedDates, dateStr) {
        state.date = dateStr; state.time = '';
        fetchAndRenderSlots(dateStr);
        document.getElementById('slots-wrap').style.display = 'block';
        document.getElementById('summary-section').style.display = 'none';
        const btn = document.getElementById('btn-submit');
        btn.style.opacity = '0.5'; btn.style.pointerEvents = 'none';
      }
    });
  });
}

function fetchAndRenderSlots(dateStr) {
  const grid = document.getElementById('time-slots');
  grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#9ca3af;font-size:13px;padding:12px;">' + __t.loading + '</div>';

  fetch('/api/availability/slots?date=' + encodeURIComponent(dateStr))
    .then(r => r.json())
    .then(data => {
      grid.innerHTML = '';

      // Update timezone label - show only visitor's timezone
      const tzLabel = document.getElementById('timezone-label');
      let friendlyTz = visitorTimezone.replace(/_/g, ' ');
      // Dutch page localizes the "Europe/" prefix to "Europa/".
      if (currentLocale === 'nl') {
        friendlyTz = friendlyTz.replace(/^Europe\//, 'Europa/');
      }
      tzLabel.textContent = __t.timezoneNotice.replace(':tz', friendlyTz);

      if (!data.available || !data.slots.length) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#9ca3af;font-size:13px;padding:12px;">' + __t.noSlots + '</div>';
        return;
      }
      data.slots.forEach(slot => {
        const localTime = convertSlotToLocal(slot, dateStr);
        const div = document.createElement('div');
        div.className = 'time-slot';
        div.textContent = localTime;
        div.dataset.originalTime = slot; // Store Amsterdam time for submission
        div.addEventListener('click', () => {
          document.querySelectorAll('#time-slots .time-slot').forEach(s => s.classList.remove('selected'));
          div.classList.add('selected');
          state.time = slot; // Store original Amsterdam time
          state.localTime = localTime; // Store converted time for display
          showSummary();
          const btn = document.getElementById('btn-submit');
          btn.style.opacity = '1'; btn.style.pointerEvents = 'auto';
        });
        grid.appendChild(div);
      });
    })
    .catch(() => {
      grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#dc2626;font-size:13px;padding:12px;">' + __t.loadError + '</div>';
    });
}

function showSummary() {
  document.getElementById('sum-name').textContent = state.name;
  const displayTime = state.localTime || state.time;
  document.getElementById('sum-datetime').textContent = formatBookingDateTime(state.date, displayTime);
  document.getElementById('sum-email').textContent = state.email;
  document.getElementById('summary-section').style.display = 'block';
}

function submitBooking() {
  if (!state.date || !state.time) { showToast(__t.toastSelectTime,'info'); return; }
  const btn = document.getElementById('btn-submit');
  btn.disabled = true; btn.textContent = __t.sending;

  const payload = {
    name: state.name,
    email: state.email,
    format: state.format,
    type: state.type,
    date: state.date,
    time: state.time,
    notes: '',
    pi_brings: state.piGoals || null,
    client_timezone: visitorTimezone,
    preferred_language: currentLocale,
  };

  fetch(bookingSubmitUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
    body: JSON.stringify(payload),
  })
  .then(r => {
    if (r.status === 409) {
      return r.json().then(data => { throw new Error(data.message || 'Slot taken'); });
    }
    if (!r.ok) throw new Error('Server error');
    return r.json();
  })
  .then(data => {
    const msg = encodeURIComponent(
      'Hi Lysander,\n\nI\'d like to book a session:\n\n' +
      'Name: ' + state.name + '\n' +
      'Type: ' + (state.type === 'online' ? __t.waTypeOnline : __t.waTypeInperson) + '\n' +
      'Format: ' + state.format + '\n' +
      'Date: ' + formatBookingDateTime(state.date, state.time) + '\n' +
      (state.piGoals ? '\nReason: ' + state.piGoals : '') +
      '\n\nThank you!'
    );

    document.getElementById('step-3').innerHTML = `
      <div class="success-state">
        <div class="success-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="var(--color-teal)" stroke-width="2" width="34" height="34"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </div>
        <h2 style="margin-bottom:var(--space-3);">${__t.successTitle}</h2>
        <p style="color:var(--color-text-muted);font-size:var(--size-sm);margin:0 auto var(--space-8);max-width:480px;line-height:1.6;">
          ${__t.successDesc.replace(':name', '<span style="white-space:nowrap;">' + state.name + '</span>').replace(':datetime', '<strong style="white-space:nowrap;">' + formatBookingDateTime(state.date, state.localTime || state.time) + '</strong>')}
        </p>
        <a href="https://wa.me/66935309052?text=${msg}" target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp" style="margin:0 auto;">
          <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          ${__t.alsoWhatsapp}
        </a>
        <a href="{{ route('home') }}" class="btn btn--outline" style="display:block;margin:var(--space-4) auto 0;max-width:200px;justify-content:center;">${__t.backToHome}</a>
      </div>`;
    showToast(__t.successToast, 'success');
    document.getElementById('progress-fill').style.width = '100%';
    document.getElementById('progress-pct').textContent = '100%';
    document.getElementById('progress-label').textContent = __t.complete;
    updateStepIndicators(4);
    // Bring the success/confirmation message into view immediately.
    window.scrollTo({ top: 0, behavior: 'smooth' });
  })
  .catch(err => {
    btn.disabled = false; btn.textContent = __t.sendRequest;
    showToast(err.message || __t.toastError, 'error');
  });
}
</script>
@endsection
