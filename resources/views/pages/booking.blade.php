@extends('layouts.app')

@section('title', 'Book a Free Intro Call | Lysander Verschuur, MSc.')
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
        <span class="progress-bar-label" id="progress-label">Step 1 of 3</span>
        <span class="progress-bar-pct" id="progress-pct">0%</span>
      </div>
      <div class="progress-bar-track">
        <div class="progress-bar-fill" id="progress-fill" style="width:0%;"></div>
      </div>
    </div>

    @php $bookingHero = $sections['booking_hero'] ?? null; @endphp
    <!-- STEP 1: Details -->
    <div id="step-1" class="step-content">
      <h1 class="step-heading">{{ $bookingHero?->content['heading'] ?? "Let's get started" }}</h1>
      <p class="step-subheading">{{ $bookingHero?->content['subheading'] ?? 'Book a free 30-minute introduction call. No commitment required.' }}</p>

      <div class="step-section">
        <label class="step-section__label">You're booking</label>
        <div style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-4) var(--space-6);background:var(--color-teal-light);border:1.5px solid var(--color-accent-light);border-radius:12px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="var(--color-teal)" stroke-width="2" width="22" height="22" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
          <div>
            <span style="font-size:var(--size-base);font-weight:500;color:var(--color-text);display:block;">Free introduction call &middot; Online</span>
            <span style="font-size:var(--size-sm);color:var(--color-text-muted);">30-minute video call &mdash; no commitment required</span>
          </div>
        </div>
      </div>

      <div class="step-section">
        <label class="step-section__label">Your details</label>
        <div class="form-group">
          <label class="form-label" for="b-name">Full name</label>
          <input type="text" class="form-input" id="b-name" placeholder="Your name" autocomplete="name">
        </div>
        <div class="form-group" style="margin-top:var(--space-4);">
          <label class="form-label" for="b-email">Email address</label>
          <input type="email" class="form-input" id="b-email" placeholder="your@email.com" autocomplete="email">
        </div>
      </div>

      <div class="step-nav step-nav--end">
        <button class="btn btn--primary" onclick="goStep(2)">
          Continue
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </button>
      </div>
    </div>

    <!-- STEP 2: Simple Questionnaire -->
    <div id="step-2" class="step-content" style="display:none;">
      <h1 class="step-heading">What brings you here today?</h1>
      <p class="step-subheading">A few sentences are enough. What are you currently struggling with, and what would you like help with? This will help guide our first conversation.</p>

      <div class="step-section">
        <div class="form-group">
          <textarea class="form-textarea" id="pi-goals" placeholder="I'd like to feel more... / I struggle with..." style="min-height:160px;"></textarea>
        </div>
        <p class="step-section__helper">This is optional. You can also discuss this during the call itself.</p>
      </div>

      <div class="step-nav">
        <button class="btn--ghost" onclick="goStep(1)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
          Back
        </button>
        <button class="btn btn--primary" onclick="goStep(3)">
          Continue
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </button>
      </div>
    </div>

    <!-- STEP 3: Schedule & Confirm -->
    <div id="step-3" class="step-content" style="display:none;">
      <h1 class="step-heading">Choose a time</h1>
      <p class="step-subheading">Weekday sessions only. Tap a day to see available time slots.</p>

      <div class="step-section">
        <label class="step-section__label">Select a date</label>
        <div id="inline-calendar"></div>
      </div>

      <div class="step-section" id="slots-wrap" style="display:none;">
        <label class="step-section__label">Available times</label>
        <div class="time-slots" id="time-slots"></div>
      </div>

      <div id="summary-section" style="display:none;">
        <div class="step-section" style="margin-top:var(--space-8);">
          <label class="step-section__label">Review your booking</label>
          <div class="summary-card" id="summary-card">
            <div class="summary-row"><span class="summary-label">Name</span><span class="summary-value" id="sum-name">—</span></div>
            <div class="summary-row"><span class="summary-label">Date &amp; time</span><span class="summary-value" id="sum-datetime">—</span></div>
            <div class="summary-row"><span class="summary-label">Email</span><span class="summary-value" id="sum-email">—</span></div>
          </div>
        </div>

        <div class="step-section" style="margin-top:var(--space-6);">
          <label class="step-section__label">Additional notes <span>(optional)</span></label>
          <div class="form-group">
            <textarea class="form-textarea" id="b-notes" placeholder="Briefly share what brings you here — this helps me prepare for our first conversation."></textarea>
          </div>
        </div>
      </div>

      <div class="step-nav">
        <button class="btn--ghost" onclick="goStep(2)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
          Back
        </button>
        <button class="btn btn--primary" id="btn-submit" style="opacity:0.5;pointer-events:none;" onclick="submitBooking()">
          Send booking request
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
        </button>
      </div>
      <p style="font-size:var(--size-xs);color:var(--color-text-light);margin-top:var(--space-4);text-align:center;">Your booking request will be sent to Lysander. You'll receive a confirmation via email or WhatsApp within 24 hours.</p>
    </div>

  </div>
</div>
@endsection

@section('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
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
  date: '', time: '',
  piGoals: '',
};

let currentStep = 1;
let inlinePicker;
let scheduleData = { inactive_days: [5, 6], fully_blocked_dates: [] };

// Load schedule data on page load (working days + blocked dates)
fetch('/api/availability/schedule')
  .then(r => r.json())
  .then(data => { scheduleData = data; })
  .catch(() => {});

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
    if (!name || !email) { showToast('Please enter your name and email before continuing.','info'); return; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showToast('Please enter a valid email address.','info'); return; }
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
  document.getElementById('progress-label').textContent = 'Step ' + step + ' of ' + totalSteps;
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
}

function fetchAndRenderSlots(dateStr) {
  const grid = document.getElementById('time-slots');
  grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#9ca3af;font-size:13px;padding:12px;">Loading...</div>';

  fetch('/api/availability/slots?date=' + encodeURIComponent(dateStr))
    .then(r => r.json())
    .then(data => {
      grid.innerHTML = '';
      if (!data.available || !data.slots.length) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#9ca3af;font-size:13px;padding:12px;">No available slots for this date.</div>';
        return;
      }
      data.slots.forEach(slot => {
        const div = document.createElement('div');
        div.className = 'time-slot';
        div.textContent = slot;
        div.addEventListener('click', () => {
          document.querySelectorAll('#time-slots .time-slot').forEach(s => s.classList.remove('selected'));
          div.classList.add('selected');
          state.time = slot;
          showSummary();
          const btn = document.getElementById('btn-submit');
          btn.style.opacity = '1'; btn.style.pointerEvents = 'auto';
        });
        grid.appendChild(div);
      });
    })
    .catch(() => {
      grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#dc2626;font-size:13px;padding:12px;">Failed to load availability. Please try again.</div>';
    });
}

function showSummary() {
  document.getElementById('sum-name').textContent = state.name;
  document.getElementById('sum-datetime').textContent = state.date + ' at ' + state.time;
  document.getElementById('sum-email').textContent = state.email;
  document.getElementById('summary-section').style.display = 'block';
}

function submitBooking() {
  if (!state.date || !state.time) { showToast('Please select a date and time.','info'); return; }
  const notes = document.getElementById('b-notes').value.trim();
  const btn = document.getElementById('btn-submit');
  btn.disabled = true; btn.textContent = 'Sending...';

  const payload = {
    name: state.name,
    email: state.email,
    format: state.format,
    type: state.type,
    date: state.date,
    time: state.time,
    notes: notes,
    pi_brings: state.piGoals || null,
  };

  fetch('/booking', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
    body: JSON.stringify(payload),
  })
  .then(r => {
    if (!r.ok) throw new Error('Server error');
    return r.json();
  })
  .then(data => {
    const msg = encodeURIComponent(
      'Hi Lysander,\n\nI\'d like to book a session:\n\n' +
      'Name: ' + state.name + '\n' +
      'Type: ' + (state.type === 'online' ? 'Online' : 'In-person') + '\n' +
      'Format: ' + state.format + '\n' +
      'Date: ' + state.date + ' at ' + state.time + '\n' +
      (notes ? '\nNotes: ' + notes : '') +
      '\n\nThank you!'
    );

    document.getElementById('step-3').innerHTML = `
      <div class="success-state">
        <div class="success-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="var(--color-teal)" stroke-width="2" width="34" height="34"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </div>
        <h2 style="margin-bottom:var(--space-3);">Booking request sent!</h2>
        <p style="color:var(--color-text-muted);font-size:var(--size-sm);margin:0 auto var(--space-8);max-width:380px;">
          Thank you, ${state.name}. Lysander will confirm your session for <strong>${state.date} at ${state.time}</strong> via email or WhatsApp within 24 hours.
        </p>
        <a href="https://wa.me/66935309052?text=${msg}" target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp" style="margin:0 auto;">
          <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          Also send via WhatsApp
        </a>
        <a href="{{ route('home') }}" class="btn btn--outline" style="display:block;margin:var(--space-4) auto 0;max-width:200px;justify-content:center;">Back to home</a>
      </div>`;
    showToast('Booking request submitted!', 'success');
    document.getElementById('progress-fill').style.width = '100%';
    document.getElementById('progress-pct').textContent = '100%';
    document.getElementById('progress-label').textContent = 'Complete';
    updateStepIndicators(4);
  })
  .catch(err => {
    btn.disabled = false; btn.textContent = 'Send booking request';
    showToast('Something went wrong. Please try again.', 'error');
  });
}
</script>
@endsection
